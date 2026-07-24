@props(['type' => 'info', 'title' => null, 'dismissible' => false])

@php
    $typeClass = match($type) {
        'success' => 'sz-alert--success',
        'warning' => 'sz-alert--warning',
        'danger' => 'sz-alert--danger',
        'info' => 'sz-alert--info',
        default => 'sz-alert--info',
    };

    $icon = match($type) {
        'success' => 'check-circle',
        'warning' => 'alert-triangle',
        'danger' => 'alert-octagon',
        'info' => 'info',
        default => 'info',
    };
@endphp

<div {{ $attributes->class(['sz-alert', $typeClass]) }} role="alert">
    <span class="sz-alert__icon">
        @include('admin.partials.icon', ['name' => $icon, 'size' => 18])
    </span>
    <div class="sz-alert__content">
        @if ($title)
            <strong class="sz-alert__title" style="display: block; margin-bottom: 0.15rem;">{{ $title }}</strong>
        @endif
        <div class="sz-alert__body">{{ $slot }}</div>
    </div>
    @if ($dismissible)
        <button type="button" class="sz-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert" style="background: none; border: none; cursor: pointer; padding: 0.25rem; margin-left: auto;">
            @include('admin.partials.icon', ['name' => 'x', 'size' => 16])
        </button>
    @endif
</div>
