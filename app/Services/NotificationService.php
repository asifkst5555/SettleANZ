<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\Lead;
use App\Models\Review;

class NotificationService
{
    /**
     * Create a notification for a new lead
     */
    public static function createLeadNotification(Lead $lead): AdminNotification
    {
        $title = 'New Lead: ' . ($lead->full_name ?: $lead->first_name ?: 'Unknown');
        $message = match ($lead->form_type) {
            'contact-page' => 'New contact form submission from ' . ($lead->city ?? 'website'),
            'consultation-booking' => 'New consultation booking request',
            'directory-lead' => 'New lead from directory listing',
            'homepage_roadmap' => 'New arrival checklist roadmap claimed',
            default => 'New lead received',
        };

        return AdminNotification::create([
            'type' => 'lead',
            'title' => $title,
            'message' => $message,
            'link' => route('admin.leads.edit', $lead),
            'lead_id' => $lead->id,
            'is_read' => false,
        ]);
    }

    /**
     * Create a notification for a new review
     */
    public static function createReviewNotification(Review $review): AdminNotification
    {
        $title = 'New Review Pending';
        $message = $review->reviewer_name . ' left a ' . $review->rating . '-star review for ' . ($review->directoryListing?->name ?? 'a listing');

        return AdminNotification::create([
            'type' => 'review',
            'title' => $title,
            'message' => $message,
            'link' => route('admin.reviews.index'),
            'review_id' => $review->id,
            'is_read' => false,
        ]);
    }

    /**
     * Create a system notification
     */
    public static function createSystemNotification(string $title, string $message, ?string $link = null): AdminNotification
    {
        return AdminNotification::create([
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount(): int
    {
        return AdminNotification::unread()->count();
    }

    /**
     * Get recent notifications for top bar
     */
    public static function getRecentNotifications(int $limit = 10): array
    {
        $notifications = AdminNotification::with(['lead', 'review'])
            ->latest()
            ->limit($limit)
            ->get();

        return [
            'notifications' => $notifications,
            'unread_count' => AdminNotification::unread()->count(),
        ];
    }
}
