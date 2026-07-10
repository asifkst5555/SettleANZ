<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplateRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_template_id',
        'name',
        'subject',
        'body_html',
        'body_text',
        'builder_json',
        'created_by',
    ];

    protected $casts = [
        'builder_json' => 'array',
    ];

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
