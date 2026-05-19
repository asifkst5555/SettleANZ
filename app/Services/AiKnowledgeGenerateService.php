<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiKnowledgeGenerateService
{
    public function generateEntries(string $prompt, string $category = 'general', int $count = 10): array
    {
        $apiKey = trim((string) SiteSetting::getValue('ai_openai_api_key', config('assistant.openai.api_key')));
        if ($apiKey === '') {
            return [
                'success' => false,
                'message' => 'No AI API key configured. Please set an OpenAI API key in AI & Site Settings.',
                'entries' => [],
            ];
        }

        $baseUrl = rtrim((string) SiteSetting::getValue('ai_openai_base_url', config('assistant.openai.base_url', 'https://api.openai.com/v1')), '/');
        $model = (string) SiteSetting::getValue('ai_openai_model', config('assistant.openai.model', 'gpt-4o-mini'));

        $systemPrompt = $this->buildSystemPrompt($category, $count);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 4000,
            'response_format' => ['type' => 'json_object'],
        ];

        try {
            $response = Http::timeout(60)
                ->withToken($apiKey)
                ->acceptJson()
                ->post($baseUrl . '/chat/completions', $payload);

            if (!$response->successful()) {
                Log::warning('AI knowledge generation failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'AI API request failed: ' . ($response->json('error.message') ?? 'Unknown error'),
                    'entries' => [],
                ];
            }

            $json = $response->json();
            $content = trim((string) data_get($json, 'choices.0.message.content', ''));

            if ($content === '') {
                return [
                    'success' => false,
                    'message' => 'AI returned an empty response. Please try again.',
                    'entries' => [],
                ];
            }

            $parsed = json_decode($content, true);
            if (!$parsed || !isset($parsed['entries']) || !is_array($parsed['entries'])) {
                return [
                    'success' => false,
                    'message' => 'AI response format was invalid. Please try again with a clearer prompt.',
                    'entries' => [],
                ];
            }

            $entries = [];
            foreach ($parsed['entries'] as $entry) {
                if (empty($entry['title']) || empty($entry['content'])) {
                    continue;
                }

                $entries[] = [
                    'title' => $this->sanitize($entry['title'], 255),
                    'content' => $this->sanitize($entry['content'], 5000),
                    'search_keywords' => $this->sanitize($entry['keywords'] ?? '', 500),
                    'category' => $category,
                    'priority' => (int) ($entry['priority'] ?? 0),
                ];
            }

            if (empty($entries)) {
                return [
                    'success' => false,
                    'message' => 'No valid entries were generated. Try a more specific prompt.',
                    'entries' => [],
                ];
            }

            return [
                'success' => true,
                'message' => count($entries) . ' entries generated successfully.',
                'entries' => $entries,
            ];
        } catch (\Throwable $e) {
            Log::warning('AI knowledge generation exception.', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Generation failed: ' . $e->getMessage(),
                'entries' => [],
            ];
        }
    }

    private function buildSystemPrompt(string $category, int $count): string
    {
        return implode("\n", [
            'You are an expert knowledge base creator for SettleANZ, a relocation and migration support platform for Australia and New Zealand.',
            '',
            'Your task is to generate ' . $count . ' high-quality Q&A knowledge entries based on the user\'s prompt.',
            '',
            'Each entry must have:',
            '- title: A clear, question-style or descriptive title (e.g., "How do I open a bank account in Australia?")',
            '- content: A practical, detailed answer (2-5 paragraphs or bullet points). Include real-world context, common pitfalls, and actionable steps.',
            '- keywords: 5-10 comma-separated search keywords that help match visitor questions',
            '- priority: A number 0-100 indicating importance (higher = more important)',
            '',
            'Category context: ' . $category,
            '',
            'RULES:',
            '1. Answers must be practical, grounded in real migrant experiences, and actionable.',
            '2. Use simple language. Avoid jargon unless explained.',
            '3. Include specific details (e.g., costs, timelines, requirements) where relevant.',
            '4. Reference SettleANZ pages when helpful (e.g., /banking, /housing, /migration-services, /settlement-services, /new-to-australia, /directory, /contact).',
            '5. For regulated topics (visas, legal, tax), add a disclaimer to consult a professional.',
            '6. Do not invent policies, laws, or facts. If uncertain, say so briefly.',
            '7. Each entry must be unique and cover a different aspect of the topic.',
            '',
            'RESPONSE FORMAT:',
            'Return ONLY a valid JSON object with this structure:',
            '{',
            '  "entries": [',
            '    {',
            '      "title": "Question or topic title",',
            '      "content": "Detailed answer content...",',
            '      "keywords": "keyword1, keyword2, keyword3",',
            '      "priority": 10',
            '    }',
            '  ]',
            '}',
            '',
            'Do not include any text outside the JSON object.',
        ]);
    }

    private function sanitize(string $text, int $maxLength): string
    {
        $clean = strip_tags($text);
        $clean = preg_replace('/\s+/', ' ', $clean) ?: $clean;

        return trim(substr($clean, 0, $maxLength));
    }
}
