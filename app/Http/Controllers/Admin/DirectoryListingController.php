<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectoryListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DirectoryListingController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.directory-listings.index', [
            'metaTitle' => 'Directory Listings | SettleANZ Admin',
            'listings' => DirectoryListing::query()->orderByDesc('featured')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.directory-listings.create', [
            'metaTitle' => 'New Directory Listing | SettleANZ Admin',
            'listing' => new DirectoryListing([
                'rating' => '4.8',
                'featured' => false,
                'is_published' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validateListing($request);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['services'] = collect(explode(PHP_EOL, $validated['services']))->map(fn ($item) => trim($item))->filter()->values()->all();

        $listing = DirectoryListing::create($validated);

        return redirect()->route('admin.directory-listings.edit', $listing)->with('status', 'Directory listing created successfully.');
    }

    public function edit(Request $request, DirectoryListing $directoryListing): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.directory-listings.edit', [
            'metaTitle' => 'Edit Directory Listing | SettleANZ Admin',
            'listing' => $directoryListing,
        ]);
    }

    public function update(Request $request, DirectoryListing $directoryListing): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validateListing($request, $directoryListing->id);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['services'] = collect(explode(PHP_EOL, $validated['services']))->map(fn ($item) => trim($item))->filter()->values()->all();

        $directoryListing->update($validated);

        return redirect()->route('admin.directory-listings.edit', $directoryListing)->with('status', 'Directory listing updated successfully.');
    }

    public function destroy(Request $request, DirectoryListing $directoryListing): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $directoryListing->delete();

        return redirect()->route('admin.directory-listings.index')->with('status', 'Directory listing deleted successfully.');
    }

    public function toggleFeatured(Request $request, DirectoryListing $directoryListing): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $directoryListing->update(['featured' => !$directoryListing->featured]);

        $status = $directoryListing->featured ? 'Listing added to featured.' : 'Listing removed from featured.';
        return redirect()->route('admin.directory-listings.index')->with('status', $status);
    }

    protected function validateListing(Request $request, ?int $listingId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:directory_listings,slug,' . $listingId],
            'category' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'rating' => ['required', 'numeric', 'between:0,5'],
            'description' => ['required', 'string', 'max:255'],
            'full_description' => ['nullable', 'string'],
            'services' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'website' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'booking_url' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }
}
