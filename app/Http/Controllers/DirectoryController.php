<?php

namespace App\Http\Controllers;

use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    public function index(): View
    {
        $listings = $this->listings();

        return view('directory.index', [
            'metaTitle' => 'Find Trusted Expat Services in Australia and New Zealand | SettleANZ',
            'metaDescription' => 'Curated expat-friendly businesses across migration, relocation, schools, finance, real estate, healthcare, banking, and insurance.',
            'listings' => $listings,
            'categories' => array_values(array_unique(array_merge(['All'], $listings->pluck('category')->all()))),
            'cities' => array_values(array_unique(array_merge(['All Cities'], $listings->pluck('city')->all()))),
            'settings' => Schema::hasTable('site_settings') ? SiteSetting::keyValueMap() : SiteDefaults::siteSettings(),
        ]);
    }

    public function show(string $slug): View
    {
        $listing = $this->listings()->firstWhere('slug', $slug);
        abort_if(!$listing, 404);

        return view('directory.show', [
            'metaTitle' => $listing->name . ' | SettleANZ Directory',
            'metaDescription' => $listing->description,
            'listing' => $listing,
        ]);
    }

    protected function listings(): Collection
    {
        if (Schema::hasTable('directory_listings')) {
            return DirectoryListing::query()->where('is_published', true)->orderByDesc('featured')->orderBy('name')->get();
        }

        return collect(SiteDefaults::directoryListings())
            ->map(fn (array $listing) => (object) $listing)
            ->sortByDesc('featured')
            ->values();
    }
}
