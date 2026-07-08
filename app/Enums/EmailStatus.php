<?php

namespace App\Enums;

enum EmailStatus: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Opened = 'opened';
    case Clicked = 'clicked';
    case Bounced = 'bounced';
    case Failed = 'failed';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Sent => 'Sent',
            self::Delivered => 'Delivered',
            self::Opened => 'Opened',
            self::Clicked => 'Clicked',
            self::Bounced => 'Bounced',
            self::Failed => 'Failed',
            self::Spam => 'Spam',
        };
    }
}
