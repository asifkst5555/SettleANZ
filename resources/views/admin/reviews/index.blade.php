@extends('admin.layouts.app')

@section('content')

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
            {{-- Bulk Action Bar --}}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.reviews.bulk') }}" style="display:none;">
                @csrf
                <input type="hidden" name="action" id="bulkActionValue">
                <div id="bulkActionBar" class="admin-bulk-bar" style="display:none;">
                    <span class="admin-bulk-bar__count" id="bulkSelectedCount">0 selected</span>
                    <div class="admin-bulk-bar__actions">
                        <button type="button" class="button button--small" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;" onclick="submitBulk('approve')">Approve Selected</button>
                        <button type="button" class="button button--small" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;" onclick="submitBulk('reject')">Reject Selected</button>
                        <button type="button" class="button button--small" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;" onclick="submitBulk('delete')">Delete Selected</button>
                        <button type="button" class="button button--small button--ghost" onclick="clearSelection()">Cancel</button>
                    </div>
                </div>
            </form>

            <div class="reviews-table-wrap">
                <table class="reviews-table admin-table-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 3%;"><input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"></th>
                            <th style="width: 17%;">Reviewer</th>
                            <th style="width: 19%;">Listing</th>
                            <th style="width: 9%;">Rating</th>
                            <th style="width: 11%;">Status</th>
                            <th style="width: 11%;">Date</th>
                            <th style="width: 26%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr class="{{ $review->status === 'pending' ? 'row--pending' : '' }}">
                            <td data-label="Select" style="text-align:center;"><input type="checkbox" class="review-checkbox" value="{{ $review->id }}" onchange="updateBulkBar()"></td>
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
                            <td colspan="7" style="text-align: center; padding: 3rem;">
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

        // Bulk actions
        function toggleSelectAll(source) {
            document.querySelectorAll('.review-checkbox').forEach(function(cb) {
                cb.checked = source.checked;
            });
            updateBulkBar();
        }

        function updateBulkBar() {
            const checkboxes = document.querySelectorAll('.review-checkbox:checked');
            const count = checkboxes.length;
            const bar = document.getElementById('bulkActionBar');
            const countDisplay = document.getElementById('bulkSelectedCount');
            if (count > 0) {
                bar.style.display = 'flex';
                countDisplay.textContent = count + ' selected';
            } else {
                bar.style.display = 'none';
                document.getElementById('selectAllCheckbox').checked = false;
            }
        }

        function submitBulk(action) {
            const checkboxes = document.querySelectorAll('.review-checkbox:checked');
            if (checkboxes.length === 0) return;
            if (action === 'delete' && !confirm('Are you sure you want to delete ' + checkboxes.length + ' review(s)?')) return;
            
            const form = document.getElementById('bulkActionForm');
            document.getElementById('bulkActionValue').value = action;
            
            // Remove any existing hidden inputs
            form.querySelectorAll('input[type="hidden"][name="ids[]"]').forEach(function(el) { el.remove(); });
            
            checkboxes.forEach(function(cb) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            
            form.submit();
        }

        function clearSelection() {
            document.querySelectorAll('.review-checkbox').forEach(function(cb) { cb.checked = false; });
            document.getElementById('selectAllCheckbox').checked = false;
            updateBulkBar();
        }
    </script>
@endsection