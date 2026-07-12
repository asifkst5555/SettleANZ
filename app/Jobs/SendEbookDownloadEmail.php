<?php

namespace App\Jobs;

use App\Enums\EmailStatus;
use App\Mail\EbookDownloadMail;
use App\Models\DownloadToken;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEbookDownloadEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Lead $lead,
        private readonly DownloadToken $token,
        private readonly ?int $templateId = null,
    ) {}

    public function handle(EmailService $emailService): void
    {
        $ebook = $this->token->ebook;
        $subject = $this->templateId
            ? null
            : "Your Download: {$ebook->title}";

        $emailService->applyMailConfig();

        $log = EmailLog::create([
            'lead_id' => $this->lead->id,
            'to_email' => $this->lead->email,
            'to_name' => $this->lead->full_name,
            'subject' => $subject ?? "Your Download: {$ebook->title}",
            'status' => EmailStatus::Pending->value,
        ]);

        try {
            Mail::to($this->lead->email)->send(new EbookDownloadMail(
                lead: $this->lead,
                token: $this->token,
                customSubject: $subject,
            ));

            $log->markSent();

            Log::info('Download email sent', [
                'lead_id' => $this->lead->id,
                'token_id' => $this->token->id,
                'email' => $this->lead->email,
            ]);
        } catch (\Throwable $e) {
            $log->markFailed($e->getMessage());

            Log::error('Failed to send download email', [
                'lead_id' => $this->lead->id,
                'token_id' => $this->token->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendEbookDownloadEmail job failed', [
            'lead_id' => $this->lead->id,
            'token_id' => $this->token->id,
            'error' => $e->getMessage(),
        ]);
    }
}
