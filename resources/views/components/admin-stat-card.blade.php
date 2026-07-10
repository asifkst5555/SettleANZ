@props(['label', 'value', 'desc' => null, 'accent' => false])

<div {{ $attributes->class(['admin-brand-stat-card', 'accent' => $accent]) }}>
    <div class="admin-brand-stat-card__label">{{ $label }}</div>
    <div class="admin-brand-stat-card__value">{{ $value }}</div>
    @if ($desc)
        <div class="admin-brand-stat-card__desc">{{ $desc }}</div>
    @endif
</div>
