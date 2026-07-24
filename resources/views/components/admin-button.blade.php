@props(['variant' => 'primary', 'size' => 'md'])

@php
    $classes = match($variant) {
        'primary' => 'sz-btn sz-btn--primary',
        'secondary' => 'sz-btn sz-btn--secondary',
        'danger' => 'sz-btn sz-btn--danger',
        'ghost' => 'sz-btn sz-btn--ghost',
        default => 'sz-btn sz-btn--primary',
    };

    $classes .= match($size) {
        'sm' => ' sz-btn--sm',
        'lg' => ' sz-btn--lg',
        default => '',
    };
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
