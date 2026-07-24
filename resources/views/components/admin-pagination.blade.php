@props(['paginator', 'elements' => []])

@if ($paginator->hasPages())
    <nav class="sz-pagination" role="navigation" aria-label="Pagination Navigation" style="display: flex; gap: var(--sz-space-1); align-items: center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="sz-pagination__btn disabled" aria-disabled="true">
                @include('admin.partials.icon', ['name' => 'chevron-left', 'size' => 16])
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="sz-pagination__btn" rel="prev">
                @include('admin.partials.icon', ['name' => 'chevron-left', 'size' => 16])
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="sz-pagination__btn" rel="next">
                @include('admin.partials.icon', ['name' => 'chevron-right', 'size' => 16])
            </a>
        @else
            <span class="sz-pagination__btn disabled" aria-disabled="true">
                @include('admin.partials.icon', ['name' => 'chevron-right', 'size' => 16])
            </span>
        @endif
    </nav>
@endif
