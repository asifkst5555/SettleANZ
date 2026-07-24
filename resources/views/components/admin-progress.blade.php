@props(['value' => 0, 'max' => 100])

@php
    $percentage = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;
@endphp

<div class="sz-progress-container" {{ $attributes }} style="width: 100%; background: var(--sz-surface-hover); border-radius: var(--sz-radius-full); height: 8px; overflow: hidden;">
    <div class="sz-progress-bar" style="height: 100%; background: var(--sz-primary); width: {{ $percentage }}%; transition: width var(--sz-transition-normal);"></div>
</div>
