<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DirectoryListingController as AdminDirectoryListingController;
use App\Http\Controllers\Admin\DocumentationController as AdminDocumentationController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\PageSeoController as AdminPageSeoController;
use App\Http\Controllers\Admin\AiContentController as AdminAiContentController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\BlogController;
use App\Models\PageSeo;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\LeadCaptureController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoAssetController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SeoAssetController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoAssetController::class, 'robots'])->name('robots');

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/new-to-australia', function () {
    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('new-to-australia') : null;
    return view('guides.new-to-australia', [
        'metaTitle'       => $seo?->meta_title       ?: 'New to Australia? Complete Guide for New Arrivals 2026 | SettleANZ',
        'metaDescription' => $seo?->meta_description ?: 'Arrived in Australia or moving soon? Here\'s exactly what to do first — TFN, bank account, Medicare, housing — in the right order, from someone who did it in 2001.',
        'metaOgTitle'     => $seo?->og_title         ?: null,
        'metaOgDesc'      => $seo?->og_description   ?: null,
        'metaOgImage'     => $seo?->og_image         ?: null,
        'metaCanonical'   => $seo?->canonical_url    ?: null,
        'metaNoIndex'     => $seo?->no_index         ?? false,
        'metaSchemaType'  => $seo?->schema_type      ?: null,
        'readingTime' => '2026 guide',
        'lastUpdated' => '2026',
        'tocItems' => [
            ['id' => 'before-you-land', 'label' => 'Before You Land'],
            ['id' => 'dos-and-donts', 'label' => 'Dos and Don\'ts'],
            ['id' => 'first-7-days', 'label' => 'First 7 Days'],
            ['id' => 'faq', 'label' => 'FAQ'],
        ],
    ]);
})->name('guides.new-to-australia');

Route::get('/settlement-services', [PageController::class, 'settlementServices'])->name('guides.settlement-services');

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
Route::post('/directory/{slug}/review', [DirectoryController::class, 'storeReview'])->name('directory.review.store');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/privacy-policy', function () {
    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('privacy-policy') : null;
    return view('legal.privacy-policy', [
        'metaTitle'       => $seo?->meta_title       ?: 'Privacy Policy | SettleANZ',
        'metaDescription' => $seo?->meta_description ?: 'How SettleANZ collects, uses, and protects your personal information when using our migration and settlement services.',
        'metaOgTitle'     => $seo?->og_title         ?: null,
        'metaOgDesc'      => $seo?->og_description   ?: null,
        'metaCanonical'   => $seo?->canonical_url    ?: null,
        'metaNoIndex'     => $seo?->no_index         ?? false,
        'metaSchemaType'  => $seo?->schema_type      ?: null,
    ]);
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('terms-of-service') : null;
    return view('legal.terms-of-service', [
        'metaTitle'       => $seo?->meta_title       ?: 'Terms of Service | SettleANZ',
        'metaDescription' => $seo?->meta_description ?: 'Terms and conditions for using SettleANZ settlement services, guides, and directory.',
        'metaOgTitle'     => $seo?->og_title         ?: null,
        'metaOgDesc'      => $seo?->og_description   ?: null,
        'metaCanonical'   => $seo?->canonical_url    ?: null,
        'metaNoIndex'     => $seo?->no_index         ?? false,
        'metaSchemaType'  => $seo?->schema_type      ?: null,
    ]);
})->name('terms-of-service');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('guest')->name('admin.login.store');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Documentation
    Route::get('/documentation/seo-system', [AdminDocumentationController::class, 'seoSystemPdf'])->name('admin.documentation.seo-system');
    Route::get('/documentation/client-guide', [AdminDocumentationController::class, 'clientGuidePdf'])->name('admin.documentation.client-guide');
    
    Route::get('/leads', [AdminLeadController::class, 'index'])->name('admin.leads.index');
    Route::get('/leads/{lead}', [AdminLeadController::class, 'show'])->name('admin.leads.show');
    Route::get('/leads/{lead}/edit', [AdminLeadController::class, 'edit'])->name('admin.leads.edit');
    Route::put('/leads/{lead}', [AdminLeadController::class, 'update'])->name('admin.leads.update');
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('admin.leads.destroy');

    Route::get('/blog-posts', [AdminBlogPostController::class, 'index'])->name('admin.blog-posts.index');
    Route::get('/blog-posts/create', [AdminBlogPostController::class, 'create'])->name('admin.blog-posts.create');
    Route::post('/blog-posts/upload-image', [AdminBlogPostController::class, 'uploadImage'])->name('admin.blog-posts.upload-image');
    Route::post('/blog-posts/import-file', [AdminBlogPostController::class, 'importFile'])->name('admin.blog-posts.import-file');
    Route::post('/ai/blog-draft', [AdminAiContentController::class, 'blogDraft'])->name('admin.ai.blog-draft');
    Route::post('/ai/blog-seo', [AdminAiContentController::class, 'blogSeo'])->name('admin.ai.blog-seo');
    Route::post('/blog-posts', [AdminBlogPostController::class, 'store'])->name('admin.blog-posts.store');
    Route::get('/blog-posts/{blogPost}/edit', [AdminBlogPostController::class, 'edit'])->name('admin.blog-posts.edit');
    Route::put('/blog-posts/{blogPost}', [AdminBlogPostController::class, 'update'])->name('admin.blog-posts.update');
    Route::post('/blog-posts/{blogPost}/status', [AdminBlogPostController::class, 'updateStatus'])->name('admin.blog-posts.status');
    Route::delete('/blog-posts/{blogPost}', [AdminBlogPostController::class, 'destroy'])->name('admin.blog-posts.destroy');

    Route::get('/directory-listings', [AdminDirectoryListingController::class, 'index'])->name('admin.directory-listings.index');
    Route::get('/directory-listings/create', [AdminDirectoryListingController::class, 'create'])->name('admin.directory-listings.create');
    Route::post('/directory-listings', [AdminDirectoryListingController::class, 'store'])->name('admin.directory-listings.store');
    Route::get('/directory-listings/{directoryListing}/edit', [AdminDirectoryListingController::class, 'edit'])->name('admin.directory-listings.edit');
    Route::put('/directory-listings/{directoryListing}', [AdminDirectoryListingController::class, 'update'])->name('admin.directory-listings.update');
    Route::delete('/directory-listings/{directoryListing}', [AdminDirectoryListingController::class, 'destroy'])->name('admin.directory-listings.destroy');
    Route::post('/directory-listings/{directoryListing}/toggle-featured', [AdminDirectoryListingController::class, 'toggleFeatured'])->name('admin.directory-listings.toggle-featured');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('admin.reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    Route::get('/settings', [AdminSiteSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/settings', [AdminSiteSettingController::class, 'update'])->name('admin.settings.update');

    Route::get('/seo', [AdminPageSeoController::class, 'index'])->name('admin.seo.index');
    Route::get('/seo/{pageKey}/edit', [AdminPageSeoController::class, 'edit'])->name('admin.seo.edit');
    Route::post('/ai/page-seo', [AdminAiContentController::class, 'pageSeo'])->name('admin.ai.page-seo');
    Route::put('/seo/{pageKey}', [AdminPageSeoController::class, 'update'])->name('admin.seo.update');

    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

    // Notifications API
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/unread', [AdminNotificationController::class, 'unread'])->name('admin.notifications.unread');
    Route::post('/notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    Route::delete('/notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('admin.notifications.destroy');
});

Route::post('/lead-capture', [LeadCaptureController::class, 'store'])->name('lead-capture.store');
