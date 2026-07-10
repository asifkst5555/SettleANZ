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

        return $this->callAI($prompt);
    }

    public function rewriteEmail(string $content, string $tone, string $language = 'en'): array
    {
        $prompt = "Rewrite the following email content in a {$tone} tone and in {$language} language.\n\nOriginal:\n{$content}\n\nRewritten version:";

        return $this->callAI($prompt);
    }

    public function generateFollowUpEmail(array $context, string $tone = 'professional', string $language = 'en'): array
    {
        $prompt = $this->buildFollowUpPrompt($context, $tone, $language);

        return $this->callAI($prompt);
    }

    public function generateCampaignEmail(array $campaignData, string $tone = 'marketing', string $language = 'en'): array
    {
        $prompt = $this->buildCampaignPrompt($campaignData, $tone, $language);

        return $this->callAI($prompt);
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

Return a JSON with keys: "subject", "body_html", "body_text"
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

Return a JSON with keys: "subject", "body_html", "body_text"
PROMPT;
    }

    private function buildCampaignPrompt(array $data, string $tone, string $language): string
    {
        return <<<PROMPT
Generate a marketing campaign email in {$language} with a {$tone} tone.

Campaign: {$data['campaign_name']}
Ebook: {$data['ebook_title']}
Description: {$data['campaign_description']}

Return a JSON with keys: "subject", "body_html", "body_text"
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

        $json = json_decode($content, true);

        if ($json && isset($json['subject'], $json['body_html'])) {
            return $json;
        }

        return [
            'subject' => 'Your Download from ' . config('app.name'),
            'body_html' => $content,
            'body_text' => strip_tags($content),
        ];
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
}
