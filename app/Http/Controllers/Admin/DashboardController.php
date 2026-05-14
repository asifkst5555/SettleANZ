<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.dashboard', [
            'metaTitle' => 'Admin Dashboard | SettleANZ',
            'leadCount' => Lead::count(),
            'newLeadCount' => Lead::where('status', 'new')->count(),
            'contactLeadCount' => Lead::where('form_type', 'contact-page')->count(),
            'migrationLeadCount' => Lead::where('form_type', 'migration-consultation')->count(),
            'consultationBookingCount' => Lead::where('form_type', 'consultation-booking')->count(),
            'packageBookingCount' => Lead::where('form_type', 'package_booking')->count(),
            'blogPostCount' => BlogPost::count(),
            'directoryListingCount' => DirectoryListing::count(),
            'recentLeads' => Lead::query()->latest()->limit(6)->get(),
            'recentContactLeads' => Lead::query()->where('form_type', 'contact-page')->latest()->limit(5)->get(),
            'recentConsultationBookings' => Lead::query()->where('form_type', 'consultation-booking')->latest()->limit(5)->get(),
            'recentPackageBookings' => Lead::query()->where('form_type', 'package_booking')->latest()->limit(5)->get(),
            'recentPosts' => BlogPost::query()->latest('published_at')->limit(5)->get(),
            'featuredListings' => DirectoryListing::query()->where('featured', true)->latest()->limit(5)->get(),
        ]);
    }
}
