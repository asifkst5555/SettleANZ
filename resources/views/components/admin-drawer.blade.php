@props(['id', 'title' => null])

<div id="{{ $id }}" class="sz-drawer" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" hidden>
    <div class="sz-drawer-overlay" onclick="closeDrawer('{{ $id }}')"></div>
    <div class="sz-drawer-box">
        <div class="sz-drawer-header">
            @if ($title)
                <h3 id="{{ $id }}-title" class="sz-drawer-title">{{ $title }}</h3>
            @endif
            <button type="button" class="sz-drawer-close" onclick="closeDrawer('{{ $id }}')" aria-label="Close panel">
                @include('admin.partials.icon', ['name' => 'x', 'size' => 18])
            </button>
        </div>
        <div class="sz-drawer-body">
            {{ $slot }}
        </div>
    </div>
</div>
