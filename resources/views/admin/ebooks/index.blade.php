@extends('admin.layouts.app')

@section('page-title', 'Ebook Library')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Ebook Library</h2>
            <p>Manage your digital guides, checklists, and lead magnet ebooks</p>
        </div>
        <a href="{{ route('admin.ebooks.create') }}" class="admin-primary-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Ebook
        </a>
    </section>

    <section class="admin-panel-card">
        <div class="admin-filter-chips" style="padding:1.25rem;border-bottom:1px solid var(--admin-border);">
            <a href="{{ route('admin.ebooks.index') }}" class="admin-filter-chip {{ !request('status') && !request('category_id') ? 'is-active' : '' }}">
                <span>All</span>
                <span class="admin-filter-chip__count">{{ $ebooks->total() }}</span>
            </a>
            <a href="{{ route('admin.ebooks.index', ['status' => 'published']) }}" class="admin-filter-chip {{ request('status') === 'published' ? 'is-active' : '' }}">
                <span>Published</span>
            </a>
            <a href="{{ route('admin.ebooks.index', ['status' => 'draft']) }}" class="admin-filter-chip {{ request('status') === 'draft' ? 'is-active' : '' }}">
                <span>Draft</span>
            </a>
            @if(request('status') || request('category_id') || request('search'))
            <a href="{{ route('admin.ebooks.index') }}" class="admin-filter-chip" style="color:#ef4444;border-color:#fca5a5;">
                Clear filters
            </a>
            @endif
        </div>

        @if($ebooks->count() > 0)
        <div class="admin-table-wrap" style="border:none;">
            <table class="admin-table admin-table-mobile-cards">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Downloads</th>
                        <th>Leads</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ebooks as $ebook)
                    <tr>
                        <td data-label="Title">
                            <strong>{{ $ebook->title }}</strong>
                            <small>{{ str($ebook->description)->limit(60) }}</small>
                        </td>
                        <td data-label="Category">{{ $ebook->category?->name ?? '—' }}</td>
                        <td data-label="Type" style="text-transform:uppercase;">{{ $ebook->file_type }}</td>
                        <td data-label="Downloads">{{ $ebook->download_count }}</td>
                        <td data-label="Leads">{{ $ebook->lead_count }}</td>
                        <td data-label="Status">
                            @php
                                $badge = match($ebook->status) {
                                    'published' => 'admin-badge--green',
                                    'draft' => 'admin-badge--orange',
                                    'archived' => 'admin-badge--red',
                                    default => 'admin-badge--gray'
                                };
                            @endphp
                            <span class="admin-badge {{ $badge }}">{{ ucfirst($ebook->status) }}</span>
                        </td>
                        <td data-label="Date">{{ $ebook->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions" style="text-align:right;">
                            <div class="admin-table-actions" style="justify-content:flex-end;">
                                <a href="{{ route('admin.ebooks.show', $ebook) }}" class="admin-action-btn admin-action-btn--view">
                                    <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="admin-action-btn admin-action-btn--edit">
                                    <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">
            {{ $ebooks->links() }}
        </div>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state__icon">📚</div>
            <h3 class="admin-empty-state__title">No ebooks yet</h3>
            <p class="admin-empty-state__text">Create your first ebook to start generating leads.</p>
            <a href="{{ route('admin.ebooks.create') }}" class="admin-primary-btn">+ Create Your First Ebook</a>
        </div>
        @endif
    </section>
</div>
@endsection
