<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookCategoryController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ebooks.categories', [
            'metaTitle' => 'Ebook Categories | Admin',
            'categories' => EbookCategory::withCount('ebooks')->ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:ebook_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        EbookCategory::create($validated);

        return redirect()->route('admin.ebook-categories.index')
            ->with('status', 'Category created successfully.');
    }

    public function update(Request $request, EbookCategory $category): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:ebook_categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.ebook-categories.index')
            ->with('status', 'Category updated successfully.');
    }

    public function destroy(Request $request, EbookCategory $category): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        if ($category->ebooks()->count() > 0) {
            return back()->withErrors(['Cannot delete category with associated ebooks.']);
        }

        $category->delete();

        return redirect()->route('admin.ebook-categories.index')
            ->with('status', 'Category deleted successfully.');
    }
}
