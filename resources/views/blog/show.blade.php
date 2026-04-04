@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero article-hero">
        <div class="container article-hero__inner">
            <p class="banking-disclosure">Disclosure: This article may include affiliate links. We may earn a commission at no extra cost to you.</p>
            <p class="eyebrow">{{ $post->category }}</p>
            <h1>{{ $post->title }}</h1>
            <div class="article-hero__meta">
                <span>{{ $post->author_name }}</span>
                <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
                <span>{{ $post->reading_time }}</span>
            </div>
            @php($heroImagePath = !empty($post->image) ? public_path('media/blog/' . $post->image) : null)
            @if (!empty($post->image) && file_exists($heroImagePath))
                <img class="article-hero__image article-hero__image--file" src="{{ asset('media/blog/' . $post->image) }}" alt="{{ $post->title }}">
            @else
                <div class="article-hero__image {{ $post->image_class }}" aria-hidden="true"></div>
            @endif
        </div>
    </section>

    <section class="article-page">
        <div class="container article-layout">
            <aside class="article-sidebar">
                <div class="guide-sidebar__panel">
                    <p class="guide-sidebar__title">On this page</p>
                    <nav class="guide-toc" aria-label="Article table of contents">
                        @foreach ($tocItems as $item)
                            <a href="#{{ $item['id'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </nav>
                </div>

                <div class="guide-share" aria-label="Share this article">
                    <span>Share</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noreferrer">Facebook</a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noreferrer">WhatsApp</a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noreferrer">LinkedIn</a>
                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noreferrer">Pinterest</a>
                </div>
            </aside>

            <article class="article-content">
                <section id="why-it-matters" class="guide-block guide-block--white">
                    <h2>Why It Matters</h2>
                    <p>{{ $post->intro_content ?: $post->excerpt }}</p>
                    <p>{{ $post->excerpt }} This article is written to be practical first, helping a newcomer understand the decision, the common risks, and the fastest next step.</p>
                </section>

                <section id="what-to-check" class="guide-block guide-block--sand">
                    <h2>What to Check</h2>
                    <p>{{ $post->checks_content ?: 'Start by looking at timing, cost, setup friction, and whether the option still makes sense after your first few weeks on the ground.' }}</p>
                    <ul class="guide-list">
                        <li>Check the practical steps that must be handled before you land.</li>
                        <li>Separate urgent setup tasks from nice-to-have optimisations.</li>
                        <li>Use trusted providers or internal guides when the next decision has money or compliance implications.</li>
                    </ul>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Want the shorter version?</h3>
                        <p>Use the main guide pages when you need a faster summary with the key actions and decisions already prioritised.</p>
                        <a class="text-link" href="{{ route('guides.new-to-australia') }}">Go to the newcomer guide</a>
                    </div>
                </section>

                <section id="best-next-step" class="guide-block guide-block--white">
                    <h2>Best Next Step</h2>
                    <p>{{ $post->next_steps_content ?: 'The strongest next step is usually one of three things: choose a trusted tool, move to a deeper guide, or submit your details when the decision is complex enough to need support.' }}</p>
                    <div class="guide-cta-box">
                        <h3>Need help with the next move?</h3>
                        <p>Get the SettleANZ starter guide or move directly into Housing, Banking, or Migration support depending on your situation.</p>
                        <button class="button button--small" type="button" data-open-lead-modal>Get free help</button>
                    </div>
                </section>

                <section class="article-author-box">
                    <div class="founder-photo-placeholder article-author-box__photo"><span>SA</span></div>
                    <div>
                        <h3>About the author</h3>
                        <p>SettleANZ content is shaped by real relocation experience, practical research, and the day-to-day decisions new arrivals actually need help making.</p>
                        <div class="social-row">
                            <a href="#" aria-label="Facebook">Fb</a>
                            <a href="#" aria-label="Instagram">Ig</a>
                            <a href="#" aria-label="LinkedIn">In</a>
                        </div>
                    </div>
                </section>

                @if ($relatedPosts->count())
                    <section class="related-posts">
                        <h2>Related articles</h2>
                        <div class="blog-grid blog-grid--related">
                            @foreach ($relatedPosts as $relatedPost)
                                @php($relatedImagePath = !empty($relatedPost->image) ? public_path('media/blog/' . $relatedPost->image) : null)
                                <article class="blog-card">
                                    @if (!empty($relatedPost->image) && file_exists($relatedImagePath))
                                        <img class="blog-card__image blog-card__image--file" src="{{ asset('media/blog/' . $relatedPost->image) }}" alt="{{ $relatedPost->title }}">
                                    @else
                                        <div class="blog-card__image {{ $relatedPost->image_class }}" aria-hidden="true"></div>
                                    @endif
                                    <div class="blog-card__body">
                                        <p class="blog-card__tag">{{ $relatedPost->category }}</p>
                                        <h3>{{ $relatedPost->title }}</h3>
                                        <p>{{ $relatedPost->excerpt }}</p>
                                        <a class="text-link" href="{{ route('blog.show', $relatedPost->slug) }}">Read more</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </article>
        </div>
    </section>
@endsection
