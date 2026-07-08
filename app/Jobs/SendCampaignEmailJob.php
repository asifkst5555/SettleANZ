<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendCampaignEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Campaign $campaign,
        private readonly Lead $lead,
        private readonly EmailTemplate $template,
    ) {}

    public function handle(EmailService $emailService): void
    {
        $variables = [
            'lead_name' => $this->lead->full_name ?? 'Valued Lead',
            'lead_email' => $this->lead->email,
            'company_name' => config('app.name'),
            'current_year' => date('Y'),
        ];

        if ($this->campaign->ebook) {
            $variables['ebook_title'] = $this->campaign->ebook->title;
            $variables['ebook_description'] = $this->campaign->ebook->description;
        }

        $parsed = $this->template->parseVariables($variables);

        $log = $emailService->sendCustomEmail(
            to: $this->lead->email,
            subject: $parsed['subject'],
            bodyHtml: $parsed['body_html'],
            bodyText: $parsed['body_text'] ?? null,
            leadId: $this->lead->id,
            templateId: $this->template->id,
            campaignId: $this->campaign->id,
        );

        $this->campaign->leads()->updateExistingPivot($this->lead->id, [
            'status' => $log->status,
            'sent_at' => now(),
        ]);

        $this->campaign->increment('sent_count');
    }

    public function failed(\Throwable $e): void
    {
        logger()->error('Campaign email failed', [
            'campaign_id' => $this->campaign->id,
            'lead_id' => $this->lead->id,
            'error' => $e->getMessage(),
        ]);

        $this->campaign->leads()->updateExistingPivot($this->lead->id, [
            'status' => 'failed',
        ]);
    }
}
