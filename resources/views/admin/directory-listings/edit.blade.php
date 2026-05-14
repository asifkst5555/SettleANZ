@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Partners</p>
                <h2>Edit directory listing</h2>
                <p>Update listing details, service bullets, and consultation links.</p>
            </div>
            <div class="admin-topbar__actions">
                <a class="button button--small button--ghost" href="{{ route('admin.directory-listings.index') }}">Back</a>
                <form method="POST" action="{{ route('admin.directory-listings.destroy', $listing) }}" onsubmit="return confirmDelete(this, 'listing');">
                    @csrf
                    @method('DELETE')
                    <button class="button button--small button--danger" type="submit">Delete listing</button>
                </form>
            </div>
        </section>

        @include('admin.directory-listings.partials.form', ['action' => route('admin.directory-listings.update', $listing), 'method' => 'PUT'])
    </div>
@endsection
