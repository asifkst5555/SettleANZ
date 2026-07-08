<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiGenerateRequest;
use App\Models\AiConversation;
use App\Models\DownloadToken;
use App\Models\Ebook;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\AiAdminAssistantService;
use App\Services\AiEmailService;
use App\DTOs\DownloadTokenDTO;
use App\Services\DownloadService;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiAssistantController extends Controller
{
    public function __construct(
        private readonly AiAdminAssistantService $adminAssistantService,
        private readonly AiEmailService $aiEmailService,
        private readonly DownloadService $downloadService,
        private readonly EmailService $emailService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ai-assistant.index', [
            'metaTitle' => 'AI Admin Assistant | Admin',
            'conversations' => AiConversation::where('user_id', $request->user()->id)
                ->latest()
                ->limit(20)
                ->get(),
        ]);
    }

    public function chat(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'integer', 'exists:ai_conversations,id'],
        ]);

        $result = $this->adminAssistantService->processCommand(
            command: $validated['message'],
            userId: $request->user()->id,
        );

        return response()->json($result);
    }

    public function generateDownloadEmail(AiGenerateRequest $request): JsonResponse
    {
        $lead = Lead::findOrFail($request->input('lead_id'));
        $ebook = Ebook::findOrFail($request->input('ebook_id'));

        $token = $this->downloadService->getTokenByLeadAndEbook($lead, $ebook);
        if (!$token) {
            $token = $this->downloadService->createToken(
                new DownloadTokenDTO(
                    ebookId: $ebook->id,
                    leadId: $lead->id,
                )
            );
        }

        $result = $this->aiEmailService->generateDownloadEmail([
            'lead_name' => $lead->full_name,
            'lead_email' => $lead->email,
            'ebook_title' => $ebook->title,
            'ebook_description' => $ebook->description,
            'ebook_author' => $ebook->author ?? config('app.name'),
            'download_url' => route('ebook.download', ['token' => $token->token]),
            'expires_at' => $token->expires_at->format('F j, Y \a\t g:i A'),
            'company_name' => config('app.name'),
        ], tone: $request->input('tone', 'professional'), language: $request->input('language', 'en'));

        return response()->json([
            'success' => true,
            'data' => $result,
            'token' => $token->token,
        ]);
    }

    public function rewriteEmail(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'content' => ['required', 'string'],
            'tone' => ['required', 'string', 'in:professional,friendly,marketing'],
            'language' => ['nullable', 'string', 'max:10'],
        ]);

        $result = $this->aiEmailService->rewriteEmail(
            content: $validated['content'],
            tone: $validated['tone'],
            language: $validated['language'] ?? 'en',
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function sendAiEmail(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'lead_id' => ['required', 'integer', 'exists:leads,id'],
            'subject' => ['required', 'string', 'max:500'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
        ]);

        $lead = Lead::findOrFail($validated['lead_id']);

        $success = $this->emailService->sendRawEmail(
            to: $lead->email,
            toName: $lead->full_name,
            subject: $validated['subject'],
            bodyHtml: $validated['body_html'],
            bodyText: $validated['body_text'] ?? null,
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Email sent successfully.' : 'Failed to send email.',
        ]);
    }

    public function conversationHistory(Request $request, AiConversation $conversation): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        return response()->json([
            'conversation' => $conversation->load('messages'),
        ]);
    }

    public function clearConversations(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        AiConversation::where('user_id', $request->user()->id)->delete();

        return response()->json(['success' => true]);
    }
}
