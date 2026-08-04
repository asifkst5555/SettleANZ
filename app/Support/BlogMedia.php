<?php

namespace App\Support;

use App\Models\BlogPost;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BlogMedia
{
    /**
     * Get the configured filesystem disk for blog media.
     */
    public static function disk(): string
    {
        return config('filesystems.default_blog_disk', 'public');
    }

    /**
     * Normalize filename by removing leading storage/media paths.
     */
    public static function normalizeFilename(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $filename = ltrim((string) $filename, '/');
        $filename = preg_replace('#^(storage/blog/|storage/|media/blog/|public/media/blog/)#', '', $filename) ?? $filename;

        return $filename !== '' ? $filename : null;
    }

    /**
     * Generate the public URL for a given blog media filename.
     */
    public static function url(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        $disk = self::disk();

        if (Storage::disk($disk)->exists('blog/' . $filename)) {
            return Storage::disk($disk)->url('blog/' . $filename);
        }

        if (file_exists(public_path('media/blog/' . $filename))) {
            return asset('media/blog/' . $filename);
        }

        return Storage::disk($disk)->url('blog/' . $filename);
    }

    /**
     * Check if a blog media file exists on disk or public fallback.
     */
    public static function exists(?string $filename): bool
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return false;
        }

        $disk = self::disk();

        if (Storage::disk($disk)->exists('blog/' . $filename)) {
            return true;
        }

        return file_exists(public_path('media/blog/' . $filename));
    }

    /**
     * Get absolute local filesystem path to the file, if available.
     */
    public static function path(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return null;
        }

        $disk = self::disk();

        if (Storage::disk($disk)->exists('blog/' . $filename)) {
            return Storage::disk($disk)->path('blog/' . $filename);
        }

        $legacyPath = public_path('media/blog/' . $filename);
        if (file_exists($legacyPath)) {
            return $legacyPath;
        }

        return Storage::disk($disk)->path('blog/' . $filename);
    }

    /**
     * Delete a blog media file safely, preventing orphan files while preserving shared assets.
     */
    public static function delete(?string $filename, bool $force = false): bool
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null) {
            return false;
        }

        if (!$force) {
            $inUse = BlogPost::query()
                ->where('image', $filename)
                ->orWhere('image', 'LIKE', '%' . $filename)
                ->exists();

            if ($inUse) {
                return false;
            }
        }

        $disk = self::disk();
        $deleted = false;

        if (Storage::disk($disk)->exists('blog/' . $filename)) {
            $deleted = Storage::disk($disk)->delete('blog/' . $filename);
        }

        $legacyPath = public_path('media/blog/' . $filename);
        if (file_exists($legacyPath)) {
            @unlink($legacyPath);
            $deleted = true;
        }

        return $deleted;
    }

    /**
     * Get file MIME type.
     */
    public static function mimeType(?string $filename): ?string
    {
        $filename = self::normalizeFilename($filename);
        if ($filename === null || !self::exists($filename)) {
            return null;
        }

        $path = self::path($filename);
        if ($path && file_exists($path)) {
            return File::mimeType($path);
        }

        return Storage::disk(self::disk())->mimeType('blog/' . $filename);
    }

    /**
     * Get image dimensions [width, height], if image exists locally.
     */
    public static function dimensions(?string $filename): ?array
    {
        $path = self::path($filename);
        if (!$path || !file_exists($path)) {
            return null;
        }

        $info = @getimagesize($path);
        if (!$info) {
            return null;
        }

        return [
            'width'  => $info[0],
            'height' => $info[1],
        ];
    }
}
