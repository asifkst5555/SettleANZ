@extends('admin.layouts.app')

@section('page-title', 'Download Tokens')

@section('content')
<style>
    .tk-table { width:100%; border-collapse:collapse; }
    .tk-table thead { background:#f3f4f6; }
    .tk-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .tk-table th:first-child { padding-left:0.75rem; }
    .tk-table th:last-child { padding-right:0.75rem; text-align:right; }
    .tk-table th:not(:first-child), .tk-table td:not(:first-child) { white-space:nowrap; width:1%; }
    .tk-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; font-size:0.875rem; }
    .tk-table td:first-child { padding-left:0.75rem; }
    .tk-table td:last-child { padding-right:0.75rem; text-align:right; }
    .tk-table tbody tr:hover { background:#f9fafb; }
    .tk-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; overflow-x:auto; }
    .tk-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; white-space:nowrap; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Download Tokens</h2>
            <p>Manage secure download links issued to leads</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Status</label>
                    <select name="status" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                        <option value="revoked" @selected(request('status') === 'revoked')>Revoked</option>
                        <option value="exhausted" @selected(request('status') === 'exhausted')>Exhausted</option>
                    </select>
                </div>
                <button type="submit" style="padding:0.5rem 1rem;border:1px solid #d7e1ea;border-radius:0.375rem;background:white;cursor:pointer;font-weight:500;">Filter</button>
            </form>
        </div>

        @if($tokens->count() > 0)
        <div class="tk-wrap" style="border:none;">
            <table class="tk-table">
                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Ebook</th>
                        <th>Lead</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tokens as $token)
                    <tr>
                        <td data-label="Token" style="font-family:monospace;font-size:0.8125rem;">{{ substr($token->token, 0, 12) }}...</td>
                        <td data-label="Ebook">{{ $token->ebook?->title ?? '—' }}</td>
                        <td data-label="Lead">{{ $token->lead?->full_name ?? '—' }}</td>
                        <td data-label="Downloads">{{ $token->download_count }}/{{ $token->max_downloads }}</td>
                        <td data-label="Status">
                            <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:0.25rem;font-size:0.8125rem;font-weight:500;
                                @if($token->status === 'active') background:#d1fae5;color:#065f46;
                                @elseif($token->status === 'expired') background:#fef3c7;color:#92400e;
                                @elseif($token->status === 'revoked') background:#fee2e2;color:#7f1d1d;
                                @else background:#f3f4f6;color:#6b7280; @endif">
                                {{ ucfirst($token->status) }}
                            </span>
                        </td>
                        <td data-label="Expires" style="color:#6b7280;">{{ $token->expires_at ? $token->expires_at->diffForHumans() : 'Never' }}</td>
                        <td data-label="Actions">
                            @if($token->status === 'active')
                            <form action="{{ route('admin.downloads.tokens.revoke', $token) }}" method="POST" onsubmit="return confirm('Revoke this token?')" style="margin:0;display:inline-flex;">
                                @csrf
                                <button type="submit" class="tk-action-btn" style="background:#fee2e2;color:#7f1d1d;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 4l6 6m0 0l6-6M7 10V2"/></svg>
                                    Revoke
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.5rem;border-top:1px solid #e5e7eb;">
            {{ $tokens->links() }}
        </div>
        @else
        <div style="text-align:center;padding:3rem 1rem;color:#6b7280;">
            <div style="font-size:3rem;margin-bottom:1rem;">🔑</div>
            <h3>No download tokens yet</h3>
            <p style="color:#6b7280;">Tokens are generated automatically when users submit the lead capture form.</p>
        </div>
        @endif
    </section>
</div>
@endsection
