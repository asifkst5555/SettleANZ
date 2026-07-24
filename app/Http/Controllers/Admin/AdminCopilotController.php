<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiConversation;
use App\Services\AdminAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCopilotController extends Controller
{
    public function __construct(
        private readonly AdminAssistantService $assistantService,
    ) {}

    public function session(Request $request): JsonResponse
    {
        $conversation = $this->assistantService->createSession($request->user()->id);

        return response()->json([
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
        ], 201);
    }

    public function message(Request $request, AiConversation $conversation): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('ai_operations.view'), 403);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1800'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'page_route' => ['nullable', 'string', 'max:255'],
        ]);

        if ($conversation->status !== 'active') {
            return response()->json([
                'message' => 'Conversation is not active.',
            ], 422);
        }

        $result = $this->assistantService->handleUserMessage(
            conversation: $conversation,
            content: $validated['content'],
            pageTitle: $validated['page_title'] ?? null,
            pageRoute: $validated['page_route'] ?? null,
        );

        return response()->json([
            'conversation_id' => $conversation->id,
            'assistant' => [
                'id' => $result['assistant_message']->id,
                'content' => $result['assistant_message']->content,
                'created_at' => $result['assistant_message']->created_at,
            ],
        ]);
    }

    public function history(Request $request, AiConversation $conversation): JsonResponse
    {
        abort_unless($request->user()?->id === $conversation->user_id, 403);

        $conversation->load('messages:id,ai_conversation_id,role,content,created_at');

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
            ],
            'messages' => $conversation->messages,
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_id' => ['nullable', 'integer', 'exists:ai_conversations,id'],
        ]);

        if (!empty($validated['conversation_id'])) {
            AiConversation::query()->whereKey($validated['conversation_id'])->update([
                'status' => 'closed',
            ]);
        }

        $conversation = $this->assistantService->createSession($request->user()->id);

        return response()->json([
            'ok' => true,
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
        ], 201);
    }
}
