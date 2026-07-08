<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DownloadToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'ebook_id',
        'lead_id',
        'status',
        'max_downloads',
        'download_count',
        'expires_at',
        'last_downloaded_at',
    ];

    protected $casts = [
        'max_downloads' => 'integer',
        'download_count' => 'integer',
        'expires_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
    ];

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now())
            ->whereColumn('download_count', '<', 'max_downloads');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere('expires_at', '<=', now());
    }

    public function isValid(): bool
    {
        return $this->status === 'active'
            && $this->expires_at->isFuture()
            && $this->download_count < $this->max_downloads;
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->download_count >= $this->max_downloads;
    }

    public function markDownloaded(): void
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);

        if ($this->isExhausted()) {
            $this->update(['status' => 'exhausted']);
        }
    }

    public function expire(): void
    {
        $this->update(['status' => 'expired']);
    }

    public function revoke(): void
    {
        $this->update(['status' => 'revoked']);
    }

    public function getRemainingDownloadsAttribute(): int
    {
        return max(0, $this->max_downloads - $this->download_count);
    }

    public function getExpiresInHoursAttribute(): float
    {
        return max(0, now()->diffInHours($this->expires_at, false));
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('ebook.download', ['token' => $this->token]);
    }

    protected static function booted(): void
    {
        static::creating(function (DownloadToken $token) {
            $token->token = (string) Str::uuid();
        });
    }
}
