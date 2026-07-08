<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookTagController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ebooks.tags', [
            'metaTitle' => 'Ebook Tags | Admin',
            'tags' => EbookTag::withCount('ebooks')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:ebook_tags,name'],
        ]);

        EbookTag::create($validated);

        return redirect()->route('admin.ebook-tags.index')
            ->with('status', 'Tag created successfully.');
    }

    public function update(Request $request, EbookTag $tag): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:ebook_tags,name,' . $tag->id],
        ]);

        $tag->update($validated);

        return redirect()->route('admin.ebook-tags.index')
            ->with('status', 'Tag updated successfully.');
    }

    public function destroy(Request $request, EbookTag $tag): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $tag->delete();

        return redirect()->route('admin.ebook-tags.index')
            ->with('status', 'Tag deleted successfully.');
    }
}
