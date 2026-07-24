@props(['color' => 'gray'])

@php
    $colorClass = match($color) {
        'primary' => 'sz-tag--primary',
        'success' => 'sz-tag--success',
        'warning' => 'sz-tag--warning',
        'danger' => 'sz-tag--danger',
        'info' => 'sz-tag--info',
        default => 'sz-tag--gray',
    };
@endphp

<span {{ $attributes->class(['sz-tag', $colorClass]) }}>
    {{ $slot }}
</span>
