<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'first_name',
        'full_name',
        'email',
        'phone',
        'company',
        'country',
        'consent',
        'ebook_id',
        'goal',
        'form_type',
        'source_page',
        'status',
        'subscribed_at',
        'notes',
        'metadata',
        'ip_address',
        'user_agent',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    protected $casts = [
        'metadata' => 'array',
        'subscribed_at' => 'datetime',
        'consent' => 'boolean',
    ];

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function downloadTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DownloadToken::class);
    }

    public function downloadLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
