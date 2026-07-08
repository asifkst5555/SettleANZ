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

class EmailService
{
    public function applyMailConfig(): void
    {
        $mailer = SiteSetting::getValue('mail_mailer', 'log');
        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            Config::set('mail.mailers.smtp.host', SiteSetting::getValue('smtp_host', config('mail.mailers.smtp.host')));
            Config::set('mail.mailers.smtp.port', (int) SiteSetting::getValue('smtp_port', config('mail.mailers.smtp.port')));
            Config::set('mail.mailers.smtp.username', SiteSetting::getValue('smtp_username', config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', SiteSetting::getValue('smtp_password', config('mail.mailers.smtp.password')));
            $encryption = SiteSetting::getValue('mail_encryption', 'tls');
            Config::set('mail.mailers.smtp.encryption', $encryption ?: null);
        }

        $fromAddress = SiteSetting::getValue('mail_from_address', config('mail.from.address'));
        $fromName = SiteSetting::getValue('mail_from_name', config('mail.from.name'));
        if ($fromAddress) {
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName ?: config('app.name'));
        }

        Mail::purge();
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
        $this->applyMailConfig();

        $log = EmailLog::create([
            'email_template_id' => $templateId,
            'lead_id' => $leadId,
            'campaign_id' => $campaignId,
            'to_email' => $to,
            'subject' => $subject,
            'status' => EmailStatus::Pending->value,
        ]);

        try {
            Mail::html($bodyHtml, function ($message) use ($to, $subject, $attachmentPath) {
                $message->to($to)
                    ->subject($subject);
                if ($attachmentPath) {
                    $message->attach($attachmentPath);
                }
            });

            $log->markSent();
        } catch (\Exception $e) {
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
            'company_logo' => asset('images/logo.png'),
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
            Mail::html($bodyHtml, function ($message) use ($to, $toName, $subject) {
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
}
