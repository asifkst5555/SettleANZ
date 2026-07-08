<?php

namespace App\Http\Controllers;

use App\DTOs\DownloadTokenDTO;
use App\Models\Ebook;
use App\Models\Lead;
use App\Services\DownloadService;
use App\Services\EmailService;
use App\Services\LeadCaptureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function __construct(
        private readonly LeadCaptureService $leadCaptureService,
        private readonly DownloadService $downloadService,
        private readonly EmailService $emailService,
    ) {}

    public function claim(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'consent' => ['sometimes', 'boolean'],
        ]);

        $ebook = Ebook::where('slug', config('ebook.default_ebook_slug', 'settleanZ-new-arrival-checklist'))
            ->where('status', 'published')
            ->first();

        if (!$ebook) {
            $ebook = Ebook::published()->first();
        }

        if (!$ebook) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'The requested resource is not available yet. Please try again later.']);
        }

        try {
            $lead = Lead::where('email', $validated['email'])->first();

            if ($lead) {
                $lead->update([
                    'full_name' => $validated['name'],
                    'ebook_id' => $ebook->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } else {
                $lead = Lead::create([
                    'full_name' => $validated['name'],
                    'first_name' => str($validated['name'])->trim()->explode(' ')->filter()->first(),
                    'email' => $validated['email'],
                    'ebook_id' => $ebook->id,
                    'form_type' => 'homepage_roadmap',
                    'source_page' => 'homepage_roadmap',
                    'status' => 'new',
                    'consent' => (bool) ($validated['consent'] ?? false),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $ebook->increment('lead_count');
            }

            \App\Services\NotificationService::createLeadNotification($lead);

            $existingToken = $this->downloadService->getTokenByLeadAndEbook($lead, $ebook);

            if ($existingToken) {
                $token = $existingToken;
            } else {
                $dto = new DownloadTokenDTO(
                    ebookId: $ebook->id,
                    leadId: $lead->id,
                    maxDownloads: config('ebook.download.max_downloads_per_token', 5),
                    expiryHours: config('ebook.download.token_expiry_hours', 72),
                );

                $token = $this->downloadService->createToken($dto);
            }

            $this->emailService->sendDownloadEmail($lead, $token);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Check your email — we\'ve sent the download link for your free roadmap!',
                ]);
            }

            return redirect()->route('home')->with('success', 'Check your email — we\'ve sent the download link for your free roadmap!');
        } catch (\Throwable $e) {
            logger()->error('Roadmap claim failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['email' => 'Something went wrong. Please try again later.']);
        }
    }

    public function thankYou(string $token): View
    {
        return view('landing.thank-you', [
            'metaTitle' => 'Thank You | ' . config('app.name'),
            'ebook' => null,
            'token' => $token,
        ]);
    }
}
