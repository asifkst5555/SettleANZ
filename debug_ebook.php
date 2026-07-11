<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ebook = App\Models\Ebook::first();

if (!$ebook) {
    echo "No ebooks found in database\n";
    exit(1);
}

echo "=== EBOOK RECORD ===\n";
echo "ID: {$ebook->id}\n";
echo "Title: {$ebook->title}\n";
echo "file_path: " . var_export($ebook->file_path, true) . "\n";
echo "file_name: " . var_export($ebook->file_name, true) . "\n";
echo "file_size: {$ebook->file_size}\n";
echo "storage_disk: " . var_export($ebook->storage_disk ?? null, true) . "\n";
echo "pdf_path: " . var_export($ebook->pdf_path ?? null, true) . "\n";

$disk = $ebook->storage_disk ?? config('ebook.storage.disk', 'local');
echo "\n=== RESOLVED ===\n";
echo "Disk: {$disk}\n";

$pdfPath = $ebook->pdf_path ?? $ebook->file_path;
echo "PDF Path from DB: " . var_export($pdfPath, true) . "\n";

if (empty($pdfPath)) {
    echo "\n!!! CRITICAL: pdf_path is empty/null. No file is associated with this ebook.\n";
    exit(1);
}

echo "\n=== STORAGE CHECK ===\n";

try {
    $exists = Illuminate\Support\Facades\Storage::disk($disk)->exists($pdfPath);
    echo "File exists on disk: " . ($exists ? 'YES' : 'NO') . "\n";

    if ($exists) {
        $size = Illuminate\Support\Facades\Storage::disk($disk)->size($pdfPath);
        echo "File size: {$size}\n";

        $stream = Illuminate\Support\Facades\Storage::disk($disk)->readStream($pdfPath);
        echo "readStream: " . (is_resource($stream) ? 'OK (resource)' : 'FAILED') . "\n";
        if (is_resource($stream)) {
            $meta = stream_get_meta_data($stream);
            echo "Stream URI: {$meta['uri']}\n";
            echo "Stream seekable: " . ($meta['seekable'] ? 'YES' : 'NO') . "\n";
            fclose($stream);
        }
    } else {
        echo "\n!!! FILE MISSING ON DISK !!!\n";
        echo "The database has a path but the file doesn't exist.\n";
        echo "You may need to re-upload the PDF.\n";
    }
} catch (Exception $e) {
    echo "Storage error: " . $e->getMessage() . "\n";
}

echo "\n=== FILESYSTEM ROOT ===\n";
$localRoot = config('filesystems.disks.local.root');
echo "Local disk root: {$localRoot}\n";

if ($pdfPath) {
    $fullPath = $localRoot . DIRECTORY_SEPARATOR . $pdfPath;
    echo "Full expected path: {$fullPath}\n";
    echo "file_exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
}

echo "\n=== CONFIG ===\n";
echo "FILESYSTEM_DISK: " . env('FILESYSTEM_DISK', 'not set') . "\n";
echo "EBOOK_STORAGE_DISK: " . env('EBOOK_STORAGE_DISK', 'not set') . "\n";
echo "APP_ENV: " . env('APP_ENV', 'not set') . "\n";
