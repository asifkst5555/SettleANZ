<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatSessionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'channel' => ['nullable', 'string', 'max:30'],
            'visitor_id' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'in:en'],
        ]);

        $conversation = Conversation::create([
            'channel' => $validated['channel'] ?? 'website_widget',
            'visitor_id' => $validated['visitor_id'] ?? null,
            'status' => 'active',
            'started_at' => now(),
            'last_message_at' => now(),
            'metadata' => [
                'ip' => $request->ip(),
                'language' => $validated['language'] ?? 'en',
            ],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
        ], 201);
    }

    public function reset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'channel' => ['nullable', 'string', 'max:30'],
            'visitor_id' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'in:en'],
            'conversation_id' => ['nullable', 'string', 'max:100'],
        ]);

        if (!empty($validated['conversation_id'])) {
            Conversation::query()->whereKey($validated['conversation_id'])->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);
        }

        $conversation = Conversation::create([
            'channel' => $validated['channel'] ?? 'website_widget',
            'visitor_id' => $validated['visitor_id'] ?? null,
            'status' => 'active',
            'started_at' => now(),
            'last_message_at' => now(),
            'metadata' => [
                'ip' => $request->ip(),
                'language' => $validated['language'] ?? 'en',
                'reset' => true,
            ],
        ]);

        return response()->json([
            'ok' => true,
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
        ], 201);
    }
}
