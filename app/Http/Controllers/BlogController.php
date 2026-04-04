<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = $this->posts();

        return view('blog.index', [
            'metaTitle' => 'The SettleANZ Blog | Practical Guides for Expats',
            'metaDescription' => 'Practical guides, honest advice, and real insights for expats in Australia and New Zealand.',
            'posts' => $posts,
            'categories' => array_values(array_unique(array_merge(['All'], $posts->pluck('category')->all()))),
        ]);
    }

    public function show(string $slug): View
    {
        $posts = $this->posts();
        $post = $posts->firstWhere('slug', $slug);
        abort_if(!$post, 404);

        $relatedPosts = $posts
            ->where('category', $post->category)
            ->reject(fn ($relatedPost) => $relatedPost->slug === $post->slug)
            ->take(3)
            ->values();

        return view('blog.show', [
            'metaTitle' => $post->title . ' | SettleANZ Blog',
            'metaDescription' => $post->excerpt,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'tocItems' => [
                ['id' => 'why-it-matters', 'label' => 'Why It Matters'],
                ['id' => 'what-to-check', 'label' => 'What to Check'],
                ['id' => 'best-next-step', 'label' => 'Best Next Step'],
            ],
        ]);
    }

    protected function posts(): Collection
    {
        if (Schema::hasTable('blog_posts')) {
            return BlogPost::query()->where('is_published', true)->orderByDesc('published_at')->get();
        }

        return collect(SiteDefaults::blogPosts())
            ->map(fn (array $post) => (object) $post)
            ->sortByDesc('published_at')
            ->values();
    }
}
