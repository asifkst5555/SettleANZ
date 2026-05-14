@extends('admin.layouts.app')

@section('content')
    <style>
        .reviews-table-wrap {
            border: 1px solid #edf2f7;
            border-radius: 0.75rem;
            background: #fff;
            width: 100%;
            overflow-x: visible;
        }
        .reviews-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .reviews-table thead {
            background: #f3f4f6;
        }
        .reviews-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .reviews-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .reviews-table tbody tr:hover {
            background: #f9fafb;
        }
        .reviews-table tbody tr.row--pending {
            background: rgba(251, 191, 36, 0.05);
        }
        .reviews-table td strong,
        .reviews-table td small {
            display: block;
        }
        .reviews-table td small {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .reviews-listing-link {
            color: #0b7a75;
            font-weight: 500;
            text-decoration: none;
        }
        .reviews-listing-link:hover {
            text-decoration: underline;
        }
        .reviews-rating {
            display: flex;
            gap: 2px;
        }
        .reviews-rating .star {
            color: #d1d5db;
            font-size: 1rem;
        }
        .reviews-rating .star.filled {
            color: #f59e0b;
        }
        .reviews-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 999px;
            background: #e8f5f4;
            color: #0b7a75;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .reviews-status-badge--pending {
            background: #fef3c7;
            color: #92400e;
        }
        .reviews-status-badge--rejected {
            background: #fee2e2;
            color: #7f1d1d;
        }
        .reviews-view-btn {
            background: #dbeafe;
            color: #1e40af;
        }
        .reviews-view-btn:hover {
            background: #bfdbfe;
        }
        
        /* Review Modal Styles */
        .review-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }
        .review-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .review-modal__content {
            background: #fff;
            border-radius: 14px;
            width: 92%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
        }
        .review-modal__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .review-modal__header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }
        .review-modal__close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .review-modal__close:hover {
            background: #f3f4f6;
            color: #374151;
        }
        .review-modal__body {
            padding: 1.25rem;
        }
        .review-modal__comment {
            margin: 0;
            background: #f8fafc;
            padding: 1.1rem 1.15rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            white-space: pre-wrap;
            word-break: break-word;
            color: #1e293b;
            font-size: 0.98rem;
            line-height: 1.55;
        }
        .review-modal__footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0 0 12px 12px;
        }
        .review-modal__footer-form {
            margin: 0;
            display: inline-flex;
        }
        .reviews-modal-close-btn {
            background: #f3f4f6;
            color: #374151;
            margin-left: auto;
        }
        .reviews-modal-close-btn:hover {
            background: #e5e7eb;
        }
        @media (max-width: 480px) {
            .review-modal__footer {
                flex-direction: column;
                align-items: stretch;
            }
            .reviews-modal-close-btn {
                margin-left: 0;
            }
        }
        @media (max-width: 1100px) {
            .reviews-table-wrap {
                overflow-x: auto;
            }
            .reviews-table {
                min-width: 760px;
            }
        }
    </style>

    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Reviews</p>
                <h2>Review Management</h2>
                <p>Review and moderate user-submitted reviews for directory listings.</p>
            </div>
        </section>

        <section class="admin-panel-card admin-panel-card--filters">
            <div>
                <h3>Filter Reviews</h3>
                <p>Narrow reviews by status or listing.</p>
            </div>
            <form class="admin-filter-form" method="GET" action="{{ route('admin.reviews.index') }}">
                <label>
                    <span>Status</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </label>
                <label>
                    <span>Listing</span>
                    <select name="listing" onchange="this.form.submit()">
                        <option value="">All Listings</option>
                        @foreach($listings as $listing)
                            <option value="{{ $listing->id }}" {{ ($filters['listing'] ?? '') == $listing->id ? 'selected' : '' }}>{{ $listing->name }}</option>
                        @endforeach
                    </select>
                </label>
                @if(request()->has('status') || request()->has('listing'))
                    <a class="button button--ghost" href="{{ route('admin.reviews.index') }}">Clear</a>
                @endif
            </form>
        </section>

        <section class="admin-panel-card" style="padding: 0;">
            <div class="reviews-table-wrap">
                <table class="reviews-table admin-table-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Reviewer</th>
                            <th style="width: 20%;">Listing</th>
                            <th style="width: 10%;">Rating</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 12%;">Date</th>
                            <th style="width: 26%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr class="{{ $review->status === 'pending' ? 'row--pending' : '' }}">
                            <td data-label="Reviewer">
                                <strong>{{ $review->reviewer_name }}</strong>
                                <small><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>{{ $review->reviewer_email }}</small>
                            </td>
                            <td data-label="Listing">
                                <a href="{{ route('directory.show', $review->directoryListing->slug) }}" target="_blank" class="reviews-listing-link">
                                    {{ $review->directoryListing->name }}
                                </a>
                            </td>
                            <td data-label="Rating">
                                <div class="reviews-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td data-label="Status">
                                <span class="reviews-status-badge reviews-status-badge--{{ $review->status }}">{{ ucfirst($review->status) }}</span>
                            </td>
                            <td data-label="Date">
                                <strong>{{ $review->created_at->format('d M Y') }}</strong>
                                <small>{{ $review->created_at->format('h:i A') }}</small>
                            </td>
                            <td data-label="Actions">
                                <div class="reviews-actions-cell">
                                    <button type="button" class="reviews-action-btn reviews-view-btn" onclick="openReviewModal({{ $review->id }})">
                                        <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>View</span>
                                    </button>
                                    @if($review->status === 'pending')
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            <button type="submit" class="reviews-action-btn reviews-approve-btn">
                                                <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                            @csrf
                                            <button type="submit" class="reviews-action-btn reviews-reject-btn">
                                                <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirmDelete(this, 'review');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="reviews-action-btn reviews-delete-btn">
                                            <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Review Modal -->
                        <div id="reviewModal-{{ $review->id }}" class="review-modal" role="dialog" aria-modal="true" aria-labelledby="review-modal-title-{{ $review->id }}">
                            <div class="review-modal__content">
                                <div class="review-modal__header">
                                    <h3 id="review-modal-title-{{ $review->id }}">Review comment</h3>
                                    <button type="button" class="review-modal__close" onclick="closeReviewModal({{ $review->id }})" aria-label="Close">&times;</button>
                                </div>
                                <div class="review-modal__body">
                                    <p class="review-modal__comment">{{ $review->comment }}</p>
                                </div>
                                <div class="review-modal__footer">
                                    @if($review->status === 'pending')
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="review-modal__footer-form">
                                            @csrf
                                            <button type="submit" class="reviews-action-btn reviews-approve-btn">
                                                <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="review-modal__footer-form">
                                            @csrf
                                            <button type="submit" class="reviews-action-btn reviews-reject-btn">
                                                <svg class="reviews-action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="reviews-action-btn reviews-modal-close-btn" onclick="closeReviewModal({{ $review->id }})">Close</button>
                                </div>
                            </div>
                        </div>
                        
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">⭐</div>
                                <h3>No reviews found.</h3>
                                <p style="color: #6b7280;">Reviews will appear here when users submit them.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.25rem; border-top: 1px solid #e5e7eb;">{{ $reviews->links() }}</div>
        </section>
    </div>
    
    <script>
        function openReviewModal(reviewId) {
            const modal = document.getElementById('reviewModal-' + reviewId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeReviewModal(reviewId) {
            const modal = document.getElementById('reviewModal-' + reviewId);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('review-modal')) {
                e.target.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.review-modal.active');
                if (activeModal) {
                    activeModal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
@endsection