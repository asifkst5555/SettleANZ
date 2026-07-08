@extends('admin.layouts.app')

@section('page-title', 'Download Logs')

@section('content')
<style>
    .dl-table { width:100%; border-collapse:collapse; }
    .dl-table thead { background:#f3f4f6; }
    .dl-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .dl-table th:first-child { padding-left:0.75rem; }
    .dl-table th:last-child { padding-right:0.75rem; }
    .dl-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; font-size:0.875rem; }
    .dl-table td:first-child { padding-left:0.75rem; }
    .dl-table td:last-child { padding-right:0.75rem; }
    .dl-table tbody tr:hover { background:#f9fafb; }
    .dl-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; overflow-x:auto; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Download Logs</h2>
            <p>Track every ebook download with IP, user agent, and timestamp</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
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
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                </div>
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                </div>
                <button type="submit" style="padding:0.5rem 1rem;border:1px solid #d7e1ea;border-radius:0.375rem;background:white;cursor:pointer;font-weight:500;">Filter</button>
            </form>
        </div>

        @if($downloads->count() > 0)
        <div class="dl-wrap" style="border:none;">
            <table class="dl-table">
                <thead>
                    <tr>
                        <th>Ebook</th>
                        <th>Lead</th>
                        <th>IP</th>
                        <th>User Agent</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($downloads as $download)
                    <tr>
                        <td data-label="Ebook" style="font-weight:500;">{{ $download->ebook?->title ?? '—' }}</td>
                        <td data-label="Lead">
                            <div>{{ $download->lead?->full_name ?? '—' }}</div>
                            <div style="font-size:0.8125rem;color:#6b7280;">{{ $download->lead?->email ?? '' }}</div>
                        </td>
                        <td data-label="IP" style="font-family:monospace;font-size:0.8125rem;color:#6b7280;">{{ $download->ip_address ?? '—' }}</td>
                        <td data-label="User Agent" style="font-size:0.8125rem;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $download->user_agent ?? '—' }}</td>
                        <td data-label="Date" style="color:#6b7280;">{{ $download->created_at->format('M j, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.5rem;border-top:1px solid #e5e7eb;">
            {{ $downloads->links() }}
        </div>
        @else
        <div style="text-align:center;padding:3rem 1rem;color:#6b7280;">
            <div style="font-size:3rem;margin-bottom:1rem;">📥</div>
            <h3>No download logs yet</h3>
            <p style="color:#6b7280;">Download logs will appear here when users start downloading your ebooks.</p>
        </div>
        @endif
    </section>
</div>
@endsection
