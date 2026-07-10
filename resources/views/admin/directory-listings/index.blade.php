@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Partners</p>
                <h2>Directory listings</h2>
                <p>Manage migration partners, relocation services, and featured directory placements.</p>
            </div>
            <a class="admin-primary-btn" href="{{ route('admin.directory-listings.create') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                New listing
            </a>
        </section>

        <section class="admin-panel-card" style="padding: 0;">
            <div class="admin-table-wrap">
                <table class="admin-table admin-table-mobile-cards">
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
                                    <span class="admin-badge admin-badge--indigo">{{ $listing->category }}</span>
                                </td>
                                <td data-label="City" style="font-weight:500;color:#334155;">
                                    {{ $listing->city }}
                                </td>
                                <td data-label="Feature">
                                    <form method="POST" action="{{ route('admin.directory-listings.toggle-featured', $listing) }}" style="margin:0;">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="admin-btn-toggle {{ $listing->featured ? 'is-on' : '' }}"
                                            role="switch"
                                            aria-checked="{{ $listing->featured ? 'true' : 'false' }}"
                                            aria-label="{{ $listing->featured ? 'Remove from featured' : 'Add to featured' }} {{ $listing->name }}"
                                        >
                                            <span class="admin-btn-toggle__track" aria-hidden="true"><span class="admin-btn-toggle__knob"></span></span>
                                            <span class="admin-btn-toggle__text">{{ $listing->featured ? 'Featured' : 'Off' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-table-actions">
                                        <a href="{{ route('admin.directory-listings.edit', $listing) }}" class="admin-action-btn admin-action-btn--edit">
                                            <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.directory-listings.destroy', $listing) }}" onsubmit="return confirmDelete(this, 'listing');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action-btn admin-action-btn--delete">
                                                <svg class="admin-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty-state">
                                    <div class="admin-empty-state__icon">🏢</div>
                                    <h3 class="admin-empty-state__title">No directory listings yet.</h3>
                                    <p class="admin-empty-state__text">Add your first partner or service provider to get started.</p>
                                    <a class="admin-primary-btn" href="{{ route('admin.directory-listings.create') }}">➕ Add First Listing</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination" style="border-top:1px solid var(--admin-border);">{{ $listings->links() }}</div>
        </section>
    </div>
@endsection
