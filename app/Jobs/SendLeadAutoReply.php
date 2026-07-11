<?php

namespace App\Jobs;

use App\Enums\EmailStatus;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Services\AiEmailService;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendLeadAutoReply implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Lead $lead,
    ) {}

    public function handle(AiEmailService $aiEmail, EmailService $emailService): void
    {
        $data = [
            'lead_name' => $this->lead->full_name ?? $this->lead->first_name ?? 'there',
            'company_name' => config('app.name'),
            'form_type' => $this->lead->form_type ?? 'general',
        ];

        $response = $aiEmail->generateAutoReplyEmail($data);

        $subject = $response['subject'] ?? 'Thank you for reaching out';
        $bodyHtml = $response['body_html'] ?? $this->fallbackHtml();
        $bodyText = $response['body_text'] ?? null;

        $emailService->sendCustomEmail(
            to: $this->lead->email,
            subject: $subject,
            bodyHtml: $bodyHtml,
            bodyText: $bodyText,
            leadId: $this->lead->id,
        );
    }

    public function failed(\Throwable $e): void
    {
        logger()->error('Failed to send auto-reply email', [
            'lead_id' => $this->lead->id,
            'error' => $e->getMessage(),
        ]);

        $exists = EmailLog::where('lead_id', $this->lead->id)
            ->where('status', EmailStatus::Failed->value)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if (!$exists) {
            EmailLog::create([
                'lead_id' => $this->lead->id,
                'to_email' => $this->lead->email,
                'to_name' => $this->lead->full_name,
                'subject' => 'Thank you for reaching out',
                'status' => EmailStatus::Failed->value,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function fallbackHtml(): string
    {
        $name = e($this->lead->full_name ?? $this->lead->first_name ?? 'there');
        $company = e(config('app.name'));
        $year = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Thank You</title></head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#f5f7fa;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;">
<tr><td style="padding:36px 40px 10px;">
<h1 style="margin:0;font-size:24px;line-height:32px;font-weight:700;color:#0f172a;">Thank You, {$name}</h1>
</td></tr>
<tr><td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {$name},</p>
<p style="margin:0 0 18px;">Thank you for reaching out to {$company}. We have received your message and our team will review it shortly.</p>
<p style="margin:0 0 18px;">A member of our team will get back to you within <strong>24 hours</strong>.</p>
<p style="margin:0 0 18px;">If you have any urgent questions, feel free to reply directly to this email.</p>
</td></tr>
<tr><td style="padding:0 40px 28px;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0;">Warm regards,<br>The {$company} Team</p>
</td></tr>
<tr><td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{$company}</div>
<div>&copy; {{ date('Y') }} {$company}. All rights reserved.</div>
</td></tr>
</table>
</center>
</body>
</html>
HTML;
    }
}
