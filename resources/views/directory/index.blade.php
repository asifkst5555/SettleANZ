@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero directory-hero">
        <div class="container guide-hero__inner directory-hero__inner">
            <div class="directory-hero__content">
                <div class="directory-hero__copy-block">
                    <h1 class="directory-hero__title">Find trusted expat services in Australia</h1>
                    <p class="guide-hero__copy directory-hero__copy">Compare schools, migration experts, relocation partners, banking, and arrival support curated for newcomers in one directory.</p>
                </div>

                <div class="directory-hero__stats" aria-label="Directory overview">
                    <div class="directory-hero__stat">
                        <strong>{{ $listings->count() }}+</strong>
                        <span>trusted providers</span>
                    </div>
                    <div class="directory-hero__stat">
                        <strong>{{ max(count($cities) - 1, 0) }}</strong>
                        <span>cities covered</span>
                    </div>
                    <div class="directory-hero__stat">
                        <strong>{{ max(count($categories) - 1, 0) }}</strong>
                        <span>service categories</span>
                    </div>
                </div>
            </div>

            <div class="directory-filter-panel">
                <header class="directory-filter-panel__header">
                    <div>
                        <h2 class="directory-filter-panel__title">Search &amp; filter</h2>
                        <p class="directory-filter-panel__lede">Enter a provider, service, or city, then choose a category to narrow results.</p>
                    </div>
                    <span class="directory-filter-panel__badge">Fast shortlist</span>
                </header>

                <div class="directory-filter-panel__toolbar">
                    <div class="directory-search-row">
                        <label class="directory-filter-field directory-filter-field--search">
                            <span>Search</span>
                            <input type="search" placeholder="Provider, service, or city" autocomplete="off" data-directory-search>
                        </label>
                        <label class="directory-filter-field directory-filter-field--city">
                            <span>City</span>
                            <select class="pro-select" data-directory-city>@foreach ($cities as $city)<option value="{{ strtolower($city) }}">{{ $city }}</option>@endforeach</select>
                        </label>
                        <button class="directory-filter-reset" type="button" data-directory-reset>Clear all</button>
                    </div>
                </div>

                <div class="directory-filter-panel__categories">
                    <p class="directory-filter-section-label" id="directory-category-label">Category</p>

                    {{-- Desktop: Button chips --}}
                    <div class="directory-chip-group directory-chip-group--desktop" role="group" aria-labelledby="directory-category-label">
                        @foreach ($categories as $category)
                            <button class="directory-chip{{ $loop->first ? ' is-active' : '' }}" type="button" data-directory-filter="{{ strtolower($category) }}">{{ $category }}</button>
                        @endforeach
                    </div>

                    {{-- Mobile: Dropdown select --}}
                    <div class="directory-select-group directory-select-group--mobile">
                        <select class="pro-select directory-category-select" data-directory-category-select aria-label="Select category">
                            @foreach ($categories as $category)
                                <option value="{{ strtolower($category) }}"{{ $loop->first ? ' selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="directory-page">
        <div class="container">
            <div class="directory-grid" data-directory-grid>
                @foreach ($listings as $listing)
                    @php
                        $accentPalette = [
                            ['#2563eb', '#eff6ff', '#1e40af'],
                            ['#15803d', '#ecfdf5', '#14532d'],
                            ['#7c3aed', '#f5f3ff', '#5b21b6'],
                            ['#d97706', '#fffbeb', '#b45309'],
                            ['#0d9488', '#f0fdfa', '#115e59'],
                            ['#6366f1', '#eef2ff', '#4338ca'],
                        ];
                        $accentIdx = abs(crc32((string) ($listing->category ?? '') . '-' . (string) ($listing->slug ?? ''))) % count($accentPalette);
                        [$accent, $accentSoft, $accentDeep] = $accentPalette[$accentIdx];
                        $services = $listing->services ?? [];
                        $features = is_array($services) ? array_slice($services, 0, 3) : [];
                    @endphp
                    <article
                        class="directory-card{{ !empty($listing->featured) ? ' directory-card--featured' : '' }}"
                        style="--card-accent: {{ $accent }}; --card-accent-soft: {{ $accentSoft }}; --card-accent-deep: {{ $accentDeep }};"
                        data-directory-listing
                        data-category="{{ strtolower($listing->category) }}"
                        data-city="{{ strtolower($listing->city) }}"
                        data-name="{{ strtolower($listing->name) }}"
                    >
                        <div class="directory-card__header">
                            <div class="directory-card__icon" aria-hidden="true">
                                @include('directory.partials.listing-icon', ['category' => $listing->category])
                            </div>
                            <div class="directory-card__intro">
                                <p class="directory-card__category">{{ strtoupper($listing->category) }}</p>
                                <h3 class="directory-card__title">{{ $listing->name }}</h3>
                                <div class="directory-card__meta">
                                    <span class="directory-card__rating" aria-label="Rated {{ $listing->rating }} out of 5">
                                        <span class="directory-card__rating-value">{{ $listing->rating }}</span>
                                        <span class="directory-card__rating-stars" aria-hidden="true" style="--rating-fill: {{ ($listing->rating / 5) * 100 }}%;">
                                            <span class="directory-card__rating-stars-base">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                            <span class="directory-card__rating-stars-fill">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                        </span>
                                    </span>
                                    <span class="directory-card__location">
                                        <svg class="directory-card__location-icon" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>
                                        <span>{{ $listing->city }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if (!empty($listing->featured))
                            <span class="directory-card__featured">Featured</span>
                        @endif

                        <p class="directory-card__desc">{{ $listing->description }}</p>

                        @if (count($features))
                            <ul class="directory-card__features">
                                @foreach ($features as $feature)
                                    <li class="directory-card__feature">
                                        <span class="directory-card__check" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                        </span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="directory-card__footer">
                            <a class="directory-card__cta" href="{{ route('directory.show', $listing->slug) }}">
                                View Details
                                <svg class="directory-card__cta-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                            <button
                                type="button"
                                class="directory-card__bookmark"
                                data-directory-bookmark
                                data-directory-slug="{{ $listing->slug }}"
                                aria-label="Save {{ $listing->name }}"
                                aria-pressed="false"
                            >
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="directory-sticky-cta">
            <div class="container directory-sticky-cta__inner">
                <p>Are you a service provider? Get listed and reach thousands of expats.</p>
                <a class="button button--small directory-sticky-cta__action" href="{{ $settings['directory_apply_link'] ?? $sharedSettings['directory_apply_link'] }}">
                    Apply Now
                    <svg class="directory-sticky-cta__action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
