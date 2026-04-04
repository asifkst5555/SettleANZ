<?php

namespace App\Models;

use App\Support\SiteDefaults;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value')
            ?? SiteDefaults::siteSettings()[$key]
            ?? $default;
    }

    public static function keyValueMap(): array
    {
        $stored = static::query()->pluck('value', 'key')->all();

        return array_merge(SiteDefaults::siteSettings(), $stored);
    }
}
