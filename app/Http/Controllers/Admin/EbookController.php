<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\EbookDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEbookRequest;
use App\Http\Requests\UpdateEbookRequest;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\EbookTag;
use App\Services\EbookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookController extends Controller
{
    public function __construct(
        private readonly EbookService $ebookService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = Ebook::with(['category', 'tags'])->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->integer('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($search = $request->string('search')->toString()) {
            $query->search($search);
        }

        return view('admin.ebooks.index', [
            'metaTitle' => 'Ebook Library | Admin',
            'ebooks' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'categories' => EbookCategory::active()->ordered()->get(),
            'statuses' => ['draft', 'published', 'archived'],
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ebooks.create', [
            'metaTitle' => 'Create Ebook | Admin',
            'categories' => EbookCategory::active()->ordered()->get(),
            'tags' => EbookTag::orderBy('name')->get(),
        ]);
    }

    public function store(StoreEbookRequest $request): RedirectResponse
    {
        $dto = EbookDTO::fromRequest($request->validated());
        $ebook = $this->ebookService->create($dto);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook created successfully.');
    }

    public function show(Request $request, Ebook $ebook): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ebooks.show', [
            'metaTitle' => "{$ebook->title} | Admin",
            'ebook' => $ebook->load(['category', 'tags', 'versions', 'leads' => function ($q) {
                $q->latest()->limit(10);
            }]),
            'downloadLogs' => $ebook->downloadLogs()->with('lead')->latest()->limit(20)->get(),
        ]);
    }

    public function edit(Request $request, Ebook $ebook): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ebooks.edit', [
            'metaTitle' => "Edit {$ebook->title} | Admin",
            'ebook' => $ebook->load(['category', 'tags', 'versions']),
            'categories' => EbookCategory::active()->ordered()->get(),
            'tags' => EbookTag::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEbookRequest $request, Ebook $ebook): RedirectResponse
    {
        $dto = EbookDTO::fromRequest($request->validated());
        $this->ebookService->update($ebook, $dto);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook updated successfully.');
    }

    public function destroy(Request $request, Ebook $ebook): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $this->ebookService->delete($ebook);

        return redirect()->route('admin.ebooks.index')
            ->with('status', 'Ebook deleted successfully.');
    }

    public function publish(Request $request, Ebook $ebook): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $this->ebookService->publish($ebook);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook published successfully.');
    }

    public function archive(Request $request, Ebook $ebook): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $this->ebookService->archive($ebook);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook archived successfully.');
    }
}
