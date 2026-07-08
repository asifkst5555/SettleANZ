<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use App\Models\DownloadToken;
use App\Services\DownloadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function __construct(
        private readonly DownloadService $downloadService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = DownloadLog::with(['ebook:id,title', 'lead:id,full_name,email'])->latest();

        if ($ebookId = $request->integer('ebook_id')) {
            $query->where('ebook_id', $ebookId);
        }

        if ($dateFrom = $request->string('date_from')->toString()) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->string('date_to')->toString()) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return view('admin.downloads.index', [
            'metaTitle' => 'Download Logs | Admin',
            'downloads' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'ebooks' => \App\Models\Ebook::published()->get(['id', 'title']),
        ]);
    }

    public function tokens(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = DownloadToken::with(['ebook:id,title', 'lead:id,full_name,email'])->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        return view('admin.downloads.tokens', [
            'metaTitle' => 'Download Tokens | Admin',
            'tokens' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
        ]);
    }

    public function revokeToken(Request $request, DownloadToken $token): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $this->downloadService->revokeToken($token);

        return redirect()->route('admin.downloads.tokens')
            ->with('status', 'Token revoked successfully.');
    }
}
