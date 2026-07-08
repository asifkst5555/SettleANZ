<?php

namespace App\Services;

use App\DTOs\LeadDTO;
use App\Enums\LeadStatus;
use App\Events\LeadCaptured;
use App\Models\Ebook;
use App\Models\Lead;

class LeadCaptureService
{
    public function capture(LeadDTO $dto): Lead
    {
        $lead = Lead::create([
            'full_name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'company' => $dto->company,
            'country' => $dto->country,
            'ebook_id' => $dto->ebookId,
            'consent' => $dto->consent,
            'utm_source' => $dto->utmParams['utm_source'] ?? null,
            'utm_medium' => $dto->utmParams['utm_medium'] ?? null,
            'utm_campaign' => $dto->utmParams['utm_campaign'] ?? null,
            'utm_term' => $dto->utmParams['utm_term'] ?? null,
            'utm_content' => $dto->utmParams['utm_content'] ?? null,
            'source_page' => $dto->sourcePage,
            'form_type' => 'ebook_download',
            'status' => LeadStatus::New->value,
            'ip_address' => $dto->ipAddress,
            'user_agent' => $dto->userAgent,
        ]);

        if ($dto->ebookId) {
            Ebook::where('id', $dto->ebookId)->increment('lead_count');
        }

        LeadCaptured::dispatch($lead);

        return $lead;
    }

    public function findOrCreate(LeadDTO $dto): Lead
    {
        $lead = Lead::where('email', $dto->email)->first();

        if ($lead) {
            $lead->update([
                'full_name' => $dto->name,
                'phone' => $dto->phone ?: $lead->phone,
                'company' => $dto->company ?: $lead->company,
                'country' => $dto->country ?: $lead->country,
                'ebook_id' => $dto->ebookId ?: $lead->ebook_id,
                'consent' => $dto->consent ?: $lead->consent,
                'ip_address' => $dto->ipAddress,
                'user_agent' => $dto->userAgent,
            ]);

            if ($dto->ebookId && !$lead->ebook_id) {
                Ebook::where('id', $dto->ebookId)->increment('lead_count');
            }

            return $lead;
        }

        return $this->capture($dto);
    }

    public function getLeadByEmail(string $email): ?Lead
    {
        return Lead::where('email', $email)->first();
    }

    public function getLeadsByEbook(int $ebookId): array
    {
        return Lead::where('ebook_id', $ebookId)
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        return [
            'total' => Lead::count(),
            'new' => Lead::where('status', LeadStatus::New->value)->count(),
            'downloaded' => Lead::where('status', LeadStatus::Downloaded->value)->count(),
            'converted' => Lead::where('status', LeadStatus::Converted->value)->count(),
            'today' => Lead::whereDate('created_at', today())->count(),
            'this_week' => Lead::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'with_consent' => Lead::where('consent', true)->count(),
        ];
    }
}
