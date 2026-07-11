<?php

namespace App\Http\Controllers;

use App\Services\DownloadService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EbookDownloadController extends Controller
{
    public function __construct(
        private readonly DownloadService $downloadService,
    ) {}

    public function viewPdf(Request $request, string $token): StreamedResponse|RedirectResponse
    {
        $downloadToken = $this->downloadService->validateToken($token);

        if (!$downloadToken) {
            return redirect()->route('ebook.download.expired')
                ->with('error', 'This link is invalid or has expired.');
        }

        $ebook = $downloadToken->ebook;
        $pdfPath = $ebook->pdf_path ?? $ebook->file_path;
        $disk = $ebook->storage_disk ?? config('ebook.storage.disk', 'local');
        $fileName = $ebook->file_name ?? 'document.pdf';

        try {
            $filePath = Storage::disk($disk)->path($pdfPath);

            if (!file_exists($filePath)) {
                throw new Exception('File not found.');
            }

            $fileSize = filesize($filePath);

            return response()->stream(function () use ($filePath) {
                $stream = fopen($filePath, 'rb');
                if ($stream) {
                    fpassthru($stream);
                    fclose($stream);
                }
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Content-Length' => $fileSize,
                'X-Robots-Tag' => 'noindex',
                'Cache-Control' => 'private, max-age=3600, must-revalidate',
            ]);
        } catch (Exception $e) {
            logger()->error('PDF view failed', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('ebook.download.error')
                ->with('error', 'Unable to load PDF.');
        }
    }

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
