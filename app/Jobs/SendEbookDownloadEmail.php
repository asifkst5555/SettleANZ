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
                'company_logo' => asset('media/logo/logo.webp'),
                'current_year' => date('Y'),
            ];

            $emailService->sendTemplatedEmail($this->lead, $template, $variables);
        } else {
            $downloadUrl = route('ebook.download', ['token' => $this->token->token]);
            $expiresAt = $this->token->expires_at->format('F j, Y \a\t g:i A');
            $companyName = config('app.name');
            $companyLogo = asset('media/logo/logo.webp');
            $hours = config('ebook.download.token_expiry_hours', 72);

            $bodyHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background-color:#f5f0e8;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f0e8;">
<tr><td align="center" style="padding:40px 20px;">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
<tr><td align="center" style="padding:30px 0 20px 0;">
<img src="{$companyLogo}" alt="{$companyName}" style="height:44px;width:auto;display:block;margin:0 auto;border:0;">
</td></tr>
<tr><td style="background:#ffffff;border-radius:12px;padding:40px 35px;box-shadow:0 2px 12px rgba(11,122,117,0.08);">
<h2 style="color:#065e5b;font-size:24px;margin:0 0 8px 0;font-weight:700;">Your Download is Ready!</h2>
<p style="color:#607080;font-size:14px;margin:0 0 24px 0;">Ebook access link inside</p>
<hr style="border:none;border-top:1px solid #e6f4f3;margin:0 0 24px 0;">
<p style="color:#2c3a47;font-size:16px;line-height:1.6;margin:0 0 16px 0;">Hi {$this->lead->full_name},</p>
<p style="color:#2c3a47;font-size:16px;line-height:1.6;margin:0 0 16px 0;">Thank you for your interest in <strong style="color:#0b7a75;">{$ebook->title}</strong>.</p>
<p style="color:#607080;font-size:15px;line-height:1.6;margin:0 0 20px 0;font-style:italic;">{$ebook->description}</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;"><tr><td align="center">
<a href="{$downloadUrl}" style="background:#e8773a;color:#ffffff;padding:15px 40px;border-radius:8px;text-decoration:none;font-size:16px;font-weight:bold;display:inline-block;box-shadow:0 4px 12px rgba(232,119,58,0.3);">Download Your Ebook</a>
</td></tr></table>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#e6f4f3;border-radius:8px;margin:0 0 20px 0;"><tr><td style="padding:16px 20px;">
<p style="font-size:13px;color:#0b7a75;margin:0 0 4px 0;font-weight:600;">&#9200; Expires {$expiresAt}</p>
<p style="font-size:13px;color:#607080;margin:0;">You can download it up to {$hours} times within this period.</p>
</td></tr></table>
<p style="font-size:13px;color:#607080;margin:0 0 0 0;">If the button above doesn't work, copy and paste this URL into your browser:</p>
<p style="font-size:13px;margin:4px 0 0 0;"><a href="{$downloadUrl}" style="color:#0b7a75;word-break:break-all;">{$downloadUrl}</a></p>
</td></tr>
<tr><td align="center" style="padding:24px 20px;">
<p style="font-size:12px;color:#607080;line-height:1.5;margin:0 0 6px 0;">You received this email because you requested this ebook from {$companyName}.</p>
<p style="font-size:12px;color:#607080;line-height:1.5;margin:0;">&copy; {$companyName} " . date('Y') . ". All rights reserved.</p>
</td></tr>
</table>
</td></tr>
</table>
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
