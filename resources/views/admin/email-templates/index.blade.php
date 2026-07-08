@extends('admin.layouts.app')

@section('page-title', 'Email Templates')

@section('content')
<style>
    .et-table { width:100%; border-collapse:collapse; }
    .et-table thead { background:#f3f4f6; }
    .et-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .et-table th:first-child { padding-left:0.75rem; }
    .et-table th:last-child { padding-right:0.75rem; text-align:right; }
    .et-table th:not(:first-child), .et-table td:not(:first-child) { white-space:nowrap; width:1%; }
    .et-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; font-size:0.875rem; }
    .et-table td:first-child { padding-left:0.75rem; white-space:normal; width:auto; }
    .et-table td:last-child { padding-right:0.75rem; text-align:right; }
    .et-table tbody tr:hover { background:#f9fafb; }
    .et-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; overflow-x:auto; }
    .et-actions-cell { display:inline-flex; flex-wrap:wrap; gap:0.45rem; align-items:center; }
    .et-actions-cell form { margin:0; display:inline-flex; }
    .et-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; transition:background 0.2s,color 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; white-space:nowrap; }
    .et-new-btn { background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; padding:0.75rem 1.5rem; border:none; border-radius:0.375rem; cursor:pointer; font-weight:600; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; }
    .et-new-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(16,185,129,0.4); }
    .et-empty-state { text-align:center; padding:3rem 1rem; }
    @media (max-width:1100px) { .et-wrap { overflow-x:auto; } .et-table { min-width:700px; } }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Email Templates</h2>
            <p>Create and manage email templates for download notifications and campaigns</p>
        </div>
        <a href="{{ route('admin.email-templates.create') }}" class="et-new-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Template
        </a>
    </section>

    <section class="admin-panel-card">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;">
            <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;">
                <div>
                    <label style="display:block;font-size:0.8125rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Type</label>
                    <select name="type" style="border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem;font-size:0.875rem;">
                        <option value="">All</option>
                        @foreach($types as $t)
                        <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="padding:0.5rem 1rem;border:1px solid #d7e1ea;border-radius:0.375rem;background:white;cursor:pointer;font-weight:500;">Filter</button>
            </form>
        </div>

        @if($templates->count() > 0)
        <div class="et-wrap" style="border:none;">
            <table class="et-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Active</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td data-label="Name" style="font-weight:500;">{{ $template->name }}</td>
                        <td data-label="Type">
                            <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:999px;background:#dbeafe;color:#1e40af;font-size:0.8125rem;font-weight:500;">{{ ucfirst(str_replace('_', ' ', $template->type)) }}</span>
                        </td>
                        <td data-label="Subject" style="color:#6b7280;">{{ $template->subject }}</td>
                        <td data-label="Active">
                            <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:0.25rem;font-size:0.8125rem;font-weight:500;{{ $template->is_active ? 'background:#d1fae5;color:#065f46;' : 'background:#f3f4f6;color:#6b7280;' }}">{{ $template->is_active ? 'Yes' : 'No' }}</span>
                        </td>
                        <td data-label="Created By" style="color:#6b7280;">{{ $template->creator?->name ?? '—' }}</td>
                        <td data-label="Actions">
                            <div class="et-actions-cell">
                                <a href="{{ route('admin.email-templates.edit', $template) }}" class="et-action-btn" style="background:#dbeafe;color:#0c4a6e;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Delete this template?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="et-action-btn" style="background:#fee2e2;color:#7f1d1d;">
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
            {{ $templates->links() }}
        </div>
        @else
        <div class="et-empty-state">
            <div style="font-size:3rem;margin-bottom:1rem;">📧</div>
            <h3>No email templates yet</h3>
            <p style="color:#6b7280;margin-bottom:1.5rem;">Create your first template to send branded download emails.</p>
            <a href="{{ route('admin.email-templates.create') }}" class="et-new-btn">+ Create Template</a>
        </div>
        @endif
    </section>
</div>
@endsection
