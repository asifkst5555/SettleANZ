@props(['id', 'title' => null])

<div id="{{ $id }}" class="sz-modal" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" hidden>
    <div class="sz-modal-overlay" onclick="closeModal('{{ $id }}')"></div>
    <div class="sz-modal-box">
        <div class="sz-modal-header">
            @if ($title)
                <h3 id="{{ $id }}-title" class="sz-modal-title">{{ $title }}</h3>
            @endif
            <button type="button" class="sz-modal-close" onclick="closeModal('{{ $id }}')" aria-label="Close dialog">
                @include('admin.partials.icon', ['name' => 'x', 'size' => 18])
            </button>
        </div>
        <div class="sz-modal-body">
            {{ $slot }}
        </div>
    </div>
</div>
