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
            'defaultBodyHtml' => $this->getDefaultTemplateHtml(),
        ]);
    }

    private function getDefaultTemplateHtml(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f5f0e8;font-family:Arial,\'Helvetica Neue\',Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f0e8;">
<tr><td align="center" style="padding:40px 20px;">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
<tr><td align="center" style="padding:30px 0 20px 0;">
<img src="{{ company_logo }}" alt="{{ company_name }}" style="height:44px;width:auto;display:block;margin:0 auto;border:0;">
</td></tr>
<tr><td style="background:#ffffff;border-radius:12px;padding:40px 35px;">
<h2 style="color:#065e5b;font-size:24px;margin:0 0 16px 0;font-weight:700;">Hello {{ lead_name }},</h2>
<p style="color:#2c3a47;font-size:16px;line-height:1.6;margin:0 0 16px 0;">This is a custom email from {{ company_name }}.</p>
<p style="color:#2c3a47;font-size:16px;line-height:1.6;margin:0 0 20px 0;">Your {{ ebook_title }} download is ready.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;"><tr><td align="center">
<a href="{{ download_url }}" style="background:#e8773a;color:#ffffff;padding:15px 40px;border-radius:8px;text-decoration:none;font-size:16px;font-weight:bold;display:inline-block;box-shadow:0 4px 12px rgba(232,119,58,0.3);">Download Now</a>
</td></tr></table>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#e6f4f3;border-radius:8px;margin:0 0 20px 0;"><tr><td style="padding:16px 20px;">
<p style="font-size:13px;color:#0b7a75;margin:0;">&#9200; Expires {{ expires_at }} &middot; {{ expires_in_hours }} downloads allowed</p>
</td></tr></table>
</td></tr>
<tr><td align="center" style="padding:24px 20px;">
<p style="font-size:12px;color:#607080;line-height:1.5;margin:0;">&copy; {{ company_name }} {{ current_year }}. All rights reserved.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>';
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
