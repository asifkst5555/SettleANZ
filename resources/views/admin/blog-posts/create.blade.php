@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Create blog post</h2>
                <p>Add a new article for the blog and homepage guide area.</p>
            </div>
            <a class="button button--small button--ghost" href="{{ route('admin.blog-posts.index') }}">Back</a>
        </section>

        @include('admin.blog-posts.partials.form', ['action' => route('admin.blog-posts.store'), 'method' => 'POST'])
    </div>
@endsection
