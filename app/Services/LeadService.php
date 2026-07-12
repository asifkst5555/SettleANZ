<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function dashboardCards(): array
    {
        $base = Lead::notArchived();

        return [
            'total' => (clone $base)->count(),
            'today' => (clone $base)->whereDate('created_at', today())->count(),
            'contact_messages' => (clone $base)->where('form_type', 'contact-page')->count(),
            'consultation_requests' => (clone $base)->whereIn('form_type', ['package_booking'])->count(),
            'package_requests' => (clone $base)->where('form_type', 'package_booking')->count(),
            'ebook_downloads' => (clone $base)->where('form_type', 'ebook_download')->count(),
            'ai_requests' => (clone $base)->where('form_type', 'ai_chat')->count(),
            'newsletter_subscribers' => (clone $base)->where('form_type', 'newsletter_signup')->count(),
            'roadmap_downloads' => (clone $base)->where('form_type', 'homepage_roadmap')->count(),
            'new_leads' => (clone $base)->where('status', 'new')->count(),
            'qualified' => (clone $base)->where('status', 'qualified')->count(),
            'conversion_rate' => $this->conversionRate(clone $base),
            'this_month' => (clone $base)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'last_month' => (clone $base)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count(),
            'pending_tasks' => LeadTask::where('status', 'pending')
                ->where(function ($q) {
                    $q->where('assigned_to', auth()->id())
                      ->orWhere('user_id', auth()->id());
                })->count(),
        ];
    }

    protected function conversionRate($query): float
    {
        $total = (clone $query)->count();
        if ($total === 0) return 0;
        $won = (clone $query)->where('status', 'won')->count();
        return round(($won / $total) * 100, 1);
    }

    public function chartData(string $period = '30'): Collection
    {
        $days = (int) $period;
        $results = collect();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $results->push([
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'new' => Lead::whereDate('created_at', $date)->where('is_archived', false)->count(),
                'contacted' => Lead::whereDate('updated_at', $date)->where('status', 'contacted')->count(),
                'won' => Lead::whereDate('converted_at', $date)->count(),
            ]);
        }

        return $results;
    }

    public function leadsBySource(): Collection
    {
        return Lead::notArchived()
            ->select('form_type', DB::raw('count(*) as count'))
            ->groupBy('form_type')
            ->orderByDesc('count')
            ->get();
    }

    public function leadsByPage(): Collection
    {
        return Lead::notArchived()
            ->select('source_page', DB::raw('count(*) as count'))
            ->whereNotNull('source_page')
            ->groupBy('source_page')
            ->orderByDesc('count')
            ->get();
    }

    public function leadsByVisaType(): Collection
    {
        return Lead::notArchived()
            ->select('visa_type', DB::raw('count(*) as count'))
            ->whereNotNull('visa_type')
            ->groupBy('visa_type')
            ->orderByDesc('count')
            ->get();
    }

    public function monthlyTrend(int $months = 12): Collection
    {
        $results = collect();
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $results->push([
                'month' => $date->format('M Y'),
                'total' => Lead::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->where('is_archived', false)->count(),
                'won' => Lead::whereYear('converted_at', $date->year)->whereMonth('converted_at', $date->month)->count(),
            ]);
        }
        return $results;
    }

    public function topStaff(): Collection
    {
        return User::selectRaw('users.id, users.name, users.email, count(leads.id) as lead_count')
            ->join('leads', 'users.id', '=', 'leads.assigned_to')
            ->whereNotNull('leads.assigned_to')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('lead_count')
            ->limit(5)
            ->get();
    }

    public function advancedSearch(array $filters, string $sortField = 'created_at', string $sortDir = 'desc', int $perPage = 25): LengthAwarePaginator
    {
        $query = Lead::notArchived()
            ->with(['assignedStaff:id,name', 'tags:id,name,color'])
            ->withCount(['activities', 'leadNotes', 'tasks']);

        $query->filter($filters);

        $allowedSorts = ['created_at', 'updated_at', 'full_name', 'email', 'status', 'priority', 'lead_score', 'form_type', 'source_page', 'visa_type', 'country'];
        $sortField = in_array($sortField, $allowedSorts) ? $sortField : 'created_at';
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortField, $sortDir)->paginate($perPage);
    }

    public function exportData(array $filters, string $format = 'csv'): string
    {
        $query = Lead::notArchived()->with(['assignedStaff:id,name', 'ebook:id,title']);
        $query->filter($filters);

        $leads = $query->orderBy('created_at', 'desc')->limit(5000)->get();

        $headers = [
            'ID', 'Name', 'Email', 'Phone', 'Company', 'Country',
            'Lead Source', 'Website Page', 'Form Name',
            'Visa Type', 'Package', 'Ebook', 'Service Interest',
            'Status', 'Priority', 'Score', 'Assigned To',
            'Created', 'Last Activity',
        ];

        $rows = $leads->map(function ($lead) {
            return [
                $lead->id,
                $lead->full_name ?? $lead->first_name ?? '',
                $lead->email,
                $lead->phone ?? '',
                $lead->company ?? '',
                $lead->country ?? '',
                $lead->lead_source_label,
                $lead->website_page_label,
                $lead->form_name_label,
                $lead->visa_type ? (Lead::visaTypes()[$lead->visa_type] ?? $lead->visa_type) : '',
                $lead->package_name ?? '',
                $lead->ebook_title ?? ($lead->ebook?->title ?? ''),
                $lead->interested_service ?? '',
                ucfirst($lead->status),
                ucfirst($lead->priority),
                $lead->lead_score,
                $lead->assignedStaff?->name ?? 'Unassigned',
                $lead->created_at?->format('Y-m-d H:i'),
                $lead->last_activity_at?->format('Y-m-d H:i') ?? '',
            ];
        })->toArray();

        if ($format === 'csv') {
            $output = fopen('php://temp', 'r+');
            fputcsv($output, $headers);
            foreach ($rows as $row) fputcsv($output, $row);
            rewind($output);
            $content = stream_get_contents($output);
            fclose($output);
            return $content;
        }

        return '';
    }

    public function exportPdf(array $filters)
    {
        $query = Lead::notArchived()->with(['assignedStaff:id,name', 'ebook:id,title']);
        $query->filter($filters);
        $leads = $query->orderBy('created_at', 'desc')->limit(500)->get();

        $data = [
            'title' => 'Lead Export - ' . now()->format('d M Y'),
            'date' => now()->format('d/m/Y'),
            'leads' => $leads,
        ];

        $html = view('admin.leads.export-pdf', $data)->render();
        $pdf = Pdf::loadHTML($html);
        return $pdf->download('leads-' . now()->format('Y-m-d') . '.pdf');
    }

    public function recalculateScore(Lead $lead): int
    {
        $score = 0;
        if ($lead->email) $score += 10;
        if ($lead->phone) $score += 10;
        if ($lead->company) $score += 5;
        if ($lead->budget && $lead->budget > 0) $score += 10;
        if ($lead->interested_service) $score += 5;
        if ($lead->country) $score += 5;
        if ($lead->notes) $score += 5;
        if ($lead->visa_type) $score += 5;
        $score += min($lead->activities()->count() * 2, 15);
        $score += min($lead->leadNotes()->count() * 2, 10);
        $score += $lead->tags()->exists() ? 5 : 0;
        $sourceBonuses = ['homepage_roadmap' => 5, 'contact-page' => 10, 'package_booking' => 15];
        if ($lead->form_type && isset($sourceBonuses[$lead->form_type])) $score += $sourceBonuses[$lead->form_type];
        $daysSinceCreated = now()->diffInDays($lead->created_at);
        if ($daysSinceCreated <= 7) $score += 10;
        elseif ($daysSinceCreated <= 30) $score += 5;

        $score = min(max($score, 0), 100);
        if ($lead->lead_score !== $score) $lead->updateQuietly(['lead_score' => $score]);
        return $score;
    }

    public function assignLead(Lead $lead, int $userId): void
    {
        $lead->update(['assigned_to' => $userId]);
    }

    public function bulkAssign(Collection $leads, int $userId): int
    {
        $count = 0;
        foreach ($leads as $lead) { $this->assignLead($lead, $userId); $count++; }
        return $count;
    }

    public function bulkStatus(Collection $leads, string $status): int
    {
        return $leads->each(fn($l) => $l->update(['status' => $status]))->count();
    }

    public function bulkDelete(Collection $leads): int
    {
        return $leads->each(fn($l) => $l->delete())->count();
    }

    public function bulkArchive(Collection $leads): int
    {
        return $leads->each(fn($l) => $l->update(['is_archived' => true]))->count();
    }

    public function calendarData(string $start, string $end): Collection
    {
        return Lead::notArchived()
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function ($lead) {
                $colors = Lead::statusColors();
                return [
                    'id' => $lead->id,
                    'title' => $lead->full_name ?: $lead->email,
                    'start' => $lead->created_at->format('Y-m-d'),
                    'end' => $lead->created_at->format('Y-m-d'),
                    'backgroundColor' => $colors[$lead->status] ?? '#6366f1',
                    'borderColor' => $colors[$lead->status] ?? '#6366f1',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'status' => $lead->status,
                        'email' => $lead->email,
                        'source' => $lead->lead_source_label,
                    ],
                ];
            });
    }
}
