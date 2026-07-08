<?php

namespace App\Services;

use App\DTOs\DownloadTokenDTO;
use App\Enums\DownloadTokenStatus;
use App\Events\EbookDownloaded;
use App\Models\DownloadLog;
use App\Models\DownloadToken;
use App\Models\Ebook;
use App\Models\Lead;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadService
{
    public function __construct(
        private readonly EbookService $ebookService,
    ) {}

    public function createToken(DownloadTokenDTO $dto): DownloadToken
    {
        return DownloadToken::create($dto->toArray());
    }

    public function validateToken(string $token): ?DownloadToken
    {
        $downloadToken = DownloadToken::where('token', $token)->first();

        if (!$downloadToken) {
            return null;
        }

        if (!$downloadToken->isValid()) {
            if ($downloadToken->isExpired()) {
                $downloadToken->expire();
            }
            return null;
        }

        return $downloadToken;
    }

    public function download(string $token, array $tracking = []): StreamedResponse
    {
        $downloadToken = $this->validateToken($token);

        if (!$downloadToken) {
            throw new Exception('Invalid or expired download token.');
        }

        $ebook = $downloadToken->ebook;
        $lead = $downloadToken->lead;

        if (!$this->ebookService->fileExists($ebook)) {
            throw new Exception('Ebook file not found.');
        }

        $filePath = $this->ebookService->getFilePath($ebook);
        $fileName = $ebook->file_name;

        return DB::transaction(function () use ($downloadToken, $ebook, $lead, $filePath, $fileName, $tracking) {
            $downloadToken->markDownloaded();

            $this->logDownload($downloadToken, $ebook, $lead, $tracking);

            $ebook->incrementDownloadCount();

            if ($lead && $lead->status === 'new') {
                $lead->update(['status' => 'downloaded']);
            }

            EbookDownloaded::dispatch($downloadToken, $ebook, $lead);

            return $this->createStreamResponse($filePath, $fileName);
        });
    }

    private function logDownload(DownloadToken $token, Ebook $ebook, ?Lead $lead, array $tracking): void
    {
        $deviceInfo = $this->parseUserAgent($tracking['user_agent'] ?? request()->userAgent());

        DownloadLog::create([
            'download_token_id' => $token->id,
            'ebook_id' => $ebook->id,
            'lead_id' => $lead->id ?? null,
            'ip_address' => $tracking['ip'] ?? request()->ip(),
            'user_agent' => $tracking['user_agent'] ?? request()->userAgent(),
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'os' => $deviceInfo['os'],
            'country' => $tracking['country'] ?? null,
            'city' => $tracking['city'] ?? null,
            'referer' => $tracking['referer'] ?? request()->header('referer'),
            'successful' => true,
        ]);
    }

    private function parseUserAgent(?string $ua): array
    {
        $result = ['device_type' => 'desktop', 'browser' => 'Unknown', 'os' => 'Unknown'];

        if (!$ua) {
            return $result;
        }

        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $ua)) {
            $result['device_type'] = preg_match('/iPad/i', $ua) ? 'tablet' : 'mobile';
        }

        if (preg_match('/(Chrome|Safari|Firefox|Edge|Opera|Brave)\/(\d+)/i', $ua, $m)) {
            $result['browser'] = $m[1];
        }

        if (preg_match('/(Windows NT|Mac OS X|Linux|Android|iOS|iPhone OS)/i', $ua, $m)) {
            $result['os'] = $m[1];
        }

        return $result;
    }

    private function createStreamResponse(string $filePath, string $fileName): StreamedResponse
    {
        return response()->stream(function () use ($filePath) {
            $stream = fopen($filePath, 'rb');
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => filesize($filePath),
            'X-Robots-Tag' => 'noindex',
            'Cache-Control' => 'private, no-transform, no-store, must-revalidate',
        ]);
    }

    public function revokeToken(DownloadToken $token): void
    {
        $token->revoke();
    }

    public function getTokenByLeadAndEbook(Lead $lead, Ebook $ebook): ?DownloadToken
    {
        return DownloadToken::where('lead_id', $lead->id)
            ->where('ebook_id', $ebook->id)
            ->where('status', DownloadTokenStatus::Active->value)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function getStats(): array
    {
        return [
            'total_downloads' => DownloadLog::count(),
            'today_downloads' => DownloadLog::whereDate('created_at', today())->count(),
            'unique_ips' => DownloadLog::distinct('ip_address')->count('ip_address'),
            'active_tokens' => DownloadToken::where('status', DownloadTokenStatus::Active->value)
                ->where('expires_at', '>', now())
                ->count(),
            'expired_tokens' => DownloadToken::where('status', DownloadTokenStatus::Expired->value)->count(),
            'downloads_by_device' => [
                'desktop' => DownloadLog::where('device_type', 'desktop')->count(),
                'mobile' => DownloadLog::where('device_type', 'mobile')->count(),
                'tablet' => DownloadLog::where('device_type', 'tablet')->count(),
            ],
        ];
    }
}
