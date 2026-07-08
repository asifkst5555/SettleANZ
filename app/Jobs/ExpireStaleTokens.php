<?php

namespace App\Jobs;

use App\Enums\DownloadTokenStatus;
use App\Events\DownloadTokenExpired;
use App\Models\DownloadToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpireStaleTokens implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $expiredTokens = DownloadToken::where('status', DownloadTokenStatus::Active->value)
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expiredTokens as $token) {
            $token->expire();
            DownloadTokenExpired::dispatch($token);
        }
    }
}
