<?php

namespace App\Support;

class AssetVersion
{
    public static function url(string $path): string
    {
        $path = ltrim($path, '/');
        $url = asset($path);
        $filePath = public_path($path);

        if (! is_file($filePath)) {
            return $url;
        }

        $modifiedAt = filemtime($filePath);

        if ($modifiedAt === false) {
            return $url;
        }

        return $url . '?v=' . $modifiedAt;
    }
}