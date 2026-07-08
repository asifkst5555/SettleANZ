<?php

namespace App\DTOs;

use App\Enums\EbookStatus;
use Illuminate\Http\UploadedFile;

class EbookDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly ?UploadedFile $file = null,
        public readonly ?UploadedFile $thumbnail = null,
        public readonly EbookStatus $status = EbookStatus::Draft,
        public readonly ?array $metadata = [],
        public readonly ?array $categoryIds = [],
        public readonly ?array $tagIds = [],
        public readonly ?string $author = null,
        public readonly ?int $versionNumber = 1,
        public readonly ?string $changeLog = null,
        public readonly ?string $isbn = null,
        public readonly ?int $pageCount = null,
        public readonly ?string $language = 'en',
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'] ?? str($data['title'])->slug(),
            description: $data['description'] ?? '',
            file: $data['file'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            status: isset($data['status']) ? EbookStatus::from($data['status']) : EbookStatus::Draft,
            metadata: $data['metadata'] ?? [],
            categoryIds: $data['category_ids'] ?? [],
            tagIds: $data['tag_ids'] ?? [],
            author: $data['author'] ?? null,
            versionNumber: $data['version_number'] ?? 1,
            changeLog: $data['change_log'] ?? null,
            isbn: $data['isbn'] ?? null,
            pageCount: $data['page_count'] ?? null,
            language: $data['language'] ?? 'en',
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status->value,
            'metadata' => $this->metadata,
            'author' => $this->author,
            'version_number' => $this->versionNumber,
            'change_log' => $this->changeLog,
            'isbn' => $this->isbn,
            'page_count' => $this->pageCount,
            'language' => $this->language,
        ];
    }
}
