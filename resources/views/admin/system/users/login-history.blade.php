@extends('admin.layouts.app')

@section('page-title', 'Login History: ' . $user->name)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Users</p>
            <h2>Login History: {{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.users.edit', $user) }}">Back to User</a>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>IP Address</th>
                        <th>Browser</th>
                        <th>Platform</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $entry)
                        <tr>
                            <td>
                                <span class="admin-badge {{ $entry->event === 'login' ? 'admin-badge--success' : ($entry->event === 'failed' ? 'admin-badge--error' : 'admin-badge--muted') }}">
                                    {{ ucfirst($entry->event) }}
                                </span>
                            </td>
                            <td><small>{{ $entry->ip_address }}</small></td>
                            <td><small>{{ Str::limit($entry->browser ?? $entry->user_agent, 40) }}</small></td>
                            <td><small>{{ $entry->platform ?? 'Unknown' }}</small></td>
                            <td><small>{{ $entry->created_at->format('d M Y H:i:s') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-muted)">No login history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $history->links() }} </div>
    </section>
</div>
@endsection