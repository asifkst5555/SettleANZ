@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero directory-hero">
        <div class="container guide-hero__inner directory-hero__inner">
            <p class="eyebrow">Curated directory</p>
            <h1>Find Trusted Expat Services in Australia &amp; New Zealand</h1>
            <p class="guide-hero__copy directory-hero__copy">Compare expat-friendly schools, migration experts, relocation partners, banking support, and arrival services in one place.</p>

            <div class="directory-filter-panel">
                <div class="directory-search-row">
                    <label class="directory-filter-field directory-filter-field--search">
                        <span>Search</span>
                        <input type="search" placeholder="Search by provider, service type, or city" data-directory-search>
                    </label>
                    <label class="directory-filter-field directory-filter-field--city">
                        <span>City</span>
                        <select data-directory-city>@foreach ($cities as $city)<option value="{{ strtolower($city) }}">{{ $city }}</option>@endforeach</select>
                    </label>
                    <button class="directory-filter-reset" type="button" data-directory-reset>Clear filters</button>
                </div>

                <div class="directory-filter-meta">
                    <p class="directory-filter-hint">Choose a category, narrow by city, or search by name to find the right fit faster.</p>
                </div>

                <div class="directory-chip-group" aria-label="Service categories">
                    @foreach ($categories as $category)
                        <button class="directory-chip{{ $loop->first ? ' is-active' : '' }}" type="button" data-directory-filter="{{ strtolower($category) }}">{{ $category }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="directory-page">
        <div class="container">
            <div class="directory-grid" data-directory-grid>
                @foreach ($listings as $listing)
                    <article class="directory-card" data-directory-listing data-category="{{ strtolower($listing->category) }}" data-city="{{ strtolower($listing->city) }}" data-name="{{ strtolower($listing->name) }}">
                        <div class="directory-card__logo">{{ strtoupper(substr($listing->name, 0, 2)) }}</div>
                        <div class="directory-card__body">
                            <div class="directory-card__topline">
                                <p class="blog-card__tag">{{ $listing->category }}</p>
                                @if ($listing->featured)
                                    <span class="winner-badge">Featured</span>
                                @endif
                            </div>
                            <h3>{{ $listing->name }}</h3>
                            <div class="directory-card__meta"><span class="directory-card__rating" aria-label="Rated {{ $listing->rating }} out of 5"><span class="directory-card__rating-value">{{ $listing->rating }}</span><span class="directory-card__rating-stars" aria-hidden="true" style="--rating-fill: {{ ($listing->rating / 5) * 100 }}%;"><span class="directory-card__rating-stars-base">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span class="directory-card__rating-stars-fill">&#9733;&#9733;&#9733;&#9733;&#9733;</span></span></span><span class="directory-card__location"><svg class="directory-card__location-icon" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg><span>{{ $listing->city }}</span></span></div>
                            <p>{{ $listing->description }}</p>
                            <a class="button button--small" href="{{ route('directory.show', $listing->slug) }}">View Details</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="directory-sticky-cta">
            <div class="container directory-sticky-cta__inner">
                <p>Are you a service provider? Get listed and reach thousands of expats.</p>
                <a class="button button--small" href="{{ $settings['directory_apply_link'] ?? $sharedSettings['directory_apply_link'] }}">Apply Now</a>
            </div>
        </div>
    </section>
@endsection
