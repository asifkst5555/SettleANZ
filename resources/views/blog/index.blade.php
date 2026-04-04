@extends('layouts.app')

@section('content')
<section id="top" class="blog-hero-v2">
        <div class="blog-hero-v2__visual" aria-hidden="true">
            <img src="{{ asset('media/blog/blog_hero.webp') }}" alt="" class="blog-hero-v2__image">
        </div>
        <div class="container">
            <div class="blog-hero-v2__inner">
                <div class="blog-hero-v2__content">
                    <p class="blog-hero-v2__eyebrow">SettleANZ Blog</p>
                    <h1>Your Guide to a Better Life in Australia</h1>
                    <p class="blog-hero-v2__copy">Practical guides, honest advice, and real insights for expats in Australia and New Zealand.</p>
                    <label class="blog-hero-v2__search" aria-label="Search articles">
                        <span class="sr-only">Search articles</span>
                        <input type="search" placeholder="Search articles..." data-blog-search>
                        <span class="blog-hero-v2__search-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-page blog-page--v2">
        <div class="container">
            <div class="blog-filter-bar blog-filter-bar--v2" data-blog-filters>
                @foreach ($categories as $category)
                    <button class="blog-filter-chip{{ $loop->first ? ' is-active' : '' }}" type="button" data-blog-filter="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
            </div>

            <div class="blog-grid blog-grid--v2" data-blog-grid>
                @foreach ($posts as $post)
                    @php($blogImagePath = !empty($post->image) ? public_path('media/blog/' . $post->image) : null)
                    <article class="blog-card blog-card--v2{{ $loop->index > 5 ? ' is-hidden' : '' }}" data-blog-post data-category="{{ strtolower($post->category) }}" data-search="{{ strtolower(trim($post->title . ' ' . $post->category . ' ' . $post->excerpt . ' ' . ($post->author_name ?? ''))) }}">
                        <a class="blog-card__media-link" href="{{ route('blog.show', $post->slug) }}">
                            @if (!empty($post->image) && file_exists($blogImagePath))
                                <img class="blog-card__image blog-card__image--file" src="{{ asset('media/blog/' . $post->image) }}" alt="{{ $post->title }}">
                            @else
                                <div class="blog-card__image {{ $post->image_class }}" aria-hidden="true"></div>
                            @endif
                        </a>
                        <div class="blog-card__body blog-card__body--v2">
                            <p class="blog-card__tag">{{ $post->category }}</p>
                            <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                            <p>{{ $post->excerpt }}</p>
                            <div class="blog-card__meta">
                                <span>{{ $post->author_name }}</span>
                                @if (!empty($post->reading_time))
                                    <span>{{ $post->reading_time }}</span>
                                @endif
                                <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
                            </div>
                            <a class="text-link" href="{{ route('blog.show', $post->slug) }}">Read article</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="section-cta-center"><button class="button button--outline-accent button--large" type="button" data-blog-load-more>Load More</button></div>
        </div>
    </section>
@endsection







