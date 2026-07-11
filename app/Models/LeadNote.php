<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'content',
        'is_private',
        'is_pinned',
        'mentions',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_pinned' => 'boolean',
        'mentions' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
