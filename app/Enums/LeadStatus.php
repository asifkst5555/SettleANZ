<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Downloaded = 'downloaded';
    case Qualified = 'qualified';
    case Converted = 'converted';
    case Unsubscribed = 'unsubscribed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Contacted => 'Contacted',
            self::Downloaded => 'Downloaded',
            self::Qualified => 'Qualified',
            self::Converted => 'Converted',
            self::Unsubscribed => 'Unsubscribed',
        };
    }
}
