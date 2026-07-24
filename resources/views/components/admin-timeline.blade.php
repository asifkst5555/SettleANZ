@props(['activities' => []])

<div class="sz-timeline" style="display: flex; flex-direction: column; gap: 1rem; position: relative;">
    @forelse ($activities as $act)
        <div class="sz-timeline-item" style="position: relative; padding-left: 1.5rem;">
            <span class="sz-timeline-dot" style="position: absolute; left: 4px; top: 6px; width: 8px; height: 8px; border-radius: 50%; background: var(--sz-primary);"></span>
            <div style="display: flex; justify-content: space-between; gap: 0.5rem; align-items: baseline;">
                <strong style="font-size: 0.85rem; color: var(--sz-text);">{{ $act['label'] ?? $act['title'] }}</strong>
                <span style="font-size: 0.725rem; color: var(--sz-text-muted);">{{ \Carbon\Carbon::parse($act['time'] ?? $act['created_at'])->diffForHumans() }}</span>
            </div>
            <div style="font-size: 0.775rem; color: var(--sz-text-muted); margin-top: 0.15rem;">
                {{ $act['description'] ?? $act['message'] ?? '' }}
                @if (isset($act['user']))
                    &bull; <small>by {{ $act['user'] }}</small>
                @endif
            </div>
        </div>
    @empty
        <x-admin-empty-state title="No activity logged yet" desc="System logs and movements will display here." icon="activity" />
    @endforelse
</div>
