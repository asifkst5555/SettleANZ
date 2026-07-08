<?php

namespace App\Models;

use App\Enums\EbookStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ebook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'thumbnail_path',
        'status',
        'author',
        'current_version',
        'isbn',
        'page_count',
        'language',
        'download_count',
        'lead_count',
        'metadata',
        'published_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'current_version' => 'integer',
        'page_count' => 'integer',
        'download_count' => 'integer',
        'lead_count' => 'integer',
        'metadata' => 'array',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EbookCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(EbookTag::class, 'ebook_ebook_tag');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(EbookVersion::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function downloadTokens(): HasMany
    {
        return $this->hasMany(DownloadToken::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', EbookStatus::Published->value);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', EbookStatus::Draft->value);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%");
        });
    }

    public function isPublished(): bool
    {
        return $this->status === EbookStatus::Published->value;
    }

    public function isDraft(): bool
    {
        return $this->status === EbookStatus::Draft->value;
    }

    public function publish(): void
    {
        $this->update([
            'status' => EbookStatus::Published->value,
            'published_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => EbookStatus::Archived->value]);
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function incrementLeadCount(): void
    {
        $this->increment('lead_count');
    }

    public function getFileSizeForHumansAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    protected static function booted(): void
    {
        static::creating(function (Ebook $ebook) {
            if (empty($ebook->slug)) {
                $ebook->slug = Str::slug($ebook->title);
            }
        });
    }
}
