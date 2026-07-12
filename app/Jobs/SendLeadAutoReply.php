<?php

namespace App\Jobs;

use App\Enums\EmailStatus;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendLeadAutoReply implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Lead $lead,
    ) {}

    public function handle(EmailService $emailService): void
    {
        $leadId = $this->lead->id;
        $email = $this->lead->email;
        $formType = $this->lead->form_type ?? 'general';

        Log::debug('[TRACE] SendLeadAutoReply::handle started', [
            'lead_id' => $leadId,
            'email' => $email,
            'form_type' => $formType,
        ]);

        $template = $this->resolveTemplate($formType);

        if ($template) {
            Log::debug('[TRACE] SendLeadAutoReply using admin email template', [
                'lead_id' => $leadId,
                'template_id' => $template->id,
                'template_type' => $template->type,
            ]);

            $emailService->sendTemplatedEmail(
                lead: $this->lead,
                template: $template,
                variables: $this->templateVariables($formType),
            );

            Log::debug('[TRACE] SendLeadAutoReply::handle completed successfully', [
                'lead_id' => $leadId,
            ]);

            return;
        }

        $message = $this->buildAutoReply();

        Log::debug('[TRACE] Calling EmailService::sendCustomEmail', [
            'lead_id' => $leadId,
            'to' => $email,
            'subject' => $message['subject'],
        ]);

        $emailService->sendCustomEmail(
            to: $email,
            subject: $message['subject'],
            bodyHtml: $message['body_html'],
            bodyText: $message['body_text'],
            leadId: $leadId,
        );

        Log::debug('[TRACE] SendLeadAutoReply::handle completed successfully', [
            'lead_id' => $leadId,
        ]);
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

    private function buildAutoReply(): array
    {
        $name = $this->lead->full_name ?? $this->lead->first_name ?? 'there';
        $formType = $this->lead->form_type ?? 'general';

        $isBooking = in_array($formType, ['package_booking', 'consultation-booking', 'migration-consultation'], true);
        $subject = $isBooking
            ? 'We received your booking request'
            : 'Thanks for contacting SettleANZ';

        $specificLine = $isBooking
            ? 'We have received your booking request and will confirm the session details shortly.'
            : 'We have received your message and will review it carefully.';

        $bodyText = <<<TEXT
Hi {$name},

Thank you for reaching out to SettleANZ.

{$specificLine} A member of our team will get back to you within 24 hours.

Warm regards,
The SettleANZ Team

P.S. If your question is urgent, you can reply directly to this email and we will do our best to help.
TEXT;

        return [
            'subject' => $subject,
            'body_html' => $this->buildAutoReplyHtml($name, $specificLine, $subject),
            'body_text' => $bodyText,
        ];
    }

    private function resolveTemplate(string $formType): ?EmailTemplate
    {
        $type = in_array($formType, ['package_booking', 'consultation-booking', 'migration-consultation'], true)
            ? EmailTemplate::TYPE_BOOKING_AUTO_REPLY
            : EmailTemplate::TYPE_CONTACT_AUTO_REPLY;

        return EmailTemplate::active()
            ->byType($type)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
    }

    private function templateVariables(string $formType): array
    {
        $isBooking = in_array($formType, ['package_booking', 'consultation-booking', 'migration-consultation'], true);

        return [
            'lead_name' => $this->lead->full_name ?? $this->lead->first_name ?? 'there',
            'lead_email' => $this->lead->email,
            'form_type' => $formType,
            'enquiry_type' => $isBooking ? 'booking request' : 'contact enquiry',
            'response_time' => '24 hours',
            'company_name' => 'SettleANZ',
        ];
    }

    private function buildAutoReplyHtml(string $name, string $specificLine, string $subject): string
    {
        $safeName = e($name);
        $safeSpecificLine = e($specificLine);
        $safeSubject = e($subject);
        $year = now()->year;

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$safeSubject}</title>
</head>
<body style="margin:0;padding:0;background:#F8F4EC;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#F8F4EC;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #E5E7EB;border-radius:12px;">
<tr><td style="padding:36px 40px 8px;">
<div style="font-size:14px;line-height:20px;font-weight:700;letter-spacing:.02em;color:#0F766E;text-transform:uppercase;">SettleANZ</div>
<h1 style="margin:12px 0 0;font-size:26px;line-height:34px;font-weight:700;color:#0f172a;">Thanks, {$safeName}</h1>
</td></tr>
<tr><td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {$safeName},</p>
<p style="margin:0 0 18px;">Thank you for reaching out to SettleANZ.</p>
<p style="margin:0 0 18px;">{$safeSpecificLine} A member of our team will get back to you within <strong>24 hours</strong>.</p>
</td></tr>
<tr><td style="padding:0 40px 28px;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Warm regards,<br>The SettleANZ Team</p>
<p style="margin:0;color:#64748b;">P.S. If your question is urgent, you can reply directly to this email and we will do our best to help.</p>
</td></tr>
<tr><td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">SettleANZ</div>
<div>&copy; {$year} SettleANZ. All rights reserved.</div>
</td></tr>
</table>
</center>
</body>
</html>
HTML;
    }
}
