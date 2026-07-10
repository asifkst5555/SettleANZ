<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectoryListing;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with('directoryListing')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('listing')) {
            $query->where('directory_listing_id', $request->listing);
        }

        $reviews = $query->paginate(20)->withQueryString();
        $listings = DirectoryListing::orderBy('name')->get();

        return view('admin.reviews.index', [
            'metaTitle' => 'Manage Reviews | SettleANZ Admin',
            'reviews' => $reviews,
            'listings' => $listings,
            'filters' => $request->only(['status', 'listing']),
        ]);
    }

    public function approve(Review $review): \Illuminate\Http\RedirectResponse
    {
        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review): \Illuminate\Http\RedirectResponse
    {
        $review->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review): \Illuminate\Http\RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    public function bulk(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:approve,reject,delete'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:reviews,id'],
        ]);

        $reviews = Review::whereIn('id', $validated['ids']);

        match ($validated['action']) {
            'approve' => $reviews->update(['status' => 'approved', 'approved_at' => now()]),
            'reject' => $reviews->update(['status' => 'rejected', 'rejected_at' => now()]),
            'delete' => $reviews->delete(),
        };

        $count = count($validated['ids']);
        $actionLabel = match ($validated['action']) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'delete' => 'deleted',
        };

        return redirect()->route('admin.reviews.index')
            ->with('status', "{$count} review(s) {$actionLabel} successfully.");
    }
}