@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Blog posts</h2>
                <p>Manage featured guides, supporting articles, and the content shown on the blog and homepage.</p>
            </div>
            <a class="button button--small" href="{{ route('admin.blog-posts.create') }}">New post</a>
        </section>

        <section class="admin-panel-card admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Homepage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td><strong>{{ $post->title }}</strong><small>{{ $post->slug }}</small></td>
                            <td>{{ $post->category }}</td>
                            <td>{{ optional($post->published_at)->format('d M Y') ?: 'Draft' }}</td>
                            <td>{{ $post->is_featured_home ? 'Featured' : 'Standard' }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="text-link" href="{{ route('admin.blog-posts.edit', $post) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete this blog post permanently?');">
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
            <div class="admin-pagination">{{ $posts->links() }}</div>
        </section>
    </div>
@endsection
