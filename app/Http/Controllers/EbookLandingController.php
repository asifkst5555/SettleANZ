<?php

namespace App\Http\Controllers;

use App\DTOs\LeadDTO;
use App\Http\Requests\LeadCaptureRequest;
use App\Models\Ebook;
use App\Services\LeadCaptureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EbookLandingController extends Controller
{
    public function __construct(
        private readonly LeadCaptureService $leadCaptureService,
    ) {}

    public function show(string $slug): View
    {
        $ebook = Ebook::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('landing.ebook-offer', [
            'metaTitle' => $ebook->title,
            'metaDescription' => str($ebook->description)->limit(160),
            'ebook' => $ebook,
        ]);
    }

    public function capture(LeadCaptureRequest $request): RedirectResponse
    {
        $dto = LeadDTO::fromRequest(
            data: $request->validated(),
            utmParams: $this->extractUtmParams($request),
        );

        $lead = $this->leadCaptureService->findOrCreate($dto);

        return redirect()->route('ebook.thank-you', [
            'slug' => $lead->ebook?->slug ?? 'download',
            'token' => $lead->downloadTokens()->latest()->first()?->token,
        ]);
    }

    public function thankYou(string $slug, ?string $token = null): View
    {
        $ebook = Ebook::where('slug', $slug)->first();

        return view('landing.thank-you', [
            'metaTitle' => 'Thank You | ' . config('app.name'),
            'ebook' => $ebook,
            'token' => $token,
        ]);
    }

    private function extractUtmParams(LeadCaptureRequest $request): array
    {
        return [
            'utm_source' => $request->input('utm_source', $request->string('utm_source', session('utm_source'))),
            'utm_medium' => $request->input('utm_medium', $request->string('utm_medium', session('utm_medium'))),
            'utm_campaign' => $request->input('utm_campaign', $request->string('utm_campaign', session('utm_campaign'))),
            'utm_term' => $request->input('utm_term', $request->string('utm_term', session('utm_term'))),
            'utm_content' => $request->input('utm_content', $request->string('utm_content', session('utm_content'))),
        ];
    }
}
