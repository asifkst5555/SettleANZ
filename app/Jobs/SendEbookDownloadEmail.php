<?php

namespace App\Jobs;

use App\Models\DownloadToken;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        $template = null;
        if ($this->templateId) {
            $template = EmailTemplate::find($this->templateId);
        }

        if (!$template) {
            $template = EmailTemplate::where('type', 'download')
                ->where('is_active', true)
                ->first();
        }

        $ebook = $this->token->ebook;

        if ($template) {
            $variables = [
                'lead_name' => $this->lead->full_name ?? 'Valued Reader',
                'lead_email' => $this->lead->email,
                'ebook_title' => $ebook->title,
                'ebook_description' => $ebook->description,
                'ebook_author' => $ebook->author ?? config('app.name'),
                'download_url' => route('ebook.download', ['token' => $this->token->token]),
                'download_link' => "<a href=\"" . route('ebook.download', ['token' => $this->token->token]) . "\">Click here to download</a>",
                'expires_at' => $this->token->expires_at->format('F j, Y \a\t g:i A'),
                'expires_in_hours' => (string) config('ebook.download.token_expiry_hours', 72),
                'company_name' => config('app.name'),
                'company_logo' => 'https://settleanz.com/media/logo/logo.webp',
                'current_year' => date('Y'),
            ];

            $emailService->sendTemplatedEmail($this->lead, $template, $variables);
        } else {
            $downloadUrl = route('ebook.download', ['token' => $this->token->token]);
            $expiresAt = $this->token->expires_at->format('F j, Y \a\t g:i A');
            $companyName = config('app.name');
            $companyLogo = 'https://settleanz.com/media/logo/logo.webp';
            $hours = config('ebook.download.token_expiry_hours', 72);

            $bodyHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{$companyLogo}" alt="{$companyName}" style="height: 42px; width: auto; display: block; margin: 0 auto;">
    </div>
    <div style="background: #f8f9fa; border-radius: 8px; padding: 30px; margin-bottom: 20px;">
        <h2 style="color: #1a73e8; margin-top: 0;">Your Download is Ready!</h2>
        <p>Hi {$this->lead->full_name},</p>
        <p>Thank you for your interest in <strong>{$ebook->title}</strong>.</p>
        <p>{$ebook->description}</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{$downloadUrl}" style="background: #1a73e8; color: white; padding: 14px 32px; border-radius: 6px; text-decoration: none; font-size: 16px; font-weight: bold; display: inline-block;">
                Download Your Ebook
            </a>
        </div>
        <p style="font-size: 14px; color: #666;">
            This download link will expire on <strong>{$expiresAt}</strong>.
            You can download it up to {$hours} times within this period.
        </p>
        <p style="font-size: 14px; color: #666;">
            If the button above doesn't work, copy and paste this URL into your browser:<br>
            <a href="{$downloadUrl}" style="color: #1a73e8;">{$downloadUrl}</a>
        </p>
    </div>
    <div style="text-align: center; font-size: 12px; color: #999; margin-top: 20px;">
        <p>You received this email because you requested this ebook from {$companyName}.</p>
        <p>&copy; {$companyName} " . date('Y') . ". All rights reserved.</p>
    </div>
</body>
</html>
HTML;

            $emailService->sendCustomEmail(
                to: $this->lead->email,
                subject: "Your Download: {$ebook->title}",
                bodyHtml: $bodyHtml,
                leadId: $this->lead->id,
            );
        }
    }

    public function failed(\Throwable $e): void
    {
        logger()->error('Failed to send download email', [
            'lead_id' => $this->lead->id,
            'token_id' => $this->token->id,
            'error' => $e->getMessage(),
        ]);

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
}
