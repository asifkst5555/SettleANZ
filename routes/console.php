<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process email queue every minute (runs queue:work until empty, then stops)
// Required for shared hosting — set up this cron in cPanel:
//   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
Schedule::command('queue:work', ['--queue' => 'emails', '--stop-when-empty' => true, '--tries' => 3, '--backoff' => 60])
    ->everyMinute()
    ->withoutOverlapping();
