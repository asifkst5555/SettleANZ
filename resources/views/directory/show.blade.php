@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero directory-listing-hero">
        <div class="container guide-hero__inner guide-hero__inner--split">
            <div>
                <p class="eyebrow">{{ $listing->category }}</p>
                <h1>{{ $listing->name }}</h1>
                <div class="directory-card__meta directory-card__meta--hero"><span>{{ $listing->rating }} stars</span><span>{{ $listing->city }}</span></div>
                <p class="guide-hero__copy">{{ $listing->description }}</p>
            </div>
            <aside class="directory-listing-hero__logo">{{ strtoupper(substr($listing->name, 0, 2)) }}</aside>
        </div>
    </section>

    <section class="directory-listing-page">
        <div class="container directory-listing-layout">
            <div class="directory-listing-content">
                <section class="guide-block guide-block--white"><h2>About This Business</h2><p>{{ $listing->full_description }}</p></section>
                <section class="guide-block guide-block--sand"><h2>Services Offered</h2><ul class="guide-list">@foreach (($listing->services ?? []) as $service)<li>{{ $service }}</li>@endforeach</ul></section>
                <section class="guide-block guide-block--white">
                    <h2>Contact Details</h2>
                    <div class="directory-contact-grid">
                        @if ($listing->phone)<a class="directory-contact-card" href="tel:{{ preg_replace('/\s+/', '', $listing->phone) }}">{{ $listing->phone }}</a>@endif
                        @if ($listing->email)<a class="directory-contact-card" href="mailto:{{ $listing->email }}">{{ $listing->email }}</a>@endif
                        @if ($listing->website)<a class="directory-contact-card" href="{{ $listing->website }}" target="_blank" rel="noreferrer">Visit website</a>@endif
                        @if (!empty($listing->whatsapp))<a class="directory-contact-card" href="{{ $listing->whatsapp }}" target="_blank" rel="noreferrer">WhatsApp</a>@endif
                        @if (!empty($listing->booking_url))<a class="directory-contact-card" href="{{ $listing->booking_url }}" target="_blank" rel="noreferrer">Book consultation</a>@endif
                    </div>
                </section>
                <section class="guide-block guide-block--sand"><h2>Location</h2><div class="directory-map-placeholder"><strong>Google Maps embed area</strong><p>This can be replaced with a live Google Maps embed for {{ $listing->city }} when your real listing data is connected.</p></div></section>
            </div>

            <aside class="directory-listing-sidebar">
                <div class="housing-booking-card">
                    <p class="housing-booking-card__eyebrow">Get in touch</p>
                    <h2>Contact {{ $listing->name }}</h2>
                    <form class="lead-form" method="POST" action="{{ route('lead-capture.store') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="directory-enquiry">
                        <input type="hidden" name="source_page" value="directory-{{ $listing->slug }}">
                        <input type="hidden" name="goal" value="Directory enquiry for {{ $listing->name }}">
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <input type="hidden" name="listing_name" value="{{ $listing->name }}">
                        <label><span>Full Name</span><input type="text" name="name" value="{{ old('name') }}" required></label>
                        <label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required></label>
                        <label><span>Phone (optional)</span><input type="text" name="phone" value="{{ old('phone') }}"></label>
                        <label><span>What do you need help with?</span><textarea name="help_details" rows="4">{{ old('help_details') }}</textarea></label>
                        <button class="button button--large button--full" type="submit">Get in Touch</button>
                    </form>
                </div>
            </aside>
        </div>
    </section>
@endsection
