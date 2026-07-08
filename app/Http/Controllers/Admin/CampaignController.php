<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\Ebook;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Jobs\ProcessCampaignEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = Campaign::with(['emailTemplate', 'ebook', 'creator'])->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        return view('admin.campaigns.index', [
            'metaTitle' => 'Campaigns | Admin',
            'campaigns' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'statuses' => ['draft', 'scheduled', 'sending', 'sent', 'cancelled'],
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.campaigns.create', [
            'metaTitle' => 'Create Campaign | Admin',
            'templates' => EmailTemplate::active()->byType('campaign')->get(),
            'ebooks' => Ebook::published()->get(['id', 'title']),
        ]);
    }

    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $campaign = Campaign::create([
            ...$request->validated(),
            'status' => 'draft',
            'created_by' => $request->user()->id,
        ]);

        if ($leadIds = $request->input('lead_ids')) {
            $campaign->leads()->attach($leadIds);
        }

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Campaign created successfully.');
    }

    public function edit(Request $request, Campaign $campaign): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.campaigns.edit', [
            'metaTitle' => "Edit: {$campaign->name} | Admin",
            'campaign' => $campaign->load(['emailTemplate', 'ebook', 'leads']),
            'templates' => EmailTemplate::active()->byType('campaign')->get(),
            'ebooks' => Ebook::published()->get(['id', 'title']),
        ]);
    }

    public function update(StoreCampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update($request->validated());

        if ($request->has('lead_ids')) {
            $campaign->leads()->sync($request->input('lead_ids', []));
        }

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Campaign updated successfully.');
    }

    public function destroy(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('status', 'Campaign deleted successfully.');
    }

    public function send(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        if (!$campaign->isDraft()) {
            return back()->withErrors(['Campaign has already been sent or is in progress.']);
        }

        ProcessCampaignEmails::dispatch($campaign);

        return redirect()->route('admin.campaigns.index')
            ->with('status', 'Campaign sending has been initiated.');
    }

    public function duplicate(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $duplicate = $campaign->replicate();
        $duplicate->name = $campaign->name . ' (Copy)';
        $duplicate->status = 'draft';
        $duplicate->sent_count = 0;
        $duplicate->open_count = 0;
        $duplicate->click_count = 0;
        $duplicate->bounce_count = 0;
        $duplicate->sent_at = null;
        $duplicate->completed_at = null;
        $duplicate->save();

        return redirect()->route('admin.campaigns.edit', $duplicate)
            ->with('status', 'Campaign duplicated successfully.');
    }
}
