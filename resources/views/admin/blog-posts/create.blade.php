@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Create New Blog Post</h2>
                <p>Write a compelling article for your audience. Use the tabs below to organize your content.</p>
            </div>
            <a class="button button--small button--ghost" href="{{ route('admin.blog-posts.index') }}">← Back to Posts</a>
        </section>

        @include('admin.blog-posts.partials.form', ['action' => route('admin.blog-posts.store'), 'method' => 'POST'])
    </div>
@endsection
