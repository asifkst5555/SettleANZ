<?php

namespace App\Http\Controllers;

use App\Models\DirectoryListing;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use App\Support\SiteDefaults;
use Illuminate\Http\Request;
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

        $reviews = Schema::hasTable('reviews')
            ? $listing->approvedReviews()->latest()->get()
            : collect();

        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : $listing->rating;

        return view('directory.show', [
            'metaTitle' => $listing->name . ' | SettleANZ Directory',
            'metaDescription' => $listing->description,
            'listing' => $listing,
            'reviews' => $reviews,
            'avgRating' => $avgRating,
        ]);
    }

    public function storeReview(Request $request, string $slug): \Illuminate\Http\RedirectResponse
    {
        $listing = $this->listings()->firstWhere('slug', $slug);
        abort_if(!$listing, 404);

        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'reviewer_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:20|max:1000',
        ]);

        if (Schema::hasTable('reviews')) {
            $review = Review::create([
                'directory_listing_id' => $listing->id,
                'reviewer_name' => $validated['reviewer_name'],
                'reviewer_email' => $validated['reviewer_email'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'status' => 'pending',
            ]);

            // Create notification for new review
            NotificationService::createReviewNotification($review);
        }

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted and will be visible after approval.');
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
