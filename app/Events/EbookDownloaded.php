<?php

namespace App\Events;

use App\Models\DownloadToken;
use App\Models\Ebook;
use App\Models\Lead;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EbookDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly DownloadToken $downloadToken,
        public readonly Ebook $ebook,
        public readonly ?Lead $lead = null,
    ) {}
}
