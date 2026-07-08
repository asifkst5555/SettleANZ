@extends('admin.layouts.app')

@section('page-title', 'Campaigns')

@section('content')
<style>
    .cmp-table { width:100%; border-collapse:collapse; }
    .cmp-table thead { background:#f3f4f6; }
    .cmp-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .cmp-table th:first-child { padding-left:0.75rem; }
    .cmp-table th:last-child { padding-right:0.75rem; text-align:right; }
    .cmp-table th:not(:first-child), .cmp-table td:not(:first-child) { white-space:nowrap; width:1%; }
    .cmp-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; font-size:0.875rem; }
    .cmp-table td:first-child { padding-left:0.75rem; white-space:normal; width:auto; }
    .cmp-table td:last-child { padding-right:0.75rem; text-align:right; }
    .cmp-table tbody tr:hover { background:#f9fafb; }
    .cmp-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; overflow-x:auto; }
    .cmp-actions-cell { display:inline-flex; flex-wrap:wrap; gap:0.45rem; align-items:center; }
    .cmp-actions-cell form { margin:0; display:inline-flex; }
    .cmp-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; transition:background 0.2s,color 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; white-space:nowrap; }
    .cmp-new-btn { background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; padding:0.75rem 1.5rem; border:none; border-radius:0.375rem; cursor:pointer; font-weight:600; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; }
    .cmp-new-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(16,185,129,0.4); }
    .cmp-empty-state { text-align:center; padding:3rem 1rem; }
    @media (max-width:1100px) { .cmp-wrap { overflow-x:auto; } .cmp-table { min-width:800px; } }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Campaigns</h2>
            <p>Create and manage email campaigns for targeted ebook promotions</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="cmp-new-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Campaign
        </a>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Status</label>
                    <select name="status" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                        <option value="">All</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="padding:0.5rem 1rem;border:1px solid #d7e1ea;border-radius:0.375rem;background:white;cursor:pointer;font-weight:500;">Filter</button>
            </form>
        </div>

        @if($campaigns->count() > 0)
        <div class="cmp-wrap" style="border:none;">
            <table class="cmp-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Ebook</th>
                        <th>Template</th>
                        <th>Sent / Open / Click</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
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
                            <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:0.25rem;font-size:0.8125rem;font-weight:500;
                                @if($campaign->status === 'sent') background:#d1fae5;color:#065f46;
                                @elseif($campaign->status === 'scheduled') background:#dbeafe;color:#1e40af;
                                @elseif($campaign->status === 'sending') background:#fef3c7;color:#92400e;
                                @elseif($campaign->status === 'cancelled') background:#fee2e2;color:#7f1d1d;
                                @else background:#f3f4f6;color:#6b7280; @endif">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td data-label="Created" style="color:#6b7280;">{{ $campaign->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions">
                            <div class="cmp-actions-cell">
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="cmp-action-btn" style="background:#dbeafe;color:#0c4a6e;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                @if($campaign->isDraft())
                                <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Send this campaign now?')">
                                    @csrf
                                    <button type="submit" class="cmp-action-btn" style="background:#d1fae5;color:#065f46;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                        Send
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Delete this campaign?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cmp-action-btn" style="background:#fee2e2;color:#7f1d1d;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
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
        <div style="padding:1.5rem;border-top:1px solid #e5e7eb;">
            {{ $campaigns->links() }}
        </div>
        @else
        <div class="cmp-empty-state">
            <div style="font-size:3rem;margin-bottom:1rem;">📢</div>
            <h3>No campaigns yet</h3>
            <p style="color:#6b7280;margin-bottom:1.5rem;">Create your first campaign to send targeted email broadcasts.</p>
            <a href="{{ route('admin.campaigns.create') }}" class="cmp-new-btn">+ Create Campaign</a>
        </div>
        @endif
    </section>
</div>
@endsection
