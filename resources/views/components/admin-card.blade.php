@props(['title' => null, 'desc' => null])

<div {{ $attributes->merge(['class' => 'sz-card']) }}>
    @if ($title || $desc)
        <div class="sz-card__header">
            @if ($title)
                <h3 class="sz-card__title">{{ $title }}</h3>
            @endif
            @if ($desc)
                <p class="sz-card__desc">{{ $desc }}</p>
            @endif
        </div>
    @endif
    <div class="sz-card__body">
        {{ $slot }}
    </div>
</div>
