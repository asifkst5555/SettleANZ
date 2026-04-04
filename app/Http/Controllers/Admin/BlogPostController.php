<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.blog-posts.index', [
            'metaTitle' => 'Blog Posts | SettleANZ Admin',
            'posts' => BlogPost::query()->orderByDesc('published_at')->paginate(15),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.blog-posts.create', [
            'metaTitle' => 'New Blog Post | SettleANZ Admin',
            'post' => new BlogPost([
                'author_name' => 'SettleANZ Team',
                'reading_time' => '6 min read',
                'image_class' => 'guide-feature-card__image--teal',
                'is_published' => true,
                'is_featured_home' => false,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validatePost($request);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $post = BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.edit', $post)->with('status', 'Blog post created successfully.');
    }

    public function edit(Request $request, BlogPost $blogPost): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.blog-posts.edit', [
            'metaTitle' => 'Edit Blog Post | SettleANZ Admin',
            'post' => $blogPost,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validatePost($request, $blogPost->id);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $blogPost->update($validated);

        return redirect()->route('admin.blog-posts.edit', $blogPost)->with('status', 'Blog post updated successfully.');
    }

    public function destroy(Request $request, BlogPost $blogPost): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('status', 'Blog post deleted successfully.');
    }

    protected function validatePost(Request $request, ?int $postId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug,' . $postId],
            'category' => ['required', 'string', 'max:80'],
            'excerpt' => ['required', 'string', 'max:400'],
            'author_name' => ['required', 'string', 'max:120'],
            'reading_time' => ['nullable', 'string', 'max:40'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_class' => ['nullable', 'string', 'max:120'],
            'intro_content' => ['nullable', 'string'],
            'checks_content' => ['nullable', 'string'],
            'next_steps_content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured_home' => ['nullable', 'boolean'],
        ]);
    }
}
