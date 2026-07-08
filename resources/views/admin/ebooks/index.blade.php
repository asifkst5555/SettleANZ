@extends('admin.layouts.app')

@section('page-title', 'Ebook Library')

@section('content')
<style>
    .ebook-table { width:100%; border-collapse:collapse; table-layout:auto; }
    .ebook-table thead { background:#f3f4f6; }
    .ebook-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .ebook-table th:first-child { padding-left:0.75rem; }
    .ebook-table th:last-child { padding-right:0.75rem; text-align:right; }
    .ebook-table th:not(:first-child), .ebook-table td:not(:first-child) { white-space:nowrap; width:1%; }
    .ebook-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; }
    .ebook-table td:first-child { padding-left:0.75rem; white-space:normal; width:auto; }
    .ebook-table td:last-child { padding-right:0.75rem; text-align:right; }
    .ebook-table tbody tr:hover { background:#f9fafb; }
    .ebook-title-cell { min-width:0; max-width:52rem; }
    .ebook-title-cell strong { display:block; margin-bottom:0.25rem; color:#1f2937; }
    .ebook-title-cell small { color:#6b7280; font-size:0.85rem; }
    .ebook-status-badge { display:inline-block; padding:0.375rem 0.75rem; border-radius:0.25rem; font-size:0.85rem; font-weight:500; }
    .ebook-status-published { background:#d1fae5; color:#065f46; }
    .ebook-status-draft { background:#fef3c7; color:#92400e; }
    .ebook-status-archived { background:#fee2e2; color:#7f1d1d; }
    .ebook-actions-cell { display:inline-flex; flex-wrap:wrap; gap:0.45rem; align-items:center; }
    .ebook-actions-cell form { margin:0; display:inline-flex; }
    .ebook-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; line-height:1.2; transition:background 0.2s,color 0.2s,box-shadow 0.2s; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:0.35rem; white-space:nowrap; }
    .ebook-edit-btn { background:#dbeafe; color:#0c4a6e; }
    .ebook-edit-btn:hover { background:#93c5fd; }
    .ebook-delete-btn { background:#fee2e2; color:#7f1d1d; }
    .ebook-delete-btn:hover { background:#fca5a5; }
    .ebook-new-btn { background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; padding:0.75rem 1.5rem; border:none; border-radius:0.375rem; cursor:pointer; font-weight:600; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; }
    .ebook-new-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(16,185,129,0.4); }
    .ebook-empty-state { text-align:center; padding:3rem 1rem; }
    .ebook-empty-state-icon { font-size:3rem; margin-bottom:1rem; }
    .ebook-filter-tabs { display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem; }
    .ebook-filter-tab { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border-radius:999px; text-decoration:none; border:1px solid #d7e1ea; color:#476072; background:#fff; font-weight:600; font-size:0.875rem; }
    .ebook-filter-tab.is-active { background:#0f8b8d; border-color:#0f8b8d; color:#fff; box-shadow:0 10px 24px rgba(15,139,141,0.18); }
    .ebook-filter-tab__count { display:inline-flex; align-items:center; justify-content:center; min-width:28px; height:28px; border-radius:999px; background:rgba(0,0,0,0.06); color:inherit; font-size:0.85rem; }
    .ebook-filter-tab.is-active .ebook-filter-tab__count { background:rgba(255,255,255,0.18); color:#fff; }
    .ebook-stat-card { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; padding:1.5rem; border-radius:0.5rem; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
    .ebook-stat-card.published { background:linear-gradient(135deg,#10b981 0%,#059669 100%); }
    .ebook-stat-card.draft { background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%); }
    .ebook-stat-card.archived { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); }
    .ebook-stat-label { font-size:0.875rem; opacity:0.9; margin-bottom:0.5rem; }
    .ebook-stat-value { font-size:2rem; font-weight:700; }
    .ebook-table-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; width:100%; overflow-x:visible; }
    .ebook-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
    @media (max-width:1100px) { .ebook-table-wrap { overflow-x:auto; } .ebook-table { min-width:800px; } }
    @media (max-width:768px) { .ebook-table-wrap { overflow-x:hidden !important; } .ebook-table { min-width:auto !important; width:100% !important; } }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Ebook Library</h2>
            <p>Manage your digital guides, checklists, and lead magnet ebooks</p>
        </div>
        <a href="{{ route('admin.ebooks.create') }}" class="ebook-new-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Ebook
        </a>
    </section>

    <section class="admin-panel-card">
        <div class="ebook-filter-tabs">
            <a href="{{ route('admin.ebooks.index') }}" class="ebook-filter-tab {{ !request('status') && !request('category_id') ? 'is-active' : '' }}">
                <span>All</span>
                <span class="ebook-filter-tab__count">{{ $ebooks->total() }}</span>
            </a>
            <a href="{{ route('admin.ebooks.index', ['status' => 'published']) }}" class="ebook-filter-tab {{ request('status') === 'published' ? 'is-active' : '' }}">
                <span>Published</span>
            </a>
            <a href="{{ route('admin.ebooks.index', ['status' => 'draft']) }}" class="ebook-filter-tab {{ request('status') === 'draft' ? 'is-active' : '' }}">
                <span>Draft</span>
            </a>
            @if(request('status') || request('category_id') || request('search'))
            <a href="{{ route('admin.ebooks.index') }}" class="ebook-filter-tab" style="color:#ef4444;border-color:#fca5a5;">
                Clear filters
            </a>
            @endif
        </div>

        @if($ebooks->count() > 0)
        <div class="ebook-table-wrap">
            <table class="ebook-table admin-table-mobile-cards">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Downloads</th>
                        <th>Leads</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="ebook-th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ebooks as $ebook)
                    <tr>
                        <td data-label="Title">
                            <div class="ebook-title-cell">
                                <strong>{{ $ebook->title }}</strong>
                                <small>{{ str($ebook->description)->limit(60) }}</small>
                            </div>
                        </td>
                        <td data-label="Category">{{ $ebook->category?->name ?? '—' }}</td>
                        <td data-label="Type" class="uppercase">{{ $ebook->file_type }}</td>
                        <td data-label="Downloads">{{ $ebook->download_count }}</td>
                        <td data-label="Leads">{{ $ebook->lead_count }}</td>
                        <td data-label="Status">
                            <span class="ebook-status-badge {{ 'ebook-status-' . $ebook->status }}">{{ ucfirst($ebook->status) }}</span>
                        </td>
                        <td data-label="Date">{{ $ebook->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions">
                            <div class="ebook-actions-cell">
                                <a href="{{ route('admin.ebooks.show', $ebook) }}" class="ebook-action-btn" style="background:#e0e7ff;color:#3730a3;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="ebook-action-btn ebook-edit-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.5rem;border-top:1px solid #e5e7eb;">
            {{ $ebooks->links() }}
        </div>
        @else
        <div class="ebook-empty-state">
            <div class="ebook-empty-state-icon">📚</div>
            <h3>No ebooks yet</h3>
            <p style="color:#6b7280;margin-bottom:1.5rem;">Create your first ebook to start generating leads.</p>
            <a href="{{ route('admin.ebooks.create') }}" class="ebook-new-btn">+ Create Your First Ebook</a>
        </div>
        @endif
    </section>
</div>
@endsection
