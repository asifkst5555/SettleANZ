<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSeo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PageSeoController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $pages = PageSeo::pages();
        $stored = Schema::hasTable('page_seo')
            ? PageSeo::query()->get()->keyBy('page_key')->all()
            : [];

        $rows = collect($pages)->map(function (array $pageConfig, string $key) use ($stored) {
            $record = $stored[$key] ?? null;
            return [
                'key'         => $key,
                'label'       => $pageConfig['label'],
                'url'         => $pageConfig['url'],
                'default_title' => $pageConfig['default_title'],
                'default_description' => $pageConfig['default_description'],
                'meta_title'  => $record?->meta_title ?: $pageConfig['default_title'],
                'meta_desc'   => $record?->meta_description ?: $pageConfig['default_description'],
                'no_index'    => (bool) ($record?->no_index ?? false),
                'has_custom'  => (bool) $record,
                'og_image'    => $record?->og_image ?? null,
                'schema_type' => $record?->schema_type ?? null,
            ];
        })->values();

        return view('admin.seo.index', [
            'metaTitle' => 'SEO Manager | SettleANZ Admin',
            'rows' => $rows,
        ]);
    }

    public function edit(Request $request, string $pageKey): View
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $pages = PageSeo::pages();
        abort_unless(isset($pages[$pageKey]), 404);

        $pageConfig = $pages[$pageKey];
        $record = Schema::hasTable('page_seo') ? PageSeo::forPage($pageKey) : null;

        return view('admin.seo.edit', [
            'metaTitle'  => "SEO: {$pageConfig['label']} | SettleANZ Admin",
            'pageKey'    => $pageKey,
            'pageConfig' => $pageConfig,
            'record'     => $record,
        ]);
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $pages = PageSeo::pages();
        abort_unless(isset($pages[$pageKey]), 404);

        $validated = $request->validate([
            'meta_title'       => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_title'         => ['nullable', 'string', 'max:120'],
            'og_description'   => ['nullable', 'string', 'max:200'],
            'og_image'         => ['nullable', 'string', 'max:255'],
            'canonical_url'    => ['nullable', 'url', 'max:255'],
            'no_index'         => ['nullable', 'boolean'],
            'schema_type'      => ['nullable', 'string', 'max:60'],
            'focus_keyword'    => ['nullable', 'string', 'max:120'],
            'secondary_keywords' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['no_index'] = $request->boolean('no_index');

        PageSeo::query()->updateOrCreate(
            ['page_key' => $pageKey],
            array_merge($validated, [
                'page_key'   => $pageKey,
                'page_label' => $pages[$pageKey]['label'],
            ])
        );

        return redirect()->route('admin.seo.index')->with('status', 'SEO for "' . $pages[$pageKey]['label'] . '" saved successfully.');
    }
}
