@extends('layouts.app')

@section('page_styles')
    <style>
        /* Hide mobile dropdown filter on desktop — only show below 768px */
        .blog-page--v2 .blog-filter-select-wrap { display: none !important; }
        @@media (max-width: 767px) {
            .blog-page--v2 .blog-filter-select-wrap { display: flex !important; }
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

        .blog-hero-v2__search {
            position: relative;
            display: block;
            width: min(100%, 430px);
            margin-top: 2.05rem;
        }

        .blog-hero-v2__search input {
            width: 100%;
            height: 68px;
            padding: 1rem 5.25rem 1rem 1.45rem;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.99);
            box-shadow: 0 22px 42px rgba(3, 58, 56, 0.2);
            color: #2c3a47;
            font-size: 1rem;
        }

        .blog-hero-v2__search input::placeholder {
            color: #72848d;
        }

        .blog-hero-v2__search-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: linear-gradient(180deg, #ff9a4f 0%, #f27d2d 100%);
            color: #fff;
            transform: translateY(-50%);
            box-shadow: 0 12px 20px rgba(242, 125, 45, 0.28);
        }

        .blog-hero-v2__search-icon svg {
            width: 20px;
            height: 20px;
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

            .blog-hero-v2__search {
                width: 100%;
            }

            .blog-hero-v2__search input {
                height: 60px;
                padding-left: 1.15rem;
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
                    <label class="blog-hero-v2__search" aria-label="Search articles">
                        <span class="sr-only">Search articles</span>
                        <input type="search" placeholder="Search articles..." data-blog-search>
                        <span class="blog-hero-v2__search-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="blog-hero-v2__visual" aria-hidden="true">
            <img src="{{ asset('media/blog/blog_hero.webp') }}" alt="" class="blog-hero-v2__image" width="1000" height="530">
        </div>
    </section>

    <section class="blog-page blog-page--v2">
        <div class="container">
            <div class="blog-filter-bar blog-filter-bar--v2" data-blog-filters>
                @foreach ($categories as $category)
                    <button class="blog-filter-chip{{ $loop->first ? ' is-active' : '' }}" type="button" data-blog-filter="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
            </div>

            <div class="blog-filter-select-wrap">
                <label for="blog-filter-select" class="sr-only">Filter blog posts by category</label>
                <span class="blog-filter-select-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M3 5h18v2H3V5Zm3 6h12v2H6v-2Zm4 6h4v2h-4v-2Z"/></svg>
                </span>
                <select id="blog-filter-select" class="blog-filter-select pro-select" data-blog-filter-select>
                    @foreach ($categories as $category)
                        <option value="{{ strtolower($category) }}"{{ $loop->first ? ' selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <span class="blog-filter-select-caret" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="m7 10 5 5 5-5H7Z"/></svg>
                </span>
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
                                    <span>{{ $post->author_name }}</span>
                                    @if (!empty($post->reading_time))
                                        <span>{{ $post->reading_time }}</span>
                                    @endif
                                    <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
                                </div>
                                <a class="text-link blog-card__read" href="{{ route('blog.show', $post->slug) }}">Read article</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="section-cta-center"><button class="button button--outline-accent button--large" type="button" data-blog-load-more>Load More</button></div>
        </div>
    </section>
@endsection







