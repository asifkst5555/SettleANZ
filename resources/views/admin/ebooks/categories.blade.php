@extends('admin.layouts.app')

@section('page-title', 'Ebook Categories')

@section('content')
<style>
    .ebook-cat-table { width:100%; border-collapse:collapse; }
    .ebook-cat-table thead { background:#f3f4f6; }
    .ebook-cat-table th { padding:0.65rem 0.5rem; text-align:left; font-weight:600; color:#374151; border-bottom:2px solid #e5e7eb; font-size:0.8125rem; letter-spacing:0.01em; }
    .ebook-cat-table th:first-child { padding-left:0.75rem; }
    .ebook-cat-table th:last-child { padding-right:0.75rem; text-align:right; }
    .ebook-cat-table td { padding:0.7rem 0.5rem; border-bottom:1px solid #e5e7eb; vertical-align:middle; }
    .ebook-cat-table td:first-child { padding-left:0.75rem; }
    .ebook-cat-table td:last-child { padding-right:0.75rem; text-align:right; }
    .ebook-cat-table tbody tr:hover { background:#f9fafb; }
    .ebook-cat-wrap { border:1px solid #edf2f7; border-radius:0.75rem; background:#fff; width:100%; overflow-x:auto; }
    .ebook-action-btn { box-sizing:border-box; min-height:2.125rem; padding:0.45rem 0.85rem; border-radius:0.375rem; border:none; cursor:pointer; font-size:0.8125rem; font-weight:600; transition:background 0.2s,color 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; white-space:nowrap; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Categories</h2>
            <p>Organize ebooks into categories</p>
        </div>
        <a href="{{ route('admin.ebooks.index') }}" class="button button--small button--ghost">&larr; Back to Library</a>
    </section>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;">
        <section class="admin-panel-card">
            <div style="padding:1.5rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;">Add Category</h3>
                <form action="{{ route('admin.ebook-categories.store') }}" method="POST">
                    @csrf
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <div>
                            <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;font-size:0.875rem;">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Description</label>
                            <textarea name="description" rows="3" style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;font-size:0.875rem;">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;font-size:0.875rem;">
                        </div>
                        <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" checked style="accent-color:#0f8b8d;"> Active
                        </label>
                        <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.625rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;">Add Category</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="ebook-cat-wrap">
                <table class="ebook-cat-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Ebooks</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td data-label="Name">
                                <div style="font-weight:500;">{{ $cat->name }}</div>
                                <div style="font-size:0.8125rem;color:#6b7280;">{{ str($cat->description)->limit(40) ?? '' }}</div>
                            </td>
                            <td data-label="Ebooks">{{ $cat->ebooks_count }}</td>
                            <td data-label="Order">{{ $cat->sort_order }}</td>
                            <td data-label="Active">
                                <span style="display:inline-block;padding:0.25rem 0.625rem;border-radius:0.25rem;font-size:0.8125rem;font-weight:500;{{ $cat->is_active ? 'background:#d1fae5;color:#065f46;' : 'background:#f3f4f6;color:#6b7280;' }}">{{ $cat->is_active ? 'Yes' : 'No' }}</span>
                            </td>
                            <td data-label="Actions">
                                <div style="display:inline-flex;gap:0.375rem;">
                                    <button onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', {{ $cat->sort_order }}, {{ $cat->is_active ? 'true' : 'false' }})" class="ebook-action-btn" style="background:#dbeafe;color:#0c4a6e;">Edit</button>
                                    <form action="{{ route('admin.ebook-categories.destroy', $cat) }}" method="POST" onsubmit="return confirmDelete(this, 'category')" style="margin:0;display:inline-flex;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ebook-action-btn" style="background:#fee2e2;color:#7f1d1d;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:3rem 1rem;color:#6b7280;">No categories yet. Create one to organize your ebooks.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:50;" onclick="if(event.target===this)closeEditModal()">
    <div style="background:white;border-radius:12px;padding:1.5rem;width:100%;max-width:480px;margin:1rem;">
        <h3 style="font-size:1.125rem;font-weight:700;margin-bottom:1rem;">Edit Category</h3>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Name *</label>
                    <input type="text" name="name" id="editName" required style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;">
                </div>
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Description</label>
                    <textarea name="description" id="editDescription" rows="2" style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;"></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.375rem;">Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" min="0" style="width:100%;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.75rem;">
                </div>
                <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="editIsActive" value="1" style="accent-color:#0f8b8d;"> Active
                </label>
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:1.5rem;padding-top:1rem;border-top:1px solid #e5e7eb;">
                <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.5rem 1.5rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;">Update</button>
                <button type="button" onclick="closeEditModal()" style="padding:0.5rem 1.5rem;border:1px solid #d7e1ea;border-radius:0.375rem;cursor:pointer;background:white;color:#476072;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name, description, sortOrder, isActive) {
    document.getElementById('editForm').action = '{{ url("admin/ebook-categories") }}/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editSortOrder').value = sortOrder;
    document.getElementById('editIsActive').checked = isActive;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }
</script>
@endsection
