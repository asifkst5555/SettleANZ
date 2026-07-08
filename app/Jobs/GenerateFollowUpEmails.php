<?php

namespace App\Jobs;

use App\Models\Ebook;
use App\Models\Lead;
use App\Services\AiEmailService;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateFollowUpEmails implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public function __construct(
        private readonly int $daysSinceDownload = 3,
        private readonly ?int $ebookId = null,
    ) {}

    public function handle(AiEmailService $aiEmailService, EmailService $emailService): void
    {
        $query = Lead::where('status', 'downloaded')
            ->whereHas('downloadLogs', function ($q) {
                $q->where('created_at', '<=', now()->subDays($this->daysSinceDownload));
            });

        if ($this->ebookId) {
            $query->where('ebook_id', $this->ebookId);
        }

        $leads = $query->get();

        foreach ($leads as $lead) {
            $ebook = Ebook::find($lead->ebook_id);
            if (!$ebook) {
                continue;
            }

            $lastDownload = $lead->downloadLogs()->latest()->first();

            $aiResult = $aiEmailService->generateFollowUpEmail([
                'lead_name' => $lead->full_name,
                'ebook_title' => $ebook->title,
                'days_since_download' => $lastDownload ? now()->diffInDays($lastDownload->created_at) : $this->daysSinceDownload,
                'download_count' => $ebook->download_count,
            ]);

            $emailService->sendRawEmail(
                to: $lead->email,
                toName: $lead->full_name,
                subject: $aiResult['subject'] ?? "Follow-up: {$ebook->title}",
                bodyHtml: $aiResult['body_html'] ?? '',
                bodyText: $aiResult['body_text'] ?? null,
            );
        }
    }
}
