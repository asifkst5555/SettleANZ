@extends('admin.layouts.app')

@section('page-title', 'AI Knowledge Base')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">AI Training</p>
                <h2>AI Knowledge Base</h2>
                <p>Add, edit, and manage custom knowledge entries that train the AI assistant.</p>
            </div>
            <div class="admin-table-actions">
                <a href="{{ route('admin.ai-knowledge.generate-form') }}" class="ai-btn">
                    <span class="ai-btn__label">Bulk Generate with AI</span>
                    <span></span><span></span><span></span><span></span>
                </a>
                <a href="{{ route('admin.ai-knowledge.create') }}" class="admin-primary-btn">+ Add Knowledge Entry</a>
            </div>
        </section>

        @if (session('status'))
            <div class="admin-note-block" style="background:#e8f5e9;border-color:#66bb6a;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        <div class="admin-card-stats-grid" style="margin-bottom:1.5rem;">
            <div class="admin-brand-stat-card" style="text-align:center;">
                <div class="admin-brand-stat-card__value" style="color:#0b7a75;">{{ $totalActive }}</div>
                <div class="admin-brand-stat-card__label">Active Entries</div>
            </div>
            <div class="admin-brand-stat-card accent" style="text-align:center;">
                <div class="admin-brand-stat-card__value" style="color:#e65100;">{{ $totalInactive }}</div>
                <div class="admin-brand-stat-card__label">Inactive Entries</div>
            </div>
            <div class="admin-brand-stat-card" style="text-align:center;">
                <div class="admin-brand-stat-card__value" style="color:#6d28d9;">{{ $categories->sum('count') }}</div>
                <div class="admin-brand-stat-card__label">Total Entries</div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.ai-knowledge.index') }}" class="admin-filters-card" style="margin-bottom:1.5rem;">
            <div class="admin-filters-card__body" style="margin-top:0;padding-top:0;border-top:none;">
                <div class="admin-form-group" style="flex:1;min-width:200px;margin-bottom:0;">
                    <label class="admin-form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, content, or keywords..." class="admin-form-input">
                </div>
                <div class="admin-form-group" style="min-width:180px;margin-bottom:0;">
                    <label class="admin-form-label">Category</label>
                    <select name="category" class="admin-form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->category }}" @selected(request('category') === $cat->category)>{{ ucfirst($cat->category) }} ({{ $cat->count }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-action-btn" style="background:#0b7a75;color:#fff;">Filter</button>
                @if (request('search') || request('category'))
                    <a href="{{ route('admin.ai-knowledge.index') }}" class="admin-action-btn" style="background:#667788;color:#fff;">Clear</a>
                @endif
            </div>
        </form>

        <div class="admin-table-wrap">
            @if ($entries->isEmpty())
                <div class="admin-empty-state">
                    <div class="admin-empty-state__icon">🧠</div>
                    <h3 class="admin-empty-state__title">No knowledge entries found</h3>
                    <p class="admin-empty-state__text">Add your first knowledge entry to train the AI assistant.</p>
                    <a href="{{ route('admin.ai-knowledge.create') }}" class="admin-primary-btn">+ Add Knowledge Entry</a>
                </div>
            @else
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr>
                                <td data-label="Title">
                                    <strong>{{ Str::limit($entry->title, 60) }}</strong>
                                    <small>{{ Str::limit($entry->content, 80) }}</small>
                                </td>
                                <td data-label="Category">
                                    <span class="admin-badge admin-badge--teal">{{ ucfirst($entry->category) }}</span>
                                </td>
                                <td data-label="Priority">{{ $entry->priority }}</td>
                                <td data-label="Status">
                                    <span class="admin-badge {{ $entry->is_active ? 'admin-badge--green' : 'admin-badge--orange' }}">{{ $entry->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td data-label="Updated" style="color:#6b7280;">{{ $entry->updated_at->diffForHumans() }}</td>
                                <td data-label="Actions" style="text-align:right;">
                                    <div class="admin-table-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.ai-knowledge.edit', $entry) }}" class="admin-action-btn admin-action-btn--edit">Edit</a>
                                        <form method="POST" action="{{ route('admin.ai-knowledge.toggle-active', $entry) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="admin-action-btn {{ $entry->is_active ? 'admin-action-btn--deactivate' : 'admin-action-btn--activate' }}">
                                                {{ $entry->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.ai-knowledge.destroy', $entry) }}" style="display:inline;" onsubmit="return confirmDelete(this, 'knowledge')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action-btn admin-action-btn--delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($entries->hasPages())
                    <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">
                        {{ $entries->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
