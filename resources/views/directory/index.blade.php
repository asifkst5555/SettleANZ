@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero directory-hero">
        <div class="container guide-hero__inner">
            <p class="eyebrow">Curated directory</p>
            <h1>Find Trusted Expat Services in Australia &amp; New Zealand</h1>
            <div class="directory-search-row">
                <label class="directory-search"><span class="sr-only">Search directory</span><input type="search" placeholder="Search by service type or city" data-directory-search></label>
                <label class="directory-city-filter"><span class="sr-only">Filter by city</span><select data-directory-city>@foreach ($cities as $city)<option value="{{ strtolower($city) }}">{{ $city }}</option>@endforeach</select></label>
            </div>
            <div class="blog-filter-bar directory-filter-bar">
                @foreach ($categories as $category)
                    <button class="blog-filter-chip{{ $loop->first ? ' is-active' : '' }}" type="button" data-directory-filter="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
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
                            <div class="directory-card__meta"><span>{{ $listing->rating }} stars</span><span>{{ $listing->city }}</span></div>
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
