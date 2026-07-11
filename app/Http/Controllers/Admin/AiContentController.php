<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAiContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiContentController extends Controller
{
    public function blogDraft(Request $request, AdminAiContentService $service): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('content_core.view'), 403);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body_html' => ['nullable', 'string'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'reading_time' => ['nullable', 'string', 'max:40'],
        ]);

        try {
            return response()->json([
                'data' => $service->generateBlogDraft($validated),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Admin AI blog draft request failed.', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'message' => 'AI draft generation failed.',
                'user_message' => 'The AI API is not working right now. Please try again. If it keeps failing, check the API key, model, or provider settings.',
                'error_type' => 'ai_api_failed',
            ], 503);
        }
    }

    public function blogSeo(Request $request, AdminAiContentService $service): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('content_core.view'), 403);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body_html' => ['nullable', 'string'],
        ]);

        try {
            return response()->json([
                'data' => $service->generateBlogSeo($validated),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Admin AI blog SEO request failed.', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'message' => 'AI SEO generation failed.',
                'user_message' => 'The AI API is not working right now. Please try again. If it keeps failing, check the API key, model, or provider settings.',
                'error_type' => 'ai_api_failed',
            ], 503);
        }
    }

    public function pageSeo(Request $request, AdminAiContentService $service): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('content_core.view'), 403);

        $validated = $request->validate([
            'page_label' => ['required', 'string', 'max:120'],
            'page_url' => ['required', 'string', 'max:255'],
            'default_title' => ['nullable', 'string', 'max:255'],
            'default_description' => ['nullable', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'schema_type' => ['nullable', 'string', 'max:60'],
        ]);

        try {
            return response()->json([
                'data' => $service->generatePageSeo($validated),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Admin AI page SEO request failed.', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'message' => 'AI page SEO generation failed.',
                'user_message' => 'The AI API is not working right now. Please try again. If it keeps failing, check the API key, model, or provider settings.',
                'error_type' => 'ai_api_failed',
            ], 503);
        }
    }
}
