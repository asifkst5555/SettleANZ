<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\SiteSetting;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEmailService
{
    private string $provider;
    private string $model;
    private string $apiKey;
    private int $maxTokens;
    private float $temperature;
    private int $timeout;
    private string $baseUrl;

    public function __construct()
    {
        $this->provider = config('ebook.ai.provider', 'openai');
        $this->model = trim((string) SiteSetting::getValue('ai_openai_model', config('ebook.ai.model', 'gpt-4o')));
        $this->apiKey = trim((string) SiteSetting::getValue('ai_openai_api_key', config('ebook.ai.api_key')));
        $this->maxTokens = config('ebook.ai.max_tokens', 2000);
        $this->temperature = config('ebook.ai.temperature', 0.7);
        $this->timeout = config('ebook.ai.timeout', 60);
        $this->baseUrl = rtrim((string) SiteSetting::getValue('ai_openai_base_url', config('ebook.ai.providers.openai.url', 'https://api.openai.com/v1/chat/completions')), '/');
    }

    public function generateDownloadEmail(array $data, string $tone = 'professional', string $language = 'en'): array
    {
        $prompt = $this->buildDownloadEmailPrompt($data, $tone, $language);
        return $this->callAI($prompt, $this->getSystemPrompt());
    }

    public function rewriteEmail(string $content, string $tone, string $language = 'en'): array
    {
        $prompt = "Rewrite the following email content in a {$tone} tone and in {$language} language.\n\nOriginal:\n{$content}\n\nRewritten version:\n\nReturn ONLY valid JSON with exactly these keys:\n- \"subject\": max 80 chars\n- \"body_html\": full responsive HTML email (DOCTYPE, inline CSS, table-based, 600px max width)\n- \"body_text\": plain text version\n\nDo NOT include markdown, code fences, explanations, or any text outside the JSON. Output ONLY the JSON object.";
        return $this->callAI($prompt, $this->getSystemPrompt());
    }

    public function generateFollowUpEmail(array $context, string $tone = 'professional', string $language = 'en'): array
    {
        $prompt = $this->buildFollowUpPrompt($context, $tone, $language);
        return $this->callAI($prompt, $this->getSystemPrompt());
    }

    public function generateCampaignEmail(array $campaignData, string $tone = 'marketing', string $language = 'en'): array
    {
        $prompt = $this->buildCampaignPrompt($campaignData, $tone, $language);
        return $this->callAI($prompt, $this->getSystemPrompt());
    }

    public function copilotChat(string $message, string $systemPrompt, string $conversationHistory = ''): array
    {
        return $this->callAI($message, $systemPrompt . "\n\n" . $conversationHistory);
    }

    public function copilotChatStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature = 0.2): array
    {
        return $this->callAIStructured($message, $systemPrompt, $historyMessages, $temperature);
    }

    private function callAIStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature): array
    {
        return match ($this->provider) {
            'openai' => $this->callOpenAIStructured($message, $systemPrompt, $historyMessages, $temperature),
            'gemini' => $this->callGeminiStructured($message, $systemPrompt, $historyMessages, $temperature),
            'claude' => $this->callClaudeStructured($message, $systemPrompt, $historyMessages, $temperature),
            'deepseek' => $this->callDeepSeekStructured($message, $systemPrompt, $historyMessages, $temperature),
            default => throw new Exception("Unsupported AI provider: {$this->provider}"),
        };
    }

    private function callOpenAIStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature): array
    {
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($historyMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $url = str_ends_with($this->baseUrl, '/chat/completions')
            ? $this->baseUrl
            : $this->baseUrl . '/chat/completions';

        $response = $this->httpClient()->post($url, [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $temperature,
        ]);

        return $this->parseResponse($response->json(), 'openai');
    }

    private function callGeminiStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature): array
    {
        $contents = [];

        foreach ($historyMessages as $msg) {
            $role = $msg['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = ['role' => $role, 'parts' => [['text' => $msg['content']]]];
        }

        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        $url = config('ebook.ai.providers.gemini.url') . "?key={$this->apiKey}";

        $response = $this->httpClient()->post($url, [
            'contents' => $contents,
            'systemInstruction' => $systemPrompt ? ['parts' => [['text' => $systemPrompt]]] : null,
            'generationConfig' => [
                'maxOutputTokens' => $this->maxTokens,
                'temperature' => $temperature,
            ],
        ]);

        return $this->parseResponse($response->json(), 'gemini');
    }

    private function callClaudeStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature): array
    {
        $claudeMessages = [];

        foreach ($historyMessages as $msg) {
            $claudeMessages[] = [
                'role' => $msg['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $msg['content'],
            ];
        }

        $claudeMessages[] = ['role' => 'user', 'content' => $message];

        $response = $this->httpClient()
            ->withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])
            ->post(config('ebook.ai.providers.claude.url', 'https://api.anthropic.com/v1/messages'), [
                'model' => $this->model,
                'system' => $systemPrompt ?: 'You are a helpful email marketing assistant.',
                'messages' => $claudeMessages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $temperature,
            ]);

        return $this->parseResponse($response->json(), 'claude');
    }

    private function callDeepSeekStructured(string $message, string $systemPrompt, array $historyMessages, float $temperature): array
    {
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($historyMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = $this->httpClient()->post(config('ebook.ai.providers.deepseek.url', 'https://api.deepseek.com/v1/chat/completions'), [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $temperature,
        ]);

        return $this->parseResponse($response->json(), 'deepseek');
    }

    public function chat(string $message, string $conversationHistory = ''): array
    {
        $systemPrompt = <<<PROMPT
You are an AI Admin Assistant for an Ebook Lead Magnet & Digital Asset Delivery System.

You can help admins with:
1. Sending download emails to leads
2. Resending expired download links
3. Generating follow-up emails
4. Creating campaign emails
5. Viewing download analytics
6. Managing ebook leads

IMPORTANT RULES:
- Never share the actual file paths or internal URLs
- Never expose API keys or sensitive configuration
- Use the tools and commands available to you through the system
- When asked to send emails, confirm with the admin before sending
- Keep responses concise and actionable

Current context: Admin managing ebook downloads and leads.
PROMPT;

        return $this->callAI($message, $systemPrompt . "\n\n" . $conversationHistory);
    }

    private function buildDownloadEmailPrompt(array $data, string $tone, string $language): string
    {
        $author = $data['ebook_author'] ?? 'Our Team';
        $company = $data['company_name'] ?? config('app.name');

        return <<<PROMPT
Generate a professional download email in {$language} language with a {$tone} tone.

Lead Information:
- Name: {$data['lead_name']}
- Email: {$data['lead_email']}

Ebook Information:
- Title: {$data['ebook_title']}
- Description: {$data['ebook_description']}
- Author: {$author}

Download Link: {$data['download_url']}
Expires: {$data['expires_at']}
Company: {$company}

Please include:
1. A warm greeting using the lead's name
2. Thank them for their interest
3. Brief description of what they'll learn
4. Clear CTA with the download link
5. Note about expiration
6. Professional signature with company details
7. Unsubscribe link at the bottom

Return ONLY valid JSON with exactly these keys:
- "subject": max 80 chars, friendly and professional
- "body_html": complete responsive HTML email with DOCTYPE, inline CSS, table-based layout, max width 600px, white background
- "body_text": plain text version

Do NOT include markdown, code fences, explanations, or any text outside the JSON. Output ONLY the JSON object.
PROMPT;
    }

    private function buildFollowUpPrompt(array $context, string $tone, string $language): string
    {
        return <<<PROMPT
Generate a follow-up email in {$language} with a {$tone} tone.

Context:
- Lead Name: {$context['lead_name']}
- Ebook: {$context['ebook_title']}
- Days since download: {$context['days_since_download']}
- Download Count: {$context['download_count']}

Return ONLY valid JSON with exactly these keys:
- "subject": max 80 chars, friendly and professional
- "body_html": complete responsive HTML email with DOCTYPE, inline CSS, table-based layout, max width 600px, white background
- "body_text": plain text version

Do NOT include markdown, code fences, explanations, or any text outside the JSON. Output ONLY the JSON object.
PROMPT;
    }

    public function generateAutoReplyEmail(array $data, string $tone = 'warm', string $language = 'en'): array
    {
        $leadEmail = $data['lead_email'] ?? 'unknown';
        $formType = $data['form_type'] ?? 'unknown';

        Log::debug('[TRACE] AiEmailService::generateAutoReplyEmail', [
            'form_type' => $formType,
            'lead_email' => $leadEmail,
            'provider' => $this->provider,
            'model' => $this->model,
            'api_key_set' => !empty($this->apiKey),
            'api_key_length' => strlen($this->apiKey),
        ]);

        $prompt = $this->buildAutoReplyPrompt($data, $tone, $language);

        Log::debug('[TRACE] Calling AiEmailService::callAI', [
            'form_type' => $formType,
            'prompt_length' => strlen($prompt),
        ]);

        $start = microtime(true);
        try {
            $result = $this->callAI($prompt);
            $duration = round((microtime(true) - $start) * 1000);
            Log::debug('[TRACE] AiEmailService::callAI succeeded', [
                'form_type' => $formType,
                'duration_ms' => $duration,
                'has_subject' => isset($result['subject']),
                'has_body_html' => isset($result['body_html']),
            ]);
            return $result;
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $start) * 1000);
            Log::debug('[TRACE] AiEmailService::callAI FAILED', [
                'form_type' => $formType,
                'duration_ms' => $duration,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
            ]);
            throw $e;
        }
    }

    private function buildAutoReplyPrompt(array $data, string $tone, string $language): string
    {
        $formType = $data['form_type'] ?? 'general';
        $formLabel = match ($formType) {
            'contact-page' => 'Contact Form enquiry',
            'package_booking' => 'Settlement Package booking request',
            'homepage_roadmap' => 'Roadmap download request',
            'ebook_download' => 'Ebook download request',
            'ai_chat' => 'AI Chat consultation',
            default => 'enquiry',
        };

        $name = $data['lead_name'] ?? 'there';
        $company = $data['company_name'] ?? config('app.name');

        return <<<PROMPT
Generate a warm, professional auto-reply email in {$language} language with a {$tone} tone.

Context:
- This is an automated acknowledgment for a {$formLabel} submitted on {$company}.
- Lead name: {$name}
- Form type: {$formType}

The email must:
1. Greet the lead by name warmly
2. Acknowledge receipt of their {$formLabel}
3. Reassure them that our team has received their request
4. State clearly that a team member will respond within 24 hours
5. If it's a contact enquiry, mention that we'll review their message carefully
6. If it's a package booking, mention that we'll confirm their session details
7. Close with a professional signature: "Warm regards, The {$company} Team"
8. Include a brief P.S. that they can reply to this email if they have urgent questions

Important:
- Keep the tone warm and human, not robotic
- Do NOT include download links or promotional content
- Do NOT mention AI or automation
- Use the lead's name naturally in the greeting

Return ONLY valid JSON with exactly these keys:
- "subject": a concise, friendly subject line (max 80 chars)
- "body_html": complete responsive HTML email with DOCTYPE, inline CSS, table-based layout, max width 600px, white card background (#FFFFFF), body background #F8F4EC, professional typography (Arial/Helvetica/sans-serif), heading 28px, body 16px, primary color #0F766E
- "body_text": plain text version

Do NOT include markdown, code fences, explanations, or any text outside the JSON. Output ONLY the JSON object.
PROMPT;
    }

    private function buildCampaignPrompt(array $data, string $tone, string $language): string
    {
        return <<<PROMPT
Generate a marketing campaign email in {$language} with a {$tone} tone.

Campaign: {$data['campaign_name']}
Ebook: {$data['ebook_title']}
Description: {$data['campaign_description']}

Return ONLY valid JSON with exactly these keys:
- "subject": max 80 chars
- "body_html": complete responsive HTML email with DOCTYPE, inline CSS, table-based layout, max width 600px, white card
- "body_text": plain text version

Do NOT include markdown, code fences, explanations, or any text outside the JSON. Output ONLY the JSON object.
PROMPT;
    }

    private function callAI(string $prompt, ?string $systemPrompt = null): array
    {
        return match ($this->provider) {
            'openai' => $this->callOpenAI($prompt, $systemPrompt),
            'gemini' => $this->callGemini($prompt, $systemPrompt),
            'claude' => $this->callClaude($prompt, $systemPrompt),
            'deepseek' => $this->callDeepSeek($prompt, $systemPrompt),
            default => throw new Exception("Unsupported AI provider: {$this->provider}"),
        };
    }

    private function callOpenAI(string $prompt, ?string $systemPrompt = null): array
    {
        $messages = [];

        if ($systemPrompt) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        $url = str_ends_with($this->baseUrl, '/chat/completions')
            ? $this->baseUrl
            : $this->baseUrl . '/chat/completions';

        $response = $this->httpClient()->post($url, [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
            'response_format' => ['type' => 'json_object'],
        ]);

        return $this->parseResponse($response->json(), 'openai');
    }

    private function callGemini(string $prompt, ?string $systemPrompt = null): array
    {
        $contents = [];

        if ($systemPrompt) {
            $contents[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt]]];
        }

        $contents[] = ['role' => 'user', 'parts' => [['text' => $prompt]]];

        $url = config('ebook.ai.providers.gemini.url') . "?key={$this->apiKey}";

        $response = $this->httpClient()->post($url, [
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ],
        ]);

        return $this->parseResponse($response->json(), 'gemini');
    }

    private function callClaude(string $prompt, ?string $systemPrompt = null): array
    {
        $response = $this->httpClient()
            ->withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])
            ->post(config('ebook.ai.providers.claude.url', 'https://api.anthropic.com/v1/messages'), [
                'model' => $this->model,
                'system' => $systemPrompt ?? 'You are a helpful email marketing assistant.',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

        return $this->parseResponse($response->json(), 'claude');
    }

    private function callDeepSeek(string $prompt, ?string $systemPrompt = null): array
    {
        $messages = [];

        if ($systemPrompt) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        $response = $this->httpClient()->post(config('ebook.ai.providers.deepseek.url', 'https://api.deepseek.com/v1/chat/completions'), [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ]);

        return $this->parseResponse($response->json(), 'deepseek');
    }

    private function parseResponse(array $response, string $provider): array
    {
        $content = match ($provider) {
            'openai', 'deepseek' => $response['choices'][0]['message']['content'] ?? '',
            'gemini' => $response['candidates'][0]['content']['parts'][0]['text'] ?? '',
            'claude' => $response['content'][0]['text'] ?? '',
            default => throw new Exception("Unsupported provider for response parsing: {$provider}"),
        };

        $content = $this->stripReasoningText($content);
        $content = preg_replace('/^```(?:json)?\s*\n?(.*?)\n?```$/s', '$1', trim($content));

        $json = $this->decodeEmailJson($content);
        if ($json) {
            return $json;
        }

        foreach ($this->extractJsonCandidates($content) as $candidate) {
            $json = $this->decodeEmailJson($candidate);
            if ($json) {
                return $json;
            }
        }

        Log::warning('AI email response did not contain valid email JSON; using safe fallback.', [
            'provider' => $provider,
            'content_preview' => mb_substr($content, 0, 500),
        ]);

        return [
            'subject' => 'Thank you from SettleANZ',
            'body_html' => $this->safeFallbackEmailHtml(),
            'body_text' => "Hi there,\n\nThank you for your interest in SettleANZ. We received your request and our team will follow up shortly.\n\nWarm regards,\nThe SettleANZ Team",
        ];
    }

    private function stripReasoningText(string $content): string
    {
        $content = trim($content);
        $content = preg_replace('/<think>.*?<\/think>/is', '', $content) ?? $content;
        return trim($content);
    }

    private function decodeEmailJson(string $content): ?array
    {
        $json = json_decode(trim($content), true);
        if (!is_array($json) || !isset($json['subject'], $json['body_html'])) {
            return null;
        }

        $subject = trim((string) $json['subject']);
        $bodyHtml = $this->normalizeEmailBody((string) $json['body_html']);
        $bodyText = isset($json['body_text']) && trim((string) $json['body_text']) !== ''
            ? $this->normalizePlainText((string) $json['body_text'])
            : $this->normalizePlainText(strip_tags($bodyHtml));

        if ($subject === '' || $bodyHtml === '') {
            return null;
        }

        if (!str_contains($bodyHtml, '<!DOCTYPE') && !str_contains($bodyHtml, '<html')) {
            $bodyHtml = $this->wrapInEmailHtml($bodyHtml);
        }

        return [
            'subject' => mb_substr($subject, 0, 80),
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    private function extractJsonCandidates(string $content): array
    {
        $candidates = [];
        $start = strpos($content, '{');
        $end = strrpos($content, '}');

        if ($start !== false && $end !== false && $end > $start) {
            $candidates[] = substr($content, $start, $end - $start + 1);
        }

        if (preg_match_all('/\{(?:(?:[^{}]|(?R))*)\}/s', $content, $matches)) {
            $candidates = array_merge($candidates, $matches[0]);
        }

        return array_values(array_unique($candidates));
    }

    private function normalizeEmailBody(string $body): string
    {
        $body = trim($body);
        $body = str_replace(["\\r\\n", "\\n", "\\r", "\\t"], ["\n", "\n", "\n", "\t"], $body);
        return $body;
    }

    private function normalizePlainText(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\\r\\n", "\\n", "\\r", "\\t"], ["\n", "\n", "\n", "\t"], $text);
        return trim(preg_replace("/\n{3,}/", "\n\n", $text) ?? $text);
    }

    private function ensureValidEmailHtml(string $html): string
    {
        if (str_contains($html, '<!DOCTYPE') || str_contains($html, '<html')) {
            return $html;
        }
        return $this->wrapInEmailHtml($html);
    }

    private function wrapInEmailHtml(string $bodyContent): string
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#F8F4EC;font-family:Arial,Helvetica,sans-serif;">
<center style="width:100%;background-color:#F8F4EC;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F4EC;">
<tr>
<td align="center" style="padding:32px 16px;">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;border:1px solid #E5E7EB;">
<tr>
<td style="padding:40px;font-size:16px;line-height:1.6;color:#1F2937;">
' . $bodyContent . '
</td>
</tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>';
    }

    private function safeFallbackEmailHtml(): string
    {
        return $this->wrapInEmailHtml('<p style="margin:0 0 16px;">Hi there,</p><p style="margin:0 0 16px;">Thank you for your interest in SettleANZ. We received your request and our team will follow up shortly.</p><p style="margin:0;">Warm regards,<br>The SettleANZ Team</p>');
    }

    private function httpClient(): PendingRequest
    {
        return Http::timeout($this->timeout)
            ->withToken($this->apiKey)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->throw();
    }

    public function getConversationHistory(AiConversation $conversation): string
    {
        return $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn (AiMessage $msg) => "{$msg->role}: {$msg->content}")
            ->implode("\n");
    }

    public function getAvailableModels(): array
    {
        return [
            'openai' => ['gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo'],
            'gemini' => ['gemini-pro', 'gemini-1.5-pro', 'gemini-1.5-flash'],
            'claude' => ['claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307'],
            'deepseek' => ['deepseek-chat', 'deepseek-reasoner'],
        ];
    }

    private function getSystemPrompt(): string
    {
        return 'You are a professional email template generator. You output ONLY valid JSON. Never include markdown, code fences (```json), explanations, reasoning, or any text outside the JSON object. Your response must be parseable by json_decode() with no modification. The JSON must have exactly three keys: "subject" (string, max 80 chars), "body_html" (string, complete responsive HTML email document), "body_text" (string, plain text version).';
    }
}
