@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Partners</p>
                <h2>Directory listings</h2>
                <p>Manage migration partners, relocation services, and featured directory placements.</p>
            </div>
            <a class="button button--small" href="{{ route('admin.directory-listings.create') }}">New listing</a>
        </section>

        <section class="admin-panel-card admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>City</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listings as $listing)
                        <tr>
                            <td><strong>{{ $listing->name }}</strong><small>{{ $listing->slug }}</small></td>
                            <td>{{ $listing->category }}</td>
                            <td>{{ $listing->city }}</td>
                            <td>{{ $listing->featured ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="text-link" href="{{ route('admin.directory-listings.edit', $listing) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.directory-listings.destroy', $listing) }}" onsubmit="return confirm('Delete this directory listing permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-delete-link" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="admin-pagination">{{ $listings->links() }}</div>
        </section>
    </div>
@endsection
