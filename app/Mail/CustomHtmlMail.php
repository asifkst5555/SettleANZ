<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class CustomHtmlMail
 * 
 * Reusable dynamic HTML mailable for visual builder drafts,
 * test sends, and custom formatted notifications.
 */
class CustomHtmlMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $bodyHtml;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $bodyHtml)
    {
        $this->subject = $subject;
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->html($this->bodyHtml);
    }
}
