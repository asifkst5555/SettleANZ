@props(['type' => 'success', 'id' => ''])

<div id="{{ $id }}" {{ $attributes->class(['sz-toast', 'sz-toast--' . $type]) }} role="status" aria-live="polite">
    <span class="sz-toast__icon">
        @include('admin.partials.icon', ['name' => $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info'), 'size' => 16])
    </span>
    <span class="sz-toast__message" style="flex-grow: 1;">{{ $slot }}</span>
    <button type="button" class="sz-toast__close" onclick="this.parentElement.remove()" aria-label="Close notification" style="background: none; border: none; cursor: pointer; padding: 0.15rem; opacity: 0.8; margin-left: auto;">
        @include('admin.partials.icon', ['name' => 'x', 'size' => 14])
    </button>
</div>
