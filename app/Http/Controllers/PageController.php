<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\PageSeo;
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

        return view('home', array_merge($this->seo('home', 'SettleANZ | Move To Australia With Confidence', 'Practical migration guides, relocation support, trusted partners, and useful resources for people starting a new life in Australia and New Zealand.'), [
            'latestPosts' => $latestPosts,
            'heroStats' => [
                ['value' => '10,000+', 'label' => 'Expats helped'],
                ['value' => 'Trusted', 'label' => 'Partner network'],
                ['value' => 'Expert-reviewed', 'label' => 'Content and guides'],
            ],
            'partnerLogos' => ['Wise', 'SafetyWing', 'Booking.com', 'Cigna Global', 'OFX'],
            'settings' => $this->settings(),
        ]));
    }

    public function settlementServices(): View
    {
        return view('guides.settlement-services', array_merge($this->seo('settlement-services', 'SettleANZ — Personal Concierge Services', 'Personal Concierge Services for your journey to a thriving life in Australia & New Zealand.'), [
            'settings' => $this->settings(),
        ]));
    }

    public function arriveServices(): View
    {
        return view('guides.services.arrive', array_merge($this->seo('services.arrive', 'Stage 01 - Arrive | SettleANZ', 'Your Smooth Start in a New Land. Meticulous planning before you leave and a warm welcome upon arrival.'), [
            'settings' => $this->settings(),
        ]));
    }

    public function settleServices(): View
    {
        return view('guides.services.settle', array_merge($this->seo('services.settle', 'Stage 02 - Settle | SettleANZ', 'Establish Your Foundation. Helping you establish your new life, from finding a home to setting up essential services.'), [
            'settings' => $this->settings(),
        ]));
    }

    public function workInvestServices(): View
    {
        return view('guides.services.work-invest', array_merge($this->seo('services.work-invest', 'Stage 03 - Work & Invest | SettleANZ', 'Build Your Future. Help you thrive professionally and financially in Australia or New Zealand.'), [
            'settings' => $this->settings(),
        ]));
    }

    public function enjoyServices(): View
    {
        return view('guides.services.enjoy', array_merge($this->seo('services.enjoy', 'Stage 04 - Enjoy | SettleANZ', 'Embrace Your New Life Fully. Community integration, family support, and long-term planning.'), [
            'settings' => $this->settings(),
        ]));
    }

    public function contact(): View
    {
        return view('contact', array_merge($this->seo('contact', 'Contact SettleANZ | Get In Touch', 'Contact SettleANZ for general enquiries, business listings, partnerships, media, or relocation support.'), [
            'contactSubjects' => ['General Enquiry', 'Business Listing', 'Partnership', 'Media'],
            'settings' => $this->settings(),
        ]));
    }

    public function about(): View
    {
        return view('about', array_merge($this->seo('about', "About SettleANZ | Entel's Story and Mission", "Read Entel's journey of settling in Australia and learn why SettleANZ was built to help new arrivals avoid expensive mistakes and settle with confidence."), [
            'settings' => $this->settings(),
        ]));
    }

    protected function blogPosts(): Collection
    {
        if (Schema::hasTable('blog_posts')) {
            return BlogPost::query()->where('is_published', true)->orderByDesc('published_at')->get();
        }

        return collect(SiteDefaults::blogPosts())
            ->map(function (array $post) {
                $obj = (object) $post;
                $obj->image_url = \App\Support\BlogMedia::url($obj->image ?? '');
                return $obj;
            })
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

    protected function seo(string $pageKey, string $defaultTitle, string $defaultDescription): array
    {
        if (!Schema::hasTable('page_seo')) {
            return [
                'metaTitle'       => $defaultTitle,
                'metaDescription' => $defaultDescription,
            ];
        }

        $record = PageSeo::forPage($pageKey);

        return array_filter([
            'metaTitle'       => $record?->meta_title       ?: $defaultTitle,
            'metaDescription' => $record?->meta_description ?: $defaultDescription,
            'metaOgTitle'     => $record?->og_title         ?: null,
            'metaOgDesc'      => $record?->og_description   ?: null,
            'metaOgImage'     => $record?->og_image         ?: null,
            'metaCanonical'   => $record?->canonical_url    ?: null,
            'metaNoIndex'     => $record?->no_index         ?? false,
            'metaSchemaType'  => $record?->schema_type      ?: null,
        ], fn ($v) => $v !== null && $v !== '' && $v !== false);
    }
}
