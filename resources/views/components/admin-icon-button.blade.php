@props(['icon', 'variant' => 'secondary', 'size' => 'md'])

@php
    $classes = match($variant) {
        'primary' => 'sz-btn sz-btn--primary sz-btn--icon',
        'secondary' => 'sz-btn sz-btn--secondary sz-btn--icon',
        'danger' => 'sz-btn sz-btn--danger sz-btn--icon',
        'ghost' => 'sz-btn sz-btn--ghost sz-btn--icon',
        default => 'sz-btn sz-btn--secondary sz-btn--icon',
    };

    $classes .= match($size) {
        'sm' => ' sz-btn--sm',
        'lg' => ' sz-btn--lg',
        default => '',
    };
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    @include('admin.partials.icon', ['name' => $icon, 'size' => $size === 'sm' ? 14 : ($size === 'lg' ? 20 : 16)])
    @if ($slot->isNotEmpty())
        <span style="margin-left: 0.35rem;">{{ $slot }}</span>
    @endif
</button>
