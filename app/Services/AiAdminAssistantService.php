<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\Lead;
use App\Models\Ebook;
use App\Models\DownloadToken;
use App\Enums\LeadStatus;
use App\Enums\DownloadTokenStatus;
use App\DTOs\DownloadTokenDTO;
use Exception;
use Illuminate\Support\Facades\Log;

class AiAdminAssistantService
{
    public function __construct(
        private readonly AiEmailService $aiEmailService,
        private readonly EmailService $emailService,
        private readonly DownloadService $downloadService,
        private readonly LeadCaptureService $leadCaptureService,
    ) {}

    public function processCommand(string $command, int $userId): array
    {
        $conversation = AiConversation::create([
            'user_id' => $userId,
            'title' => 'Admin Command: ' . str($command)->limit(50),
            'context_type' => 'admin_assistant',
            'status' => 'active',
        ]);

        $conversation->addMessage('user', $command);

        $result = $this->executeCommand($command, $conversation);

        $conversation->addMessage('assistant', $result['message'], [
            'action' => $result['action'] ?? null,
            'data' => $result['data'] ?? null,
        ]);

        return $result;
    }

    private function executeCommand(string $command, AiConversation $conversation): array
    {
        $command = strtolower(trim($command));

        if (preg_match('/send\s+(the\s+)?(.+?)\s+(ebook|guide|checklist|pdf)\s+to\s+(.+)/i', $command, $matches)) {
            return $this->handleSendEbook($matches[2], $matches[4]);
        }

        if (preg_match('/resend\s+(yesterday\'s\s+)?download\s+links/i', $command)) {
            return $this->handleResendYesterday();
        }

        if (preg_match('/email\s+(everyone|all)\s+who\s+downloaded\s+(the\s+)?(.+)/i', $command, $matches)) {
            return $this->handleEmailDownloaders($matches[3]);
        }

        if (preg_match('/generate\s+(a\s+)?follow.up\s+email\s+(for|to)\s+(.+)/i', $command, $matches)) {
            return $this->handleGenerateFollowUp($matches[3]);
        }

        if (preg_match('/(show|view|get)\s+(today\'s\s+)?download\s+(analytics|stats|statistics)/i', $command)) {
            return $this->handleDownloadAnalytics();
        }

        if (preg_match('/(send|email)\s+(a\s+)?campaign\s+(.+)\s+to\s+(.+)/i', $command, $matches)) {
            return $this->handleCampaignSend($matches[3], $matches[4]);
        }

        if (preg_match('/(show|list|view)\s+(all\s+)?(leads|downloads|ebooks)/i', $command, $matches)) {
            return $this->handleList($matches[3]);
        }

        $aiResult = $this->aiEmailService->chat($command);
        return [
            'message' => $aiResult['body_html'] ?? $aiResult['body_text'] ?? 'I understand you need help. Could you please provide more details about what you\'d like me to do?',
            'action' => 'ai_response',
        ];
    }

    private function handleSendEbook(string $ebookQuery, string $recipientQuery): array
    {
        $ebook = $this->findEbook($ebookQuery);
        if (!$ebook) {
            return $this->error("Could not find an ebook matching '{$ebookQuery}'.");
        }

        $email = filter_var(trim($recipientQuery), FILTER_VALIDATE_EMAIL);
        $lead = $email
            ? $this->leadCaptureService->getLeadByEmail($email)
            : $this->findLead($recipientQuery);

        if (!$lead) {
            return $this->error("Could not find a lead matching '{$recipientQuery}'.");
        }

        $token = $this->downloadService->createToken(
            new DownloadTokenDTO(
                ebookId: $ebook->id,
                leadId: $lead->id,
            )
        );

        $this->emailService->sendDownloadEmail($lead, $token);

        return [
            'message' => "✅ The *{$ebook->title}* has been sent to {$lead->email} ({$lead->full_name}).\n\nDownload link will expire in " . config('ebook.download.token_expiry_hours', 72) . " hours.",
            'action' => 'send_ebook',
            'data' => [
                'ebook_id' => $ebook->id,
                'ebook_title' => $ebook->title,
                'lead_id' => $lead->id,
                'lead_email' => $lead->email,
                'token' => $token->token,
            ],
        ];
    }

    private function handleResendYesterday(): array
    {
        $yesterday = now()->subDay()->startOfDay();
        $tokens = DownloadToken::where('created_at', '>=', $yesterday)
            ->where('created_at', '<', $yesterday->copy()->endOfDay())
            ->whereIn('status', [DownloadTokenStatus::Active->value, DownloadTokenStatus::Exhausted->value])
            ->get();

        if ($tokens->isEmpty()) {
            return $this->error('No download links were created yesterday.');
        }

        $count = 0;
        foreach ($tokens as $token) {
            if ($token->isExpired() || $token->isExhausted()) {
                $newToken = $this->downloadService->createToken(
                    new DownloadTokenDTO(
                        ebookId: $token->ebook_id,
                        leadId: $token->lead_id,
                    )
                );
                $this->emailService->sendDownloadEmail($token->lead, $newToken);
                $count++;
            } else {
                $this->emailService->sendDownloadEmail($token->lead, $token);
                $count++;
            }
        }

        return [
            'message' => "✅ Resent download links to {$count} lead(s) from yesterday.",
            'action' => 'resend_yesterday',
            'data' => ['count' => $count],
        ];
    }

    private function handleEmailDownloaders(string $ebookQuery): array
    {
        $ebook = $this->findEbook($ebookQuery);
        if (!$ebook) {
            return $this->error("Could not find an ebook matching '{$ebookQuery}'.");
        }

        $leads = Lead::where('ebook_id', $ebook->id)
            ->whereIn('status', [LeadStatus::Downloaded->value, LeadStatus::New->value])
            ->get();

        if ($leads->isEmpty()) {
            return $this->error("No leads found who downloaded '{$ebook->title}'.");
        }

        $count = 0;
        foreach ($leads as $lead) {
            $token = $this->downloadService->createToken(
                new DownloadTokenDTO(
                    ebookId: $ebook->id,
                    leadId: $lead->id,
                )
            );
            $this->emailService->sendDownloadEmail($lead, $token);
            $count++;
        }

        return [
            'message' => "✅ Emailed {$count} lead(s) who downloaded '{$ebook->title}'.",
            'action' => 'email_downloaders',
            'data' => ['count' => $count, 'ebook_id' => $ebook->id],
        ];
    }

    private function handleGenerateFollowUp(string $query): array
    {
        $lead = $this->findLead($query);
        if (!$lead) {
            return $this->error("Could not find a lead matching '{$query}'.");
        }

        if (!$lead->ebook) {
            return $this->error("Lead '{$lead->full_name}' has not downloaded any ebook.");
        }

        $lastDownload = $lead->downloadLogs()->latest()->first();
        $daysSince = $lastDownload ? now()->diffInDays($lastDownload->created_at) : 0;

        $aiResult = $this->aiEmailService->generateFollowUpEmail([
            'lead_name' => $lead->full_name,
            'ebook_title' => $lead->ebook->title,
            'days_since_download' => $daysSince,
            'download_count' => $lead->ebook->download_count,
        ]);

        return [
            'message' => "Here's a follow-up email for {$lead->full_name} about '{$lead->ebook->title}':\n\n**Subject:** {$aiResult['subject']}\n\n{$aiResult['body_html']}\n\n---\nWould you like me to send this now? Reply with 'send' or 'preview and edit'.",
            'action' => 'generate_follow_up',
            'data' => [
                'lead_id' => $lead->id,
                'subject' => $aiResult['subject'],
                'body_html' => $aiResult['body_html'],
                'body_text' => $aiResult['body_text'] ?? null,
            ],
        ];
    }

    private function handleDownloadAnalytics(): array
    {
        $downloadStats = $this->downloadService->getStats();
        $ebookStats = app(EbookService::class)->getStats();
        $leadStats = $this->leadCaptureService->getStats();
        $emailStats = $this->emailService->getStats();

        $message = "📊 **Download Analytics**\n\n";
        $message .= "**Downloads Today:** {$downloadStats['today_downloads']}\n";
        $message .= "**Total Downloads:** {$downloadStats['total_downloads']}\n";
        $message .= "**Active Tokens:** {$downloadStats['active_tokens']}\n";
        $message .= "**Unique IPs:** {$downloadStats['unique_ips']}\n\n";
        $message .= "**Ebooks:** {$ebookStats['published']} published, {$ebookStats['draft']} drafts\n";
        $message .= "**Leads:** {$leadStats['total']} total, {$leadStats['new']} new today\n";
        $message .= "**Emails:** {$emailStats['total_sent']} sent, {$emailStats['open_rate']}% open rate\n";

        return [
            'message' => $message,
            'action' => 'analytics',
            'data' => [
                'downloads' => $downloadStats,
                'ebooks' => $ebookStats,
                'leads' => $leadStats,
                'emails' => $emailStats,
            ],
        ];
    }

    private function handleCampaignSend(string $campaignQuery, string $recipientQuery): array
    {
        $leads = [];
        if (strtolower(trim($recipientQuery)) === 'all') {
            $leads = Lead::where('consent', true)->get();
        } else {
            $lead = $this->findLead($recipientQuery);
            if ($lead) {
                $leads = [$lead];
            }
        }

        if (empty($leads)) {
            return $this->error("Could not find leads matching '{$recipientQuery}'.");
        }

        return [
            'message' => "📧 Would you like me to create a campaign email about '{$campaignQuery}' for " . count($leads) . " lead(s)? I can generate the email content first for your review.",
            'action' => 'campaign_confirm',
            'data' => [
                'query' => $campaignQuery,
                'recipient_count' => count($leads),
            ],
        ];
    }

    private function handleList(string $type): array
    {
        return match ($type) {
            'leads' => $this->listLeads(),
            'downloads' => $this->listDownloads(),
            'ebooks' => $this->listEbooks(),
            default => $this->error("I can list 'leads', 'downloads', or 'ebooks'."),
        };
    }

    private function listLeads(): array
    {
        $leads = Lead::latest()->limit(10)->get();
        $message = "📋 **Recent Leads (last 10)**\n\n";
        foreach ($leads as $lead) {
            $ebook = $lead->ebook ? " - {$lead->ebook->title}" : '';
            $message .= "- {$lead->full_name} ({$lead->email}) [{$lead->status}]{$ebook}\n";
        }
        return ['message' => $message ?: 'No leads found.', 'action' => 'list_leads'];
    }

    private function listDownloads(): array
    {
        $logs = \App\Models\DownloadLog::with(['ebook', 'lead'])
            ->latest()->limit(10)->get();
        $message = "📥 **Recent Downloads (last 10)**\n\n";
        foreach ($logs as $log) {
            $message .= "- {$log->lead?->full_name} downloaded {$log->ebook?->title} ({$log->created_at->diffForHumans()})\n";
        }
        return ['message' => $message ?: 'No downloads yet.', 'action' => 'list_downloads'];
    }

    private function listEbooks(): array
    {
        $ebooks = Ebook::latest()->limit(10)->get();
        $message = "📚 **Ebooks**\n\n";
        foreach ($ebooks as $ebook) {
            $message .= "- {$ebook->title} [{$ebook->status}] - {$ebook->download_count} downloads\n";
        }
        return ['message' => $message ?: 'No ebooks found.', 'action' => 'list_ebooks'];
    }

    private function findEbook(string $query): ?Ebook
    {
        $query = trim($query);
        return Ebook::where('title', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->published()
            ->first();
    }

    private function findLead(string $query): ?Lead
    {
        $query = trim($query);
        if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
            return Lead::where('email', $query)->first();
        }

        return Lead::where('full_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->first();
    }

    private function error(string $message): array
    {
        return [
            'message' => "❌ {$message}",
            'action' => 'error',
            'data' => null,
        ];
    }
}
