<?php

namespace App\Support;

class BlogMedia
{
    /**
     * Browser-facing public web root (document root).
     * On Hostinger/cPanel fallback deploys this is public_html/, not public_html/public/.
     */
    public static function publicWebRoot(): string
    {
        $publicPath = public_path();
        $pathParts = explode(DIRECTORY_SEPARATOR, $publicPath);
        $publicHtmlIndex = array_search('public_html', $pathParts, true);

        if ($publicHtmlIndex !== false) {
            return implode(DIRECTORY_SEPARATOR, array_slice($pathParts, 0, $publicHtmlIndex + 1));
        }

        return $publicPath;
    }

    public static function normalizeFilename(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $filename = ltrim((string) $filename, '/');
        $filename = preg_replace('#^storage/blog/#', '', $filename) ?? $filename;

        return $filename !== '' ? $filename : null;
    }

    public static function diskPath(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        return self::publicWebRoot()
            . DIRECTORY_SEPARATOR . 'storage'
            . DIRECTORY_SEPARATOR . 'blog'
            . DIRECTORY_SEPARATOR . $filename;
    }

    public static function url(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        return asset('storage/blog/' . $filename);
    }

    public static function exists(?string $filename): bool
    {
        $path = self::diskPath($filename);

        return $path !== null && is_file($path);
    }
}
