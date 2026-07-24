@props(['name', 'email' => null, 'photo' => null, 'size' => 'md', 'color' => null])

@php
    $initials = '';
    $parts = explode(' ', trim($name));
    foreach ($parts as $part) {
        if (!empty($part)) $initials .= strtoupper($part[0]);
    }
    $initials = substr($initials, 0, 2) ?: '?';

    $sizeClass = match($size) {
        'sm' => 'sz-avatar--sm',
        'lg' => 'sz-avatar--lg',
        default => 'sz-avatar--md',
    };

    if (!$color) {
        $colors = ['#6366f1', '#14a394', '#e8773a', '#7c3aed', '#dc3545', '#0ea5e9', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
        $color = $colors[abs(crc32($email ?? $name)) % count($colors)];
    }

    $gradientMap = [
        '#6366f1' => 'linear-gradient(135deg, #818cf8 0%, #6366f1 100%)',
        '#14a394' => 'linear-gradient(135deg, #2dd4bf 0%, #14a394 100%)',
        '#e8773a' => 'linear-gradient(135deg, #fb923c 0%, #e8773a 100%)',
        '#7c3aed' => 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)',
        '#dc3545' => 'linear-gradient(135deg, #f87171 0%, #dc3545 100%)',
        '#0ea5e9' => 'linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%)',
        '#f59e0b' => 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)',
        '#10b981' => 'linear-gradient(135deg, #34d399 0%, #10b981 100%)',
        '#8b5cf6' => 'linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%)',
        '#ec4899' => 'linear-gradient(135deg, #f472b6 0%, #ec4899 100%)',
    ];
    $gradient = $gradientMap[$color] ?? "linear-gradient(135deg, #94a3b8 0%, #64748b 100%)";
@endphp

<div {{ $attributes->class(['sz-avatar-container', $sizeClass]) }} style="background: {{ $gradient }};">
    @if ($photo)
        <img src="{{ $photo }}" alt="{{ $name }}" class="sz-avatar-image">
    @else
        <span>{{ $initials }}</span>
    @endif
</div>
