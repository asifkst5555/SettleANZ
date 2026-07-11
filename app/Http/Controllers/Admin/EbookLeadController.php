<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookLeadController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $query = Lead::with('ebook')->whereNotNull('ebook_id')->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($ebookId = $request->integer('ebook_id')) {
            $query->where('ebook_id', $ebookId);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('admin.leads.ebook-index', [
            'metaTitle' => 'Ebook Leads | Admin',
            'leads' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'statuses' => LeadStatus::cases(),
            'ebooks' => \App\Models\Ebook::published()->get(['id', 'title']),
        ]);
    }

    public function show(Request $request, Lead $lead): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        return view('admin.leads.show', [
            'metaTitle' => "Lead: {$lead->full_name} | Admin",
            'lead' => $lead->load(['ebook', 'downloadTokens' => function ($q) {
                $q->latest();
            }, 'downloadLogs' => function ($q) {
                $q->latest()->limit(20);
            }]),
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $lead->update($validated);

        return redirect()->route('admin.ebook-leads.show', $lead)
            ->with('status', 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $lead->delete();

        return redirect()->route('admin.ebook-leads.index')
            ->with('status', 'Lead deleted successfully.');
    }
}
