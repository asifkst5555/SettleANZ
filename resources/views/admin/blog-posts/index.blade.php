@extends('admin.layouts.app')

@section('content')
    <style>
        .blog-index-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .blog-stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .blog-stat-card.published {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .blog-stat-card.draft {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .blog-stat-card.featured {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        }
        .blog-stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        .blog-stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        .blog-table-enhanced {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .blog-table-wrap {
            border: 1px solid #edf2f7;
            border-radius: 0.75rem;
            background: #fff;
            width: 100%;
            overflow-x: visible;
        }
        .blog-filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .blog-filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            border: 1px solid #d7e1ea;
            color: #476072;
            background: #fff;
            font-weight: 600;
        }
        .blog-filter-tab.is-active {
            background: #0f8b8d;
            border-color: #0f8b8d;
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 139, 141, 0.18);
        }
        .blog-filter-tab__count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(15, 139, 141, 0.08);
            color: #0f8b8d;
            font-size: 0.85rem;
        }
        .blog-filter-tab.is-active .blog-filter-tab__count {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .blog-table-enhanced thead {
            background: #f3f4f6;
        }
        .blog-table-enhanced th {
            padding: 0.65rem 0.5rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.8125rem;
            letter-spacing: 0.01em;
        }
        .blog-table-enhanced th:first-child {
            padding-left: 0.75rem;
        }
        .blog-table-enhanced th:last-child {
            padding-right: 0.75rem;
        }
        /* Title column uses remaining space; other columns stay content-tight */
        .blog-table-enhanced th:not(:first-child),
        .blog-table-enhanced td:not(:first-child) {
            white-space: nowrap;
            width: 1%;
        }
        .blog-table-enhanced td {
            padding: 0.7rem 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .blog-table-enhanced td:first-child {
            padding-left: 0.75rem;
            white-space: normal;
            width: auto;
        }
        .blog-table-enhanced td:last-child {
            padding-right: 0.75rem;
            text-align: right;
        }
        .blog-table-enhanced th.blog-th-actions {
            text-align: right;
        }
        .blog-table-enhanced tbody tr:hover {
            background: #f9fafb;
        }
        .blog-title-cell {
            min-width: 0;
            max-width: 52rem;
        }
        .blog-title-cell strong {
            display: block;
            margin-bottom: 0.25rem;
            color: #1f2937;
        }
        .blog-title-cell small {
            color: #6b7280;
            font-size: 0.85rem;
        }
        .blog-category-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.82rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
        }
        .blog-status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .blog-status-published {
            background: #d1fae5;
            color: #065f46;
        }
        .blog-status-draft {
            background: #fef3c7;
            color: #92400e;
        }
        .blog-status-featured {
            background: #fce7f3;
            color: #831843;
        }
        .blog-toggle-form {
            margin: 0;
        }
        .blog-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            min-width: 0;
            padding: 0.25rem 0.45rem;
            border: 1px solid #d8e4ed;
            border-radius: 999px;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            white-space: nowrap;
        }
        .blog-toggle:hover,
        .blog-toggle:focus-visible {
            border-color: #0f8b8d;
            box-shadow: 0 0 0 3px rgba(15, 139, 141, 0.12);
            outline: none;
        }
        .blog-toggle__track {
            position: relative;
            width: 36px;
            height: 20px;
            flex: 0 0 auto;
            border-radius: 999px;
            background: #cbd5e1;
            transition: background 0.2s ease;
        }
        .blog-toggle__knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.2);
            transition: transform 0.2s ease;
        }
        .blog-toggle.is-on {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #047857;
        }
        .blog-toggle.is-on .blog-toggle__track {
            background: #10b981;
        }
        .blog-toggle.is-on .blog-toggle__knob {
            transform: translateX(16px);
        }
        .blog-toggle--feature.is-on {
            background: #fdf2f8;
            border-color: #fbcfe8;
            color: #be185d;
        }
        .blog-toggle--feature.is-on .blog-toggle__track {
            background: #ec4899;
        }
        @media (max-width: 1100px) {
            .blog-table-wrap {
                overflow-x: auto;
            }
            .blog-table-enhanced {
                min-width: 800px;
            }
        }
        .blog-toggle__text {
            white-space: nowrap;
        }
        .blog-date-cell {
            color: #334155;
            font-weight: 600;
            white-space: nowrap;
        }
        .blog-actions-cell {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            align-items: center;
        }
        .blog-actions-cell form {
            margin: 0;
            display: inline-flex;
        }
        .blog-action-btn {
            box-sizing: border-box;
            min-height: 2.125rem;
            padding: 0.45rem 0.85rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1.2;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            white-space: nowrap;
        }
        .blog-action-icon {
            flex-shrink: 0;
        }
        .blog-edit-btn {
            background: #dbeafe;
            color: #0c4a6e;
        }
        .blog-edit-btn:hover {
            background: #93c5fd;
        }
        .blog-delete-btn {
            background: #fee2e2;
            color: #7f1d1d;
        }
        .blog-delete-btn:hover {
            background: #fca5a5;
        }
        .blog-new-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .blog-new-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16,185,129,0.4);
        }
        .blog-empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .blog-empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 900px) {
            .blog-toggle {
                min-width: 0;
            }
        }
        
        /* Mobile responsive container only - action button styles come from admin.css */
        @media (max-width: 768px) {
            .blog-table-wrap {
                overflow-x: hidden !important;
            }
            .blog-table-enhanced {
                min-width: auto !important;
                width: 100% !important;
            }
        }
    </style>

    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Blog Posts Management</h2>
                <p>Create, edit, and manage all blog articles for your site</p>
            </div>
            <button class="blog-new-btn" onclick="window.location.href='{{ route('admin.blog-posts.create') }}'">
                Create New Post
            </button>
        </section>

        <!-- Statistics -->
        <div class="blog-index-stats">
            <div class="blog-stat-card">
                <div class="blog-stat-label">Total Posts</div>
                <div class="blog-stat-value">{{ $stats['all'] }}</div>
            </div>
            <div class="blog-stat-card published">
                <div class="blog-stat-label">Published</div>
                <div class="blog-stat-value">{{ $stats['published'] }}</div>
            </div>
            <div class="blog-stat-card draft">
                <div class="blog-stat-label">Drafts</div>
                <div class="blog-stat-value">{{ $stats['draft'] }}</div>
            </div>
            <div class="blog-stat-card featured">
                <div class="blog-stat-label">Featured</div>
                <div class="blog-stat-value">{{ $stats['featured'] }}</div>
            </div>
        </div>

        <!-- Blog Table -->
        <section class="admin-panel-card">
            <div class="blog-filter-tabs">
                <a href="{{ route('admin.blog-posts.index', ['status' => 'all']) }}" class="blog-filter-tab {{ $statusFilter === 'all' ? 'is-active' : '' }}">
                    <span>All Posts</span>
                    <span class="blog-filter-tab__count">{{ $stats['all'] }}</span>
                </a>
                <a href="{{ route('admin.blog-posts.index', ['status' => 'draft']) }}" class="blog-filter-tab {{ $statusFilter === 'draft' ? 'is-active' : '' }}">
                    <span>Drafts</span>
                    <span class="blog-filter-tab__count">{{ $stats['draft'] }}</span>
                </a>
                <a href="{{ route('admin.blog-posts.index', ['status' => 'published']) }}" class="blog-filter-tab {{ $statusFilter === 'published' ? 'is-active' : '' }}">
                    <span>Published</span>
                    <span class="blog-filter-tab__count">{{ $stats['published'] }}</span>
                </a>
                <a href="{{ route('admin.blog-posts.index', ['status' => 'featured']) }}" class="blog-filter-tab {{ $statusFilter === 'featured' ? 'is-active' : '' }}">
                    <span>Featured</span>
                    <span class="blog-filter-tab__count">{{ $stats['featured'] }}</span>
                </a>
            </div>

            @if ($posts->count() > 0)
                <div class="blog-table-wrap">
                    <table class="blog-table-enhanced admin-table-mobile-cards">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Feature</th>
                            <th>Published</th>
                            <th class="blog-th-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td data-label="Post Title">
                                    <div class="blog-title-cell">
                                        <strong>{{ $post->title }}</strong>
                                        <small>{{ $post->slug }}</small>
                                    </div>
                                </td>
                                <td data-label="Category">
                                    <span class="blog-category-pill">{{ $post->category }}</span>
                                </td>
                                <td data-label="Status">
                                    <form class="blog-toggle-form" method="POST" action="{{ route('admin.blog-posts.status', $post) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="{{ $post->is_published ? 'unpublish' : 'publish' }}">
                                        <button
                                            type="submit"
                                            class="blog-toggle {{ $post->is_published ? 'is-on' : '' }}"
                                            role="switch"
                                            aria-checked="{{ $post->is_published ? 'true' : 'false' }}"
                                            aria-label="{{ $post->is_published ? 'Unpublish' : 'Publish' }} {{ $post->title }}"
                                        >
                                            <span class="blog-toggle__track" aria-hidden="true"><span class="blog-toggle__knob"></span></span>
                                            <span class="blog-toggle__text">{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td data-label="Feature">
                                    <form class="blog-toggle-form" method="POST" action="{{ route('admin.blog-posts.status', $post) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="{{ $post->is_featured_home ? 'unfeature' : 'feature' }}">
                                        <button
                                            type="submit"
                                            class="blog-toggle blog-toggle--feature {{ $post->is_featured_home ? 'is-on' : '' }}"
                                            role="switch"
                                            aria-checked="{{ $post->is_featured_home ? 'true' : 'false' }}"
                                            aria-label="{{ $post->is_featured_home ? 'Remove feature from' : 'Feature' }} {{ $post->title }}"
                                        >
                                            <span class="blog-toggle__track" aria-hidden="true"><span class="blog-toggle__knob"></span></span>
                                            <span class="blog-toggle__text">{{ $post->is_featured_home ? 'Featured' : 'Off' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td data-label="Published" class="blog-date-cell">
                                    {{ optional($post->published_at)->format('d M Y') ?: '—' }}
                                </td>
                                <td data-label="Actions">
                                    <div class="blog-actions-cell">
                                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="blog-action-btn blog-edit-btn">
                                            <svg class="blog-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirmDelete(this, 'blog');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="blog-action-btn blog-delete-btn">
                                                <svg class="blog-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="blog-empty-state">
                    <div class="blog-empty-state-icon">📭</div>
                    <h3>No posts found in this tab</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Create a new post or switch to another filter to see more content.</p>
                    <button class="blog-new-btn" onclick="window.location.href='{{ route('admin.blog-posts.create') }}'">
                        ➕ Create Your First Post
                    </button>
                </div>
            @endif
        </section>
    </div>
@endsection
