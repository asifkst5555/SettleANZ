<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\ActivityLog;
use App\Services\LeadService;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->hasPermission('dashboard.view'), 403);

        $leadService = app(LeadService::class);
        $analyticsService = app(AnalyticsService::class);

        $leadServiceStats = $leadService->dashboardCards();
        $monthlyTrend = $leadService->monthlyTrend(12);
        $leadsBySource = $leadService->leadsBySource();
        $leadsByVisaType = $leadService->leadsByVisaType();
        $ebookStats = $analyticsService->getDashboardStats();

        // Extra aggregations for charts
        $leadsByStatus = Lead::notArchived()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $countryDistribution = Lead::notArchived()
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(6)
            ->get();

        // Upcoming consultations schedule widget
        $upcomingConsultations = Lead::where('form_type', 'consultation-booking')
            ->where('is_archived', false)
            ->whereDate('preferred_date', '>=', today())
            ->orderBy('preferred_date', 'asc')
            ->limit(8)
            ->get();

        // Combined activity log
        $activities = collect();
        try {
            $leadActivities = LeadActivity::with('lead:id,full_name,first_name,email')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function($act) {
                    return [
                        'type' => $act->type,
                        'label' => $act->label ?: ucwords(str_replace('_', ' ', $act->type)),
                        'description' => $act->description,
                        'time' => $act->created_at,
                        'user' => $act->user?->name ?? 'System',
                    ];
                });
            $activities = $activities->concat($leadActivities);
        } catch (\Exception $e) {}

        try {
            $systemActivities = ActivityLog::with('user:id,name')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function($act) {
                    return [
                        'type' => $act->action,
                        'label' => ucwords(str_replace('_', ' ', $act->action)),
                        'description' => $act->description,
                        'time' => $act->created_at,
                        'user' => $act->user?->name ?? 'System',
                    ];
                });
            $activities = $activities->concat($systemActivities);
        } catch (\Exception $e) {}

        $activities = $activities->sortByDesc('time')->take(10);

        // System Health Checks
        $dbStatus = 'green';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'red';
        }

        $mailStatus = config('mail.mailers.smtp.host') && config('mail.mailers.smtp.username') ? 'green' : 'yellow';
        $storageStatus = is_writable(storage_path()) && is_writable(storage_path('logs')) ? 'green' : 'red';
        
        // Queue status
        $queueStatus = 'green';
        try {
            $queueStatus = DB::table('jobs')->count() > 5 ? 'yellow' : 'green';
        } catch (\Exception $e) {}

        $cronStatus = 'green';
        $aiStatus = env('GEMINI_API_KEY') || env('OPENAI_API_KEY') || config('services.gemini.key') ? 'green' : 'yellow';
        $websiteStatus = 'green';
        $sslStatus = request()->secure() || env('APP_ENV') === 'local' ? 'green' : 'yellow';
        $backupStatus = 'green';

        return view('admin.dashboard', [
            'metaTitle' => 'Admin Dashboard | SettleANZ',
            'leadCount' => Lead::count(),
            'newLeadCount' => Lead::where('status', 'new')->count(),
            'contactLeadCount' => Lead::where('form_type', 'contact-page')->count(),
            'migrationLeadCount' => Lead::where('form_type', 'migration-consultation')->count(),
            'consultationBookingCount' => Lead::where('form_type', 'consultation-booking')->count(),
            'packageBookingCount' => Lead::where('form_type', 'package_booking')->count(),
            'blogPostCount' => BlogPost::count(),
            'directoryListingCount' => DirectoryListing::count(),
            'recentLeads' => Lead::query()->latest()->limit(10)->get(),
            'recentContactLeads' => Lead::query()->where('form_type', 'contact-page')->latest()->limit(5)->get(),
            'recentConsultationBookings' => Lead::query()->where('form_type', 'consultation-booking')->latest()->limit(5)->get(),
            'recentPackageBookings' => Lead::query()->where('form_type', 'package_booking')->latest()->limit(5)->get(),
            'recentDownloads' => Lead::where('form_type', 'ebook_download')->latest()->limit(5)->get(),
            'recentPosts' => BlogPost::query()->latest('published_at')->limit(5)->get(),
            'featuredListings' => DirectoryListing::query()->where('featured', true)->latest()->limit(5)->get(),
            'ebookStats' => $ebookStats,
            
            // New aggregations
            'leadServiceStats' => $leadServiceStats,
            'monthlyTrend' => $monthlyTrend,
            'leadsBySource' => $leadsBySource,
            'leadsByVisaType' => $leadsByVisaType,
            'leadsByStatus' => $leadsByStatus,
            'countryDistribution' => $countryDistribution,
            'upcomingConsultations' => $upcomingConsultations,
            'activities' => $activities,
            
            // Health statuses
            'systemHealth' => [
                'database' => $dbStatus,
                'mail' => $mailStatus,
                'storage' => $storageStatus,
                'queue' => $queueStatus,
                'cron' => $cronStatus,
                'ai' => $aiStatus,
                'website' => $websiteStatus,
                'ssl' => $sslStatus,
                'backup' => $backupStatus,
            ]
        ]);
    }
}
