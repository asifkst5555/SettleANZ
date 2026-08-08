@extends('layouts.app')

@section('page_styles')
    <style>
        /* Blog Toolbar & Custom Category Filter Dropdown */
        .blog-toolbar {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
            margin-bottom: 2.25rem;
            position: relative;
            z-index: 10;
        }

        .blog-toolbar__search {
            flex: 1 1 auto;
            position: relative;
            display: flex;
            align-items: center;
            height: 48px;
            background: #ffffff;
            border: 1px solid rgba(25, 96, 104, 0.18);
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(13, 55, 67, 0.05);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .blog-toolbar__search:focus-within {
            border-color: #f27d2d;
            box-shadow: 0 0 0 3px rgba(242, 125, 45, 0.15);
        }

        .blog-toolbar__search-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #0b7a75;
            pointer-events: none;
            display: inline-flex;
        }

        .blog-toolbar__search-input {
            width: 100%;
            height: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            padding: 0 2.5rem 0 2.85rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e293b;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            box-shadow: none;
            border-radius: 0;
        }

        .blog-toolbar__search-input::-webkit-search-cancel-button,
        .blog-toolbar__search-input::-webkit-search-decoration,
        .blog-toolbar__search-input::-webkit-search-results-button,
        .blog-toolbar__search-input::-webkit-search-results-decoration {
            -webkit-appearance: none;
            appearance: none;
            display: none !important;
        }

        .blog-toolbar__search-input::placeholder {
            color: #72848d;
        }

        .blog-toolbar__search-clear {
            position: absolute;
            right: 0.75rem !important;
            left: auto !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 24px !important;
            min-width: 24px !important;
            max-width: 24px !important;
            height: 24px !important;
            padding: 0 !important;
            border: 0 !important;
            background: rgba(100, 116, 139, 0.12) !important;
            color: #64748b !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            transition: background 0.15s ease, color 0.15s ease !important;
            box-shadow: none !important;
            margin: 0 !important;
            z-index: 5 !important;
        }

        .blog-toolbar__search-clear[hidden] {
            display: none !important;
        }

        .blog-toolbar__search-clear:hover {
            background: rgba(242, 125, 45, 0.15);
            color: #f27d2d;
        }

        .blog-toolbar__filter {
            position: relative;
            flex: 0 0 auto;
        }

        .blog-toolbar__filter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            height: 48px;
            min-width: 210px;
            padding: 0 1.15rem;
            background: #ffffff;
            border: 1px solid rgba(25, 96, 104, 0.18);
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(13, 55, 67, 0.05);
            color: #1e293b;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .blog-toolbar__filter-btn:hover,
        .blog-toolbar__filter-btn:focus-visible {
            border-color: rgba(242, 125, 45, 0.4);
            color: #f27d2d;
        }

        .blog-toolbar__filter-btn:focus-visible {
            outline: 2px solid #f27d2d;
            outline-offset: 2px;
        }

        .blog-toolbar__filter-btn[aria-expanded="true"] {
            border-color: #f27d2d;
            box-shadow: 0 0 0 3px rgba(242, 125, 45, 0.15);
        }

        .blog-toolbar__filter-icon {
            display: inline-flex;
            color: #0b7a75;
        }

        .blog-toolbar__filter-label {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            flex: 1 1 auto;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .blog-toolbar__filter-prefix {
            color: #64748b;
            font-weight: 500;
        }

        .blog-toolbar__filter-value {
            color: #1e293b;
            font-weight: 700;
        }

        .blog-toolbar__filter-chevron {
            display: inline-flex;
            color: #64748b;
            transition: transform 0.2s ease;
        }

        .blog-toolbar__filter-btn[aria-expanded="true"] .blog-toolbar__filter-chevron {
            transform: rotate(180deg);
        }

        .blog-toolbar__dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: max(100%, 280px);
            max-width: 320px;
            background: #ffffff;
            border: 1px solid rgba(25, 96, 104, 0.16);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14), 0 4px 12px rgba(15, 23, 42, 0.05);
            z-index: 100;
            overflow: hidden;
            animation: blogDropdownFadeIn 0.18s ease-out forwards;
        }

        @keyframes blogDropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .blog-toolbar__dropdown[hidden] {
            display: none !important;
        }

        .blog-toolbar__dropdown-header {
            padding: 0.85rem 1.15rem 0.6rem;
            border-bottom: 1px solid rgba(25, 96, 104, 0.08);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #0b7a75;
        }

        .blog-toolbar__dropdown-list {
            max-height: 320px;
            overflow-y: auto;
            padding: 0.4rem;
            scrollbar-width: thin;
        }

        .blog-toolbar__dropdown-item {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 0;
            background: transparent;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .blog-toolbar__dropdown-item:hover,
        .blog-toolbar__dropdown-item:focus-visible {
            background: rgba(11, 122, 117, 0.06);
            color: #0b7a75;
            outline: none;
        }

        .blog-toolbar__dropdown-item.is-selected {
            background: rgba(242, 125, 45, 0.1);
            color: #f27d2d;
            font-weight: 700;
        }

        .blog-toolbar__dropdown-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            margin-right: 0.65rem;
            opacity: 0;
            color: #f27d2d;
            transition: opacity 0.15s ease;
        }

        .blog-toolbar__dropdown-item.is-selected .blog-toolbar__dropdown-check {
            opacity: 1;
        }

        .blog-empty-state {
            padding: 4rem 1.5rem;
            text-align: center;
            background: #ffffff;
            border: 1px dashed rgba(25, 96, 104, 0.22);
            border-radius: 20px;
            margin: 2rem 0;
        }

        .blog-empty-state__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(11, 122, 117, 0.06);
            color: #0b7a75;
            margin-bottom: 1.25rem;
        }

        .blog-empty-state__title {
            margin: 0 0 0.5rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
        }

        .blog-empty-state__copy {
            margin: 0 0 1.5rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        .blog-card--v2 .blog-card__body {
            padding: 1.25rem 1.15rem 1.15rem 1.15rem;
        }

        .blog-card--v2 .blog-card__footer {
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(11, 122, 117, 0.09);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .blog-card--v2 .blog-card__meta {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 0 !important;
            font-size: 0.8125rem !important;
            color: #6b7c86;
            line-height: 1.4;
            white-space: nowrap !important;
            width: 100%;
        }

        .blog-card--v2 .blog-card__meta > * {
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .blog-card--v2 .blog-card__meta > * + *::before {
            content: "·" !important;
            margin: 0 0.45rem !important;
            color: rgba(107, 124, 134, 0.6) !important;
            font-size: 1em !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            pointer-events: none;
            flex-shrink: 0 !important;
        }

        .blog-card--v2 .blog-card__actions {
            display: flex;
            align-items: center;
            margin-top: 0.1rem;
        }

        .blog-card--v2 .blog-card__read {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #f27d2d !important;
            text-decoration: none;
            transition: color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .blog-card--v2 .blog-card__read:hover {
            color: #0b7a75 !important;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .blog-card--v2 .blog-card__body {
                padding: 1.15rem 1rem 1rem 1rem !important;
            }
            .blog-card--v2 .blog-card__footer {
                padding-top: 0.75rem !important;
                gap: 0.55rem;
            }
            .blog-card--v2 .blog-card__meta {
                font-size: 0.75rem !important;
            }
            .blog-card--v2 .blog-card__meta > * + *::before {
                margin: 0 0.3rem !important;
            }
            .blog-card--v2 .blog-card__read {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 767px) {
            .blog-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.85rem;
            }

            .blog-toolbar__filter {
                width: 100%;
            }

            .blog-toolbar__filter-btn {
                width: 100%;
            }

            .blog-toolbar__dropdown {
                width: 100%;
                max-width: none;
                right: auto;
                left: 0;
            }
        }

        .blog-hero-v2 {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            min-height: 620px;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
        }

        .blog-hero-v2__visual {
            position: absolute;
            inset: 0 0 0 auto;
            width: min(60vw, 1000px);
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .blog-hero-v2__image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: left center;
        }

        .blog-hero-v2__inner {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            min-height: 620px;
            padding: 64px 0 104px;
        }

        .blog-hero-v2__content {
            max-width: 540px;
            padding-right: clamp(1rem, 3vw, 2rem);
            color: #fff;
        }

        .blog-hero-v2__eyebrow {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            margin-bottom: 1.15rem;
            padding: 0.5rem 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.94);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .blog-hero-v2 h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(3rem, 5vw, 4.85rem);
            line-height: 0.96;
            letter-spacing: -0.02em;
            text-wrap: balance;
        }

        .blog-hero-v2__copy {
            max-width: 30rem;
            margin: 1.35rem 0 0;
            color: rgba(255, 255, 255, 0.86);
            font-size: clamp(1.02rem, 1.6vw, 1.28rem);
            line-height: 1.68;
        }

        @media (max-width: 1180px) {
            .blog-hero-v2__visual {
                width: min(57vw, 860px);
            }

            .blog-hero-v2__content {
                max-width: 470px;
            }
        }

        @media (max-width: 1024px) {
            .blog-hero-v2 {
                min-height: 0;
                padding: 24px 0 34px;
            }

            .blog-hero-v2__visual {
                position: relative;
                inset: auto;
                width: 100%;
                height: 360px;
                margin-top: 2rem;
                border-radius: 28px 28px 0 0;
            }

            .blog-hero-v2__inner {
                display: grid;
                min-height: 0;
                padding: 0 0 44px;
            }

            .blog-hero-v2__content {
                max-width: none;
                padding-right: 0;
            }
        }

        @media (max-width: 767px) {
            .blog-hero-v2 {
                padding: 20px 0 28px;
            }

            .blog-hero-v2__content {
                text-align: center !important;
            }

            .blog-hero-v2__content .blog-hero-v2__eyebrow,
            .blog-hero-v2__content h1,
            .blog-hero-v2__content .blog-hero-v2__copy {
                text-align: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .blog-hero-v2 h1 {
                font-size: clamp(2.35rem, 11vw, 3.35rem);
            }

            .blog-hero-v2__copy {
                font-size: 1rem;
            }

            .blog-hero-v2__visual {
                height: auto;
                margin-top: 1.5rem;
                margin-bottom: 0;
                border-radius: 0;
                background: transparent;
            }

            .blog-hero-v2__image {
                height: auto !important;
                width: 100% !important;
                object-fit: contain !important;
                object-position: center bottom !important;
                display: block;
            }

            .blog-hero-v2 {
                padding-bottom: 0 !important;
            }

            .blog-hero-v2__inner {
                padding-bottom: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
<section id="top" class="blog-hero-v2">
        <div class="container">
            <div class="blog-hero-v2__inner">
                <div class="blog-hero-v2__content">
                    <p class="blog-hero-v2__eyebrow">SettleANZ Blog</p>
                    <h1>Your Guide to a Better Life in Australia</h1>
                    <p class="blog-hero-v2__copy">Practical guides, honest advice, and real insights for new expats in Australia and New Zealand.</p>
                </div>
            </div>
        </div>
        <div class="blog-hero-v2__visual" aria-hidden="true">
            <img src="{{ asset('media/blog/blog_hero.webp') }}" alt="" class="blog-hero-v2__image" width="1000" height="530">
        </div>
    </section>

    <section class="blog-page blog-page--v2">
        <div class="container">
            {{-- Professional Search + Filter Toolbar --}}
            <div class="blog-toolbar" data-blog-toolbar>
                <div class="blog-toolbar__search">
                    <span class="blog-toolbar__search-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                    </span>
                    <input type="search" class="blog-toolbar__search-input" placeholder="Search articles..." aria-label="Search articles" data-blog-search>
                    <button type="button" class="blog-toolbar__search-clear" aria-label="Clear search" data-blog-search-clear hidden>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="blog-toolbar__filter" data-blog-filter-popover-wrap>
                    <button type="button" class="blog-toolbar__filter-btn" id="blog-filter-trigger" aria-haspopup="listbox" aria-expanded="false" aria-controls="blog-filter-menu" data-blog-filter-trigger>
                        <span class="blog-toolbar__filter-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="10" y1="18" x2="14" y2="18"></line></svg>
                        </span>
                        <span class="blog-toolbar__filter-label">
                            <span class="blog-toolbar__filter-prefix">Filter:</span>
                            <span class="blog-toolbar__filter-value" data-blog-filter-selected-text>All</span>
                        </span>
                        <span class="blog-toolbar__filter-chevron" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </span>
                    </button>

                    <div class="blog-toolbar__dropdown" id="blog-filter-menu" role="listbox" aria-labelledby="blog-filter-trigger" data-blog-filter-menu hidden>
                        <div class="blog-toolbar__dropdown-header">Filter by category</div>
                        <div class="blog-toolbar__dropdown-list" tabindex="-1">
                            @foreach ($categories as $category)
                                <button type="button" 
                                        class="blog-toolbar__dropdown-item{{ $loop->first ? ' is-selected' : '' }}" 
                                        role="option" 
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        data-blog-filter-option="{{ strtolower($category) }}"
                                        data-category-label="{{ $category }}">
                                    <span class="blog-toolbar__dropdown-check" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </span>
                                    <span class="blog-toolbar__dropdown-text">{{ $category }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Empty Results State --}}
            <div class="blog-empty-state" data-blog-empty-state hidden>
                <div class="blog-empty-state__icon" aria-hidden="true">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                </div>
                <h3 class="blog-empty-state__title">No articles found</h3>
                <p class="blog-empty-state__copy">Try a different search term or select another category.</p>
                <button type="button" class="button button--outline-accent button--small" data-blog-reset-filters>Reset Filters</button>
            </div>

            <div class="blog-grid blog-grid--v2" data-blog-grid>
                @foreach ($posts as $post)
                    <article class="blog-card blog-card--v2{{ $loop->index > 5 ? ' is-hidden' : '' }}" data-blog-post data-category="{{ strtolower($post->category) }}" data-search="{{ strtolower(trim($post->title . ' ' . $post->category . ' ' . $post->excerpt . ' ' . ($post->author_name ?? ''))) }}">
                        <a class="blog-card__media-link" href="{{ route('blog.show', $post->slug) }}">
                            @if (!empty($post->image))
                                <img class="blog-card__image blog-card__image--file" src="{{ $post->image_url ?? \App\Support\BlogMedia::url($post->image ?? null) }}" alt="{{ $post->title }}" loading="lazy">
                            @else
                                <div class="blog-card__image {{ $post->image_class }}" aria-hidden="true"></div>
                            @endif
                        </a>
                        <div class="blog-card__body blog-card__body--v2">
                            <p class="blog-card__tag">{{ $post->category }}</p>
                            <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                            <p class="blog-card__excerpt">{{ $post->excerpt }}</p>
                            <div class="blog-card__footer">
                                <div class="blog-card__meta">
                                    <span>{{ $post->display_author }}</span>
                                    @if (!empty($post->reading_time))
                                        <span>{{ $post->reading_time }}</span>
                                    @endif
                                    @if ($post->published_at)
                                        <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('M j, Y') }}</time>
                                    @endif
                                </div>
                                <div class="blog-card__actions">
                                    <a class="text-link blog-card__read" href="{{ route('blog.show', $post->slug) }}">Read article &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="section-cta-center"><button class="button button--outline-accent button--large" type="button" data-blog-load-more>Load More</button></div>
        </div>
    </section>
@endsection







