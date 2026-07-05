<?php

namespace App\Support;

class BlogMedia
{
    public static function normalizeFilename(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $filename = ltrim((string) $filename, '/');
        $filename = preg_replace('#^(storage/blog/|media/blog/|public/media/blog/)#', '', $filename) ?? $filename;

        return $filename !== '' ? $filename : null;
    }

    public static function url(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        return asset('media/blog/' . $filename);
    }

    public static function exists(?string $filename): bool
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return false;
        }

        $path = base_path('media/blog/' . $filename);
        return file_exists($path);
    }
}
