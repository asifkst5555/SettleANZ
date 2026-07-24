@props(['title', 'eyebrow' => null, 'desc' => null])

<div class="sz-page-header">
    <div class="sz-page-header__left">
        @if ($eyebrow)
            <span class="sz-page-header__eyebrow">{{ $eyebrow }}</span>
        @endif
        <h2 class="sz-page-header__title">{{ $title }}</h2>
        @if ($desc)
            <p class="sz-page-header__desc">{{ $desc }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="sz-page-header__actions">
            {{ $actions }}
        </div>
    @endif
</div>
