<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class SeoAssetController extends Controller
{
    public function sitemap(): Response
    {
        $pages = collect([
            ['loc' => route('home'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('contact'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('blog.index'), 'lastmod' => now(), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('directory.index'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('guides.new-to-australia'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('guides.settlement-services'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('guides.housing'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('guides.banking'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('guides.migration-services'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('privacy-policy'), 'lastmod' => now(), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('terms-of-service'), 'lastmod' => now(), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ]);

        $posts = collect();
        if (Schema::hasTable('blog_posts')) {
            $query = BlogPost::query()
                ->where('is_published', true);

            if (Schema::hasColumn('blog_posts', 'no_index')) {
                $query->where('no_index', false);
            }

            $posts = $query
                ->get()
                ->map(fn (BlogPost $post) => [
                    'loc' => route('blog.show', $post->slug),
                    'lastmod' => $post->updated_at ?: $post->published_at ?: $post->created_at ?: now(),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ]);
        }

        $listings = collect();
        if (Schema::hasTable('directory_listings')) {
            $listings = DirectoryListing::query()
                ->where('is_published', true)
                ->get()
                ->map(fn (DirectoryListing $listing) => [
                    'loc' => route('directory.show', $listing->slug),
                    'lastmod' => $listing->updated_at ?: $listing->created_at ?: now(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ]);
        }

        $urls = $pages->merge($posts)->merge($listings)->values();

        $xml = view('seo.sitemap', [
            'urls' => $urls,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            '',
            'Sitemap: ' . route('sitemap'),
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
