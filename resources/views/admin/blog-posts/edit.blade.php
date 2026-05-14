@extends('admin.layouts.app')

@section('content')
    <style>
        .edit-header-status {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 0.5rem;
        }
        .edit-status-indicator {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-published {
            background: #d1fae5;
            color: #065f46;
        }
        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }
        .status-featured {
            background: #fce7f3;
            color: #831843;
        }
    </style>

    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Content</p>
                <h2>Edit Blog Post</h2>
                <p>{{ $post->title }}</p>
                <div class="edit-header-status">
                    @if ($post->is_published)
                        <span class="edit-status-indicator status-published">Published</span>
                    @else
                        <span class="edit-status-indicator status-draft">Draft</span>
                    @endif
                    @if ($post->is_featured_home)
                        <span class="edit-status-indicator status-featured">Featured on Homepage</span>
                    @endif
                </div>
            </div>
            <div class="admin-topbar__actions">
                <a class="button button--small button--ghost" href="{{ route('admin.blog-posts.index') }}">← Back</a>
                <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirmDelete(this, 'blog', 'Delete this blog post permanently? This cannot be undone.');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="button button--small button--danger" type="submit">Delete</button>
                </form>
            </div>
        </section>

        @include('admin.blog-posts.partials.form', ['action' => route('admin.blog-posts.update', $post), 'method' => 'PUT'])
    </div>
@endsection
