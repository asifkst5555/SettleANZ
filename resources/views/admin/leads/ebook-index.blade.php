@extends('admin.layouts.app')

@section('page-title', 'Ebook Leads')

@section('content')
<style>
    .el-table { width:100%; border-collapse:collapse; }
    .el-table thead { background:#f3f4f6; }
    .el-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .el-table th:first-child { padding-left:0.75rem; }
    .el-table th:last-child { padding-right:0.75rem; text-align:right; }
    .el-table th:not(:first-child), .el-table td:not(:first-child) { white-space:nowrap; width:1%; }
    .el-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; font-size:0.875rem; }
    .el-table td:first-child { padding-left:0.75rem; white-space:normal; width:auto; }
    .el-table td:last-child { padding-right:0.75rem; text-align:right; }
    .el-table tbody tr:hover { background:#f9fafb; }
    .el-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; overflow-x:auto; }
    .el-actions-cell { display:inline-flex; flex-wrap:wrap; gap:0.45rem; align-items:center; }
    .el-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; transition:background 0.2s,color 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; white-space:nowrap; }
    .el-empty-state { text-align:center; padding:3rem 1rem; }
    @media (max-width:1100px) { .el-wrap { overflow-x:auto; } .el-table { min-width:700px; } }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Ebook Leads</h2>
            <p>View all leads generated through ebook downloads and landing pages</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Status</label>
                    <select name="status" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                        <option value="">All</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s->value ?? $s }}" @selected(request('status') === ($s->value ?? $s))>{{ ucfirst($s->value ?? $s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Ebook</label>
                    <select name="ebook_id" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                        <option value="">All</option>
                        @foreach($ebooks as $ebook)
                        <option value="{{ $ebook->id }}" @selected(request('ebook_id') == $ebook->id)>{{ $ebook->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;font-size:0.875rem;width:220px;">
                </div>
                <button type="submit" style="padding:0.5rem 1rem;border:1px solid #d7e1ea;border-radius:0.375rem;background:white;cursor:pointer;font-weight:500;">Filter</button>
            </form>
        </div>

        @if($leads->count() > 0)
        <div class="el-wrap" style="border:none;">
            <table class="el-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Ebook</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leads as $lead)
                    <tr>
                        <td data-label="Name" style="font-weight:500;">{{ $lead->full_name }}</td>
                        <td data-label="Email" style="color:#6b7280;">{{ $lead->email }}</td>
                        <td data-label="Ebook" style="color:#6b7280;">{{ $lead->ebook?->title ?? '—' }}</td>
                        <td data-label="Status">
                            <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:0.25rem;font-size:0.8125rem;font-weight:500;
                                @if(($lead->status->value ?? $lead->status) === 'qualified') background:#d1fae5;color:#065f46;
                                @elseif(($lead->status->value ?? $lead->status) === 'new') background:#dbeafe;color:#1e40af;
                                @elseif(($lead->status->value ?? $lead->status) === 'downloaded') background:#fef3c7;color:#92400e;
                                @else background:#f3f4f6;color:#6b7280; @endif">
                                {{ ucfirst($lead->status->value ?? $lead->status) }}
                            </span>
                        </td>
                        <td data-label="Date" style="color:#6b7280;">{{ $lead->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions">
                            <div class="el-actions-cell">
                                <a href="{{ route('admin.ebook-leads.show', $lead) }}" class="el-action-btn" style="background:#e0e7ff;color:#3730a3;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.5rem;border-top:1px solid #e5e7eb;">
            {{ $leads->links() }}
        </div>
        @else
        <div class="el-empty-state">
            <div style="font-size:3rem;margin-bottom:1rem;">👤</div>
            <h3>No ebook leads yet</h3>
            <p style="color:#6b7280;">Leads will appear here when users submit the download form on your ebook landing pages.</p>
        </div>
        @endif
    </section>
</div>
@endsection
