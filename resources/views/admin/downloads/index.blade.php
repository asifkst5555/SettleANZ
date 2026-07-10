@extends('admin.layouts.app')

@section('page-title', 'Download Logs')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Download Logs</h2>
            <p>Track every ebook download with IP, user agent, and timestamp</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border);">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Ebook</label>
                    <select name="ebook_id" class="admin-form-select">
                        <option value="">All</option>
                        @foreach($ebooks as $ebook)
                        <option value="{{ $ebook->id }}" @selected(request('ebook_id') == $ebook->id)>{{ $ebook->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-form-input">
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-form-input">
                </div>
                <button type="submit" class="admin-action-btn" style="background:#e2e8f0;color:#1e293b;">Filter</button>
            </form>
        </div>

        @if($downloads->count() > 0)
        <div class="admin-table-wrap" style="border:none;">
            <table class="admin-table">
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
        <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">
            {{ $downloads->links() }}
        </div>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state__icon">📥</div>
            <h3 class="admin-empty-state__title">No download logs yet</h3>
            <p class="admin-empty-state__text">Download logs will appear here when users start downloading your ebooks.</p>
        </div>
        @endif
    </section>
</div>
@endsection
