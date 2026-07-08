<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'email_template_id',
        'ebook_id',
        'filters',
        'total_recipients',
        'sent_count',
        'open_count',
        'click_count',
        'bounce_count',
        'scheduled_at',
        'sent_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'filters' => 'array',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'bounce_count' => 'integer',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'campaign_lead')
            ->withPivot(['status', 'sent_at', 'opened_at', 'clicked_at'])
            ->withTimestamps();
    }

    public function emailLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', CampaignStatus::Draft->value);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', CampaignStatus::Scheduled->value);
    }

    public function scopeSending($query)
    {
        return $query->where('status', CampaignStatus::Sending->value);
    }

    public function scopeSent($query)
    {
        return $query->where('status', CampaignStatus::Sent->value);
    }

    public function isDraft(): bool
    {
        return $this->status === CampaignStatus::Draft->value;
    }

    public function isScheduled(): bool
    {
        return $this->status === CampaignStatus::Scheduled->value;
    }

    public function isSending(): bool
    {
        return $this->status === CampaignStatus::Sending->value;
    }

    public function isSent(): bool
    {
        return $this->status === CampaignStatus::Sent->value;
    }

    public function markSending(): void
    {
        $this->update(['status' => CampaignStatus::Sending->value]);
    }

    public function markSent(): void
    {
        $this->update([
            'status' => CampaignStatus::Sent->value,
            'sent_at' => now(),
            'completed_at' => now(),
        ]);
    }
}
