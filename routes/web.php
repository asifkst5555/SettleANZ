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
use App\Http\Controllers\Admin\AiKnowledgeController as AdminAiKnowledgeController;
use App\Http\Controllers\Admin\AdminAiSettingsController;
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

Route::get('/new-to-new-zealand', function () {
    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('new-to-new-zealand') : null;
    return view('guides.new-to-new-zealand', [
        'metaTitle'       => $seo?->meta_title       ?: 'New to New Zealand? Complete Guide for New Arrivals 2026 | SettleANZ',
        'metaDescription' => $seo?->meta_description ?: 'Just arrived in New Zealand or moving soon? Here\'s exactly what to do first — IRD number, bank account, ACC, housing — in the right order.',
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
})->name('guides.new-to-new-zealand');

Route::get('/settlement-services', [PageController::class, 'settlementServices'])->name('guides.settlement-services');
Route::get('/settlement-services/arrive', [PageController::class, 'arriveServices'])->name('services.arrive');
Route::get('/settlement-services/settle', [PageController::class, 'settleServices'])->name('services.settle');
Route::get('/settlement-services/work-invest', [PageController::class, 'workInvestServices'])->name('services.work-invest');
Route::get('/settlement-services/enjoy', [PageController::class, 'enjoyServices'])->name('services.enjoy');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
Route::get('/directory/{slug}', [DirectoryController::class, 'show'])->name('directory.show');
Route::post('/directory/{slug}/review', [DirectoryController::class, 'storeReview'])->name('directory.review.store');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/housing', function () {
    return redirect()->route('guides.settlement-services', 301);
})->name('guides.housing');

Route::get('/banking', function () {
    return redirect()->route('guides.settlement-services', 301);
})->name('guides.banking');

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

    // AI Settings with sidebar navigation
    Route::prefix('ai-settings')->name('admin.ai-settings.')->group(function () {
        Route::get('/api-connection', [AdminAiSettingsController::class, 'apiConnection'])->name('api-connection');
        Route::put('/api-connection', [AdminAiSettingsController::class, 'updateApiConnection'])->name('update-api-connection');
        Route::get('/chat-appearance', [AdminAiSettingsController::class, 'chatAppearance'])->name('chat-appearance');
        Route::put('/chat-appearance', [AdminAiSettingsController::class, 'updateChatAppearance'])->name('update-chat-appearance');
        Route::get('/response-behavior', [AdminAiSettingsController::class, 'responseBehavior'])->name('response-behavior');
        Route::put('/response-behavior', [AdminAiSettingsController::class, 'updateResponseBehavior'])->name('update-response-behavior');
        Route::get('/content-rules', [AdminAiSettingsController::class, 'contentRules'])->name('content-rules');
        Route::put('/content-rules', [AdminAiSettingsController::class, 'updateContentRules'])->name('update-content-rules');
        Route::get('/custom-prompts', [AdminAiSettingsController::class, 'customPrompts'])->name('custom-prompts');
        Route::put('/custom-prompts', [AdminAiSettingsController::class, 'updateCustomPrompts'])->name('update-custom-prompts');
        Route::get('/knowledge-base', [AdminAiSettingsController::class, 'knowledgeBase'])->name('knowledge-base');
    });

    Route::get('/ai-knowledge', [AdminAiKnowledgeController::class, 'index'])->name('admin.ai-knowledge.index');
    Route::get('/ai-knowledge/create', [AdminAiKnowledgeController::class, 'create'])->name('admin.ai-knowledge.create');
    Route::get('/ai-knowledge/generate', [AdminAiKnowledgeController::class, 'generateForm'])->name('admin.ai-knowledge.generate-form');
    Route::post('/ai-knowledge/generate', [AdminAiKnowledgeController::class, 'generate'])->name('admin.ai-knowledge.generate');
    Route::post('/ai-knowledge', [AdminAiKnowledgeController::class, 'store'])->name('admin.ai-knowledge.store');
    Route::get('/ai-knowledge/{aiKnowledge}/edit', [AdminAiKnowledgeController::class, 'edit'])->name('admin.ai-knowledge.edit');
    Route::put('/ai-knowledge/{aiKnowledge}', [AdminAiKnowledgeController::class, 'update'])->name('admin.ai-knowledge.update');
    Route::delete('/ai-knowledge/{aiKnowledge}', [AdminAiKnowledgeController::class, 'destroy'])->name('admin.ai-knowledge.destroy');
    Route::put('/ai-knowledge/{aiKnowledge}/toggle-active', [AdminAiKnowledgeController::class, 'toggleActive'])->name('admin.ai-knowledge.toggle-active');

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
