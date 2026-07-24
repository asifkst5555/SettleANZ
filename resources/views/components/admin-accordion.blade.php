@props(['title', 'id' => ''])

<div class="sz-accordion-item" data-accordion-wrapper style="border: 1px solid var(--sz-border); border-radius: var(--sz-radius-md); overflow: hidden; margin-bottom: 0.5rem; width: 100%;">
    <button type="button" class="sz-accordion-trigger" onclick="this.closest('[data-accordion-wrapper]').classList.toggle('open')" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--sz-surface); border: none; cursor: pointer; text-align: left;">
        <span style="font-weight: 600; color: var(--sz-text); font-size: 0.875rem;">{{ $title }}</span>
        <span class="sz-accordion-chevron" style="transition: transform var(--sz-transition-fast); display: flex; align-items: center;">
            @include('admin.partials.icon', ['name' => 'chevron-down', 'size' => 16])
        </span>
    </button>
    <div class="sz-accordion-content" style="display: none; padding: 1rem; border-top: 1px solid var(--sz-border); background: var(--sz-bg);">
        {{ $slot }}
    </div>
</div>
