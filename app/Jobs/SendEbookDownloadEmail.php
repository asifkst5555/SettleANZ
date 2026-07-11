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

        $viewUrl = route('ebook.view', ['token' => $this->token->token]);

        if ($template) {
            $variables = [
                'lead_name' => $this->lead->full_name ?? 'Valued Reader',
                'lead_email' => $this->lead->email,
                'ebook_title' => $ebook->title,
                'ebook_description' => $ebook->description,
                'ebook_author' => $ebook->author ?? config('app.name'),
                'download_url' => route('ebook.download', ['token' => $this->token->token]),
                'view_url' => $viewUrl,
                'download_link' => "<a href=\"" . route('ebook.download', ['token' => $this->token->token]) . "\">Click here to download</a>",
                'expires_at' => $this->token->expires_at->format('F j, Y \a\t g:i A'),
                'expires_in_hours' => (string) config('ebook.download.token_expiry_hours', 72),
                'company_name' => config('app.name'),
                'company_logo' => asset('media/logo/email_logo.png'),
                'current_year' => date('Y'),
            ];

            $emailService->sendTemplatedEmail($this->lead, $template, $variables);
        } else {
            $downloadUrl = route('ebook.download', ['token' => $this->token->token]);
            $expiresAt = $this->token->expires_at->format('F j, Y \a\t g:i A');
            $companyName = config('app.name');
            $companyLogo = asset('media/logo/email_logo.png');
            $hours = config('ebook.download.token_expiry_hours', 72);
            $supportEmail = \App\Models\SiteSetting::getValue('email_theme_support_email', 'support@settleanz.com');
            $year = date('Y');

            $bodyHtml = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Download Ready</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#f5f7fa;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;">
<tr>
<td align="center" style="padding:36px 30px 20px;">
<img src="{$companyLogo}" alt="{$companyName}" width="170" style="display:block;width:170px;max-width:100%;height:auto;border:0;">
</td>
</tr>

<tr>
<td style="padding:0 40px 10px;">
<h1 style="margin:0;font-size:28px;line-height:36px;font-weight:700;color:#0f172a;">
Your 90-Day Roadmap is Ready
</h1>
</td>
</tr>

<tr>
<td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {$this->lead->full_name},</p>

<p style="margin:0 0 18px;">
Thank you for downloading <strong>{$ebook->title}</strong>.
Your download is now ready.
</p>

<p style="margin:0 0 18px;">
This secure link expires on <strong>{$expiresAt}</strong>.<br>
You can download your file up to <strong>{$hours}</strong> times during this period.
</p>
</td>
</tr>

<tr>
<td align="center" style="padding:14px 40px 26px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0">
<tr>
<td bgcolor="#0f766e" style="border-radius:8px;padding:0 6px 0 0;">
<a href="{$viewUrl}" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;background:#0f766e;border-radius:8px;">
View Online
</a>
</td>
<td bgcolor="#e8773a" style="border-radius:8px;">
<a href="{$downloadUrl}" style="display:inline-block;padding:14px 34px;font-size:16px;font-weight:bold;color:#ffffff;text-decoration:none;background:#e8773a;border-radius:8px;">
Download PDF
</a>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding:0 40px 28px;font-size:14px;line-height:24px;color:#64748b;">
Having trouble viewing? <a href="{$viewUrl}" style="color:#0f766e;text-decoration:none;">Open in your browser</a> instead.
</td>
</tr>

<tr>
<td style="padding:0 40px 0;font-size:13px;line-height:20px;color:#94a3b8;">
If the buttons don't work, copy and paste these links:<br><br>
<strong>View:</strong> <a href="{$viewUrl}" style="color:#0f766e;word-break:break-all;text-decoration:none;">{$viewUrl}</a><br><br>
<strong>Download:</strong> <a href="{$downloadUrl}" style="color:#0f766e;word-break:break-all;text-decoration:none;">{$downloadUrl}</a>
</td>
</tr>

<tr>
<td style="padding:0 40px;">
<hr style="border:none;border-top:1px solid #e5e7eb;margin:0;">
</td>
</tr>

<tr>
<td style="padding:28px 40px;font-size:14px;line-height:24px;color:#64748b;">
If you have any questions, simply reply to this email or contact us at
<a href="mailto:{$supportEmail}" style="color:#0f766e;text-decoration:none;">{$supportEmail}</a>.
</td>
</tr>

<tr>
<td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{$companyName}</div>
<div style="margin-bottom:12px;">© {$year} {$companyName}. All rights reserved.</div>
<div>
<a href="mailto:{$supportEmail}" style="color:#64748b;text-decoration:none;">Support</a>
&nbsp;&nbsp;•&nbsp;&nbsp;
<a href="#" style="color:#64748b;text-decoration:none;">Unsubscribe</a>
</div>
</td>
</tr>

</table>
</center>
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
