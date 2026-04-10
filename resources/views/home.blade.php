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
    <style>
.empathy-section--inline.empathy-section {
    position: relative;
    background: #ffffff;
}
.empathy-section--inline::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 12% 14%, rgba(242, 125, 43, 0.09) 0 1px, transparent 1px),
        radial-gradient(circle at 88% 12%, rgba(242, 125, 43, 0.08) 0 1px, transparent 1px),
        radial-gradient(circle at 10% 88%, rgba(242, 125, 43, 0.08) 0 1px, transparent 1px);
    background-size: 14px 14px, 16px 16px, 14px 14px;
    pointer-events: none;
    opacity: 0.45;
}
.empathy-section--inline > .container {
    position: relative;
    z-index: 1;
}
.empathy-section--inline .empathy-section__heading {
    max-width: 100%;
    width: 100%;
    margin: 0 auto 3rem;
    text-align: center;
    display: grid;
    justify-items: center;
}
.empathy-section--inline .empathy-section__heading .eyebrow {
    display: block;
    width: 100%;
    text-align: center;
}
.empathy-section--inline .empathy-section__heading h2 {
    max-width: min(100%, 24ch);
    margin-inline: auto;
}
.empathy-section--inline .empathy-section__intro {
    max-width: min(100%, 72ch);
    margin: 1rem auto 0;
    color: #5d6670;
    font-size: 1.05rem;
    line-height: 1.75;
}
.empathy-section--inline .empathy-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.35rem;
}
.empathy-section--inline .empathy-card {
    display: grid;
    grid-template-columns: minmax(250px, 44%) minmax(0, 56%);
    align-items: stretch;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
    overflow: hidden;
}
.empathy-section--inline .empathy-card__media {
    position: relative;
    min-height: 248px;
    padding: 0.9rem;
    display: flex;
    align-items: stretch;
    background:
        linear-gradient(160deg, rgba(15, 139, 141, 0.12), rgba(15, 139, 141, 0.02)),
        linear-gradient(135deg, #f7fbfb 0%, #edf6f6 100%);
}
.empathy-section--inline .empathy-card__media::before {
    content: '';
    position: absolute;
    inset: 0.9rem;
    border-radius: 14px;
    background:
        linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0)),
        linear-gradient(135deg, rgba(15,139,141,0.06), rgba(242,125,43,0.06));
}
.empathy-section--inline .empathy-card__image {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    min-height: 230px;
    object-fit: cover;
    border-radius: 14px;
    display: block;
}
.empathy-section--inline .empathy-card__body {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    padding: 1rem 1.1rem 1.05rem;
}
.empathy-section--inline .empathy-card__kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin: 0;
    color: #2454a6;
    font-size: 0.88rem;
    font-weight: 600;
}
.empathy-section--inline .empathy-card__kicker::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #2454a6;
    box-shadow: 0 0 0 4px rgba(36,84,166,0.12);
}
.empathy-section--inline .empathy-card__body h3 {
    margin: 0;
    color: #0a6b6d;
    font-size: 1.55rem;
    line-height: 1.25;
}
.empathy-section--inline .empathy-card__points {
    display: grid;
    gap: 0.65rem;
    margin: 0;
    padding: 0 0 1rem;
    list-style: none;
    border-bottom: 1px dashed rgba(148,163,184,0.45);
}
.empathy-section--inline .empathy-card__points li {
    position: relative;
    padding-left: 1rem;
    color: #4b5563;
    font-size: 0.98rem;
    line-height: 1.6;
}
.empathy-section--inline .empathy-card__points li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.72rem;
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: #0f8b8d;
}
.empathy-section--inline .empathy-card__footer {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 1rem;
    margin-top: auto;
}
.empathy-section--inline .empathy-card__cta {
    min-height: 50px;
    margin-top: 0;
    padding-inline: 1.2rem;
    white-space: nowrap;
    border-radius: 999px;
}
@media (max-width: 1279px) {
    .empathy-section--inline .empathy-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 767px) {
    .empathy-section--inline .empathy-card {
        grid-template-columns: 1fr;
    }
    .empathy-section--inline .empathy-card__media {
        min-height: 220px;
    }
    .empathy-section--inline .empathy-card__footer {
        flex-direction: column;
        align-items: stretch;
    }
    .empathy-section--inline .empathy-card__cta {
        width: 100%;
    }
}
</style>
<section id="why-settleanz" class="section section--white empathy-section empathy-section--inline">
        <div class="container">
            <div class="section-heading section-heading--narrow empathy-section__heading">
                <p class="eyebrow">Who SettleANZ is for</p>
                <h2>My goal is simple: you should not have to struggle for years the way I did.</h2>
                <p class="empathy-section__intro">SettleANZ is for you whether you have already landed or arrive in the next few weeks...</p>
            </div>
            <div class="empathy-grid">
                <article class="empathy-card">
                    <div class="empathy-card__media">
                        <img class="empathy-card__image" src="{{ asset('media/home/International Students.webp') }}" alt="International students preparing for life in Australia">
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Pre-arrival study setup</p>
                        <h3>International Students</h3>
                        <ul class="empathy-card__points">
                            <li>You are coming to Australia mainly for study (uni, TAFE, language or vocational course).</li>
                            <li>You are worried about banking, SIM cards, and rentals close to campus before classes start.</li>
                            <li>You don't fully understand your visa work limits and you can't afford to get it wrong.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.new-to-australia') }}">Show me what to do first</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card">
                    <div class="empathy-card__media">
                        <img class="empathy-card__image" src="{{ asset('media/home/Skilled Workers.webp') }}" alt="Skilled workers planning their move and career">
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Career and landing plan</p>
                        <h3>Skilled Workers</h3>
                        <ul class="empathy-card__points">
                            <li>You hold (or are about to hold) a skilled visa and plan to work in your profession.</li>
                            <li>You want help choosing suburbs, setting up tax and banking, and reading employment offers.</li>
                            <li>You're worried about workplace culture and whether your overseas experience will actually count.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.banking') }}">Help me hit the ground running</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card">
                    <div class="empathy-card__media">
                        <img class="empathy-card__image" src="{{ asset('media/home/New Immigrants.webp') }}" alt="New immigrants settling into daily life">
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Family settlement support</p>
                        <h3>New Immigrants</h3>
                        <ul class="empathy-card__points">
                            <li>You have recently arrived as a permanent migrant or on a long-term pathway with family/partner.</li>
                            <li>You need to sort housing, schools (if you have kids), healthcare and day-to-day routines.</li>
                            <li>You feel overwhelmed by all the systems and want a step-by-step starting plan.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.housing') }}">Start with my family settlement plan</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card">
                    <div class="empathy-card__media">
                        <img class="empathy-card__image" src="{{ asset('media/home/Applying for Immigration.webp') }}" alt="Preparing documents and plans for immigration">
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Before-you-land planning</p>
                        <h3>Applying for Immigration</h3>
                        <ul class="empathy-card__points">
                            <li>You are still overseas and in the process of applying for a visa or planning your move.</li>
                            <li>You're drowning in conflicting advice about documents and what to prepare.</li>
                            <li>You want a realistic picture of costs, first-year priorities and common mistakes to avoid.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.migration-services') }}">Prepare before you land</a>
                        </div>
                    </div>
                </article>
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

    <section class="section founder-strip founder-strip--story" style="--founder-copy-max-width: 100%; --founder-heading-max-width: 24ch; --founder-copy-text-max-width: 100%;">
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

































