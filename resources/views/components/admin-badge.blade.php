@props(['color' => 'gray'])

<span {{ $attributes->class(["admin-badge", "admin-badge--{$color}"]) }}>
    {{ $slot }}
</span>
