<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $latestPosts = $this->blogPosts()->take(3);

        return view('home', [
            'metaTitle' => 'SettleANZ | Move To Australia With Confidence',
            'metaDescription' => 'Practical migration guides, relocation support, trusted partners, and useful resources for people starting a new life in Australia and New Zealand.',
            'latestPosts' => $latestPosts,
            'heroStats' => [
                ['value' => '10,000+', 'label' => 'Expats helped'],
                ['value' => 'Trusted', 'label' => 'Partner network'],
                ['value' => 'Expert-reviewed', 'label' => 'Content and guides'],
            ],
            'partnerLogos' => ['Wise', 'SafetyWing', 'Booking.com', 'Cigna Global', 'OFX'],
            'settings' => $this->settings(),
        ]);
    }

    public function migrationServices(): View
    {
        $agents = $this->directoryListings()
            ->where('category', 'Immigration Lawyers')
            ->map(fn ($agent) => (array) $agent->toArray())
            ->merge(
                collect(SiteDefaults::directoryListings())
                    ->where('category', 'Immigration Lawyers')
                    ->all(),
            )
            ->map(function (array $agent): object {
                $agent['id'] = $agent['id'] ?? $agent['slug'];

                return (object) $agent;
            })
            ->unique('slug')
            ->sortByDesc('featured')
            ->values()
            ->take(5);

        return view('guides.migration-services', [
            'metaTitle' => 'Australian Visa Help for Expats and Migrants | SettleANZ',
            'metaDescription' => 'Connect with registered migration agents, learn common visa pathways, and request a free visa eligibility check.',
            'agents' => $agents,
            'settings' => $this->settings(),
            'visaTypes' => [
                'Skilled migrant visas',
                'Student visas',
                'Partner visas',
                'Working holiday visas',
            ],
        ]);
    }

    public function contact(): View
    {
        return view('contact', [
            'metaTitle' => 'Contact SettleANZ | Get In Touch',
            'metaDescription' => 'Contact SettleANZ for general enquiries, business listings, partnerships, media, or relocation support.',
            'contactSubjects' => ['General Enquiry', 'Business Listing', 'Partnership', 'Media'],
            'settings' => $this->settings(),
        ]);
    }

    protected function blogPosts(): Collection
    {
        if (Schema::hasTable('blog_posts')) {
            return BlogPost::query()->where('is_published', true)->orderByDesc('published_at')->get();
        }

        return collect(SiteDefaults::blogPosts())
            ->map(fn (array $post) => (object) $post)
            ->sortByDesc('published_at')
            ->values();
    }

    protected function directoryListings(): Collection
    {
        if (Schema::hasTable('directory_listings')) {
            return DirectoryListing::query()->where('is_published', true)->orderByDesc('featured')->orderBy('name')->get();
        }

        return collect(SiteDefaults::directoryListings())
            ->map(fn (array $listing) => (object) $listing)
            ->values();
    }

    protected function settings(): array
    {
        return Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings();
    }
}
