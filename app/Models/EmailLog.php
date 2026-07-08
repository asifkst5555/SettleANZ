<?php

namespace App\Models;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_template_id',
        'lead_id',
        'campaign_id',
        'to_email',
        'to_name',
        'subject',
        'status',
        'provider',
        'provider_message_id',
        'error_message',
        'retry_count',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'complained_at',
    ];

    protected $casts = [
        'retry_count' => 'integer',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'complained_at' => 'datetime',
    ];

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeSent($query)
    {
        return $query->whereIn('status', [
            EmailStatus::Sent->value,
            EmailStatus::Delivered->value,
        ]);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', EmailStatus::Failed->value);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function isDelivered(): bool
    {
        return in_array($this->status, [
            EmailStatus::Delivered->value,
            EmailStatus::Opened->value,
            EmailStatus::Clicked->value,
        ]);
    }

    public function markSent(?string $providerMessageId = null): void
    {
        $this->update([
            'status' => EmailStatus::Sent->value,
            'provider_message_id' => $providerMessageId,
            'sent_at' => now(),
        ]);
    }

    public function markDelivered(): void
    {
        $this->update([
            'status' => EmailStatus::Delivered->value,
            'delivered_at' => now(),
        ]);
    }

    public function markOpened(): void
    {
        if (!$this->opened_at) {
            $this->update([
                'status' => EmailStatus::Opened->value,
                'opened_at' => now(),
            ]);
        }
    }

    public function markClicked(): void
    {
        if (!$this->clicked_at) {
            $this->update([
                'status' => EmailStatus::Clicked->value,
                'clicked_at' => now(),
            ]);
        }
    }

    public function markBounced(?string $error = null): void
    {
        $this->update([
            'status' => EmailStatus::Bounced->value,
            'error_message' => $error,
            'bounced_at' => now(),
        ]);
    }

    public function markFailed(?string $error = null): void
    {
        $this->update([
            'status' => EmailStatus::Failed->value,
            'error_message' => $error,
        ]);
    }
}
