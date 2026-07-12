@extends('admin.layouts.app')

@section('page-title', 'Email Templates')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Email Templates</h2>
            <p>Create and manage email templates for download notifications and campaigns</p>
        </div>
        <a href="{{ route('admin.email-templates.create') }}" class="admin-primary-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Template
        </a>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--admin-border);">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Type</label>
                    <select name="type" class="admin-form-select">
                        <option value="">All</option>
                        @foreach($types as $t)
                        <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-action-btn" style="background:#e2e8f0;color:#1e293b;">Filter</button>
            </form>
        </div>

        @if($templates->count() > 0)
        <div class="admin-table-wrap" style="border:none;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Active</th>
                        <th>Created By</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td data-label="Name" style="font-weight:500;">{{ $template->name }}</td>
                        <td data-label="Type">
                            <span class="admin-badge admin-badge--indigo">{{ ucfirst(str_replace('_', ' ', $template->type)) }}</span>
                        </td>
                        <td data-label="Subject" style="color:#6b7280;">{{ $template->subject }}</td>
                        <td data-label="Active">
                            <span class="admin-badge {{ $template->is_active ? 'admin-badge--green' : 'admin-badge--gray' }}">{{ $template->is_active ? 'Yes' : 'No' }}</span>
                        </td>
                        <td data-label="Created By" style="color:#6b7280;">{{ $template->creator?->name ?? '—' }}</td>
                        <td data-label="Actions" style="text-align:right;">
                            <div class="admin-table-actions" style="justify-content:flex-end;">
                                <a href="{{ route('admin.email-templates.edit', $template) }}" class="admin-action-btn admin-action-btn--edit">
                                    <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.email-templates.duplicate', $template) }}" method="POST" style="margin:0; display:inline-flex;">
                                    @csrf
                                    <button type="submit" class="admin-action-btn admin-action-btn--duplicate">
                                        <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        Duplicate
                                    </button>
                                </form>
                                <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST" onsubmit="return confirmDelete(this, 'template')">
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
            {{ $templates->links() }}
        </div>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state__icon">📧</div>
            <h3 class="admin-empty-state__title">No email templates yet</h3>
            <p class="admin-empty-state__text">Create your first template to send branded download emails.</p>
            <a href="{{ route('admin.email-templates.create') }}" class="admin-primary-btn">+ Create Template</a>
        </div>
        @endif
    </section>
</div>
@endsection
