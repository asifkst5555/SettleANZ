<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'full_name', 'email', 'phone', 'company', 'country',
        'form_type', 'form_name', 'source_page', 'landing_page_name',
        'package_name', 'ebook_title', 'visa_type',
        'preferred_date', 'preferred_time', 'preferred_contact_method',
        'conversation_summary', 'referral_url',
        'goal', 'interested_service', 'budget', 'website',
        'priority', 'lead_score', 'assigned_to',
        'status', 'notes', 'metadata',
        'ip_address', 'user_agent',
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'ebook_id', 'conversation_id', 'consent', 'subscribed_at',
        'is_archived', 'last_activity_at', 'converted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'subscribed_at' => 'datetime',
        'consent' => 'boolean',
        'is_archived' => 'boolean',
        'budget' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($lead) {
            $lead->recordActivity('created', 'Lead created via ' . ($lead->form_name ?: $lead->form_type));
        });

        static::updated(function ($lead) {
            if ($lead->isDirty('status')) {
                $lead->recordActivity('status_changed', "Status changed from {$lead->getOriginal('status')} to {$lead->status}");
            }
            if ($lead->isDirty('assigned_to') && $lead->assigned_to) {
                $assignee = User::find($lead->assigned_to);
                $name = $assignee?->name ?? 'Unknown';
                $lead->recordActivity('assigned', "Assigned to {$name}");
            }
            if ($lead->isDirty('priority')) {
                $lead->recordActivity('priority_changed', "Priority set to {$lead->priority}");
            }
        });
    }

    public function recordActivity(string $type, string $description, ?array $metadata = null): void
    {
        $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'label' => ucwords(str_replace('_', ' ', $type)),
            'description' => $description,
            'metadata' => $metadata,
        ]);
        $this->updateQuietly(['last_activity_at' => now()]);
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(LeadTask::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'lead_tag');
    }

    public function files(): HasMany
    {
        return $this->hasMany(LeadFile::class);
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->full_name ?? $this->first_name ?? $this->email ?? '?';
        $parts = explode(' ', trim($name));
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) $initials .= strtoupper($part[0]);
        }
        return substr($initials, 0, 2) ?: '?';
    }

    public function getAvatarColorAttribute(): string
    {
        $colors = ['#6366f1', '#14a394', '#e8773a', '#7c3aed', '#dc3545', '#0ea5e9', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
        return $colors[abs(crc32($this->email ?? $this->id)) % count($colors)];
    }

    public function getLeadSourceLabelAttribute(): string
    {
        return self::sourceLabels()[$this->form_type] ?? ucfirst(str_replace('_', ' ', $this->form_type));
    }

    public function getWebsitePageLabelAttribute(): string
    {
        return self::pageLabels()[$this->source_page] ?? ($this->source_page ?: 'Direct');
    }

    public function getFormNameLabelAttribute(): string
    {
        return $this->form_name ?: $this->lead_source_label;
    }

    public static function sourceLabels(): array
    {
        return [
            'contact-page' => 'Contact Form',
            'package_booking' => 'Package Booking',
            'homepage_roadmap' => 'Homepage Roadmap',
            'ebook_download' => 'Ebook Download',
            'ai_chat' => 'AI Chat',
            'newsletter_signup' => 'Newsletter Signup',
            'general' => 'General Enquiry',
        ];
    }

    public static function pageLabels(): array
    {
        return [
            '/' => 'Homepage',
            'homepage' => 'Homepage',
            'homepage_roadmap' => 'Homepage - Roadmap',
            'contact-page' => 'Contact Page',
            'settlement-services' => 'Settlement Services',
            'settlement-services/arrive' => 'Arrive Services',
            'settlement-services/settle' => 'Settle Services',
            'settlement-services/work-invest' => 'Work & Invest',
            'settlement-services/enjoy' => 'Enjoy Services',
            'website-chat' => 'AI Chat Widget',
            'ebook' => 'Ebook Landing Page',
        ];
    }

    public static function activeFormTypes(): array
    {
        return ['contact-page', 'package_booking', 'homepage_roadmap', 'ebook_download', 'ai_chat'];
    }

    public static function activeSourcePages(): array
    {
        return ['/', 'homepage', 'contact-page', 'settlement-services',
            'settlement-services/arrive', 'settlement-services/settle',
            'settlement-services/work-invest', 'settlement-services/enjoy',
            'website-chat', 'homepage_roadmap',
        ];
    }

    public static function statusColors(): array
    {
        return [
            'new' => '#6366f1',
            'contacted' => '#0ea5e9',
            'qualified' => '#10b981',
            'follow_up' => '#f59e0b',
            'consultation_booked' => '#8b5cf6',
            'proposal_sent' => '#14a394',
            'negotiating' => '#e8773a',
            'won' => '#10b981',
            'lost' => '#dc3545',
        ];
    }

    public static function priorityColors(): array
    {
        return [
            'low' => '#94a3b8',
            'medium' => '#0ea5e9',
            'high' => '#f59e0b',
            'urgent' => '#dc3545',
        ];
    }

    public static function visaTypes(): array
    {
        return [
            'skilled_migration' => 'Skilled Migration',
            'partner_visa' => 'Partner Visa',
            'student_visa' => 'Student Visa',
            'work_visa' => 'Work Visa',
            'visitor_visa' => 'Visitor Visa',
            'parent_visa' => 'Parent Visa',
            'business_visa' => 'Business Visa',
            'refugee_humanitarian' => 'Refugee / Humanitarian',
            'other' => 'Other',
        ];
    }

    public static function packages(): array
    {
        return [
            'Arrival Package', 'Settlement Package', 'Work & Invest Package',
            'EnjoY NSW Package', 'Migration Skills Assessment',
            'Visa Application Assistance', 'Document Preparation',
        ];
    }

    public function scopeFromForm($query, string $formType)
    {
        return $query->where('form_type', $formType);
    }

    public function scopeFromPage($query, ?string $page)
    {
        return $page ? $query->where('source_page', $page) : $query;
    }

    public function scopeVisaType($query, ?string $visaType)
    {
        return $visaType ? $query->where('visa_type', $visaType) : $query;
    }

    public function scopePackage($query, ?string $package)
    {
        return $package ? $query->where('package_name', $package) : $query;
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('goal', 'like', "%{$search}%");
        });
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['form_type'])) $query->where('form_type', $filters['form_type']);
        if (!empty($filters['source_page'])) $query->where('source_page', $filters['source_page']);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['visa_type'])) $query->where('visa_type', $filters['visa_type']);
        if (!empty($filters['package_name'])) $query->where('package_name', $filters['package_name']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['country'])) $query->where('country', $filters['country']);
        if (!empty($filters['assigned_to'])) $query->where('assigned_to', $filters['assigned_to']);
        if (!empty($filters['search'])) $query->search($filters['search']);
        if (!empty($filters['from_date'])) $query->whereDate('created_at', '>=', $filters['from_date']);
        if (!empty($filters['to_date'])) $query->whereDate('created_at', '<=', $filters['to_date']);
        return $query;
    }
}
