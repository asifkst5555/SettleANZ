@extends('admin.layouts.app')

@section('page-title', 'Activity: ' . $user->name)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Users</p>
            <h2>Activity Log: {{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.users.edit', $user) }}">Back to User</a>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Entity</th>
                        <th>IP Address</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $log)
                        <tr>
                            <td><span class="admin-badge">{{ $log->action }}</span></td>
                            <td><small>{{ $log->description }}</small></td>
                            <td><small>{{ $log->entity_type }} {{ $log->entity_id ? '#' . $log->entity_id : '' }}</small></td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td><small>{{ $log->created_at->format('d M Y H:i:s') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-muted)">No activity found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $activities->links() }} </div>
    </section>
</div>
@endsection