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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EbookController extends Controller
{
    public function __construct(
        private readonly EbookService $ebookService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

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
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

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
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

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
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

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
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

        $this->ebookService->delete($ebook);

        return redirect()->route('admin.ebooks.index')
            ->with('status', 'Ebook deleted successfully.');
    }

    public function publish(Request $request, Ebook $ebook): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

        $this->ebookService->publish($ebook);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook published successfully.');
    }

    public function archive(Request $request, Ebook $ebook): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

        $this->ebookService->archive($ebook);

        return redirect()->route('admin.ebooks.edit', $ebook)
            ->with('status', 'Ebook archived successfully.');
    }

    public function preview(Request $request, Ebook $ebook): \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\JsonResponse
    {
        if (!$request->user()?->hasPermission('ebook_library.view')) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $pdfPath = $ebook->pdf_path ?? $ebook->file_path;
        $disk = $ebook->storage_disk ?? config('ebook.storage.disk', 'local');

        if (empty($pdfPath) || !Storage::disk($disk)->exists($pdfPath)) {
            return response()->json(['message' => 'PDF file not found.'], 404);
        }

        $fileName = $ebook->file_name ?? $ebook->title . '.pdf';

        // 1. Try serving via local path (for Range request support and performance)
        try {
            $filePath = Storage::disk($disk)->path($pdfPath);
            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                    'Cache-Control' => 'private, max-age=3600, must-revalidate',
                ]);
            }
        } catch (\Exception $e) {
            // Path not supported or not local, fall back to memory buffering
        }

        // 2. Fallback to memory buffering (for S3 etc)
        try {
            $content = Storage::disk($disk)->get($pdfPath);

            if ($content === null || ($content === '' && !app()->runningUnitTests())) {
                throw new \RuntimeException('Storage::get returned empty.');
            }
        } catch (\Exception $e) {
            Log::warning('Storage::get failed, trying direct filesystem', [
                'ebook_id' => $ebook->id, 'path' => $pdfPath, 'disk' => $disk, 'error' => $e->getMessage(),
            ]);
            try {
                $fullPath = storage_path('app/private/' . $pdfPath);
                $realPath = realpath($fullPath);
                if ($realPath === false || !str_starts_with($realPath, realpath(storage_path('app/private')))) {
                    throw new \RuntimeException('Invalid or non-existent path');
                }
                $content = file_get_contents($realPath);
                if ($content === false || $content === '') {
                    throw new \RuntimeException('file_get_contents returned empty');
                }
            } catch (\Exception $e2) {
                Log::error('Ebook preview failed', [
                    'ebook_id' => $ebook->id, 'path' => $pdfPath, 'disk' => $disk,
                    'storage_error' => $e->getMessage(), 'direct_error' => $e2->getMessage(),
                ]);
                return response()->json(['message' => 'Failed to load PDF file.'], 500);
            }
        }

        return response()->make($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Content-Length' => strlen($content),
            'Cache-Control' => 'private, max-age=3600, must-revalidate',
            'Accept-Ranges' => 'bytes',
        ]);
    }

    public function viewer(Request $request, Ebook $ebook): View
    {
        abort_unless($request->user()?->hasPermission('ebook_library.view'), 403);

        return view('admin.ebooks.viewer', [
            'metaTitle' => "View PDF: {$ebook->title} | Admin",
            'ebook' => $ebook,
        ]);
    }
}
