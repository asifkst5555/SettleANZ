<?php

namespace App\Enums;

enum DownloadTokenStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Revoked = 'revoked';
    case Exhausted = 'exhausted';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Revoked => 'Revoked',
            self::Exhausted => 'Exhausted',
        };
    }
}
