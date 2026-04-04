@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Partners</p>
                <h2>Create directory listing</h2>
                <p>Add a new service provider for the public directory and migration or housing pages.</p>
            </div>
            <a class="button button--small button--ghost" href="{{ route('admin.directory-listings.index') }}">Back</a>
        </section>

        @include('admin.directory-listings.partials.form', ['action' => route('admin.directory-listings.store'), 'method' => 'POST'])
    </div>
@endsection
