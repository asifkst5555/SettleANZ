@props(['label', 'value', 'desc' => null, 'trend' => null, 'trendDir' => 'up', 'icon' => null, 'iconBg' => null, 'iconColor' => null])

<div {{ $attributes->class(['kpi-card']) }}>
    <div class="kpi-card__header">
        <span class="kpi-card__label">{{ $label }}</span>
        @if ($icon)
            <span class="kpi-card__icon" style="background: {{ $iconBg ?? 'rgba(20, 163, 148, 0.08)' }}; color: {{ $iconColor ?? '#14a394' }};">
                @include('admin.partials.icon', ['name' => $icon, 'size' => 18])
            </span>
        @endif
    </div>
    <div class="kpi-card__body">
        <div class="kpi-card__value">{{ $value }}</div>
        @if ($trend)
            <div class="kpi-card__trend {{ $trendDir }}">
                @include('admin.partials.icon', ['name' => $trendDir === 'up' ? 'trending-up' : ($trendDir === 'down' ? 'trending-down' : 'info'), 'size' => 14])
                <span>{{ $trend }}</span>
            </div>
        @elseif ($desc)
            <div class="kpi-card__desc">{{ $desc }}</div>
        @endif
    </div>
</div>
