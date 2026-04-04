@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Edit blog post</h2>
                <p>Update article details, content blocks, and homepage visibility.</p>
            </div>
            <div class="admin-topbar__actions">
                <a class="button button--small button--ghost" href="{{ route('admin.blog-posts.index') }}">Back</a>
                <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete this blog post permanently?');">
                    @csrf
                    @method('DELETE')
                    <button class="button button--small button--danger" type="submit">Delete post</button>
                </form>
            </div>
        </section>

        @include('admin.blog-posts.partials.form', ['action' => route('admin.blog-posts.update', $post), 'method' => 'PUT'])
    </div>
@endsection
