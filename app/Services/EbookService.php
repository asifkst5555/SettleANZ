<?php

namespace App\Services;

use App\DTOs\EbookDTO;
use App\Enums\EbookStatus;
use App\Models\Ebook;
use App\Models\EbookVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EbookService
{
    public function __construct(
        private string $disk = 'local',
        private string $prefix = 'ebooks',
    ) {
        $this->disk = config('ebook.storage.disk', 'local');
        $this->prefix = config('ebook.storage.prefix', 'ebooks');
    }

    public function create(EbookDTO $dto): Ebook
    {
        return DB::transaction(function () use ($dto) {
            $fileData = $dto->file ? $this->storeFile($dto->file) : null;
            $thumbnailData = $dto->thumbnail ? $this->storeThumbnail($dto->thumbnail) : null;

            $ebook = Ebook::create([
                ...$dto->toArray(),
                'file_path' => $fileData['path'] ?? null,
                'file_name' => $fileData['name'] ?? null,
                'file_type' => $fileData['type'] ?? null,
                'file_size' => $fileData['size'] ?? 0,
                'thumbnail_path' => $thumbnailData['path'] ?? null,
            ]);

            if (!empty($dto->categoryIds)) {
                $ebook->category_id = $dto->categoryIds[0] ?? null;
                $ebook->save();
            }

            if (!empty($dto->tagIds)) {
                $ebook->tags()->sync($dto->tagIds);
            }

            if ($fileData) {
                $this->createVersion($ebook, 1, $fileData, $dto->changeLog);
            }

            return $ebook->fresh(['category', 'tags']);
        });
    }

    public function update(Ebook $ebook, EbookDTO $dto): Ebook
    {
        return DB::transaction(function () use ($ebook, $dto) {
            $updateData = $dto->toArray();

            if ($dto->file) {
                $this->deleteFile($ebook->file_path);
                $fileData = $this->storeFile($dto->file);
                $updateData['file_path'] = $fileData['path'];
                $updateData['file_name'] = $fileData['name'];
                $updateData['file_type'] = $fileData['type'];
                $updateData['file_size'] = $fileData['size'];

                $newVersion = $ebook->current_version + 1;
                $updateData['current_version'] = $newVersion;
                $this->createVersion($ebook, $newVersion, $fileData, $dto->changeLog);
            }

            if ($dto->thumbnail) {
                $this->deleteFile($ebook->thumbnail_path);
                $thumbData = $this->storeThumbnail($dto->thumbnail);
                $updateData['thumbnail_path'] = $thumbData['path'];
            }

            $ebook->update($updateData);

            if (!empty($dto->categoryIds)) {
                $ebook->category_id = $dto->categoryIds[0] ?? null;
                $ebook->save();
            }

            if (!empty($dto->tagIds)) {
                $ebook->tags()->sync($dto->tagIds);
            }

            return $ebook->fresh(['category', 'tags']);
        });
    }

    public function delete(Ebook $ebook): void
    {
        DB::transaction(function () use ($ebook) {
            $this->deleteFile($ebook->file_path);
            $this->deleteFile($ebook->thumbnail_path);

            foreach ($ebook->versions as $version) {
                $this->deleteFile($version->file_path);
                $version->delete();
            }

            $ebook->delete();
        });
    }

    public function publish(Ebook $ebook): void
    {
        $ebook->publish();
    }

    public function archive(Ebook $ebook): void
    {
        $ebook->archive();
    }

    public function storeFile(UploadedFile $file): array
    {
        $path = $file->store($this->prefix, $this->disk);

        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ];
    }

    public function storeThumbnail(UploadedFile $file): array
    {
        $thumbPrefix = config('ebook.storage.thumbnail_prefix', 'thumbnails');
        $path = $file->store("{$this->prefix}/{$thumbPrefix}", $this->disk);

        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ];
    }

    public function deleteFile(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    private function createVersion(Ebook $ebook, int $versionNumber, array $fileData, ?string $changeLog): EbookVersion
    {
        return EbookVersion::create([
            'ebook_id' => $ebook->id,
            'version_number' => $versionNumber,
            'file_path' => $fileData['path'],
            'file_name' => $fileData['name'],
            'file_type' => $fileData['type'],
            'file_size' => $fileData['size'],
            'change_log' => $changeLog,
            'created_by' => auth()->id(),
        ]);
    }

    public function getFileContents(Ebook $ebook): ?string
    {
        $path = $ebook->file_path;
        if (!$path || !Storage::disk($this->disk)->exists($path)) {
            return null;
        }

        return Storage::disk($this->disk)->get($path);
    }

    public function getFilePath(Ebook $ebook): ?string
    {
        $path = $ebook->file_path;
        if (!$path || !Storage::disk($this->disk)->exists($path)) {
            return null;
        }

        return Storage::disk($this->disk)->path($path);
    }

    public function fileExists(Ebook $ebook): bool
    {
        return $ebook->file_path && Storage::disk($this->disk)->exists($ebook->file_path);
    }

    public function getStats(): array
    {
        return [
            'total' => Ebook::count(),
            'published' => Ebook::published()->count(),
            'draft' => Ebook::draft()->count(),
            'total_downloads' => Ebook::sum('download_count'),
            'total_leads' => Ebook::sum('lead_count'),
            'total_size' => Ebook::sum('file_size'),
        ];
    }

    public function getTopEbooks(int $limit = 5): array
    {
        return Ebook::published()
            ->orderByDesc('download_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
