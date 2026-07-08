<?php

namespace App\Listeners;

use App\DTOs\DownloadTokenDTO;
use App\Events\LeadCaptured;
use App\Models\Ebook;
use App\Services\DownloadService;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleLeadCaptured implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly DownloadService $downloadService,
        private readonly EmailService $emailService,
    ) {}

    public function handle(LeadCaptured $event): void
    {
        $lead = $event->lead;
        $ebookId = $lead->ebook_id;

        if (!$ebookId) {
            return;
        }

        $ebook = Ebook::find($ebookId);
        if (!$ebook || !$ebook->isPublished()) {
            return;
        }

        $token = $this->downloadService->createToken(
            new DownloadTokenDTO(
                ebookId: $ebook->id,
                leadId: $lead->id,
                maxDownloads: config('ebook.download.max_downloads_per_token', 5),
                expiryHours: config('ebook.download.token_expiry_hours', 72),
            )
        );

        $this->emailService->sendDownloadEmail($lead, $token);
    }
}
