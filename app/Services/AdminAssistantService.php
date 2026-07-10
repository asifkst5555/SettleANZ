<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\AiMessage;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminAssistantService
{
    public function __construct(
        private readonly AiEmailService $aiEmailService,
    ) {}

    public function createSession(int $userId): AiConversation
    {
        return AiConversation::create([
            'user_id' => $userId,
            'title' => 'Admin Copilot',
            'context_type' => 'admin_copilot',
            'status' => 'active',
        ]);
    }

    public function handleUserMessage(AiConversation $conversation, string $content, ?string $pageTitle = null, ?string $pageRoute = null): array
    {
        $content = trim($content);

        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $content,
        ]);

        $conversation->forceFill([
            'title' => 'Admin Copilot: ' . str($content)->limit(50),
        ])->save();

        $systemPrompt = $this->buildSystemPrompt($pageTitle, $pageRoute);

        $historyMessages = $conversation->messages()
            ->where('id', '!=', $userMessage->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn (AiMessage $msg) => ['role' => $msg->role, 'content' => $msg->content])
            ->toArray();

        try {
            $result = $this->aiEmailService->copilotChatStructured(
                message: $content,
                systemPrompt: $systemPrompt,
                historyMessages: $historyMessages,
            );

            $replyContent = $result['body_html'] ?? $result['body_text'] ?? 'I could not generate a reply. Please try again.';
        } catch (Exception $e) {
            Log::warning('Admin copilot AI call failed', ['error' => $e->getMessage()]);
            $replyContent = 'Sorry, I encountered an error processing your request. Please try again or check your AI configuration.';
        }

        $replyContent = $this->stripThinkBlocks($replyContent);
        $replyContent = $this->stripReasoning($replyContent);

        $assistantMessage = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $replyContent,
        ]);

        return [
            'user_message' => $userMessage,
            'assistant_message' => $assistantMessage,
        ];
    }

    private function buildSystemPrompt(?string $pageTitle, ?string $pageRoute): string
    {
        $pageContext = '';
        if ($pageTitle || $pageRoute) {
            $pageContext = "\nCURRENT PAGE (only mention if directly relevant): " . ($pageTitle ?: $pageRoute) . '.';
        }

        return <<<PROMPT
You are SettleANZ AI Admin Assistant. I will show you the conversation so far, then you must write ONLY the assistant's reply. No thinking out loud.

ABSOLUTE RULES — VIOLATION WILL BE REJECTED:
- Your output MUST START with the assistant message itself. The very first character of your output IS the first character of the answer.
- NEVER include any of these: "Okay", "First", "Let me", "I need to", "I should", "I will", "The user said", "The admin said", "They asked", "The instructions", "Looking at", "Hmm", "Well", "So I'll", "Alright", "Make sure".
- NEVER describe what you are doing, planning, checking, or thinking. No meta-commentary of any kind.
- If you catch yourself writing about your own process: STOP. DELETE IT. Output only the answer.

If the user greets you (hi, hello, hey, good morning): respond ONLY with:

👋 Hi! Welcome back. How can I help you today?

📝 Blog | 📚 eBooks | 📧 Email | ⚙️ Settings

For how-to questions: short answer → location → steps → tip → related links.

Be friendly, short, helpful. No essays, no reasoning, no explanations about what you're doing.{$pageContext}
PROMPT;
    }

    private function stripThinkBlocks(string $content): string
    {
        $content = preg_replace('/<think>.*?<\/think>/s', '', $content);
        $content = preg_replace('/<\/?think>/', '', $content);
        $content = preg_replace('/\[think\].*?\[\/think\]/s', '', $content);
        $content = preg_replace('/\[thinking\].*?\[\/thinking\]/s', '', $content);
        $content = preg_replace('/\{\/\*.*?\*\/\}/s', '', $content);
        return trim($content);
    }

    private function stripReasoning(string $content): string
    {
        $content = trim($content);
        if ($content === '') {
            return $content;
        }

        // Phrases anywhere in a paragraph that indicate meta-reasoning (thinking about the user/response/rules)
        $reasoningPatterns = [
            '/\b(the\s+)?(user|admin|person|they)\s+(said|wrote|asked|typed|wants|needs|meant|is\s+asking|is\s+trying|is\s+testing)/i',
            '/\bI\s+(should|need\s+to|will|can|would|think|am\s+going\s+to|have\s+to|must|could|might)/i',
            '/\bLet\s+me\s+(check|look|think|see|start|begin|consider|verify|double|re(phrase|read|view))/i',
            '/\b(Okay|Ok),?\s+(so|then|let|the|I)\b/i',
            '/^(Hmm|Hmmm|Um|Well|Ah),?\b/i',
            '/\bFirst,?\s+(I\s+)?(need|should|will|let|am)\b/i',
            '/\bLooking\s+at\s+(the|this|your)\b/i',
            '/\b(Let\s+me\s+double|Double)\s+check\b/i',
            '/\b(My\s+)?(reasoning|thought\s+process|thinking|internal)\b/i',
            '/\b(The|These)\s+(instructions|guidelines|prompt|rules|system\s+prompt|patterns|regex)\s+(say|tell|state|indicate|check|match|filter)/i',
            '/\bSo\s+I[\']?ll\s+(stick|go|respond|say|use|follow|give|keep)\b/i',
            '/\bAlright,?\s+that[\']?s\s+it\b/i',
            '/\bMake\s+sure\s+(the|to|I|we|links|they)\b/i',
            '/\bNo\s+need\s+to\s+(mention|talk|include|add|repeat)\b/i',
            '/\b(But\s+)?since\s+(the\s+)?(previous|last|first|earlier)\s+(greeting|message|response|time)/i',
            '/\b(maybe|perhaps)\s+(they|the\s+user|the\s+admin|he|she)\s+(is|are|was|were)\b/i',
            '/\b(Just\s+)?a\s+(simple|quick|friendly|standard)\s+(greeting|reply|response|answer)\b/i',
            '/\bStick\s+(to|with)\s+(the|a|my|our)\b/i',
        ];

        // Phrases that signal a paragraph IS the actual answer
        $answerPatterns = [
            '/^[👋📝🌐📚📧⚙️📊✅❌🔍💡📌📖📄🛠️🎯🚀💬]/u',
            '/^Here[\']?s\s+(how|what|the|a|your|where|why)\b/i',
            '/^(You\s+can|You\s+need|Go\s+to|Navigate\s+to|Click\s+on|Select|Choose|Open|Use\s+the|Head\s+to)/i',
            '/^(The\s+answer|To\s+do\s+this|To\s+get\s+started|To\s+find|To\s+access|To\s+manage|To\s+update|To\s+create|To\s+edit|To\s+view|To\s+delete)/i',
            '/^(Sorry|Thanks|Great|Perfect|Absolutely|Sure|Of\s+course|Happy\s+to|Glad\s+to)/i',
            '/^(Let\s+me\s+(know|show|help|guide|walk|explain))/i',
            '/^(I\s+(can|will|would\s+be\s+happy)\s+(help|show|guide|walk|explain|assist))/i',
        ];

        $paragraphs = preg_split('/\n\n+/', $content);
        if (count($paragraphs) <= 1) {
            // Single paragraph: check if it looks like reasoning at all
            foreach ($reasoningPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    return ''; // can't salvage single reasoning paragraph
                }
            }
            return $content;
        }

        $filtered = [];
        foreach ($paragraphs as $para) {
            $para = trim($para);
            if ($para === '') continue;

            $reasoningScore = 0;
            foreach ($reasoningPatterns as $pattern) {
                if (preg_match($pattern, $para)) {
                    $reasoningScore++;
                }
            }

            $answerScore = 0;
            foreach ($answerPatterns as $pattern) {
                if (preg_match($pattern, $para)) {
                    $answerScore++;
                }
            }

            // Keep paragraph if it looks more like an answer than reasoning,
            // or if it has no reasoning signals at all
            if ($answerScore > 0 || $reasoningScore === 0) {
                $filtered[] = $para;
            }
        }

        if (empty($filtered)) {
            return $content;
        }

        return implode("\n\n", $filtered);
    }
}
