@extends('admin.layouts.app')

@section('content')
    <style>
        .directory-table-wrap {
            border: 1px solid #edf2f7;
            border-radius: 0.75rem;
            background: #fff;
            width: 100%;
            overflow-x: visible;
        }
        .directory-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .directory-table thead {
            background: #f3f4f6;
        }
        .directory-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .directory-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .directory-table tbody tr:hover {
            background: #f9fafb;
        }
        .directory-table td strong,
        .directory-table td small {
            display: block;
        }
        .directory-table td small {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .directory-category-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.2;
        }
        .directory-city-cell {
            color: #334155;
            font-weight: 500;
        }
        .directory-toggle-form {
            margin: 0;
        }
        .directory-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            padding: 0.3rem 0.5rem;
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
        .directory-toggle:hover,
        .directory-toggle:focus-visible {
            border-color: #0f8b8d;
            box-shadow: 0 0 0 3px rgba(15, 139, 141, 0.12);
            outline: none;
        }
        .directory-toggle__track {
            position: relative;
            width: 36px;
            height: 20px;
            flex: 0 0 auto;
            border-radius: 999px;
            background: #cbd5e1;
            transition: background 0.2s ease;
        }
        .directory-toggle__knob {
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
        .directory-toggle.is-on {
            background: #fdf2f8;
            border-color: #fbcfe8;
            color: #be185d;
        }
        .directory-toggle.is-on .directory-toggle__track {
            background: #ec4899;
        }
        .directory-toggle.is-on .directory-toggle__knob {
            transform: translateX(16px);
        }
        .directory-toggle__text {
            white-space: nowrap;
        }
        .directory-actions-cell {
            display: flex;
            gap: 0.4rem;
            flex-wrap: nowrap;
            align-items: center;
            white-space: nowrap;
        }
        .directory-action-btn {
            padding: 0.4rem 0.75rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }
        .directory-edit-btn {
            background: #dbeafe;
            color: #0c4a6e;
        }
        .directory-edit-btn:hover {
            background: #93c5fd;
        }
        .directory-delete-btn {
            background: #fee2e2;
            color: #7f1d1d;
        }
        .directory-delete-btn:hover {
            background: #fca5a5;
        }
        @media (max-width: 1100px) {
            .directory-table-wrap {
                overflow-x: auto;
            }
            .directory-table {
                min-width: 800px;
            }
        }
        
        /* Mobile responsive container only - action button styles come from admin.css */
        @media (max-width: 768px) {
            .directory-table-wrap {
                overflow-x: hidden !important;
            }
            .directory-table {
                min-width: auto !important;
                width: 100% !important;
            }
        }
    </style>

    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Partners</p>
                <h2>Directory listings</h2>
                <p>Manage migration partners, relocation services, and featured directory placements.</p>
            </div>
            <a class="button button--small" href="{{ route('admin.directory-listings.create') }}">New listing</a>
        </section>

        <section class="admin-panel-card" style="padding: 0;">
            <div class="directory-table-wrap">
                <table class="directory-table admin-table-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Name</th>
                            <th style="width: 22%;">Category</th>
                            <th style="width: 14%;">City</th>
                            <th style="width: 18%;">Feature</th>
                            <th style="width: 18%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listings as $listing)
                            <tr>
                                <td data-label="Name">
                                    <strong>{{ $listing->name }}</strong>
                                    <small>{{ $listing->slug }}</small>
                                </td>
                                <td data-label="Category">
                                    <span class="directory-category-pill">{{ $listing->category }}</span>
                                </td>
                                <td data-label="City" class="directory-city-cell">
                                    {{ $listing->city }}
                                </td>
                                <td data-label="Feature">
                                    <form class="directory-toggle-form" method="POST" action="{{ route('admin.directory-listings.toggle-featured', $listing) }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="directory-toggle {{ $listing->featured ? 'is-on' : '' }}"
                                            role="switch"
                                            aria-checked="{{ $listing->featured ? 'true' : 'false' }}"
                                            aria-label="{{ $listing->featured ? 'Remove from featured' : 'Add to featured' }} {{ $listing->name }}"
                                        >
                                            <span class="directory-toggle__track" aria-hidden="true"><span class="directory-toggle__knob"></span></span>
                                            <span class="directory-toggle__text">{{ $listing->featured ? 'Featured' : 'Off' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td data-label="Actions">
                                    <div class="directory-actions-cell">
                                        <a href="{{ route('admin.directory-listings.edit', $listing) }}" class="directory-action-btn directory-edit-btn">
                                            <svg class="directory-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.directory-listings.destroy', $listing) }}" onsubmit="return confirmDelete(this, 'listing');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="directory-action-btn directory-delete-btn">
                                                <svg class="directory-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">🏢</div>
                                    <h3>No directory listings yet.</h3>
                                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Add your first partner or service provider to get started.</p>
                                    <a class="button" href="{{ route('admin.directory-listings.create') }}">➕ Add First Listing</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.25rem; border-top: 1px solid #e5e7eb;">{{ $listings->links() }}</div>
        </section>
    </div>
@endsection
