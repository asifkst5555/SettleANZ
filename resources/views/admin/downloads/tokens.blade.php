@extends('admin.layouts.app')

@section('page-title', 'Download Tokens')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Download Tokens</h2>
            <p>Manage secure download links issued to leads</p>
        </div>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border);">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Status</label>
                    <select name="status" class="admin-form-select">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                        <option value="revoked" @selected(request('status') === 'revoked')>Revoked</option>
                        <option value="exhausted" @selected(request('status') === 'exhausted')>Exhausted</option>
                    </select>
                </div>
                <button type="submit" class="admin-action-btn" style="background:#e2e8f0;color:#1e293b;">Filter</button>
            </form>
        </div>

        @if($tokens->count() > 0)
        <div class="admin-table-wrap" style="border:none;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Ebook</th>
                        <th>Lead</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th style="text-align:right;">Actions</th>
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
                            @php
                                $badge = match($token->status) {
                                    'active' => 'admin-badge--green',
                                    'expired' => 'admin-badge--orange',
                                    'revoked' => 'admin-badge--red',
                                    default => 'admin-badge--gray'
                                };
                            @endphp
                            <span class="admin-badge {{ $badge }}">{{ ucfirst($token->status) }}</span>
                        </td>
                        <td data-label="Expires" style="color:#6b7280;">{{ $token->expires_at ? $token->expires_at->diffForHumans() : 'Never' }}</td>
                        <td data-label="Actions" style="text-align:right;">
                            @if($token->status === 'active')
                            <form action="{{ route('admin.downloads.tokens.revoke', $token) }}" method="POST" onsubmit="return confirm('Revoke this token?')" style="margin:0;display:inline-flex;">
                                @csrf
                                <button type="submit" class="admin-action-btn admin-action-btn--revoke">
                                    <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 4l6 6m0 0l6-6M7 10V2"/></svg>
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
        <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">
            {{ $tokens->links() }}
        </div>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state__icon">🔑</div>
            <h3 class="admin-empty-state__title">No download tokens yet</h3>
            <p class="admin-empty-state__text">Tokens are generated automatically when users submit the lead capture form.</p>
        </div>
        @endif
    </section>
</div>
@endsection
