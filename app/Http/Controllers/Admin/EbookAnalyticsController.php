<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookAnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.downloads.analytics', [
            'metaTitle' => 'Download Analytics | Admin',
            'stats' => $this->analyticsService->getDashboardStats(),
        ]);
    }

    public function overview(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        return response()->json($this->analyticsService->getDashboardStats());
    }

    public function downloadsOverTime(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $days = $request->integer('days', 30);

        return response()->json([
            'data' => $this->analyticsService->getDownloadsOverTime($days),
        ]);
    }

    public function leadsOverTime(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $days = $request->integer('days', 30);

        return response()->json([
            'data' => $this->analyticsService->getLeadsOverTime($days),
        ]);
    }

    public function topEbooks(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $limit = $request->integer('limit', 5);

        return response()->json([
            'data' => $this->analyticsService->getTopEbooks($limit),
        ]);
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $type = $request->string('type', 'leads')->toString();
        $filters = $request->except('type');
        $data = $this->analyticsService->getExportData($type, $filters);

        $filename = "{$type}-export-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($data) {
            $output = fopen('php://output', 'w');
            if (!empty($data)) {
                fputcsv($output, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
            }
            fclose($output);
        }, 200, $headers);
    }
}
