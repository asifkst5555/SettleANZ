<?php

namespace App\Jobs;

use App\Mail\EbookDownloadMail;
use App\Models\DownloadToken;
use App\Models\Lead;
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

    public function handle(): void
    {
        try {
            $ebook = $this->token->ebook;
            $subject = $this->templateId
                ? null
                : "Your Download: {$ebook->title}";

            Mail::send(new EbookDownloadMail(
                lead: $this->lead,
                token: $this->token,
                customSubject: $subject,
            ));

            Log::info('Download email sent', [
                'lead_id' => $this->lead->id,
                'token_id' => $this->token->id,
                'email' => $this->lead->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send download email', [
                'lead_id' => $this->lead->id,
                'token_id' => $this->token->id,
                'error' => $e->getMessage(),
            ]);

            $this->logFailure($e);
        }
    }

    private function logFailure(\Throwable $e): void
    {
        $exists = \App\Models\EmailLog::where('lead_id', $this->lead->id)
            ->where('status', \App\Enums\EmailStatus::Failed->value)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if (!$exists) {
            \App\Models\EmailLog::create([
                'lead_id' => $this->lead->id,
                'to_email' => $this->lead->email,
                'to_name' => $this->lead->full_name,
                'subject' => "Your Download: {$this->token->ebook?->title}",
                'status' => \App\Enums\EmailStatus::Failed->value,
                'error_message' => $e->getMessage(),
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
