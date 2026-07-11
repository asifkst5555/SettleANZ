@extends('admin.layouts.app')

@section('page-title', 'Activity Logs')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Activity Logs</h2>
            <p>Track every action performed in the system.</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Entity</th>
                        <th>IP Address</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.4rem">
                                    <div class="admin-profile__avatar" style="width:26px;height:26px;font-size:0.75rem">
                                        {{ substr($log->user?->name ?? 'S', 0, 1) }}
                                    </div>
                                    <small>{{ $log->user?->name ?? 'System' }}</small>
                                </div>
                            </td>
                            <td><span class="admin-badge">{{ $log->action }}</span></td>
                            <td>
                                <a href="{{ route('admin.system.activity-logs.show', $log) }}" class="text-link" style="font-size:0.88rem">
                                    {{ Str::limit($log->description, 60) }}
                                </a>
                            </td>
                            <td><small>{{ $log->entity_type }} {{ $log->entity_id ? '#' . $log->entity_id : '' }}</small></td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td><small>{{ $log->created_at->format('d M Y H:i:s') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--admin-muted)">No activity logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $logs->links() }} </div>
    </section>
</div>
@endsection