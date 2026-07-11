@extends('admin.layouts.app')

@section('page-title', 'Activity Log Detail')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Activity Logs</p>
            <h2>Activity Log Detail</h2>
        </div>
        <a class="button button--small" href="{{ route('admin.system.activity-logs.index') }}">Back to Logs</a>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
        <section class="admin-panel-card">
            <h3 style="margin-top:0">Details</h3>
            <div style="display:grid;gap:0.75rem">
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>Action</span>
                    <span class="admin-badge">{{ $activityLog->action }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>User</span>
                    <span>{{ $activityLog->user?->name ?? 'System' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>Description</span>
                    <span>{{ $activityLog->description }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>Entity</span>
                    <span>{{ $activityLog->entity_type }} {{ $activityLog->entity_id ? '#' . $activityLog->entity_id : '' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>Date/Time</span>
                    <span>{{ $activityLog->created_at->format('d M Y H:i:s') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                    <span>IP Address</span>
                    <span>{{ $activityLog->ip_address }}</span>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <h3 style="margin-top:0">User Agent</h3>
            <p style="font-size:0.82rem;word-break:break-all;color:var(--admin-muted)">{{ $activityLog->user_agent ?? 'N/A' }}</p>

            @if ($activityLog->old_values || $activityLog->new_values)
                <h3 style="margin-top:1.5rem">Changes</h3>
                @if ($activityLog->old_values)
                    <div style="margin-top:0.5rem">
                        <strong style="font-size:0.85rem">Old Values</strong>
                        <pre style="background:rgba(16,88,98,0.04);padding:0.75rem;border-radius:8px;font-size:0.78rem;overflow-x:auto;margin-top:0.25rem">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
                @if ($activityLog->new_values)
                    <div style="margin-top:0.5rem">
                        <strong style="font-size:0.85rem">New Values</strong>
                        <pre style="background:rgba(16,88,98,0.04);padding:0.75rem;border-radius:8px;font-size:0.78rem;overflow-x:auto;margin-top:0.25rem">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            @endif
        </section>
    </div>
</div>
@endsection