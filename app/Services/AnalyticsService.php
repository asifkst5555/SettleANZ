<?php

namespace App\Services;

use App\Models\DownloadLog;
use App\Models\DownloadToken;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Models\Ebook;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getDashboardStats(): array
    {
        return [
            'overview' => $this->getOverview(),
            'downloads_over_time' => $this->getDownloadsOverTime(30),
            'top_ebooks' => $this->getTopEbooks(5),
            'leads_over_time' => $this->getLeadsOverTime(30),
            'email_stats' => $this->getEmailStats(),
            'device_breakdown' => $this->getDeviceBreakdown(),
            'geography' => $this->getGeography(),
        ];
    }

    private function getOverview(): array
    {
        return [
            'total_ebooks' => Ebook::count(),
            'published_ebooks' => Ebook::published()->count(),
            'total_leads' => Lead::count(),
            'new_leads_today' => Lead::whereDate('created_at', today())->count(),
            'total_downloads' => DownloadLog::count(),
            'downloads_today' => DownloadLog::whereDate('created_at', today())->count(),
            'total_emails_sent' => EmailLog::sent()->count(),
            'active_tokens' => DownloadToken::active()->count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];
    }

    public function getDownloadsOverTime(int $days = 30): array
    {
        return DownloadLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    public function getLeadsOverTime(int $days = 30): array
    {
        return Lead::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    public function getTopEbooks(int $limit = 5): array
    {
        return Ebook::published()
            ->select(['id', 'title', 'slug', 'download_count', 'lead_count', 'file_type'])
            ->orderByDesc('download_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    private function getEmailStats(): array
    {
        $total = EmailLog::count();
        $sent = EmailLog::sent()->count();

        return [
            'total' => $total,
            'sent' => $sent,
            'failed' => EmailLog::failed()->count(),
            'open_rate' => $sent > 0
                ? round((EmailLog::whereNotNull('opened_at')->count() / $sent) * 100, 2)
                : 0,
            'click_rate' => $sent > 0
                ? round((EmailLog::whereNotNull('clicked_at')->count() / $sent) * 100, 2)
                : 0,
        ];
    }

    private function getDeviceBreakdown(): array
    {
        return [
            'desktop' => DownloadLog::where('device_type', 'desktop')->count(),
            'mobile' => DownloadLog::where('device_type', 'mobile')->count(),
            'tablet' => DownloadLog::where('device_type', 'tablet')->count(),
            'unknown' => DownloadLog::whereNull('device_type')->count(),
        ];
    }

    private function getGeography(): array
    {
        return DownloadLog::select('country', DB::raw('COUNT(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function calculateConversionRate(): float
    {
        $totalLeads = Lead::count();
        if ($totalLeads === 0) {
            return 0;
        }

        $downloaded = Lead::where('status', 'downloaded')->count();
        return round(($downloaded / $totalLeads) * 100, 2);
    }

    public function getExportData(string $type, array $filters = []): array
    {
        return match ($type) {
            'leads' => $this->exportLeads($filters),
            'downloads' => $this->exportDownloads($filters),
            'email_logs' => $this->exportEmailLogs($filters),
            default => [],
        };
    }

    private function exportLeads(array $filters): array
    {
        return Lead::query()
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['ebook_id'] ?? null, fn ($q, $v) => $q->where('ebook_id', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->get()
            ->toArray();
    }

    private function exportDownloads(array $filters): array
    {
        return DownloadLog::with(['ebook:id,title', 'lead:id,full_name,email'])
            ->when($filters['ebook_id'] ?? null, fn ($q, $v) => $q->where('ebook_id', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->get()
            ->toArray();
    }

    private function exportEmailLogs(array $filters): array
    {
        return EmailLog::with(['lead:id,full_name,email', 'emailTemplate:id,name'])
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->get()
            ->toArray();
    }
}
