@extends('admin.layouts.app')

@section('page-title', 'Campaigns')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Campaigns</h2>
            <p>Create and manage email campaigns for targeted ebook promotions</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="admin-primary-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Campaign
        </a>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border);">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Status</label>
                    <select name="status" class="admin-form-select">
                        <option value="">All</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-action-btn" style="background:#e2e8f0;color:#1e293b;">Filter</button>
            </form>
        </div>

        @if($campaigns->count() > 0)
        <div class="admin-table-wrap" style="border:none;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Ebook</th>
                        <th>Template</th>
                        <th>Sent / Open / Click</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                    <tr>
                        <td data-label="Name" style="font-weight:500;">{{ $campaign->name }}</td>
                        <td data-label="Ebook" style="color:#6b7280;">{{ $campaign->ebook?->title ?? '—' }}</td>
                        <td data-label="Template" style="color:#6b7280;">{{ $campaign->emailTemplate?->name ?? '—' }}</td>
                        <td data-label="Sent / Open / Click">
                            <span style="font-weight:500;">{{ $campaign->sent_count }}</span>
                            / {{ $campaign->open_count }}
                            / {{ $campaign->click_count }}
                        </td>
                        <td data-label="Status">
                            @php
                                $badge = match($campaign->status) {
                                    'sent' => 'admin-badge--green',
                                    'scheduled' => 'admin-badge--indigo',
                                    'sending' => 'admin-badge--orange',
                                    'cancelled' => 'admin-badge--red',
                                    default => 'admin-badge--gray'
                                };
                            @endphp
                            <span class="admin-badge {{ $badge }}">{{ ucfirst($campaign->status) }}</span>
                        </td>
                        <td data-label="Created" style="color:#6b7280;">{{ $campaign->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions" style="text-align:right;">
                            <div class="admin-table-actions" style="justify-content:flex-end;">
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="admin-action-btn admin-action-btn--edit">
                                    <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                @if($campaign->isDraft())
                                <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirmAction(this, { title: 'Send campaign?', message: 'Send this campaign now?', confirmText: 'Send' })">
                                    @csrf
                                    <button type="submit" class="admin-action-btn admin-action-btn--send">
                                        <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                        Send
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirmDelete(this, 'campaign')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-action-btn admin-action-btn--delete">
                                        <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">
            {{ $campaigns->links() }}
        </div>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state__icon">📢</div>
            <h3 class="admin-empty-state__title">No campaigns yet</h3>
            <p class="admin-empty-state__text">Create your first campaign to send targeted email broadcasts.</p>
            <a href="{{ route('admin.campaigns.create') }}" class="admin-primary-btn">+ Create Campaign</a>
        </div>
        @endif
    </section>
</div>
@endsection
