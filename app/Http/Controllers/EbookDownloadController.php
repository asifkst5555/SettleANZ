<?php

namespace App\Http\Controllers;

use App\Services\DownloadService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EbookDownloadController extends Controller
{
    public function __construct(
        private readonly DownloadService $downloadService,
    ) {}

    public function download(Request $request, string $token): StreamedResponse|RedirectResponse
    {
        $downloadToken = $this->downloadService->validateToken($token);

        if (!$downloadToken) {
            return redirect()->route('ebook.download.expired')
                ->with('error', 'This download link is invalid or has expired.');
        }

        try {
            return $this->downloadService->download($token, [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'country' => $request->header('CF-IPCountry'),
            ]);
        } catch (Exception $e) {
            logger()->error('Download failed', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('ebook.download.error')
                ->with('error', $e->getMessage());
        }
    }

    public function expired(): \Illuminate\View\View
    {
        return view('landing.download-expired', [
            'metaTitle' => 'Download Link Expired | ' . config('app.name'),
        ]);
    }

    public function error(): \Illuminate\View\View
    {
        return view('landing.download-error', [
            'metaTitle' => 'Download Error | ' . config('app.name'),
        ]);
    }
}
