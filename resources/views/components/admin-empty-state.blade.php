@props(['title' => 'No Data Found', 'desc' => 'We couldn\'t find any records here.', 'icon' => 'inbox'])

<div class="sz-empty">
    <div class="sz-empty-icon" style="color: var(--sz-primary); display: flex; justify-content: center; margin-bottom: 1rem;">
        @include('admin.partials.icon', ['name' => $icon, 'size' => 64, 'strokeWidth' => 1.2])
    </div>
    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--sz-text); margin-bottom: 0.5rem;">{{ $title }}</h3>
    <p style="color: var(--sz-muted); font-size: 0.875rem; max-width: 320px; margin: 0 auto 1.5rem;">{{ $desc }}</p>
    @if (isset($actions))
        <div class="sz-empty-actions">
            {{ $actions }}
        </div>
    @endif
</div>
