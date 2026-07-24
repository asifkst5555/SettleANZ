@props(['items' => []])

<nav class="sz-breadcrumb" aria-label="Breadcrumb">
    <ol style="display: flex; gap: 0.5rem; list-style: none; padding: 0; margin: 0; align-items: center; font-size: 0.8rem;">
        @foreach ($items as $label => $url)
            @if (!$loop->last)
                <li>
                    <a href="{{ $url }}" style="color: var(--sz-text-muted); text-decoration: none;">{{ $label }}</a>
                </li>
                <li style="color: var(--sz-text-muted); display: flex; align-items: center;">
                    @include('admin.partials.icon', ['name' => 'chevron-right', 'size' => 12])
                </li>
            @else
                <li style="color: var(--sz-text); font-weight: 600;">
                    {{ $label }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
