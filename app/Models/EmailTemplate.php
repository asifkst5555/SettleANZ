<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class, 'email_template_id');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function parseVariables(array $data): array
    {
        $subject = $this->subject;
        $body = $this->body_html;

        $search = [];
        $replace = [];
        foreach ($data as $key => $value) {
            $search[] = "{{{$key}}}";
            $replace[] = $value;
            $search[] = "{{ {$key} }}";
            $replace[] = $value;
        }

        $subject = str_replace($search, $replace, $subject);
        $body = str_replace($search, $replace, $body);

        return [
            'subject' => $subject,
            'body_html' => $body,
            'body_text' => $this->body_text
                ? str_replace($search, $replace, $this->body_text)
                : strip_tags($body),
        ];
    }
}
