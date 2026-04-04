<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\WebsiteAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function store(Request $request, Conversation $conversation, WebsiteAssistantService $assistantService): JsonResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1800'],
        ]);

        if ($conversation->status !== 'active') {
            return response()->json([
                'message' => 'Conversation is not active.',
            ], 422);
        }

        $result = $assistantService->handleUserMessage($conversation, $validated['content']);

        return response()->json([
            'conversation_id' => $conversation->id,
            'assistant' => [
                'id' => $result['assistant_message']->id,
                'content' => $result['assistant_message']->content,
                'metadata' => $result['assistant_message']->metadata,
                'created_at' => $result['assistant_message']->created_at,
            ],
            'lead' => $result['lead'],
        ]);
    }
}
