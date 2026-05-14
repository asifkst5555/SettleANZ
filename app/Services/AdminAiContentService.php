<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use RuntimeException;

class AdminAiContentService
{
    protected const SEO_TITLE_MIN = 50;
    protected const SEO_TITLE_MAX = 60;
    protected const SEO_DESCRIPTION_MIN = 140;
    protected const SEO_DESCRIPTION_MAX = 160;

    public function generateBlogDraft(array $input): array
    {
        $contentContext = $this->buildBlogContentContext($input);
        $promptContext = $this->buildBlogPromptContext($contentContext, true);

        $prompt = implode("\n", [
            'Generate a structured JSON object for a SettleANZ blog post draft.',
            'Audience: newcomers, migrants, expats, and families settling in Australia.',
            'Tone: practical, warm, trustworthy, specific, not hypey.',
            'Return JSON only.',
            'Fields: title, excerpt, body_html, reading_time, category.',
            'body_html must be valid HTML using only h2, h3, p, ul, ol, li, blockquote, strong, em, a tags.',
            'Do not wrap HTML in markdown fences.',
            'reading_time should look like "8 min read".',
            'Do not invent claims, services, numbers, or steps that are not supported by the provided content.',
            'If the user already provided a title, use it and improve around it instead of replacing it unnecessarily.',
            'Context JSON:',
            json_encode($promptContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);

        try {
            $result = $this->requestStructuredJson(
                system: 'You are an expert SEO content strategist and migration content writer for SettleANZ. Return valid JSON only.',
                user: $prompt,
                keys: ['title', 'excerpt', 'body_html', 'reading_time', 'category'],
            );
        } catch (\Throwable $e) {
            Log::warning('AI blog draft generation failed, using local fallback draft.', [
                'message' => $e->getMessage(),
            ]);

            return $this->buildFallbackBlogDraft($contentContext);
        }

        return $this->normalizeGeneratedBlogDraft($result, $contentContext, 'ai');
    }

    public function generateBlogSeo(array $input): array
    {
        $contentContext = $this->buildBlogContentContext($input);
        $promptContext = $this->buildBlogPromptContext($contentContext, false);

        $prompt = implode("\n", [
            'Generate SEO fields for a SettleANZ blog post.',
            'Return JSON only.',
            'Fields: meta_title, meta_description, focus_keyword, secondary_keywords, og_title, og_description, schema_type, faq_items.',
            'Optimize for Google title and snippet best practices.',
            'Prefer realistic wording over keyword stuffing.',
            'Base every field on the provided article title, excerpt, and body only.',
            'meta_title must be between 50 and 60 characters.',
            'meta_description must be between 140 and 160 characters.',
            'Create exactly 3 FAQ items in faq_items based only on the main article content. Each item must have question and answer keys.',
            'The FAQs must be genuinely answerable from the article content and should not repeat the exact same wording.',
            'Do not invent facts or advice that are not supported by the article.',
            'schema_type should usually be "Article" or "BlogPosting".',
            'secondary_keywords should be a comma-separated string.',
            'Context JSON:',
            json_encode($promptContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);

        $result = $this->requestStructuredJson(
            system: 'You are an expert SEO strategist for migration and relocation content. Return valid JSON only.',
            user: $prompt,
            keys: ['meta_title', 'meta_description', 'focus_keyword', 'secondary_keywords', 'og_title', 'og_description', 'schema_type', 'faq_items'],
        );

        return $this->normalizeGeneratedBlogSeo($result, $contentContext);
    }

    public function generatePageSeo(array $input): array
    {
        $pageContext = $this->buildPageSeoContext($input);

        $prompt = implode("\n", [
            'Generate SEO fields for a SettleANZ website page.',
            'Return JSON only.',
            'Fields: meta_title, meta_description, focus_keyword, secondary_keywords, og_title, og_description, schema_type.',
            'Optimize for Google title and snippet best practices.',
            'Match the page intent and avoid spammy wording.',
            'Base every field on the provided page label, URL, default title, and default description only.',
            'meta_title must be between 50 and 60 characters.',
            'meta_description must be between 140 and 160 characters.',
            'schema_type should fit the page type such as WebPage, AboutPage, ContactPage, Service, FAQPage, or Article.',
            'secondary_keywords should be a comma-separated string.',
            'Context JSON:',
            json_encode($pageContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);

        $result = $this->requestStructuredJson(
            system: 'You are an expert technical SEO strategist for website pages. Return valid JSON only.',
            user: $prompt,
            keys: ['meta_title', 'meta_description', 'focus_keyword', 'secondary_keywords', 'og_title', 'og_description', 'schema_type'],
        );

        return $this->normalizeGeneratedPageSeo($result, $pageContext);
    }

    protected function requestStructuredJson(string $system, string $user, array $keys): array
    {
        $apiKey = trim((string) SiteSetting::getValue('ai_openai_api_key', config('assistant.openai.api_key')));
        if ($apiKey === '') {
            throw new RuntimeException('AI is not configured yet. Please add an API key in API Integration Settings first.');
        }

        $baseUrl = rtrim((string) SiteSetting::getValue('ai_openai_base_url', config('assistant.openai.base_url', 'https://api.openai.com/v1')), '/');
        $model = (string) SiteSetting::getValue('ai_openai_model', config('assistant.openai.model', 'gpt-4o-mini'));

        $text = $this->shouldUseGroqChatJsonMode($baseUrl, $model)
            ? $this->requestViaChatCompletions($apiKey, $baseUrl, $model, $system, $user)
            : $this->requestViaResponses($apiKey, $baseUrl, $model, $system, $user, $keys);

        $json = $this->extractJson($text);
        $decoded = json_decode($json, true);

        if (!is_array($decoded)) {
            throw new RuntimeException('The AI response could not be parsed into structured fields.');
        }

        $result = [];
        foreach ($keys as $key) {
            $value = $decoded[$key] ?? '';
            if ($key === 'faq_items') {
                $result[$key] = $this->normalizeFaqItems($value);
                continue;
            }

            $result[$key] = is_scalar($value) ? trim((string) $value) : '';
        }

        return $result;
    }

    protected function buildBlogContentContext(array $input): array
    {
        $bodyHtml = trim((string) ($input['body_html'] ?? ''));
        $bodyText = $this->plainText($bodyHtml);

        return [
            'title' => trim((string) ($input['title'] ?? '')),
            'slug' => trim((string) ($input['slug'] ?? '')),
            'category' => trim((string) ($input['category'] ?? '')),
            'excerpt' => $this->cleanText((string) ($input['excerpt'] ?? '')),
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
            'body_text_excerpt' => Str::limit($bodyText, 2500, ''),
            'headings' => $this->extractHeadings($bodyHtml),
        ];
    }

    protected function buildBlogPromptContext(array $contentContext, bool $forDraft): array
    {
        $context = [
            'title' => (string) ($contentContext['title'] ?? ''),
            'slug' => (string) ($contentContext['slug'] ?? ''),
            'category' => (string) ($contentContext['category'] ?? ''),
            'excerpt' => (string) ($contentContext['excerpt'] ?? ''),
            'headings' => $contentContext['headings'] ?? [],
            'body_text_excerpt' => (string) ($contentContext['body_text_excerpt'] ?? ''),
        ];

        if ($forDraft) {
            $context['body_html_excerpt'] = Str::limit((string) ($contentContext['body_html'] ?? ''), 2000, '');
            $context['task_hint'] = 'If the existing content is thin or partial, expand it into a complete practical draft. If it is already complete, improve clarity without changing the intent.';
        }

        return $context;
    }

    protected function buildPageSeoContext(array $input): array
    {
        return [
            'page_label' => $this->cleanText((string) ($input['page_label'] ?? '')),
            'page_url' => $this->cleanText((string) ($input['page_url'] ?? '')),
            'default_title' => $this->cleanText((string) ($input['default_title'] ?? '')),
            'default_description' => $this->cleanText((string) ($input['default_description'] ?? '')),
            'meta_title' => $this->cleanText((string) ($input['meta_title'] ?? '')),
            'meta_description' => $this->cleanText((string) ($input['meta_description'] ?? '')),
            'schema_type' => $this->cleanText((string) ($input['schema_type'] ?? '')),
        ];
    }

    protected function normalizeGeneratedBlogSeo(array $result, array $contentContext): array
    {
        $baseTitle = $this->cleanText($result['title'] ?? $contentContext['title'] ?? '');
        $focusKeyword = $this->normalizeFocusKeyword($result['focus_keyword'] ?? '', $baseTitle, $contentContext);
        $result['focus_keyword'] = $focusKeyword;
        $result['secondary_keywords'] = $this->normalizeSecondaryKeywords($result['secondary_keywords'] ?? '', $focusKeyword);

        $result['meta_title'] = $this->normalizeSeoTitle(
            $result['meta_title'] ?? '',
            $baseTitle,
            $focusKeyword,
            $contentContext
        );

        $result['meta_description'] = $this->normalizeSeoDescription(
            $result['meta_description'] ?? '',
            $contentContext,
            $focusKeyword
        );

        $result['og_title'] = $this->normalizeOgTitle($result['og_title'] ?? '', $result['meta_title']);
        $result['og_description'] = $this->normalizeOgDescription($result['og_description'] ?? '', $result['meta_description']);
        $result['schema_type'] = $this->normalizeSchemaType($result['schema_type'] ?? '');
        $result['faq_items'] = $this->buildFaqItems(
            $result['faq_items'] ?? [],
            $contentContext,
            $focusKeyword
        );

        return $result;
    }

    protected function normalizeGeneratedBlogDraft(array $result, array $contentContext, string $source = 'ai'): array
    {
        $normalized = $this->normalizeGeneratedBlogSeo($result, $contentContext);

        $normalized['title'] = $this->cleanText((string) ($normalized['title'] ?? ''));
        if ($normalized['title'] === '') {
            $normalized['title'] = $this->cleanText((string) ($contentContext['title'] ?? ''));
        }

        $normalized['excerpt'] = $this->cleanText((string) ($normalized['excerpt'] ?? ''));
        if ($normalized['excerpt'] === '') {
            $normalized['excerpt'] = Str::limit(
                $this->cleanText((string) ($contentContext['excerpt'] ?? $contentContext['body_text'] ?? '')),
                400,
                ''
            );
        }

        $bodyHtml = trim((string) ($normalized['body_html'] ?? ''));
        if ($bodyHtml === '') {
            $bodyHtml = trim((string) ($contentContext['body_html'] ?? ''));
        }
        $normalized['body_html'] = $bodyHtml;

        $readingTime = $this->cleanText((string) ($normalized['reading_time'] ?? ''));
        if ($readingTime === '') {
            $readingTime = $this->estimateReadingTime((string) ($contentContext['body_text'] ?? ''));
        }
        $normalized['reading_time'] = $readingTime;

        $category = $this->cleanText((string) ($normalized['category'] ?? ''));
        if ($category === '') {
            $category = $this->cleanText((string) ($contentContext['category'] ?? ''));
        }
        $normalized['category'] = $category;

        $normalized['faq_items'] = $this->buildFaqItems([], $contentContext, (string) ($normalized['focus_keyword'] ?? ''));
        $normalized['_meta'] = [
            'source' => $source,
            'used_ai' => $source === 'ai',
        ];

        return $normalized;
    }

    protected function normalizeGeneratedPageSeo(array $result, array $pageContext): array
    {
        $baseTitle = $this->cleanText((string) ($pageContext['meta_title'] ?: $pageContext['default_title'] ?: $pageContext['page_label'] ?? ''));
        $focusKeyword = $this->normalizePageFocusKeyword($result['focus_keyword'] ?? '', $pageContext);

        $result['focus_keyword'] = $focusKeyword;
        $result['secondary_keywords'] = $this->normalizeSecondaryKeywords($result['secondary_keywords'] ?? '', $focusKeyword);
        $result['meta_title'] = $this->normalizeSeoTitle(
            $result['meta_title'] ?? '',
            $baseTitle,
            $focusKeyword,
            [
                'title' => $baseTitle,
                'slug' => trim((string) ($pageContext['page_url'] ?? '')),
            ]
        );
        $result['meta_description'] = $this->normalizePageSeoDescription(
            $result['meta_description'] ?? '',
            $pageContext,
            $focusKeyword
        );
        $result['og_title'] = $this->normalizeOgTitle($result['og_title'] ?? '', $result['meta_title']);
        $result['og_description'] = $this->normalizeOgDescription($result['og_description'] ?? '', $result['meta_description']);
        $result['schema_type'] = $this->normalizePageSchemaType($result['schema_type'] ?? '', $pageContext);

        return $result;
    }

    protected function buildFallbackBlogDraft(array $contentContext): array
    {
        $title = $this->cleanText((string) ($contentContext['title'] ?? ''));
        $excerpt = $this->cleanText((string) ($contentContext['excerpt'] ?? ''));
        $bodyHtml = trim((string) ($contentContext['body_html'] ?? ''));
        $bodyText = $this->cleanText((string) ($contentContext['body_text'] ?? ''));
        $category = $this->cleanText((string) ($contentContext['category'] ?? ''));

        if ($title === '') {
            $title = 'New to Australia: Practical Steps to Get Started';
        }

        if ($excerpt === '') {
            $excerpt = Str::limit($bodyText !== '' ? $bodyText : 'Practical guidance for migrants and new arrivals in Australia.', 400, '');
        }

        if ($bodyHtml === '') {
            $paragraph = $bodyText !== '' ? e($bodyText) : 'Start with the most urgent tasks first, gather the right documents, and work through each step in a practical order.';
            $bodyHtml = '<h2>Start with the essentials</h2><p>' . $paragraph . '</p>'
                . '<h2>Plan your next steps</h2><p>Focus on the key actions first, then move through the process one step at a time so nothing important gets missed.</p>'
                . '<h2>Avoid common mistakes</h2><p>Check requirements early, keep copies of your documents, and confirm costs and timelines before you commit.</p>';
        }

        $draft = [
            'title' => $title,
            'excerpt' => $excerpt,
            'body_html' => $bodyHtml,
            'reading_time' => $this->estimateReadingTime($bodyText !== '' ? $bodyText : strip_tags($bodyHtml)),
            'category' => $category !== '' ? $category : 'Settlement',
            'meta_title' => '',
            'meta_description' => '',
            'focus_keyword' => '',
            'secondary_keywords' => '',
            'og_title' => '',
            'og_description' => '',
            'schema_type' => 'Article',
        ];

        $normalized = $this->normalizeGeneratedBlogDraft($draft, $contentContext, 'fallback');
        $normalized['_meta']['warning'] = 'The AI API failed, so a basic fallback draft was created instead.';
        $normalized['_meta']['used_ai'] = false;

        return $normalized;
    }

    protected function normalizeFocusKeyword(string $keyword, string $title, array $contentContext): string
    {
        $clean = $this->cleanText($keyword);
        if ($clean !== '') {
            return Str::limit($clean, 120, '');
        }

        $source = $this->cleanText($title !== '' ? $title : ($contentContext['title'] ?? ''));
        if ($source === '') {
            $source = $this->cleanText((string) ($contentContext['slug'] ?? ''));
        }

        $source = preg_replace('/\bsettleanz\b/i', '', $source ?? '');
        $source = preg_replace('/\b(blog|guide|article)\b/i', '', $source ?? '');
        $source = preg_replace('/\s+/', ' ', (string) $source);

        return Str::limit(trim((string) $source), 120, '');
    }

    protected function normalizePageFocusKeyword(string $keyword, array $pageContext): string
    {
        $clean = $this->cleanText($keyword);
        if ($clean !== '') {
            return Str::limit($clean, 120, '');
        }

        $source = $this->cleanText((string) ($pageContext['page_label'] ?? ''));
        if ($source === '') {
            $source = $this->cleanText((string) ($pageContext['default_title'] ?? ''));
        }
        if ($source === '') {
            $source = trim(str_replace(['/', '-'], ' ', (string) ($pageContext['page_url'] ?? '')));
        }

        $source = preg_replace('/\bsettleanz\b/i', '', $source ?? '');
        $source = preg_replace('/\s+/', ' ', (string) $source);

        return Str::limit(trim((string) $source), 120, '');
    }

    protected function normalizeSecondaryKeywords(string $keywords, string $focusKeyword): string
    {
        $items = collect(explode(',', $keywords))
            ->map(fn ($item) => $this->cleanText($item))
            ->filter()
            ->reject(fn ($item) => Str::lower($item) === Str::lower($focusKeyword))
            ->unique(fn ($item) => Str::lower($item))
            ->take(6)
            ->values();

        return $items->implode(', ');
    }

    protected function normalizeSeoTitle(string $candidate, string $title, string $focusKeyword, array $contentContext): string
    {
        $candidate = $this->cleanText($candidate);
        $title = $this->cleanText($title !== '' ? $title : ($contentContext['title'] ?? ''));

        $options = collect([
            $candidate,
            $title,
            $focusKeyword !== '' ? $focusKeyword . ' for New Arrivals' : '',
            $title !== '' ? $title . ' | SettleANZ' : '',
            $focusKeyword !== '' ? $focusKeyword . ' | SettleANZ' : '',
        ])->map(fn ($item) => $this->cleanText((string) $item))
            ->filter()
            ->unique()
            ->values();

        foreach ($options as $option) {
            $withinRange = $this->fitToRange($option, self::SEO_TITLE_MIN, self::SEO_TITLE_MAX);
            if ($this->stringLength($withinRange) >= self::SEO_TITLE_MIN && $this->stringLength($withinRange) <= self::SEO_TITLE_MAX) {
                return $withinRange;
            }
        }

        $fallbacks = [
            $focusKeyword !== '' ? $focusKeyword . ' Guide for New Arrivals in Australia' : '',
            $focusKeyword !== '' ? $focusKeyword . ' Tips for New Arrivals | SettleANZ' : '',
            $title !== '' ? $title . ' | SettleANZ' : '',
            'Moving to Australia Guide for New Arrivals | SettleANZ',
        ];

        foreach ($fallbacks as $fallback) {
            $withinRange = $this->fitToRange($fallback, self::SEO_TITLE_MIN, self::SEO_TITLE_MAX);
            if ($this->stringLength($withinRange) >= self::SEO_TITLE_MIN && $this->stringLength($withinRange) <= self::SEO_TITLE_MAX) {
                return $withinRange;
            }
        }

        return $this->trimToWordBoundary('Moving to Australia Guide for New Arrivals | SettleANZ', self::SEO_TITLE_MAX);
    }

    protected function normalizeSeoDescription(string $candidate, array $contentContext, string $focusKeyword): string
    {
        $parts = collect([
            $candidate,
            $contentContext['excerpt'] ?? '',
            $contentContext['body_text'] ?? '',
        ])->map(fn ($item) => $this->cleanText((string) $item))
            ->filter()
            ->values();

        $description = '';
        foreach ($parts as $part) {
            if ($part !== '') {
                $description = $part;
                break;
            }
        }

        if ($description === '' && $focusKeyword !== '') {
            $description = $focusKeyword . ' guide for migrants and new arrivals in Australia with practical steps, common mistakes to avoid, and what to do next.';
        }

        if ($focusKeyword !== '' && !Str::contains(Str::lower($description), Str::lower($focusKeyword))) {
            $description = $focusKeyword . ': ' . ltrim($description, ':;,. ');
        }

        $extraText = collect([
            $contentContext['excerpt'] ?? '',
            $contentContext['body_text'] ?? '',
        ])->map(fn ($item) => $this->cleanText((string) $item))
            ->implode(' ');

        return $this->fitDescriptionToRange($description, $extraText, self::SEO_DESCRIPTION_MIN, self::SEO_DESCRIPTION_MAX);
    }

    protected function normalizeOgTitle(string $candidate, string $metaTitle): string
    {
        $candidate = $this->cleanText($candidate);
        if ($candidate === '') {
            return Str::limit($metaTitle, 120, '');
        }

        return Str::limit($candidate, 120, '');
    }

    protected function normalizeOgDescription(string $candidate, string $metaDescription): string
    {
        $candidate = $this->cleanText($candidate);
        if ($candidate === '') {
            return Str::limit($metaDescription, 200, '');
        }

        return Str::limit($candidate, 200, '');
    }

    protected function normalizeSchemaType(string $schemaType): string
    {
        $allowed = ['Article', 'BlogPosting', 'NewsArticle', 'WebPage'];

        return in_array($schemaType, $allowed, true) ? $schemaType : 'Article';
    }

    protected function normalizePageSchemaType(string $schemaType, array $pageContext): string
    {
        $allowed = ['WebPage', 'Article', 'FAQPage', 'Service', 'ContactPage', 'AboutPage', 'LocalBusiness'];
        if (in_array($schemaType, $allowed, true)) {
            return $schemaType;
        }

        $current = (string) ($pageContext['schema_type'] ?? '');
        if (in_array($current, $allowed, true)) {
            return $current;
        }

        return 'WebPage';
    }

    protected function normalizePageSeoDescription(string $candidate, array $pageContext, string $focusKeyword): string
    {
        $description = $this->cleanText($candidate);
        if ($description === '') {
            $description = $this->cleanText((string) ($pageContext['meta_description'] ?? ''));
        }
        if ($description === '') {
            $description = $this->cleanText((string) ($pageContext['default_description'] ?? ''));
        }

        if ($focusKeyword !== '' && !Str::contains(Str::lower($description), Str::lower($focusKeyword))) {
            $description = $focusKeyword . ': ' . ltrim($description, ':;,. ');
        }

        $extraText = implode(' ', array_filter([
            $this->cleanText((string) ($pageContext['default_description'] ?? '')),
            $this->cleanText((string) ($pageContext['page_label'] ?? '')),
        ]));

        return $this->fitDescriptionToRange($description, $extraText, self::SEO_DESCRIPTION_MIN, self::SEO_DESCRIPTION_MAX);
    }

    protected function buildFaqItems(array $items, array $contentContext, string $focusKeyword): array
    {
        $normalized = $this->normalizeFaqItems($items);
        if (count($normalized) >= 3) {
            return array_slice($normalized, 0, 3);
        }

        $fallbacks = [];
        $bodyText = $this->cleanText((string) ($contentContext['body_text'] ?? ''));
        $title = $this->cleanText((string) ($contentContext['title'] ?? $focusKeyword));

        if ($title !== '') {
            $fallbacks[] = [
                'question' => 'What does this article cover about ' . Str::lower($title) . '?',
                'answer' => $this->fitToRange(
                    $this->cleanText((string) ($contentContext['excerpt'] ?? $bodyText)),
                    90,
                    260
                ),
            ];
        }

        if ($focusKeyword !== '') {
            $fallbacks[] = [
                'question' => 'What should readers focus on first for ' . Str::lower($focusKeyword) . '?',
                'answer' => $this->fitToRange(
                    $bodyText !== '' ? $bodyText : ($contentContext['excerpt'] ?? ''),
                    90,
                    260
                ),
            ];
        }

        foreach (($contentContext['headings'] ?? []) as $heading) {
            $fallbacks[] = [
                'question' => 'How does the article explain ' . Str::lower($heading) . '?',
                'answer' => $this->fitToRange($bodyText, 90, 260),
            ];
        }

        foreach ($fallbacks as $fallback) {
            if (count($normalized) >= 3) {
                break;
            }

            $question = $this->cleanText((string) ($fallback['question'] ?? ''));
            $answer = $this->cleanText((string) ($fallback['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                continue;
            }

            $duplicate = collect($normalized)->contains(function (array $item) use ($question): bool {
                return Str::lower($item['question']) === Str::lower($question);
            });

            if (!$duplicate) {
                $normalized[] = [
                    'question' => Str::limit($question, 300, ''),
                    'answer' => Str::limit($answer, 3000, ''),
                ];
            }
        }

        $genericQuestions = [
            'What is the main takeaway from this article?',
            'What should readers do first after reading this guide?',
            'Which common issue does this article help explain?',
        ];

        foreach ($genericQuestions as $index => $question) {
            if (count($normalized) >= 3) {
                break;
            }

            $answerSource = $bodyText !== '' ? $bodyText : $this->cleanText((string) ($contentContext['excerpt'] ?? ''));
            if ($answerSource === '') {
                continue;
            }

            $normalized[] = [
                'question' => $question,
                'answer' => Str::limit($this->fitToRange($answerSource, 90, 260), 3000, ''),
            ];
        }

        return array_slice($normalized, 0, 3);
    }

    protected function normalizeFaqItems(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(function ($item): ?array {
                if (!is_array($item)) {
                    return null;
                }

                $question = $this->cleanText((string) Arr::get($item, 'question', ''));
                $answer = $this->cleanText((string) Arr::get($item, 'answer', ''));

                if ($question === '' || $answer === '') {
                    return null;
                }

                return [
                    'question' => Str::limit($question, 300, ''),
                    'answer' => Str::limit($answer, 3000, ''),
                ];
            })
            ->filter()
            ->unique(fn (array $item) => Str::lower($item['question']))
            ->values()
            ->all();
    }

    protected function extractHeadings(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/is', $html, $matches);

        return collect($matches[1] ?? [])
            ->map(fn ($heading) => $this->cleanText(strip_tags((string) $heading)))
            ->filter()
            ->take(8)
            ->values()
            ->all();
    }

    protected function plainText(string $html): string
    {
        return $this->cleanText(strip_tags($html));
    }

    protected function estimateReadingTime(string $text): string
    {
        $plain = $this->cleanText($text);
        if ($plain === '') {
            return '6 min read';
        }

        $wordCount = str_word_count($plain);
        $minutes = max(1, (int) ceil($wordCount / 200));

        return $minutes . ' min read';
    }

    protected function cleanText(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    protected function fitDescriptionToRange(string $base, string $extraText, int $min, int $max): string
    {
        $description = $this->fitToRange($base, $min, $max);
        if ($this->stringLength($description) >= $min) {
            return $description;
        }

        $tail = $this->cleanText($extraText);
        if ($tail !== '') {
            $candidate = trim($description . ' ' . $tail);
            $description = $this->fitToRange($candidate, $min, $max);
        }

        if ($this->stringLength($description) >= $min) {
            return $description;
        }

        $booster = ' Practical tips for migrants and new arrivals.';

        return $this->fitToRange(trim($description . ' ' . $booster), $min, $max);
    }

    protected function fitToRange(string $value, int $min, int $max): string
    {
        $value = $this->cleanText($value);
        if ($value === '') {
            return '';
        }

        if ($this->stringLength($value) > $max) {
            $value = $this->trimToWordBoundary($value, $max);
        }

        if ($this->stringLength($value) >= $min) {
            return $value;
        }

        return $value;
    }

    protected function trimToWordBoundary(string $value, int $max): string
    {
        $value = $this->cleanText($value);
        if ($this->stringLength($value) <= $max) {
            return $value;
        }

        $slice = trim(mb_substr($value, 0, $max));
        $slice = preg_replace('/\s+\S*$/u', '', $slice) ?: $slice;
        $slice = rtrim($slice, " ,;:-|/");

        return $slice !== '' ? $slice : trim(mb_substr($value, 0, $max));
    }

    protected function stringLength(string $value): int
    {
        return mb_strlen($value);
    }

    protected function requestViaResponses(string $apiKey, string $baseUrl, string $model, string $system, string $user, array $keys): string
    {
        $request = Http::timeout((int) config('assistant.openai.timeout', 20))
            ->withToken($apiKey)
            ->acceptJson();

        if ($this->isOpenRouterBaseUrl($baseUrl)) {
            $request = $request->withHeaders([
                'HTTP-Referer' => config('app.url', 'http://127.0.0.1:8000'),
                'X-Title' => config('app.name', 'SettleANZ'),
            ]);
        }

        $payload = [
            'model' => $model,
            'instructions' => $system . "\nAlways return JSON only with no preface and no markdown code fences.",
            'input' => [[
                'role' => 'user',
                'content' => [[
                    'type' => 'input_text',
                    'text' => $user,
                ]],
            ]],
            'temperature' => 0.4,
            'max_output_tokens' => 1800,
        ];

        $schema = $this->buildStructuredOutputSchema($keys);
        if ($schema !== null) {
            $payload['text'] = [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'settleanz_structured_output',
                    'schema' => $schema,
                ],
            ];
        }

        $response = $request->post($baseUrl . '/responses', $payload);

        if (!$response->successful()) {
            Log::warning('Responses API structured output request failed, retrying without schema.', [
                'status' => $response->status(),
                'model' => $model,
                'base_url' => $baseUrl,
                'body_preview' => Str::limit($response->body(), 800, '...'),
            ]);

            unset($payload['text']);
            $response = $request->post($baseUrl . '/responses', $payload);
        }

        if (!$response->successful()) {
            if ($this->isGroqBaseUrl($baseUrl)) {
                return $this->requestViaChatCompletions($apiKey, $baseUrl, $model, $system, $user);
            }

            throw new RuntimeException('AI request failed: ' . $response->body());
        }

        $json = $response->json();
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

    protected function buildStructuredOutputSchema(array $keys): ?array
    {
        if ($keys === []) {
            return null;
        }

        $properties = [];
        $required = [];

        foreach ($keys as $key) {
            $required[] = $key;

            if ($key === 'faq_items') {
                $properties[$key] = [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'question' => ['type' => 'string'],
                            'answer' => ['type' => 'string'],
                        ],
                        'required' => ['question', 'answer'],
                        'additionalProperties' => false,
                    ],
                ];
                continue;
            }

            $properties[$key] = ['type' => 'string'];
        }

        return [
            'type' => 'object',
            'properties' => $properties,
            'required' => $required,
            'additionalProperties' => false,
        ];
    }

    protected function requestViaChatCompletions(string $apiKey, string $baseUrl, string $model, string $system, string $user): string
    {
        $response = $this->sendChatCompletionRequest($apiKey, $baseUrl, $model, $system, $user, true);

        if (!$response->successful()) {
            $errorCode = (string) data_get($response->json(), 'error.code', '');

            if ($errorCode === 'json_validate_failed') {
                Log::warning('Groq strict JSON mode failed, retrying without response_format.', [
                    'model' => $model,
                    'base_url' => $baseUrl,
                ]);

                $response = $this->sendChatCompletionRequest($apiKey, $baseUrl, $model, $system, $user, false);
            }
        }

        if (!$response->successful()) {
            throw new RuntimeException('AI request failed: ' . $response->body());
        }

        return trim((string) data_get($response->json(), 'choices.0.message.content', ''));
    }

    protected function sendChatCompletionRequest(
        string $apiKey,
        string $baseUrl,
        string $model,
        string $system,
        string $user,
        bool $strictJsonMode
    ) {
        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system . "\nAlways return JSON only with no preface and no markdown code fences."],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => 0.4,
            'max_completion_tokens' => 1800,
        ];

        if ($strictJsonMode) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        return Http::timeout((int) config('assistant.openai.timeout', 20))
            ->withToken($apiKey)
            ->acceptJson()
            ->post($baseUrl . '/chat/completions', $payload);
    }

    protected function extractJson(string $text): string
    {
        $trimmed = trim($text);
        if (Str::startsWith($trimmed, '{') && Str::endsWith($trimmed, '}')) {
            return $trimmed;
        }

        $start = strpos($trimmed, '{');
        $end = strrpos($trimmed, '}');

        if ($start === false || $end === false || $end <= $start) {
            Log::warning('AI response was not valid JSON.', [
                'preview' => Str::limit($trimmed, 600, '...'),
                'length' => mb_strlen($trimmed),
            ]);
            throw new RuntimeException('The AI response did not include a valid JSON object.');
        }

        return substr($trimmed, $start, $end - $start + 1);
    }

    protected function isGroqBaseUrl(string $baseUrl): bool
    {
        return Str::contains(Str::lower($baseUrl), 'api.groq.com');
    }

    protected function shouldUseGroqChatJsonMode(string $baseUrl, string $model): bool
    {
        if (!$this->isGroqBaseUrl($baseUrl)) {
            return false;
        }

        $normalized = Str::lower(trim($model));

        return !Str::contains($normalized, 'gpt-oss');
    }

    protected function isOpenRouterBaseUrl(string $baseUrl): bool
    {
        return Str::contains(Str::lower($baseUrl), 'openrouter.ai');
    }
}
