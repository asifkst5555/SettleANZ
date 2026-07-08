<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;

class ProcessCampaignEmails implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600;
    public int $tries = 1;

    public function __construct(
        private readonly Campaign $campaign,
    ) {}

    public function handle(EmailService $emailService): void
    {
        $this->campaign->markSending();

        $template = $this->campaign->emailTemplate;
        if (!$template || !$template->is_active) {
            $this->campaign->update([
                'status' => 'failed',
                'completed_at' => now(),
            ]);
            return;
        }

        $leads = $this->resolveLeads();

        if ($leads->isEmpty()) {
            $this->campaign->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            return;
        }

        $this->campaign->update(['total_recipients' => $leads->count()]);

        $jobs = $leads->map(fn (Lead $lead) => new SendCampaignEmailJob(
            campaign: $this->campaign,
            lead: $lead,
            template: $template,
        ));

        Bus::batch($jobs)
            ->then(function () {
                $this->campaign->markSent();
            })
            ->catch(function () {
                $this->campaign->update(['completed_at' => now()]);
            })
            ->dispatch();
    }

    private function resolveLeads()
    {
        $filters = $this->campaign->filters ?? [];

        $query = Lead::query()->where('consent', true);

        if (!empty($filters['ebook_id'])) {
            $query->where('ebook_id', $filters['ebook_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        if (!empty($this->campaign->ebook_id)) {
            $query->where('ebook_id', $this->campaign->ebook_id);
        }

        return $query->get();
    }
}
