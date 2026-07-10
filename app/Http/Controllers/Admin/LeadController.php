<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $leadQuery = Lead::with('ebook')->latest();

        if ($status = $request->string('status')->toString()) {
            $leadQuery->where('status', $status);
        }

        if ($formType = $request->string('type')->toString()) {
            $leadQuery->where('form_type', $formType);
        }

        if ($search = $request->string('search')->toString()) {
            $leadQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('admin.leads.index', [
            'metaTitle' => 'Lead Inbox | SettleANZ Admin',
            'leads' => $leadQuery->paginate(15)->withQueryString(),
            'statuses' => ['new', 'reviewing', 'contacted', 'qualified', 'closed'],
            'types' => Lead::query()->select('form_type')->distinct()->pluck('form_type')->filter()->values(),
        ]);
    }

    public function show(Request $request, Lead $lead): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.leads.show', [
            'metaTitle' => 'View Lead | SettleANZ Admin',
            'lead' => $lead,
        ]);
    }

    public function edit(Request $request, Lead $lead): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.leads.edit', [
            'metaTitle' => 'Edit Lead | SettleANZ Admin',
            'lead' => $lead,
            'statuses' => ['new', 'reviewing', 'contacted', 'qualified', 'closed'],
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.edit', $lead)->with('status', 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted successfully.');
    }
}
