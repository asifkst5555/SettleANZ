@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="directory-detail-hero">
    <div class="container">
        <div class="hero-breadcrumb">
            <a href="{{ route('directory.index') }}">← Back to Directory</a>
        </div>
        <div class="hero-content">
            <div class="hero-logo">
                @if($listing->logo)
                    <img src="{{ $listing->logo }}" alt="{{ $listing->name }}">
                @else
                    <div class="logo-placeholder">{{ strtoupper(substr($listing->name, 0, 2)) }}</div>
                @endif
            </div>
            <div class="hero-info">
                <span class="category-badge">{{ $listing->category }}</span>
                <h1>{{ $listing->name }}</h1>
                <div class="hero-meta">
                    <div class="rating-large">
                        <div class="stars" data-rating="{{ $avgRating }}">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($avgRating) ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                        <span class="rating-number">{{ $avgRating }}</span>
                        @if($reviews->count() > 0)
                            <span class="review-count">{{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</span>
                        @endif
                    </div>
                    <span class="location">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>
                        {{ $listing->city }}
                    </span>
                </div>
                <p class="hero-description">{{ $listing->description }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="directory-detail-main">
    <div class="container">
        <div class="detail-layout">
            <!-- Main Content Column -->
            <div class="detail-content">
                <!-- About Section -->
                <div class="content-card">
                    <h2>About {{ $listing->name }}</h2>
                    <div class="about-content">
                        {!! nl2br(e($listing->full_description)) !!}
                    </div>
                </div>

                <!-- Services Section -->
                @if(!empty($listing->services))
                <div class="content-card">
                    <h2>Services Offered</h2>
                    <div class="services-grid">
                        @foreach($listing->services as $service)
                        <div class="service-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>{{ $service }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Reviews Section -->
                <div class="content-card reviews-section" id="reviews">
                    <div class="reviews-header">
                        <h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Customer Reviews
                        </h2>
                        <div class="reviews-summary">
                            <span class="summary-rating">{{ $avgRating }}</span>
                            <div class="summary-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= round($avgRating) ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                            <span class="summary-count">{{ $reviews->count() }} reviews</span>
                        </div>
                    </div>

                    @if($reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                    </div>
                                    <div class="reviewer-details">
                                        <strong class="reviewer-name">{{ $review->reviewer_name }}</strong>
                                        <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="review-comment">{{ $review->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="no-reviews">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                    @endif

                    <!-- Write Review Form -->
                    <div class="write-review-section">
                        <h3>Share Your Experience</h3>
                        <form method="POST" action="{{ route('directory.review.store', $listing->slug) }}" class="review-form">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="reviewer_name">Your Name *</label>
                                    <input type="text" id="reviewer_name" name="reviewer_name" required placeholder="John Smith">
                                </div>
                                <div class="form-group">
                                    <label for="reviewer_email">Email Address *</label>
                                    <input type="email" id="reviewer_email" name="reviewer_email" required placeholder="john@example.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Your Rating *</label>
                                <div class="star-rating-input" data-rating="">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                                    @endfor
                                    <span class="rating-label">Click to rate</span>
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}">
                                @error('rating')
                                    <span class="error">Please select a rating</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="comment">Your Review *</label>
                                <textarea id="comment" name="comment" rows="5" required placeholder="Share your experience with {{ $listing->name }}...">{{ old('comment') }}</textarea>
                                <span class="char-count">Minimum 20 characters</span>
                                @error('comment')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <x-honeypot />
                            <x-math-verification />
                            
                            <button type="submit" class="btn btn--primary btn--large">
                                Submit Review
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="content-card">
                    <h2>Location</h2>
                    <div class="location-info">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>
                        <span>{{ $listing->city }}, Australia</span>
                    </div>
                    <div class="map-placeholder">
                        <div class="map-content">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>
                            <p>Map integration available for {{ $listing->city }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="detail-sidebar">
                <!-- Contact Card -->
                <div class="sidebar-card contact-card">
                    <h3>Get in Touch</h3>
                    <div class="contact-actions">
                        @if($listing->phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $listing->phone) }}" class="contact-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>{{ $listing->phone }}</span>
                        </a>
                        @endif
                        @if($listing->email)
                        <a href="mailto:{{ $listing->email }}" class="contact-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
                            <span>{{ $listing->email }}</span>
                        </a>
                        @endif
                        @if($listing->website)
                        <a href="{{ $listing->website }}" target="_blank" rel="noreferrer" class="contact-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span>Visit Website</span>
                        </a>
                        @endif
                        @if(!empty($listing->whatsapp))
                        <a href="{{ $listing->whatsapp }}" target="_blank" rel="noreferrer" class="contact-btn contact-btn--whatsapp">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                        @endif
                    </div>

                    @if(!empty($listing->booking_url))
                    <a href="{{ $listing->booking_url }}" target="_blank" rel="noreferrer" class="btn btn--primary btn--full">
                        Book Consultation
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="sidebar-card stats-card">
                    <div class="stat-item">
                        <span class="stat-value">{{ $avgRating }}</span>
                        <span class="stat-label">Rating</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $reviews->count() }}</span>
                        <span class="stat-label">Reviews</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ count($listing->services ?? []) }}</span>
                        <span class="stat-label">Services</span>
                    </div>
                </div>

                <!-- Share Card -->
                <div class="sidebar-card share-card">
                    <h4>Share This Business</h4>
                    <div class="share-buttons">
                        <a href="#" class="share-btn" title="Share on Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="share-btn" title="Share on Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="share-btn" title="Share via Email">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Review Success Modal -->
<div class="review-success-modal" id="reviewSuccessModal" style="display: none;">
    <div class="review-success-modal__overlay"></div>
    <div class="review-success-modal__content">
        <div class="review-success-modal__icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
        </div>
        <h2 class="review-success-modal__title">Review Submitted!</h2>
        <p class="review-success-modal__message">Thank you for your review. Your feedback has been received and will be visible after approval.</p>
        <button class="review-success-modal__button" id="reviewSuccessClose">Got it</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating input
    const starBtns = document.querySelectorAll('.star-rating-input .star-btn');
    const ratingInput = document.getElementById('rating-input');
    const ratingLabel = document.querySelector('.star-rating-input .rating-label');

    starBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const val = parseInt(this.dataset.value);
            starBtns.forEach((b) => {
                const bVal = parseInt(b.dataset.value);
                b.classList.toggle('hovered', bVal <= val);
            });
        });

        btn.addEventListener('mouseleave', function() {
            starBtns.forEach(b => b.classList.remove('hovered'));
        });

        btn.addEventListener('click', function() {
            const val = parseInt(this.dataset.value);
            ratingInput.value = val;
            starBtns.forEach((b) => {
                const bVal = parseInt(b.dataset.value);
                b.classList.toggle('selected', bVal <= val);
            });
            ratingLabel.textContent = val + ' star' + (val > 1 ? 's' : '');
        });
    });

    // Review Success Modal
    const reviewSuccessModal = document.getElementById('reviewSuccessModal');
    const reviewSuccessClose = document.getElementById('reviewSuccessClose');
    const hasSuccessMessage = {{ session('success') ? 'true' : 'false' }};

    if (hasSuccessMessage && reviewSuccessModal) {
        reviewSuccessModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    if (reviewSuccessClose) {
        reviewSuccessClose.addEventListener('click', function() {
            if (reviewSuccessModal) {
                reviewSuccessModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }

    // Close modal when clicking overlay
    if (reviewSuccessModal) {
        reviewSuccessModal.querySelector('.review-success-modal__overlay').addEventListener('click', function() {
            reviewSuccessModal.style.display = 'none';
            document.body.style.overflow = '';
        });
    }

    // Also close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && reviewSuccessModal && reviewSuccessModal.style.display === 'flex') {
            reviewSuccessModal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
});
</script>
@endpush