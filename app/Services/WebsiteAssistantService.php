<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebsiteAssistantService
{
    public function __construct(
        private readonly WebsiteKnowledgeService $knowledgeService,
    ) {
    }

    public function handleUserMessage(Conversation $conversation, string $content): array
    {
        $content = trim($content);

        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $content,
            'metadata' => [
                'source' => 'website_chat',
            ],
        ]);

        $conversation->forceFill([
            'last_message_at' => now(),
        ])->save();

        $lead = $this->captureLead($conversation, $content);
        $assistantReply = $this->generateReply($conversation, $content, $lead);

        $assistantMessage = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $assistantReply['content'],
            'metadata' => array_merge([
                'source' => 'website_chat',
                'lead_id' => $lead?->id,
            ], $assistantReply['metadata'] ?? []),
        ]);

        $conversation->forceFill([
            'last_message_at' => now(),
        ])->save();

        return [
            'user_message' => $userMessage,
            'assistant_message' => $assistantMessage,
            'lead' => $lead,
        ];
    }

    private function captureLead(Conversation $conversation, string $content): ?Lead
    {
        $lead = $conversation->lead()->first();
        $email = $this->extractEmail($content) ?: $lead?->email;
        $name = $this->extractName($content) ?: $lead?->full_name;
        $goal = $lead?->goal ?: $this->inferGoal($content);

        if (!$lead && blank($email)) {
            return null;
        }

        if (!$lead) {
            $lead = new Lead([
                'status' => 'new',
                'form_type' => 'ai_chat',
                'source_page' => 'website-chat',
            ]);
            $lead->conversation_id = $conversation->id;
        }

        $metadata = is_array($lead->metadata) ? $lead->metadata : [];
        $history = Arr::get($metadata, 'chat_transcript', []);
        $history[] = [
            'role' => 'user',
            'content' => $content,
            'at' => now()->toIso8601String(),
        ];

        $lead->fill([
            'first_name' => $lead->first_name ?: $this->extractFirstName($name),
            'full_name' => $name,
            'email' => $email,
            'goal' => $goal,
            'form_type' => 'ai_chat',
            'source_page' => 'website-chat',
            'status' => 'new',
            'metadata' => array_merge($metadata, [
                'chat_transcript' => array_slice($history, -20),
                'last_user_message' => $content,
                'captured_via' => 'website_chat',
            ]),
        ]);
        $lead->ip_address = request()->ip();
        $lead->user_agent = request()->userAgent();
        $lead->save();

        return $lead;
    }

    private function generateReply(Conversation $conversation, string $content, ?Lead $lead): array
    {
        $exact = $this->exactReply($content);
        if ($exact !== null) {
            return [
                'content' => $exact,
                'metadata' => [
                    'provider' => 'rule_based',
                    'web_search_used' => false,
                ],
            ];
        }

        $apiKey = trim((string) SiteSetting::getValue('ai_openai_api_key', config('assistant.openai.api_key')));
        if ($apiKey !== '') {
            $baseUrl = rtrim((string) SiteSetting::getValue('ai_openai_base_url', config('assistant.openai.base_url', 'https://api.openai.com/v1')), '/');

            $response = $this->isGroqBaseUrl($baseUrl)
                ? $this->askGroq($conversation, $content, $lead, $apiKey, $baseUrl)
                : $this->askStandardProvider($conversation, $content, $lead, $apiKey, $baseUrl);

            if (filled($response['content'] ?? null)) {
                return $response;
            }
        }

        return [
            'content' => $this->fallbackReply($content, $lead),
            'metadata' => [
                'provider' => 'fallback',
                'web_search_used' => false,
            ],
        ];
    }

    private function exactReply(string $content): ?string
    {
        $text = Str::lower(trim($content));
        $settings = SiteSetting::keyValueMap();
        $contactEmail = $settings['contact_email'] ?? 'hello@settleanz.com';
        $responseTime = $settings['contact_response_time'] ?? 'We usually respond within 24 hours.';
        $applyLink = $settings['directory_apply_link'] ?? '/contact';

        if (Str::contains($text, ['business listing', 'directory listing', 'list my business', 'add my business'])) {
            return 'To list your business, head to /contact and choose "Business Listing" as the subject. ' . $responseTime;
        }

        if (Str::contains($text, ['contact your team', 'contact the team', 'speak to your team', 'human support'])) {
            return 'You can reach the SettleANZ team through the /contact page, email ' . $contactEmail . ', or WhatsApp. ' . $responseTime;
        }

        if (Str::contains($text, ['response time', 'how fast', 'how quickly', 'when will you reply'])) {
            return $responseTime;
        }

        if (Str::contains($text, ['migration consultation', 'book migration help', 'book a consultation', 'migration agent'])) {
            return 'I can\'t help with visa applications — that requires a MARA-registered migration agent. Search for one at mara.gov.au. What I CAN help with is what happens after you arrive — housing, banking, TFN, community. Would any of that be useful?';
        }

        if (Str::contains($text, ['whatsapp', 'chat on whatsapp', 'whatsapp help'])) {
            return 'You can reach SettleANZ on WhatsApp via the /contact page or the WhatsApp button on the site. ' . $responseTime;
        }

        if (Str::contains($text, ['email address', 'contact email', 'your email', 'email you'])) {
            return 'You can email SettleANZ at ' . $contactEmail . '.';
        }

        if (Str::contains($text, ['directory categories', 'categories in your directory', 'what categories are in your directory'])) {
            return 'The directory currently has Inspection, Legal Services, and Finance categories across Brisbane, Melbourne, and Sydney. Visit /directory to explore.';
        }

        if (Str::contains($text, ['blog topics', 'what is in the blog', 'what does the blog cover'])) {
            return 'The blog covers banking and housing for newcomers. Current articles include best banks for new arrivals, how to rent without history, and decoding rental listings. Visit /blog to browse.';
        }

        if (Str::contains($text, ['what can this website help me with', 'what does this website do', 'what services do you offer overall', 'what can settleanz help with overall'])) {
            return 'SettleANZ helps with the full settlement journey — pre-arrival planning, airport arrival, rental housing, banking, TFN, Medicare, jobs, superannuation, community integration, and family support. We also have a directory of trusted providers and a blog with practical guides. What stage are you at?';
        }

        if (Str::contains($text, ['settlement service', 'settlement plan', 'what to do first', 'before you land', 'hit the ground running'])) {
            return 'Start at /settlement-services. It covers all 4 stages: Arrive (pre-arrival planning, airport pickup), Settle (rental finding, school placement), Work & Invest (career coaching, financial setup), and Enjoy (community, family support). Which stage fits your situation?';
        }

        if (Str::contains($text, ['about settleanz', 'who are you', 'what is settleanz', 'tell me about this site'])) {
            return 'SettleANZ was founded by Entel Dajsmaili, who arrived in Australia in 2001 with one suitcase. After becoming a citizen in 2004, he promised that no one else should learn the system the hard way. Today we help newcomers settle through practical guidance, not generic advice. Visit /about to read the full story.';
        }

        if (Str::contains($text, ['privacy', 'data protection', 'how you use my data'])) {
            return 'Our privacy policy is at /privacy-policy. It explains how we handle your data — we don\'t sell it, we keep it secure, and you can request deletion anytime.';
        }

        if (Str::contains($text, ['terms', 'terms of service', 'legal', 'disclaimer'])) {
            return 'Our terms of service are at /terms-of-service. Important: SettleANZ is NOT a registered migration agent. We provide settlement guidance only, not visa or legal advice.';
        }

        if (Str::contains($text, ['before arriving', 'before i land', 'pre-arrival', 'prepare before'])) {
            return 'Before you land: sort your documents (passport, visa, bank statements, insurance, passport photos), book 4 weeks of short-term accommodation, arrange airport transfer, and get travel insurance. Some banks let you open accounts before you arrive — Commonwealth Bank does. See /new-to-australia for the full checklist.';
        }

        return null;
    }

    private function askGroq(Conversation $conversation, string $content, ?Lead $lead, string $apiKey, string $baseUrl): ?array
    {
        $model = (string) SiteSetting::getValue('ai_openai_model', config('assistant.openai.model', 'openai/gpt-oss-20b'));
        $knowledgeContext = $this->knowledgeService->buildAssistantContext($content);
        $messages = $this->buildChatMessages($conversation, $this->systemPrompt($lead, false) . "\n\n" . $knowledgeContext);

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.45,
            'max_completion_tokens' => 900,
        ];

        try {
            $response = Http::timeout((int) config('assistant.openai.timeout', 20))
                ->withToken($apiKey)
                ->acceptJson()
                ->post($baseUrl . '/chat/completions', $payload);

            if (!$response->successful()) {
                Log::warning('Website assistant provider request failed.', [
                    'provider' => 'groq',
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'model' => $model,
                ]);

                return null;
            }

            $json = $response->json();
            $contentText = trim((string) data_get($json, 'choices.0.message.content', ''));

            if ($contentText === '') {
                return null;
            }

            return [
                'content' => $this->sanitizeAssistantText($contentText),
                'metadata' => [
                    'provider' => 'groq',
                    'model' => $model,
                    'web_search_used' => false,
                    'response_id' => data_get($json, 'id'),
                ],
            ];
        } catch (\Throwable $e) {
            Log::warning('Website assistant provider exception.', [
                'provider' => 'groq',
                'message' => $e->getMessage(),
                'model' => $model,
            ]);

            return null;
        }
    }

    private function askStandardProvider(Conversation $conversation, string $content, ?Lead $lead, string $apiKey, string $baseUrl): ?array
    {
        $model = (string) SiteSetting::getValue('ai_openai_model', config('assistant.openai.model', 'gpt-4o-mini'));
        $knowledgeContext = $this->knowledgeService->buildAssistantContext($content);
        $webSearchEnabled = $this->webSearchEnabled();
        $isOpenRouter = $this->isOpenRouterBaseUrl($baseUrl);

        $attempt = $this->performStandardProviderRequest(
            conversation: $conversation,
            userContent: $content,
            lead: $lead,
            apiKey: $apiKey,
            baseUrl: $baseUrl,
            model: $model,
            knowledgeContext: $knowledgeContext,
            webSearchEnabled: $webSearchEnabled,
            isOpenRouter: $isOpenRouter,
            simplifiedRetry: false,
        );

        if (($attempt['result']['content'] ?? '') !== '') {
            return $attempt['result'];
        }

        if ($webSearchEnabled && $attempt['tool_error'] === true) {
            $retry = $this->performStandardProviderRequest(
                conversation: $conversation,
                userContent: $content,
                lead: $lead,
                apiKey: $apiKey,
                baseUrl: $baseUrl,
                model: $model,
                knowledgeContext: $knowledgeContext,
                webSearchEnabled: false,
                isOpenRouter: $isOpenRouter,
                simplifiedRetry: false,
            );

            if (($retry['result']['content'] ?? '') !== '') {
                $retry['result']['metadata']['web_search_used'] = false;
                $retry['result']['metadata']['tool_retry_fallback'] = true;
                return $retry['result'];
            }
        }

        $simplifiedRetry = $this->performStandardProviderRequest(
            conversation: $conversation,
            userContent: $content,
            lead: $lead,
            apiKey: $apiKey,
            baseUrl: $baseUrl,
            model: $model,
            knowledgeContext: $this->trimKnowledgeContext($knowledgeContext),
            webSearchEnabled: false,
            isOpenRouter: $isOpenRouter,
            simplifiedRetry: true,
        );

        if (($simplifiedRetry['result']['content'] ?? '') !== '') {
            $simplifiedRetry['result']['metadata']['web_search_used'] = false;
            $simplifiedRetry['result']['metadata']['simplified_retry_fallback'] = true;
            return $simplifiedRetry['result'];
        }

        return null;
    }

    private function performStandardProviderRequest(Conversation $conversation, string $userContent, ?Lead $lead, string $apiKey, string $baseUrl, string $model, string $knowledgeContext, bool $webSearchEnabled, bool $isOpenRouter, bool $simplifiedRetry): array
    {
        $shouldPreferWebSearch = $webSearchEnabled && $this->knowledgeService->shouldPreferWebSearch($userContent);
        $instructionTail = $shouldPreferWebSearch
            ? "\n\nFor this specific user request, run web search before answering. Use sources and avoid unsupported claims."
            : '';
        $retryInstruction = $simplifiedRetry
            ? "\n\nRetry mode: answer directly with short practical guidance. Avoid long formatting."
            : '';

        $payload = [
            'model' => $model,
            'instructions' => $this->systemPrompt($lead, $webSearchEnabled) . "\n\n" . $knowledgeContext . $instructionTail . $retryInstruction,
            'input' => $this->buildResponsesInput($conversation),
            'max_output_tokens' => 900,
            'temperature' => $simplifiedRetry ? 0.2 : 0.45,
        ];

        if ($webSearchEnabled) {
            $payload['tools'] = [$this->buildWebSearchTool($isOpenRouter)];
        }

        try {
            $request = Http::timeout((int) config('assistant.openai.timeout', 20))
                ->withToken($apiKey)
                ->acceptJson();

            if ($isOpenRouter) {
                $request = $request->withHeaders([
                    'HTTP-Referer' => config('app.url', 'http://127.0.0.1:8000'),
                    'X-Title' => config('app.name', 'SettleANZ'),
                ]);
            }

            $response = $request->post($baseUrl . '/responses', $payload);

            if (!$response->successful()) {
                $body = $response->body();
                $toolError = $this->isToolSupportError($response->status(), $body);

                Log::warning('Website assistant provider request failed.', [
                    'provider' => $isOpenRouter ? 'openrouter' : 'openai',
                    'status' => $response->status(),
                    'body' => $body,
                    'model' => $model,
                    'web_search_enabled' => $webSearchEnabled,
                    'tool_error' => $toolError,
                ]);

                return ['result' => null, 'tool_error' => $toolError];
            }

            $json = $response->json();
            $contentText = $this->extractResponseText($json);

            if ($contentText === '') {
                return ['result' => null, 'tool_error' => false];
            }

            $sources = $this->extractWebSources($json);

            return [
                'result' => [
                    'content' => $this->appendSourceSummary($contentText, $sources),
                    'metadata' => [
                        'provider' => $isOpenRouter ? 'openrouter' : 'openai',
                        'model' => $model,
                        'web_search_recommended' => $shouldPreferWebSearch,
                        'web_search_used' => $sources->isNotEmpty() || $this->responseUsedWebSearch($json),
                        'simplified_retry' => $simplifiedRetry,
                        'web_sources' => $sources->values()->all(),
                        'response_id' => data_get($json, 'id'),
                    ],
                ],
                'tool_error' => false,
            ];
        } catch (\Throwable $e) {
            Log::warning('Website assistant provider exception.', [
                'provider' => $isOpenRouter ? 'openrouter' : 'openai',
                'message' => $e->getMessage(),
                'model' => $model,
                'web_search_enabled' => $webSearchEnabled,
            ]);

            return ['result' => null, 'tool_error' => false];
        }
    }

    private function trimKnowledgeContext(string $knowledgeContext, int $maxChars = 1600): string
    {
        $trimmed = trim($knowledgeContext);
        if ($trimmed === '') {
            return $trimmed;
        }

        return Str::limit($trimmed, $maxChars, '...');
    }

    private function buildChatMessages(Conversation $conversation, string $systemPrompt): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt,
            ],
        ];

        foreach ($conversation->messages()->latest('id')->take(10)->get()->reverse() as $message) {
            $messages[] = [
                'role' => $message->role === 'assistant' ? 'assistant' : 'user',
                'content' => (string) $message->content,
            ];
        }

        return $messages;
    }

    private function isToolSupportError(int $status, string $body): bool
    {
        if ($status !== 400 && $status !== 404 && $status !== 422) {
            return false;
        }

        $text = Str::lower($body);

        return Str::contains($text, [
            'support tool use',
            'tool use',
            'tools are not supported',
            'tool calling',
            'no endpoints found that support tool use',
        ]);
    }

    private function isGroqBaseUrl(string $baseUrl): bool
    {
        return Str::contains(Str::lower($baseUrl), 'api.groq.com');
    }

    private function isOpenRouterBaseUrl(string $baseUrl): bool
    {
        return Str::contains(Str::lower($baseUrl), 'openrouter.ai');
    }

    private function buildWebSearchTool(bool $isOpenRouter): array
    {
        if ($isOpenRouter) {
            return [
                'type' => 'openrouter:web_search',
                'parameters' => [
                    'engine' => 'auto',
                    'max_results' => 5,
                    'max_total_results' => 10,
                    'search_context_size' => 'medium',
                ],
            ];
        }

        return [
            'type' => 'web_search',
        ];
    }

    private function webSearchEnabled(): bool
    {
        $setting = SiteSetting::getValue('ai_web_search_enabled', null);
        if ($setting !== null && $setting !== '') {
            return (string) $setting === '1';
        }

        return (bool) config('assistant.openai.web_search_enabled', true);
    }

    private function buildResponsesInput(Conversation $conversation): array
    {
        return $conversation->messages()
            ->latest('id')
            ->take(10)
            ->get()
            ->reverse()
            ->map(function ($message) {
                return [
                    'role' => $message->role === 'assistant' ? 'assistant' : 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => (string) $message->content,
                        ],
                    ],
                ];
            })
            ->values()
            ->all();
    }

    private function extractResponseText(array $json): string
    {
        $outputText = trim((string) data_get($json, 'output_text', ''));
        if ($outputText !== '') {
            return $outputText;
        }

        $chunks = collect(data_get($json, 'output', []))
            ->filter(fn ($item) => data_get($item, 'type') === 'message')
            ->flatMap(fn ($item) => data_get($item, 'content', []))
            ->filter(fn ($item) => data_get($item, 'type') === 'output_text')
            ->map(fn ($item) => trim((string) data_get($item, 'text', '')))
            ->filter()
            ->values();

        return $chunks->implode("\n\n");
    }

    private function responseUsedWebSearch(array $json): bool
    {
        return collect(data_get($json, 'output', []))
            ->contains(fn ($item) => in_array(data_get($item, 'type'), ['web_search_call', 'tool_call'], true)
                || data_get($item, 'name') === 'openrouter:web_search');
    }

    private function extractWebSources(array $json): Collection
    {
        $annotationSources = collect(data_get($json, 'output', []))
            ->filter(fn ($item) => data_get($item, 'type') === 'message')
            ->flatMap(fn ($item) => data_get($item, 'content', []))
            ->flatMap(fn ($item) => data_get($item, 'annotations', []))
            ->filter(fn ($annotation) => in_array(data_get($annotation, 'type'), ['url_citation', 'citation'], true))
            ->map(function ($annotation) {
                $url = trim((string) data_get($annotation, 'url', ''));
                $title = trim((string) data_get($annotation, 'title', ''));
                if ($url === '') {
                    return null;
                }

                return [
                    'title' => $title !== '' ? $title : (parse_url($url, PHP_URL_HOST) ?: $url),
                    'url' => $url,
                ];
            })
            ->filter()
            ->unique('url')
            ->values();

        if ($annotationSources->isNotEmpty()) {
            return $annotationSources->take(5)->values();
        }

        return collect(data_get($json, 'output', []))
            ->filter(fn ($item) => data_get($item, 'type') === 'web_search_call')
            ->flatMap(fn ($item) => data_get($item, 'action.sources', []))
            ->map(function ($source) {
                $url = trim((string) data_get($source, 'url', ''));
                $title = trim((string) data_get($source, 'title', ''));
                if ($url === '') {
                    return null;
                }

                return [
                    'title' => $title !== '' ? $title : (parse_url($url, PHP_URL_HOST) ?: $url),
                    'url' => $url,
                ];
            })
            ->filter()
            ->unique('url')
            ->take(5)
            ->values();
    }

    private function appendSourceSummary(string $content, Collection $sources): string
    {
        $content = $this->sanitizeAssistantText($content);

        if ($sources->isEmpty()) {
            return $content;
        }

        $summary = $sources->take(3)
            ->map(fn ($source) => ($source['title'] ?? 'Source') . ': ' . ($source['url'] ?? ''))
            ->implode(' | ');

        return rtrim($content) . "\n\nSources: " . $summary;
    }

    private function sanitizeAssistantText(string $content): string
    {
        $clean = preg_replace('/<think>.*?<\/think>/s', '', $content) ?? $content;
        $clean = preg_replace('/\?\d+\+L\d+(?:-L\d+)?\?/', '', $clean) ?? $clean;
        $clean = preg_replace('/([A-Za-z0-9\/])\?([A-Za-z0-9])/', '$1 $2', $clean) ?? $clean;
        $clean = preg_replace('/[ \t]{2,}/', ' ', $clean) ?? $clean;
        $clean = preg_replace("/\n{3,}/", "\n\n", $clean) ?? $clean;

        return trim($clean);
    }

    private function systemPrompt(?Lead $lead, bool $webSearchEnabled): string
    {
        $name = trim((string) ($lead?->first_name ?? ''));
        $customInstruction = trim((string) SiteSetting::getValue('ai_assistant_system_prompt', ''));

        if ($customInstruction !== '') {
            return implode("\n", array_filter([
                $customInstruction,
                $webSearchEnabled ? 'Web search is enabled. Use it for current facts when needed.' : null,
                'Known visitor first name: ' . $name ?: null,
                'Never output internal tool traces, citation placeholders, or raw debug tokens.',
            ]));
        }

        $settings = SiteSetting::keyValueMap();
        $maxBullets = min((int) SiteSetting::getValue('ai_max_bullets', 3), 3);
        $maxLength = (int) SiteSetting::getValue('ai_max_length', 600);
        $tone = SiteSetting::getValue('ai_response_tone', 'professional');
        $language = SiteSetting::getValue('ai_response_language', 'en');
        $followUpPhrase = trim((string) SiteSetting::getValue('ai_follow_up_phrase', ''));
        $closingPhrase = trim((string) SiteSetting::getValue('ai_closing_phrase', ''));

        $languageLine = $language !== 'en' ? "RESPONSE LANGUAGE: Answer in " . strtoupper($language) . "." : null;

        $corePrompt = <<<'PROMPT'
You are the SettleANZ AI Assistant — a warm, practical relocation guide for people moving to Australia and New Zealand. You are powered by knowledge from the SettleANZ website, blog, directory, and service pages. You do NOT have internet access unless web search is explicitly enabled. You can only answer based on the knowledge provided to you.

---

## IDENTITY & PERSONA

You are a friendly, knowledgeable person who moved to Australia years ago and learned the settlement system through lived experience. You are NOT a migration agent, lawyer, financial adviser, or real estate agent. You are a guide.

- Tone: Warm, direct, conversational. Grade 8–10 reading level.
- Contractions: Always use them (you're, don't, there's, it'll).
- Sentence style: Short. One idea per sentence. Mix lengths for rhythm.
- First person: Use "I recommend" or "Here's what I'd suggest" — not "one should" or "it is recommended".
- Never start sentences with: "It is", "There are", "Please be advised", "Furthermore", "Moreover".
- Never use: "feel free to", "don't hesitate to", "kindly", "in order to", "with regards to".

---

## KNOWLEDGE BOUNDARIES

### What you CAN answer about:
- Pre-arrival planning (documents, checklist, timelines)
- Airport arrival, SIM cards, first 24 hours
- TFN application and tax basics
- Banking (Commonwealth, Airwallex, Wise, fees, international transfers)
- Medicare eligibility and enrolment (general guidance only)
- Rental housing (applications, inspections, documents, rights)
- Rental listing terminology decoding
- Employment and job search strategies
- Resume localization and interview coaching
- Workplace culture in Australia
- Superannuation basics (11% employer contribution, DASP)
- Credit score building from zero
- Property buying basics (FIRB, mortgage brokers, inspections)
- Small business setup (ABN, GST, BAS)
- Community integration, social groups, volunteering
- Family support (school enrolment, childcare, spousal support)
- Driving licenses, public transport
- PR and citizenship pathways (general timelines only)
- Cost of living overview
- Insurance basics (travel, health, Medicare gap)
- SettleANZ services, packages, pricing, and booking
- SettleANZ directory partners
- New Zealand settlement (high-level only)

### What you CANNOT answer (redirect immediately):
- Visa recommendations, eligibility, or application help -> redirect to MARA-registered migration agent at mara.gov.au
- Financial or investment advice -> redirect to ASIC-registered financial adviser
- Legal advice -> redirect to a qualified lawyer
- Tax advice beyond basics -> redirect to a tax accountant or ATO
- Medical or health diagnoses -> redirect to a GP or health professional
- Anything outside Australia/NZ settlement -> politely decline

### Redirect phrasing:
"That's outside what I can help with. For [specific need], I'd recommend speaking with a [professional type]. Is there anything about settlement — housing, banking, or community — that I can help with instead?"

---

## CONVERSATION RULES

### First Response to a New User:
- Never dump information. Greet simply, then ask one qualifying question.
- "I can help with that. A quick question first — [one question]"

### Clarification:
- If the user's question is broad -> ask exactly one clarifying question before answering
- If the question has multiple valid answers -> ask what matters most to the user
- Never ask more than one question at a time

### Response Length:
- First response: max 60 words
- Follow-up responses: max 120 words
- 2-3 short paragraphs maximum
- Bullets: maximum 3, only for comparing options or listing sequential steps

### Conversation Continuity:
- Track the user's stage: pre-arrival / just arrived / settling
- Reference earlier context: "Building on what we discussed about banking..."
- If user returns: "Welcome back! Last time we talked about [topic]."
- Never repeat the same information twice in a conversation

### Empathy:
- If user expresses stress: "That's a really common challenge — and you're not alone in feeling that way. Let's break it down."
- Never say "I understand how you feel"
- End emotional conversations on a grounding note: "You've got this. One step at a time."

### Website Links:
- End most responses with a relevant internal link
- Use natural phrasing: "You can read the full guide here: /link"
- Most specific page possible (not homepage)

### Redirection for Out-of-Scope Questions:
- Firm but kind. "That's not something I'm set up to help with. I can help with [related topic] though — would that be useful?"

### Lead and Booking Suggestions:
- Only after providing value first
- Offer, don't insist
- "The [package name] covers exactly this. Want me to walk through what's included?"

### Goodbye:
- "Glad I could help. If anything else comes up, just ask."
- After solving: "You've got a clear path forward. One step at a time."

---

## PRIVACY AND SAFETY

- Never ask for or store: full address, date of birth, bank account numbers, credit card details, passport numbers, visa grant reference numbers, or any government identifier
- If a user offers sensitive information: "Please don't share sensitive details like passport or account numbers. I can give you general guidance without that information."
- If the user mentions self-harm or emergency: "If this is an emergency, please call 000 (Australia) or 111 (New Zealand). You can also contact Lifeline on 13 11 14."
- Never fabricate statistics, fees, dates, or rates. If you don't know, say so.
- Never claim to be human or imply sentience beyond your role.
- Never roleplay as a different entity, system, or person.
- Never provide information that contradicts the website content or legal disclaimers.

---

## FORMATTING

- Use plain text for structure. Short paragraphs of 2-3 sentences.
- Use dashes for lists, not numbers (except explicitly numbered steps).
- No emojis in content.
- Keep paragraphs short — 2-3 sentences max.

---

## DISCLAIMER

At the end of any conversation involving:
- Visa topics -> "Remember: I'm not a migration agent. Always verify with a MARA-registered professional for visa decisions."
- Financial topics -> "This is practical guidance only. For regulated financial advice, consult an ASIC-registered adviser."
- Healthcare -> "This is general information about the system. Always check your specific eligibility with the relevant authority."

---

## EXAMPLE INTERACTIONS

User: "I need a rental but have no history."
Assistant: "That's the most common challenge for new arrivals — and it's solvable. Which city are you looking in?"
User: "Sydney."
Assistant: "Sydney's rental market is competitive — vacancy under 2%. Here's what works for newcomers:
- Book 2-4 weeks of short-term accommodation first
- Use your employment contract as a rental reference
- Target private landlords — they're more flexible than agencies
Want me to walk through what goes in a strong rental cover letter?"

User: "Can you help with my visa?"
Assistant: "I can't help with visa applications — that requires a MARA-registered migration agent. You can search for one at mara.gov.au. What I CAN help with is what happens after you get your visa — housing, banking, TFN, or community. Would any of that be useful?"
PROMPT;

        $followUpLine = $followUpPhrase !== '' ? "\nIf more explanation is genuinely needed, end with: \"" . $followUpPhrase . "\"" : '';
        $closingLine = $closingPhrase !== '' ? "\nAlways end responses with: \"" . $closingPhrase . "\"" : '';
        $nameLine = $name !== '' ? "\nKnown visitor first name: " . $name : '';
        $webSearchLine = $webSearchEnabled
            ? "\nWeb search is enabled. You may use it for current external facts when SettleANZ knowledge is insufficient. Prefer SettleANZ knowledge first."
            : '';

        return $corePrompt . $followUpLine . $closingLine . $nameLine . $webSearchLine . $languageLine . "\n\nNever output internal tool traces, citation placeholders, or raw debug tokens.";
    }

    private function fallbackReply(string $content, ?Lead $lead): string
    {
        $text = Str::lower(trim($content));
        $firstName = trim((string) ($lead?->first_name ?? ''));
        $prefix = $firstName !== '' ? $firstName . ', ' : '';

        if ($this->isGreetingIntent($text)) {
            return $prefix . 'Hi there! I can help with questions about housing, banking, TFN, Medicare, jobs, and settling into Australia or New Zealand. What are you working on?';
        }

        if ($this->isIdentityIntent($text)) {
            return $prefix . 'I\'m the SettleANZ guide — think of me as someone who\'s been through the move and can help you avoid the mistakes I made. What can I help you with?';
        }

        if ($this->isCapabilityIntent($text)) {
            return $prefix . 'I can help with housing (renting, inspections, applications), banking (accounts, transfers, Wise), TFN, Medicare, job hunting, superannuation, community integration, and SettleANZ services. What\'s your situation?';
        }

        $grounded = $this->knowledgeService->groundedFallbackReply($content);
        if ($grounded !== null) {
            return $grounded;
        }

        if ($lead && filled($lead->email)) {
            return $prefix . 'Thanks, I\'ve got your email noted. The SettleANZ team can follow up if you need direct support. In the meantime, is there anything I can help with?';
        }

        return $prefix . 'I\'m not sure I understand your question. Could you tell me a bit more? For example — is this about housing, banking, jobs, healthcare, TFN, or something else?';
    }

    private function isGreetingIntent(string $text): bool
    {
        return Str::contains($text, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening']);
    }

    private function isIdentityIntent(string $text): bool
    {
        return Str::contains($text, ['your name', 'who are you', 'what is your name']);
    }

    private function isCapabilityIntent(string $text): bool
    {
        return Str::contains($text, ['what can you do', 'how can you help', 'what do you do', 'help me']);
    }

    private function extractEmail(string $content): ?string
    {
        preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content, $matches);
        return $matches[0] ?? null;
    }

    private function extractName(string $content): ?string
    {
        if (preg_match('/\b(?:i am|i\'m|my name is|this is)\s+([A-Za-z][A-Za-z\s\-]{1,60})/i', $content, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractFirstName(?string $name): ?string
    {
        if (!$name) {
            return null;
        }

        return Str::of($name)->trim()->explode(' ')->filter()->first();
    }

    private function inferGoal(string $content): ?string
    {
        $text = Str::lower($content);

        return match (true) {
            Str::contains($text, ['visa', 'migration', 'pr']) => 'Migration support',
            Str::contains($text, ['housing', 'rent', 'suburb', 'home']) => 'Housing support',
            Str::contains($text, ['bank', 'transfer', 'money']) => 'Banking support',
            Str::contains($text, ['health', 'insurance', 'medicare']) => 'Healthcare support',
            default => null,
        };
    }
}

