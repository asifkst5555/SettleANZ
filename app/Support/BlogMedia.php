<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class BlogMedia
{
    public static function normalizeFilename(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $filename = ltrim((string) $filename, '/');
        $filename = preg_replace('#^storage/blog/#', '', $filename) ?? $filename;

        return $filename !== '' ? $filename : null;
    }

    public static function url(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        return Storage::disk('public')->url('blog/' . $filename);
    }

    public static function exists(?string $filename): bool
    {
        $filename = self::normalizeFilename($filename);

        return $filename !== null && Storage::disk('public')->exists('blog/' . $filename);
    }
}
