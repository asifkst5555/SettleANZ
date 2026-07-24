@props(['tabs' => [], 'active' => null])

<div class="sz-tabs-container">
    <div class="sz-tabs" role="tablist" style="display: flex; gap: 1rem; border-bottom: 1px solid var(--sz-border); padding-bottom: 2px; margin-bottom: 1rem;">
        @foreach ($tabs as $id => $label)
            <button type="button" class="sz-tab-btn {{ $active === $id ? 'active' : '' }}" role="tab" aria-selected="{{ $active === $id ? 'true' : 'false' }}" data-tab-target="{{ $id }}" style="background: none; border: none; font-size: 0.875rem; padding: 0.5rem 0.25rem; cursor: pointer; color: var(--sz-text-muted); font-weight: 500; border-bottom: 2px solid transparent; transition: all var(--sz-transition-fast);">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>
