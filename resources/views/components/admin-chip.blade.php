@props(['color' => 'gray', 'label', 'dismissible' => false, 'onDismiss' => ''])

<div {{ $attributes->class(['sz-chip', 'sz-chip--' . $color]) }} style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: var(--sz-radius-full); font-size: 0.725rem; font-weight: 500;">
    <span>{{ $label }}</span>
    @if ($dismissible)
        <button type="button" class="sz-chip__close" onclick="{{ $onDismiss ?: 'this.parentElement.remove()' }}" style="background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; color: inherit; opacity: 0.7;">
            @include('admin.partials.icon', ['name' => 'x', 'size' => 12])
        </button>
    @endif
</div>
