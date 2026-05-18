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
            return 'To start a business listing enquiry, go to ' . $applyLink . '. If you prefer direct help, you can also contact SettleANZ through /contact by email at ' . $contactEmail . ' or via WhatsApp. ' . $responseTime;
        }

        if (Str::contains($text, ['contact your team', 'contact the team', 'contact you directly', 'speak to your team', 'human support'])) {
            return 'You can contact the SettleANZ team directly through /contact. That page is the right place for general enquiries, business listings, partnerships, media enquiries, or any request that needs human follow-up. You can reach the team by email at ' . $contactEmail . ' or via WhatsApp. ' . $responseTime;
        }

        if (Str::contains($text, ['response time', 'how fast', 'how quickly', 'when will you reply'])) {
            return $responseTime;
        }

        if (Str::contains($text, ['migration consultation', 'book migration help', 'book a consultation', 'migration agent'])) {
            return 'For migration help, use /migration-services. That page explains common visa types, why registered migration agents matter, and how to request a consultation with a featured migration partner.';
        }

        if (Str::contains($text, ['whatsapp', 'chat on whatsapp', 'whatsapp help'])) {
            return 'You can reach SettleANZ on WhatsApp from the Contact page at /contact. The site also uses WhatsApp links on key support sections for quick contact.';
        }

        if (Str::contains($text, ['email address', 'contact email', 'your email', 'email you'])) {
            return 'You can contact SettleANZ by email at ' . $contactEmail . '.';
        }

        if (Str::contains($text, ['directory categories', 'categories in your directory', 'what categories are in your directory'])) {
            return 'The main directory categories are Immigration Lawyers, Relocation Companies, Financial Advisors, Healthcare, International Schools, and Real Estate Agents. You can explore them on /directory.';
        }

        if (Str::contains($text, ['blog topics', 'what is in the blog', 'what does the blog cover'])) {
            return 'The blog covers practical expat topics including banking, housing, moving, healthcare, working, and lifestyle. You can browse everything on /blog.';
        }

        if (Str::contains($text, ['what can this website help me with', 'what does this website do', 'what services do you offer overall', 'what can settleanz help with overall'])) {
            return 'SettleANZ helps with migration guidance, housing help, banking guidance, healthcare guidance, relocation checklists, blog guides, trusted directory listings, and direct contact support for people settling in Australia and New Zealand.';
        }

        if (Str::contains($text, ['settlement service', 'settlement plan', 'what to do first', 'before you land', 'hit the ground running'])) {
            return 'The Settlement Services page at /settlement-services is your central hub. It covers what to do first, pre-arrival preparation, family settlement planning, and step-by-step guidance for banking, housing, healthcare, and more. It is the best starting point for new arrivals.';
        }

        if (Str::contains($text, ['about settleanz', 'who are you', 'what is settleanz', 'tell me about this site'])) {
            return 'SettleANZ is a practical relocation and migration support platform for people settling in Australia and New Zealand. Visit /about to learn our mission and story, or explore /settlement-services for step-by-step settlement guidance.';
        }

        if (Str::contains($text, ['privacy', 'data protection', 'how you use my data'])) {
            return 'You can read our Privacy Policy at /privacy-policy. It explains how SettleANZ collects, uses, stores, and protects your personal data including contact forms, chat conversations, and directory listings.';
        }

        if (Str::contains($text, ['terms', 'terms of service', 'legal', 'disclaimer'])) {
            return 'Our Terms of Service are available at /terms-of-service. They outline the legal terms, disclaimers, and conditions for using SettleANZ website, guides, directory, and AI assistant.';
        }

        if (Str::contains($text, ['before arriving', 'before i land', 'pre-arrival', 'prepare before'])) {
            return 'Before arriving in Australia, you should research suburbs, understand visa conditions, prepare documents, arrange initial accommodation, research banking options, and understand healthcare coverage. The Settlement Services page at /settlement-services and New to Australia guide at /new-to-australia have detailed pre-arrival checklists.';
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

        return implode("\n", array_filter([
            'You are SettleANZ AI Assistant for website visitors.',
            'You help new arrivals settling in Australia and New Zealand, but you can also answer normal general questions in a natural, helpful way.',
            'Use the supplied SettleANZ website knowledge as your primary source of truth for site-specific guidance, page paths, blog content, and directory recommendations.',
            'Be strictly faithful to the supplied website knowledge. Do not invent features, steps, policies, timelines, or promises that are not clearly supported by that knowledge.',
            $webSearchEnabled
                ? 'When the user needs current external facts, recent policy changes, or broader public-web context, you may use web search. Prefer SettleANZ knowledge first, then supplement with web search.'
                : 'If a user asks a general non-site question, answer it naturally from model knowledge. For site-specific questions, stay grounded in the supplied SettleANZ website knowledge.',
            'Do not redirect to Contact unless the user clearly needs human help, regulated professional support, or asks for direct contact.',
            'For basic conversational prompts like greetings, your name, what you do, or casual questions, respond like a real assistant and do not turn the answer into a page recommendation.',
            'Answer clearly about migration, housing, banking, healthcare, relocation checklists, directory partners, contact pathways, blog guides, settlement services, and general guidance questions.',
            'Keep replies concise, practical, warm, and trustworthy.',
            'Preferred response style: short direct answer first, then practical next steps.',
            'Use plain English. Avoid jargon unless needed.',
            'Never output internal tool traces, citation placeholders, or raw debug tokens.',
            'When describing a process, use cautious wording such as "you can", "the page lets you", or "the site suggests" unless a step is explicitly confirmed.',
            'If a detail is unclear, say that briefly instead of guessing.',
            'If the visitor asks for regulated visa advice, remind them SettleANZ can connect them with migration professionals and avoid pretending to be a lawyer or migration agent.',
            'When helpful, suggest the most relevant SettleANZ page path such as /, /new-to-australia, /settlement-services, /housing, /banking, /migration-services, /about, /blog, /directory, /contact, /privacy-policy, or /terms-of-service.',
            'If website knowledge is incomplete and web search is unavailable, say so briefly and then still try to be helpful.',
            'If web search is used, include up to 3 high-quality source links at the end.',
            'If the visitor shares an email, thank them and say the team can follow up.',
            $name !== '' ? 'Known visitor first name: ' . $name : null,
            $customInstruction !== '' ? 'Custom site instruction: ' . $customInstruction : null,
            'Do not use markdown tables.',
        ]));
    }

    private function fallbackReply(string $content, ?Lead $lead): string
    {
        $text = Str::lower(trim($content));
        $firstName = trim((string) ($lead?->first_name ?? ''));
        $greeting = $firstName !== '' ? $firstName . ', ' : '';

        if ($this->isGreetingIntent($text)) {
            return $greeting . 'Hi, I am the SettleANZ AI Assistant. I can help with migration, housing, banking, healthcare, settling-in checklists, directory services, and general questions too. What would you like help with?';
        }

        if ($this->isIdentityIntent($text)) {
            return $greeting . 'I am the SettleANZ AI Assistant. I help visitors with settling in Australia and New Zealand, and I can also answer general questions where useful.';
        }

        if ($this->isCapabilityIntent($text)) {
            return $greeting . 'I can help with migration guidance, housing, banking, healthcare, newcomer checklists, blog guides, directory listings, and general questions. If you want, ask me something specific and I will answer directly.';
        }

        $grounded = $this->knowledgeService->groundedFallbackReply($content);
        if ($grounded !== null) {
            return $grounded;
        }

        if ($lead && filled($lead->email)) {
            return $greeting . 'Thanks, I have your email noted. I can keep guiding you here, and if you need direct support the SettleANZ team can also follow up through the Contact page.';
        }

        return $greeting . 'I can help with settling-in questions and general guidance. Ask me something specific about migration, housing, banking, healthcare, work, everyday life, or the SettleANZ guides, and I will answer as clearly as I can.';
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

