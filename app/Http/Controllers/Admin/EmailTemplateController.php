<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\EmailTemplateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = EmailTemplate::with('creator')->latest();

        if ($type = $request->string('type')->toString()) {
            $query->where('type', $type);
        }

        return view('admin.email-templates.index', [
            'metaTitle' => 'Email Templates | Admin',
            'templates' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'types' => ['download', 'campaign', 'follow_up', 'verification'],
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.email-templates.create', [
            'metaTitle' => 'Create Email Template | Admin',
            'types' => ['download', 'campaign', 'follow_up', 'verification'],
            'availableVariables' => [
                'lead_name', 'lead_email', 'ebook_title', 'ebook_description',
                'ebook_author', 'download_url', 'download_link', 'expires_at',
                'expires_in_hours', 'company_name', 'company_logo', 'current_year',
            ],
        ]);
    }

    public function store(StoreEmailTemplateRequest $request): RedirectResponse
    {
        $dto = EmailTemplateDTO::fromRequest($request->validated());
        $template = EmailTemplate::create([
            ...$dto->toArray(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.email-templates.edit', $template)
            ->with('status', 'Email template created successfully.');
    }

    public function edit(Request $request, EmailTemplate $template): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.email-templates.edit', [
            'metaTitle' => "Edit: {$template->name} | Admin",
            'template' => $template,
            'types' => ['download', 'campaign', 'follow_up', 'verification'],
            'availableVariables' => [
                'lead_name', 'lead_email', 'ebook_title', 'ebook_description',
                'ebook_author', 'download_url', 'download_link', 'expires_at',
                'expires_in_hours', 'company_name', 'company_logo', 'current_year',
            ],
        ]);
    }

    public function update(StoreEmailTemplateRequest $request, EmailTemplate $template): RedirectResponse
    {
        $dto = EmailTemplateDTO::fromRequest($request->validated());
        $template->update($dto->toArray());

        return redirect()->route('admin.email-templates.edit', $template)
            ->with('status', 'Email template updated successfully.');
    }

    public function destroy(Request $request, EmailTemplate $template): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $template->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('status', 'Email template deleted successfully.');
    }
}
