<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $settings = Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();

            $view->with([
                'navItems' => SiteDefaults::navItems(),
                'guides' => SiteDefaults::featuredGuides(),
                'sharedSettings' => $settings,
            ]);
        });
    }
}
