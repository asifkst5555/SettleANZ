<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\PageSeo;
use App\Support\SiteDefaults;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = $this->posts();

        $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('blog') : null;

        return view('blog.index', [
            'metaTitle'      => $seo?->meta_title       ?: 'The SettleANZ Blog | Practical Guides for Expats',
            'metaDescription'=> $seo?->meta_description ?: 'Practical guides, honest advice, and real insights for expats in Australia and New Zealand.',
            'metaOgTitle'    => $seo?->og_title         ?: null,
            'metaOgDesc'     => $seo?->og_description   ?: null,
            'metaOgImage'    => $seo?->og_image         ?: null,
            'metaCanonical'  => $seo?->canonical_url    ?: null,
            'metaNoIndex'    => $seo?->no_index         ?? false,
            'metaSchemaType' => $seo?->schema_type      ?: null,
            'posts'          => $posts,
            'categories'     => array_values(array_unique(array_merge(['All'], $posts->pluck('category')->all()))),
        ]);
    }

    public function show(string $slug): View
    {
        $posts = $this->posts();
        $post = $posts->firstWhere('slug', $slug);
        abort_if(!$post, 404);
        $faqItems = $this->faqItems($post);

        $relatedPosts = $posts
            ->where('category', $post->category)
            ->reject(fn ($relatedPost) => $relatedPost->slug === $post->slug)
            ->take(3)
            ->values();

        $recentPosts = $posts
            ->reject(fn ($recentPost) => $recentPost->slug === $post->slug)
            ->take(6)
            ->values();

        $defaultTitle = $post->title . ' | SettleANZ Blog';
        $defaultDescription = $post->excerpt;
        $canonicalUrl = filled($post->canonical_url ?? null)
            ? $post->canonical_url
            : route('blog.show', $post->slug);
        $ogImage = $this->resolveOgImage($post);
        $breadcrumbItems = $this->breadcrumbItems($post);

        return view('blog.show', [
            'metaTitle' => $post->meta_title ?: $defaultTitle,
            'metaDescription' => $post->meta_description ?: $defaultDescription,
            'metaOgTitle' => $post->og_title ?: ($post->meta_title ?: $defaultTitle),
            'metaOgDesc' => $post->og_description ?: ($post->meta_description ?: $defaultDescription),
            'metaOgImage' => $ogImage,
            'metaCanonical' => $canonicalUrl,
            'metaNoIndex' => (bool) ($post->no_index ?? false),
            'metaSchemaType' => $post->schema_type ?: 'Article',
            'metaOgType' => 'article',
            'schemaData' => $this->schemaGraph($post, $canonicalUrl, $ogImage, $breadcrumbItems, $faqItems),
            'post' => $post,
            'faqItems' => $faqItems,
            'breadcrumbItems' => $breadcrumbItems,
            'relatedPosts' => $relatedPosts,
            'recentPosts' => $recentPosts,
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

    protected function resolveOgImage(object $post): ?string
    {
        if (filled($post->og_image ?? null)) {
            $value = (string) $post->og_image;

            if (Str::startsWith($value, ['http://', 'https://'])) {
                return $value;
            }

            return asset(ltrim($value, '/'));
        }

        if (filled($post->image ?? null)) {
            return asset('storage/blog/' . ltrim((string) $post->image, '/'));
        }

        return null;
    }

    protected function articleSchema(object $post, string $canonicalUrl, ?string $ogImage): array
    {
        $publishedAt = $post->published_at ?? null;
        $updatedAt = $post->updated_at ?? null;

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => $post->schema_type ?: 'Article',
            'headline' => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: $post->excerpt,
            'url' => $canonicalUrl,
            'mainEntityOfPage' => $canonicalUrl,
            'datePublished' => $publishedAt?->toIso8601String(),
            'dateModified' => $updatedAt?->toIso8601String(),
            'image' => $ogImage ? [$ogImage] : null,
            'author' => [
                '@type' => 'Person',
                'name' => $post->author_name ?: 'SettleANZ Team',
                'url' => filled($post->author_url ?? null) ? $post->author_url : route('about'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SettleANZ',
                'url' => rtrim(config('app.url'), '/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => rtrim(config('app.url'), '/') . '/media/logo.svg',
                ],
            ],
            'articleSection' => filled($post->category ?? null) ? $post->category : null,
        ]);
    }

    protected function breadcrumbItems(object $post): array
    {
        return [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Blog', 'url' => route('blog.index')],
            ['name' => $post->title, 'url' => route('blog.show', $post->slug)],
        ];
    }

    protected function breadcrumbSchema(array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    protected function faqItems(object $post): array
    {
        $items = $post->faq_items ?? [];

        if (!is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn ($item) => is_array($item) && filled($item['question'] ?? null) && filled($item['answer'] ?? null))
            ->map(fn ($item) => [
                'question' => trim((string) $item['question']),
                'answer' => trim((string) $item['answer']),
            ])
            ->values()
            ->all();
    }

    protected function faqSchema(array $faqItems): ?array
    {
        if ($faqItems === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn (array $item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ])->all(),
        ];
    }

    protected function schemaGraph(object $post, string $canonicalUrl, ?string $ogImage, array $breadcrumbItems, array $faqItems): array
    {
        $graph = [
            $this->articleSchema($post, $canonicalUrl, $ogImage),
            $this->breadcrumbSchema($breadcrumbItems),
        ];

        if ($faq = $this->faqSchema($faqItems)) {
            $graph[] = $faq;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }
}
