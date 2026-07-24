<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Override app.name from database so emails/views never show "Laravel"
        // even if the production .env still has the default APP_NAME=Laravel.
        if (Schema::hasTable('site_settings')) {
            $companyName = SiteSetting::getValue('mail_from_name');
            if ($companyName) {
                config(['app.name' => $companyName]);
            }
        }

        View::composer('layouts.app', function ($view): void {
            $settings = Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();

            $view->with([
                'navItems' => SiteDefaults::visibleNavItems(),
                'guides' => SiteDefaults::featuredGuides(),
                'sharedSettings' => $settings,
            ]);
        });

        // Register Security Rate Limiters
        RateLimiter::for('public_forms', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())->response(function (Request $request, array $headers) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Too many submission attempts. Please try again in a minute.',
                    ], 429, $headers);
                }

                return redirect()->back()
                    ->withHeaders($headers)
                    ->withErrors(['math_answer' => 'Too many submission attempts. Please try again in a minute.']);
            });
        });

        RateLimiter::for('verification_refresh', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => 'Too many refresh requests. Please slow down.',
                ], 429, $headers);
            });
        });

        RateLimiter::for('api_chat', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => 'Too many messages sent. Please slow down.',
                ], 429, $headers);
            });
        });


    }
}
