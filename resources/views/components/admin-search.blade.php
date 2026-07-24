@props(['placeholder' => 'Search records...'])

<div class="sz-search-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
    <span class="sz-search-icon" style="position: absolute; left: 0.75rem; color: var(--sz-text-muted); display: flex; align-items: center;">
        @include('admin.partials.icon', ['name' => 'search', 'size' => 16])
    </span>
    <input type="search" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'sz-input sz-search-input']) }} style="padding-left: 2.25rem !important; width: 100%;">
</div>
