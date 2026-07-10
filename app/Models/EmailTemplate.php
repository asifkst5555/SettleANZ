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
        'builder_json',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'builder_json' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (EmailTemplate $template) {
            $template->createRevision();
        });

        static::updated(function (EmailTemplate $template) {
            if ($template->wasChanged(['name', 'subject', 'body_html', 'body_text', 'builder_json'])) {
                $template->createRevision();
            }
        });
    }

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

    public function revisions(): HasMany
    {
        return $this->hasMany(EmailTemplateRevision::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function createRevision(?int $userId = null): EmailTemplateRevision
    {
        return $this->revisions()->create([
            'name' => $this->name,
            'subject' => $this->subject,
            'body_html' => $this->body_html,
            'body_text' => $this->body_text,
            'builder_json' => $this->builder_json,
            'created_by' => $userId ?? auth()->id(),
        ]);
    }

    public function parseVariables(array $data): array
    {
        // Align variable names and aliases for client compatibility
        if (isset($data['lead_name'])) {
            $data['name'] = $data['lead_name'];
        }
        if (isset($data['lead_email'])) {
            $data['email'] = $data['lead_email'];
        }
        if (isset($data['ebook_title'])) {
            $data['ebook_name'] = $data['ebook_title'];
        }

        // Apply theme/site fallback variables
        $data['company_name'] = $data['company_name'] ?? config('app.name');
        $data['company_logo'] = asset('media/logo/email_logo.png'); // Force PNG logo
        $data['current_year'] = $data['current_year'] ?? date('Y');
        $data['website'] = $data['website'] ?? SiteSetting::getValue('email_theme_website', url('/'));
        $data['support_email'] = $data['support_email'] ?? SiteSetting::getValue('email_theme_support_email', 'hello@settleanz.com');
        $data['unsubscribe'] = $data['unsubscribe'] ?? '#';

        // Check if any variable contains webp and replace with png
        foreach ($data as $key => $value) {
            if (is_string($value) && str_contains($value, 'logo.webp')) {
                $data[$key] = str_replace('logo.webp', 'email_logo.png', $value);
            }
        }

        $subject = $this->subject;
        $body = $this->body_html;

        // Force convert any raw HTML references of logo.webp to email_logo.png inside the template
        $body = str_replace('logo.webp', 'email_logo.png', $body);

        $search = [];
        $replace = [];
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $search[] = "{{{$key}}}";
                $replace[] = $value;
                $search[] = "{{ {$key} }}";
                $replace[] = $value;
            }
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
