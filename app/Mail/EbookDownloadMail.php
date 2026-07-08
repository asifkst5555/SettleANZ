<?php

namespace App\Mail;

use App\Models\DownloadToken;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EbookDownloadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Lead $lead,
        public readonly DownloadToken $token,
        public readonly ?string $customSubject = null,
    ) {}

    public function envelope(): Envelope
    {
        $ebookTitle = $this->token->ebook?->title ?? 'Ebook';

        return new Envelope(
            subject: $this->customSubject ?? "Your Download: {$ebookTitle}",
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.ebook-download',
            with: [
                'leadName' => $this->lead->full_name ?? 'Valued Reader',
                'ebookTitle' => $this->token->ebook?->title ?? 'Ebook',
                'ebookDescription' => $this->token->ebook?->description ?? '',
                'downloadUrl' => route('ebook.download', ['token' => $this->token->token]),
                'expiresAt' => $this->token->expires_at->format('F j, Y \a\t g:i A'),
                'expiresInHours' => config('ebook.download.token_expiry_hours', 72),
                'companyName' => config('app.name'),
            ],
        );
    }
}
