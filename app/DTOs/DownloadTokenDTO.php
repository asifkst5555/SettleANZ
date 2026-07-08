<?php

namespace App\DTOs;

class DownloadTokenDTO
{
    public function __construct(
        public readonly int $ebookId,
        public readonly int $leadId,
        public readonly int $maxDownloads = 5,
        public readonly int $expiryHours = 72,
    ) {}

    public function toArray(): array
    {
        return [
            'ebook_id' => $this->ebookId,
            'lead_id' => $this->leadId,
            'max_downloads' => $this->maxDownloads,
            'expires_at' => now()->addHours($this->expiryHours),
        ];
    }
}
