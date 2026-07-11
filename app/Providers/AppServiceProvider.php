<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Services\AiAdminAssistantService;
use App\Services\AiEmailService;
use App\Services\AnalyticsService;
use App\Services\DownloadService;
use App\Services\EbookService;
use App\Services\EmailService;
use App\Services\LeadCaptureService;
use App\Support\SiteDefaults;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EbookService::class);
        $this->app->singleton(LeadCaptureService::class);
        $this->app->singleton(DownloadService::class, function ($app) {
            return new DownloadService($app->make(EbookService::class));
        });
        $this->app->singleton(EmailService::class);
        $this->app->singleton(AiEmailService::class);
        $this->app->singleton(AiAdminAssistantService::class, function ($app) {
            return new AiAdminAssistantService(
                $app->make(AiEmailService::class),
                $app->make(EmailService::class),
                $app->make(DownloadService::class),
                $app->make(LeadCaptureService::class),
            );
        });
        $this->app->singleton(AnalyticsService::class);
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $settings = Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();

            $view->with([
                'navItems' => SiteDefaults::visibleNavItems(),
                'guides' => SiteDefaults::featuredGuides(),
                'sharedSettings' => $settings,
            ]);
        });


    }
}
