<?php

namespace App\Providers;

use App\Events\DownloadTokenExpired;
use App\Events\EbookDownloaded;
use App\Events\LeadCaptured;
use App\Listeners\HandleDownloadTokenExpired;
use App\Listeners\HandleLeadCaptured;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LeadCaptured::class => [
            HandleLeadCaptured::class,
        ],
        EbookDownloaded::class => [],
        DownloadTokenExpired::class => [
            HandleDownloadTokenExpired::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
