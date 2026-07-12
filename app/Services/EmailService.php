<?php

namespace App\Services;

use App\Enums\EmailStatus;
use App\Jobs\SendEbookDownloadEmail;
use App\Models\DownloadToken;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function applyMailConfig(): void
    {
        if (Mail::getFacadeRoot() instanceof \Illuminate\Support\Testing\Fakes\MailFake) {
            return;
        }

        $mailer = SiteSetting::getValue('mail_mailer', 'log');
        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            Config::set('mail.mailers.smtp.host', SiteSetting::getValue('smtp_host', config('mail.mailers.smtp.host')));
            $port = (int) SiteSetting::getValue('smtp_port', config('mail.mailers.smtp.port'));
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', SiteSetting::getValue('smtp_username', config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', SiteSetting::getValue('smtp_password', config('mail.mailers.smtp.password')));

            // Laravel 11+ / Symfony Mailer uses 'scheme' instead of 'encryption'.
            // 'smtp' scheme = STARTTLS on port 587, 'smtps' = direct SSL on port 465.
            $encryption = SiteSetting::getValue('mail_encryption', 'tls');
            if ($encryption === 'ssl' || $port === 465) {
                Config::set('mail.mailers.smtp.scheme', 'smtps');
            } else {
                // STARTTLS (port 587) or no encryption — use 'smtp' scheme
                Config::set('mail.mailers.smtp.scheme', 'smtp');
            }
            // Also set the legacy key for backward compatibility
            Config::set('mail.mailers.smtp.encryption', $encryption ?: null);
        }

        $fromAddress = SiteSetting::getValue('mail_from_address', config('mail.from.address'));
        $fromName = SiteSetting::getValue('mail_from_name', config('mail.from.name'));
        if ($fromAddress) {
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName ?: config('app.name'));
        }

        // Purge both the specific smtp mailer and any default cached instance
        // to force Symfony to rebuild the transport with the new config
        Mail::purge('smtp');
        Mail::purge($mailer);
    }

    public function sendDownloadEmail(Lead $lead, DownloadToken $token, ?int $templateId = null): void
    {
        SendEbookDownloadEmail::dispatch($lead, $token, $templateId)
            ->onQueue('emails');
    }

    public function sendCustomEmail(
        string $to,
        string $subject,
        string $bodyHtml,
        ?string $bodyText = null,
        ?int $leadId = null,
        ?int $templateId = null,
        ?int $campaignId = null,
        ?string $attachmentPath = null,
    ): EmailLog {
        Log::debug('[TRACE] EmailService::sendCustomEmail started', [
            'lead_id' => $leadId,
            'to' => $to,
            'subject' => $subject,
        ]);

        $this->applyMailConfig();

        Log::debug('[TRACE] EmailService::applyMailConfig completed', [
            'lead_id' => $leadId,
            'mail_default' => config('mail.default'),
        ]);

        $log = EmailLog::create([
            'email_template_id' => $templateId,
            'lead_id' => $leadId,
            'campaign_id' => $campaignId,
            'to_email' => $to,
            'subject' => $subject,
            'status' => EmailStatus::Pending->value,
        ]);

        Log::debug('[TRACE] EmailLog created', [
            'log_id' => $log->id,
            'lead_id' => $leadId,
        ]);

        try {
            $mail = new \App\Mail\CustomHtmlMail($subject, $this->ensureValidEmailHtml($bodyHtml));
            if ($attachmentPath) {
                $mail->attach($attachmentPath);
            }

            Log::debug('[TRACE] Calling Mail::send', [
                'lead_id' => $leadId,
                'log_id' => $log->id,
            ]);

            Mail::to($to)->send($mail);

            $log->markSent();

            Log::debug('[TRACE] Email sent successfully', [
                'log_id' => $log->id,
                'lead_id' => $leadId,
            ]);
        } catch (\Exception $e) {
            Log::debug('[TRACE] Email send FAILED', [
                'lead_id' => $leadId,
                'log_id' => $log->id,
                'error' => $e->getMessage(),
            ]);

            $log->markFailed($e->getMessage());
            throw $e;
        }

        return $log->fresh();
    }

    public function sendTemplatedEmail(Lead $lead, EmailTemplate $template, array $variables = [], ?string $attachmentPath = null): EmailLog
    {
        $data = array_merge([
            'lead_name' => $lead->full_name ?? $lead->first_name ?? 'Valued Lead',
            'lead_email' => $lead->email,
            'company_name' => config('app.name'),
            'company_logo' => asset('media/logo/email_logo.png'),
            'current_year' => date('Y'),
        ], $variables);

        $parsed = $template->parseVariables($data);

        return $this->sendCustomEmail(
            to: $lead->email,
            subject: $parsed['subject'],
            bodyHtml: $parsed['body_html'],
            bodyText: $parsed['body_text'] ?? null,
            leadId: $lead->id,
            templateId: $template->id,
            attachmentPath: $attachmentPath,
        );
    }

    public function sendRawEmail(
        string $to,
        string $toName,
        string $subject,
        string $bodyHtml,
        ?string $bodyText = null,
    ): bool {
        $this->applyMailConfig();

        try {
            Mail::html($this->ensureValidEmailHtml($bodyHtml), function ($message) use ($to, $toName, $subject) {
                $message->to($to, $toName)
                    ->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            logger()->error('Failed to send raw email', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getStats(): array
    {
        return [
            'total_sent' => EmailLog::sent()->count(),
            'total_failed' => EmailLog::failed()->count(),
            'total_pending' => EmailLog::byStatus(EmailStatus::Pending->value)->count(),
            'open_rate' => $this->calculateOpenRate(),
            'click_rate' => $this->calculateClickRate(),
            'bounce_rate' => $this->calculateBounceRate(),
        ];
    }

    private function calculateOpenRate(): float
    {
        $sent = EmailLog::sent()->count();
        if ($sent === 0) {
            return 0;
        }
        $opened = EmailLog::whereIn('status', [
            EmailStatus::Opened->value,
            EmailStatus::Clicked->value,
        ])->count();

        return round(($opened / $sent) * 100, 2);
    }

    private function calculateClickRate(): float
    {
        $sent = EmailLog::sent()->count();
        if ($sent === 0) {
            return 0;
        }
        $clicked = EmailLog::where('status', EmailStatus::Clicked->value)->count();

        return round(($clicked / $sent) * 100, 2);
    }

    private function calculateBounceRate(): float
    {
        $total = EmailLog::count();
        if ($total === 0) {
            return 0;
        }
        $bounced = EmailLog::byStatus(EmailStatus::Bounced->value)->count();

        return round(($bounced / $total) * 100, 2);
    }

    public function ensureValidEmailHtml(string $html): string
    {
        if (str_contains($html, '<!DOCTYPE') || str_contains($html, '<html')) {
            return $html;
        }
        return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#F8F4EC;font-family:Arial,Helvetica,sans-serif;">
<center style="width:100%;background-color:#F8F4EC;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F4EC;">
<tr>
<td align="center" style="padding:32px 16px;">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;border:1px solid #E5E7EB;">
<tr>
<td style="padding:40px;font-size:16px;line-height:1.6;color:#1F2937;">
' . $html . '
</td>
</tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>';
    }
}
