<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SyncBlogMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:sync-blog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync legacy blog images into the public storage disk (storage/app/public/blog)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting blog media synchronization...');

        // Ensure storage target directory exists
        if (!Storage::disk('public')->exists('blog')) {
            Storage::disk('public')->makeDirectory('blog');
        }

        $targetDir = storage_path('app/public/blog');
        if (!is_dir($targetDir)) {
            File::makeDirectory($targetDir, 0775, true, true);
        }

        $syncedCount = 0;
        $sourceDirs = [
            public_path('media/blog'),
            base_path('media/blog'),
        ];

        foreach ($sourceDirs as $sourceDir) {
            if (!is_dir($sourceDir)) {
                continue;
            }

            $files = File::files($sourceDir);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                if ($filename === '.gitkeep') {
                    continue;
                }

                $destination = $targetDir . '/' . $filename;
                if (!file_exists($destination)) {
                    File::copy($file->getPathname(), $destination);
                    chmod($destination, 0664);
                    $this->line("  ✓ Synced: {$filename}");
                    $syncedCount++;
                }
            }
        }

        $this->info("Blog media sync complete! {$syncedCount} new image(s) copied to storage/app/public/blog.");

        return Command::SUCCESS;
    }
}
