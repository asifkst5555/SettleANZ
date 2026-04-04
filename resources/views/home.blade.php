@extends('layouts.app')

@section('content')
<section id="top" class="hero-section hero-section--image hero-section--reference section section--hero">
        <div class="container hero-reference__inner">
            <div class="hero-reference__content">
                <p class="eyebrow hero-reference__eyebrow">SettleANZ starter support</p>
                <h1>Your Guide to Settling in Australia &amp; New Zealand</h1>
                <p class="hero-reference__copy">From visa to suburb, banking to healthcare, SettleANZ gives new arrivals practical guidance, trusted next steps, and a calmer start in Australia and New Zealand.</p>
                <div class="hero-reference__actions">
                    <button class="button button--large" type="button" data-open-lead-modal>Get Your Free Starter Guide</button>
                    <a class="button button--ghost-light button--large" href="{{ route('blog.index') }}">Browse the Guides</a>
                </div>
            </div>
        </div>
    </section>

    <section id="why-settleanz" class="section section--white">
        <div class="container">
            <div class="section-heading section-heading--narrow">
                <h2>Whether You're Arriving Next Month or Already Here...</h2>
            </div>
            <div class="card-grid card-grid--three">
                <article class="info-card info-card--audience"><div class="info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14l8.5-2.5L21 7l-1.5 4-8.5 2.5L7 21l-.5-6.5L3 14z" /></svg></div><h3>New Arrivals</h3><p>Just landed? We'll walk you through every first step.</p></article>
                <article class="info-card info-card--audience"><div class="info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-4 9 4-9 4-9-4z" /><path d="M7 11.5v4.5c0 1.2 2.2 3 5 3s5-1.8 5-3v-4.5" /><path d="M21 10v5" /></svg></div><h3>International Students</h3><p>Banking, housing, SIM cards, sorted before semester starts.</p></article>
                <article class="info-card info-card--audience"><div class="info-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8z" /><path d="M9 8V6a3 3 0 0 1 6 0v2" /></svg></div><h3>Skilled Migrants</h3><p>Visa pathways, tax setup, and finding your footing professionally.</p></article>
            </div>
        </div>
    </section>

    <section id="services" class="section section--services">
        <div class="container">
            <div class="section-heading section-heading--narrow"><h2>Everything You Need in One Place</h2></div>
            <div class="service-icon-grid">
                <a class="service-icon-card" href="{{ route('guides.housing') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 4l9 6.5" /><path d="M5 9.5V20h14V9.5" /></svg></div><span>Housing</span><small>Find rentals, suburbs, and first-home setup tips.</small></a>
                <a class="service-icon-card" href="{{ route('guides.banking') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 10h18" /></svg></div><span>Banking</span><small>Open accounts, compare fees, and move money smarter.</small></a>
                <a class="service-icon-card" href="{{ route('guides.migration-services') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z" /><circle cx="12" cy="10" r="2.5" /></svg></div><span>Migration Help</span><small>Connect with agents and get visa pathway guidance.</small></a>
                <a class="service-icon-card" href="/#lead-strip"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c4-2.2 7-6.3 7-10.8V5l-7-2-7 2v5.2C5 14.7 8 18.8 12 21Z" /><path d="M9.5 12.2 11 13.7l3.8-3.8" /></svg></div><span>Health Insurance</span><small>Understand cover options before and after arrival.</small></a>
                <a class="service-icon-card" href="{{ route('guides.new-to-australia') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16" /><path d="M7 4v6" /><path d="M17 4v6" /><path d="M5 11h14l-1.2 7.2a2 2 0 0 1-2 1.8H8.2a2 2 0 0 1-2-1.8L5 11Z" /></svg></div><span>Money Transfers</span><small>Send funds internationally with fewer fee surprises.</small></a>
                <a class="service-icon-card" href="{{ route('guides.new-to-australia') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="2.5" width="10" height="19" rx="2" /><path d="M10 5.5h4" /><path d="M11 18h2" /></svg></div><span>SIM &amp; Mobile</span><small>Choose a plan quickly and stay connected from day one.</small></a>
                <a class="service-icon-card" href="{{ route('guides.banking') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3v18" /><path d="M17 7v14" /><path d="M4 9h6" /><path d="M14 11h6" /><path d="M14 15h6" /></svg></div><span>Tax &amp; Finance</span><small>Learn TFN, tax basics, and early finance essentials.</small></a>
                <a class="service-icon-card" href="{{ route('guides.new-to-australia') }}"><div class="service-icon-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V8l8-4 8 4v12" /><path d="M9 12h6" /><path d="M9 16h6" /></svg></div><span>Schools &amp; Education</span><small>Explore school options and plan education early.</small></a>
            </div>
        </div>
    </section>

    <section class="section founder-strip founder-strip--story" style="--founder-copy-max-width: 760px; --founder-heading-max-width: 24ch; --founder-copy-text-max-width: 100%;">
        <div class="container founder-strip__story-grid">
            <div class="founder-photo-wrap">
                <div class="founder-photo-ring"><img class="founder-photo-image" src="{{ asset('media/founder/founder.webp') }}" alt="Founder of SettleANZ"></div>
            </div>
            <div class="founder-story-copy">
                <h2>Written by Someone Who's Actually Done it</h2>
                <p>"I moved to Australia without knowing a single person there. No one told me about the hidden bank fees, the suburb traps, or how Medicare actually works for new arrivals. SettleANZ is the guide I wish I had."</p>
                <a class="founder-story-link" href="{{ $settings['founder_story_link'] ?? $sharedSettings['founder_story_link'] }}">Read our story</a>
            </div>
        </div>
    </section>

    <section id="guides" class="section section--white">
        <div class="container">
            <div class="section-heading section-heading--narrow"><h2>Start Here, Our Most-Read Guides</h2></div>
            <div class="guide-feature-grid">
                @foreach ($latestPosts as $post)
                    @php($homeBlogImagePath = !empty($post->image) ? public_path('media/blog/' . $post->image) : null)
                    <article class="guide-feature-card">
                        @if (!empty($post->image) && file_exists($homeBlogImagePath))
                            <img class="guide-feature-card__image guide-feature-card__image--file" src="{{ asset('media/blog/' . $post->image) }}" alt="{{ $post->title }}">
                        @else
                            <div class="guide-feature-card__image {{ $post->image_class }}" aria-hidden="true"></div>
                        @endif
                        <div class="guide-feature-card__body">
                            <p class="guide-feature-card__tag">{{ $post->category }}</p>
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->excerpt }}</p>
                            <a class="text-link" href="{{ route('blog.show', $post->slug) }}">Read more</a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="section-cta-center"><a class="button button--outline-accent button--large" href="{{ route('blog.index') }}">Browse all guides</a></div>
        </div>
    </section>

    <section id="partners" class="partner-strip">
        <div class="container">
            <p class="partner-strip__eyebrow">Trusted partners</p>
            <div class="partner-strip__viewport">
                <div class="partner-strip__logos" aria-label="Trusted partner logos">
                    <div class="partner-strip__group">
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt="Wise"></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt="SafetyWing"></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt="Booking.com"></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt="Cigna"></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt="OFX"></div>
                    </div>
                    <div class="partner-strip__group" aria-hidden="true">
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt=""></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt=""></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt=""></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt=""></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lead-strip" class="lead-strip">
        <div class="container lead-strip__inner">
            <div class="lead-strip__copy"><h2>Get the Free SettleANZ Starter Guide</h2><p>Your first 90 days in Australia or New Zealand, step by step. Straight to your inbox.</p></div>
            <form class="lead-strip__form" method="POST" action="{{ route('lead-capture.store') }}">
                @csrf
                <input type="hidden" name="form_type" value="inline-guide-strip">
                <input type="hidden" name="source_page" value="homepage-guide-strip">
                <label><span class="sr-only">First Name</span><input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required></label>
                <label><span class="sr-only">Email Address</span><input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required></label>
                <button class="button button--large" type="submit">Send It</button>
            </form>
        </div>
    </section>
@endsection












