<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DirectoryListingController as AdminDirectoryListingController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\LeadCaptureController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/new-to-australia', function () {
    return view('guides.new-to-australia', [
        'metaTitle' => 'New to Australia? Here\'s Everything You Need to Know | SettleANZ',
        'metaDescription' => 'A practical settling-in guide covering your first week, healthcare, banking, work, housing, and useful contacts for new arrivals to Australia.',
        'readingTime' => '12 min read',
        'lastUpdated' => 'April 2, 2026',
        'tocItems' => [
            ['id' => 'before-you-arrive', 'label' => 'Before You Arrive'],
            ['id' => 'first-week', 'label' => 'First Week Checklist'],
            ['id' => 'culture', 'label' => 'Understanding Culture'],
            ['id' => 'where-to-live', 'label' => 'Where to Live'],
            ['id' => 'banking-finance', 'label' => 'Banking and Finance'],
            ['id' => 'healthcare', 'label' => 'Healthcare'],
            ['id' => 'working', 'label' => 'Working in Australia'],
            ['id' => 'staying-connected', 'label' => 'Staying Connected'],
            ['id' => 'resources', 'label' => 'Useful Contacts'],
        ],
    ]);
})->name('guides.new-to-australia');

Route::get('/housing', function () {
    return view('guides.housing', [
        'metaTitle' => 'Finding a Home in Australia as an Expat | SettleANZ',
        'metaDescription' => 'A practical housing guide for expats covering short-term stays, rentals, suburbs, common mistakes, and trusted relocation partners.',
        'bookingLabel' => 'Book a Free Relocation Call',
        'featuredPartners' => [
            ['name' => 'Harbour Move Co.', 'description' => 'Relocation support for suburb shortlists, inspection coordination, and family move planning.', 'cta' => 'Talk to Harbour Move Co.'],
            ['name' => 'Anchor Relocation', 'description' => 'Useful for first-month setup, furnished stays, and getting settled before your long-term lease begins.', 'cta' => 'Book with Anchor Relocation'],
            ['name' => 'Southern Cross Settling', 'description' => 'A hands-on relocation partner for newcomers who want help comparing areas, agents, and housing options.', 'cta' => 'Speak to Southern Cross'],
        ],
    ]);
})->name('guides.housing');

Route::get('/banking', function () {
    return view('guides.banking', [
        'metaTitle' => 'Banking in Australia as an Expat | SettleANZ',
        'metaDescription' => 'A practical expat banking guide covering account setup, transfer tools, tax file numbers, and superannuation basics.',
        'bankComparison' => [
            ['name' => 'Airwallex', 'monthly_fee' => '$0', 'transfer_fee' => 'Low FX spread', 'online_setup' => 'Yes', 'rating' => 'Best for most people', 'recommended' => true],
            ['name' => 'CommBank', 'monthly_fee' => 'Varies by account', 'transfer_fee' => 'Higher than specialist tools', 'online_setup' => 'Partial', 'rating' => 'Trusted major bank', 'recommended' => false],
            ['name' => 'Westpac', 'monthly_fee' => 'Varies by account', 'transfer_fee' => 'Moderate', 'online_setup' => 'Partial', 'rating' => 'Good branch access', 'recommended' => false],
        ],
        'transferTools' => [
            ['name' => 'Wise', 'summary' => 'Strong for transparent pricing and a smooth app experience when sending money internationally.', 'cta' => 'Compare Wise'],
            ['name' => 'OFX', 'summary' => 'Useful for larger transfers when you want rate support and a more guided process.', 'cta' => 'Compare OFX'],
            ['name' => 'WorldRemit', 'summary' => 'A familiar option for smaller personal transfers, depending on your corridor and payout method.', 'cta' => 'Compare WorldRemit'],
        ],
    ]);
})->name('guides.banking');

Route::get('/migration-services', [PageController::class, 'migrationServices'])->name('guides.migration-services');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
Route::get('/directory/{slug}', [DirectoryController::class, 'show'])->name('directory.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('guest')->name('admin.login.store');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/leads', [AdminLeadController::class, 'index'])->name('admin.leads.index');
    Route::get('/leads/{lead}/edit', [AdminLeadController::class, 'edit'])->name('admin.leads.edit');
    Route::put('/leads/{lead}', [AdminLeadController::class, 'update'])->name('admin.leads.update');
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('admin.leads.destroy');

    Route::get('/blog-posts', [AdminBlogPostController::class, 'index'])->name('admin.blog-posts.index');
    Route::get('/blog-posts/create', [AdminBlogPostController::class, 'create'])->name('admin.blog-posts.create');
    Route::post('/blog-posts', [AdminBlogPostController::class, 'store'])->name('admin.blog-posts.store');
    Route::get('/blog-posts/{blogPost}/edit', [AdminBlogPostController::class, 'edit'])->name('admin.blog-posts.edit');
    Route::put('/blog-posts/{blogPost}', [AdminBlogPostController::class, 'update'])->name('admin.blog-posts.update');
    Route::delete('/blog-posts/{blogPost}', [AdminBlogPostController::class, 'destroy'])->name('admin.blog-posts.destroy');

    Route::get('/directory-listings', [AdminDirectoryListingController::class, 'index'])->name('admin.directory-listings.index');
    Route::get('/directory-listings/create', [AdminDirectoryListingController::class, 'create'])->name('admin.directory-listings.create');
    Route::post('/directory-listings', [AdminDirectoryListingController::class, 'store'])->name('admin.directory-listings.store');
    Route::get('/directory-listings/{directoryListing}/edit', [AdminDirectoryListingController::class, 'edit'])->name('admin.directory-listings.edit');
    Route::put('/directory-listings/{directoryListing}', [AdminDirectoryListingController::class, 'update'])->name('admin.directory-listings.update');
    Route::delete('/directory-listings/{directoryListing}', [AdminDirectoryListingController::class, 'destroy'])->name('admin.directory-listings.destroy');

    Route::get('/settings', [AdminSiteSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/settings', [AdminSiteSettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');
});

Route::post('/lead-capture', [LeadCaptureController::class, 'store'])->name('lead-capture.store');
