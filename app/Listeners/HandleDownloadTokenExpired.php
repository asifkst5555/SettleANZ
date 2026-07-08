<?php

namespace App\Listeners;

use App\Events\DownloadTokenExpired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleDownloadTokenExpired implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(DownloadTokenExpired $event): void
    {
        $token = $event->downloadToken;

        Log::info('Download token expired', [
            'token_id' => $token->id,
            'ebook_id' => $token->ebook_id,
            'lead_id' => $token->lead_id,
            'expires_at' => $token->expires_at,
        ]);
    }
}
