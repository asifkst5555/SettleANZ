@extends('admin.layouts.app')

@section('page-title', 'Login History')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Login History</h2>
            <p>All login and logout events across the system.</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Event</th>
                        <th>IP Address</th>
                        <th>Browser / Device</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $entry)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.4rem">
                                    <div class="admin-profile__avatar" style="width:26px;height:26px;font-size:0.75rem">
                                        {{ substr($entry->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <small>{{ $entry->user?->name ?? 'Deleted User' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="admin-badge {{ $entry->event === 'login' ? 'admin-badge--success' : ($entry->event === 'failed' ? 'admin-badge--error' : 'admin-badge--muted') }}">
                                    {{ ucfirst($entry->event) }}
                                </span>
                            </td>
                            <td><small>{{ $entry->ip_address }}</small></td>
                            <td>
                                <small>{{ $entry->browser ?? 'Unknown' }}</small>
                                @if ($entry->platform)
                                    <small style="color:var(--admin-muted)"> / {{ $entry->platform }}</small>
                                @endif
                            </td>
                            <td><small>{{ $entry->created_at->format('d M Y H:i:s') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-muted)">No login history yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $history->links() }} </div>
    </section>
</div>
@endsection