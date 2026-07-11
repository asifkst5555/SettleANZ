<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require __DIR__ . '/../../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ebook = \App\Models\Ebook::first();
if (!$ebook) {
    echo "No ebooks found\n";
    exit(1);
}

echo "Ebook ID: {$ebook->id}\n";
echo "Title: {$ebook->title}\n";
echo "file_path: " . ($ebook->file_path ?? 'NULL') . "\n";
echo "file_name: " . ($ebook->file_name ?? 'NULL') . "\n";
echo "file_size: " . ($ebook->file_size ?? 'NULL') . "\n";
echo "storage_disk: " . ($ebook->storage_disk ?? 'NULL') . "\n";
echo "pdf_path: " . ($ebook->pdf_path ?? 'NULL') . "\n";

$disk = $ebook->storage_disk ?? config('ebook.storage.disk', 'local');
echo "Resolved disk: {$disk}\n";

$pdfPath = $ebook->pdf_path ?? $ebook->file_path;
echo "Resolved pdfPath: " . ($pdfPath ?? 'NULL') . "\n";

if ($pdfPath) {
    $exists = \Illuminate\Support\Facades\Storage::disk($disk)->exists($pdfPath);
    echo "File exists on disk: " . ($exists ? 'YES' : 'NO') . "\n";
    
    if ($exists) {
        $size = \Illuminate\Support\Facades\Storage::disk($disk)->size($pdfPath);
        echo "File size: {$size}\n";
        
        $stream = \Illuminate\Support\Facades\Storage::disk($disk)->readStream($pdfPath);
        echo "readStream result: " . (is_resource($stream) ? 'resource' : var_export($stream, true)) . "\n";
        if (is_resource($stream)) {
            fclose($stream);
        }
        
        $url = \Illuminate\Support\Facades\Storage::disk($disk)->url($pdfPath);
        echo "URL: {$url}\n";
    }
} else {
    echo "ERROR: No file path found in database!\n";
}
