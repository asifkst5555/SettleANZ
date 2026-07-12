<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\EmailTemplateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailTemplateRequest;
use App\Models\EmailTemplate;
use App\Support\EmailStarterTemplates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $query = EmailTemplate::with('creator')->latest();

        if ($type = $request->string('type')->toString()) {
            $query->where('type', $type);
        }

        return view('admin.email-templates.index', [
            'metaTitle' => 'Email Templates | Admin',
            'templates' => $query->paginate(config('ebook.admin.pagination.per_page', 20))->withQueryString(),
            'types' => EmailTemplate::types(),
            'typeLabels' => EmailTemplate::typeLabels(),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        return view('admin.email-templates.create', [
            'metaTitle' => 'Create Email Template | Admin',
            'types' => EmailTemplate::types(),
            'typeLabels' => EmailTemplate::typeLabels(),
            'availableVariables' => [
                'name', 'email', 'download_link', 'download_url', 'ebook_name',
                'company_name', 'company_logo', 'website', 'support_email',
                'current_year', 'unsubscribe', 'expires_at', 'expires_in_hours',
                'view_url', 'form_type', 'enquiry_type', 'response_time',
                'ebook_description', 'days_since_download', 'download_count',
            ],
            'starterTemplates' => EmailStarterTemplates::list(),
            'defaultBodyHtml' => $this->getDefaultTemplateHtml(),
        ]);
    }

    private function getDefaultTemplateHtml(): string
    {
        return \App\Support\SystemEmailTemplates::downloadHtml();

        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Download Ready</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#f5f7fa;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;">
<tr>
<td align="center" style="padding:36px 30px 20px;">
<img src="{{ company_logo }}" alt="{{ company_name }}" width="170" style="display:block;width:170px;max-width:100%;height:auto;border:0;">
</td>
</tr>

<tr>
<td style="padding:0 40px 10px;">
<h1 style="margin:0;font-size:28px;line-height:36px;font-weight:700;color:#0f172a;">
Your 90-Day Roadmap is Ready
</h1>
</td>
</tr>

<tr>
<td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {{ lead_name }},</p>

<p style="margin:0 0 18px;">
Thank you for downloading <strong>{{ ebook_title }}</strong>.
Your download is now ready.
</p>

<p style="margin:0 0 18px;">
This secure link expires on <strong>{{ expires_at }}</strong>.<br>
You can download your file up to <strong>{{ expires_in_hours }}</strong> times during this period.
</p>
</td>
</tr>

<tr>
<td align="center" style="padding:14px 40px 26px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0">
<tr>
<td bgcolor="#0f766e" style="border-radius:8px;">
<a href="{{ download_url }}" style="display:inline-block;padding:14px 34px;font-size:16px;font-weight:bold;color:#ffffff;text-decoration:none;background:#0f766e;border-radius:8px;">
Download Your Roadmap
</a>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding:0 40px 28px;font-size:14px;line-height:24px;color:#64748b;">
If the button doesn't work, copy and paste this link into your browser:
<br><br>
<a href="{{ download_url }}" style="color:#0f766e;word-break:break-all;text-decoration:none;">
{{ download_url }}
</a>
</td>
</tr>

<tr>
<td style="padding:0 40px;">
<hr style="border:none;border-top:1px solid #e5e7eb;margin:0;">
</td>
</tr>

<tr>
<td style="padding:28px 40px;font-size:14px;line-height:24px;color:#64748b;">
If you have any questions, simply reply to this email or contact us at
<a href="mailto:{{ support_email }}" style="color:#0f766e;text-decoration:none;">{{ support_email }}</a>.
</td>
</tr>

<tr>
<td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{{ company_name }}</div>
<div style="margin-bottom:12px;">© {{ current_year }} {{ company_name }}. All rights reserved.</div>
<div>
<a href="mailto:{{ support_email }}" style="color:#64748b;text-decoration:none;">Support</a>
&nbsp;&nbsp;•&nbsp;&nbsp;
<a href="{{ unsubscribe }}" style="color:#64748b;text-decoration:none;">Unsubscribe</a>
</div>
</td>
</tr>

</table>
</center>
</body>
</html>
HTML;
    }

    public function store(StoreEmailTemplateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (!empty($validated['builder_json'])) {
            $builderArray = is_array($validated['builder_json']) ? $validated['builder_json'] : json_decode($validated['builder_json'], true);
            $validated['body_html'] = \App\Services\EmailTemplateRenderer::render($builderArray);
        }

        $dto = EmailTemplateDTO::fromRequest($validated);
        $template = EmailTemplate::create([
            ...$dto->toArray(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.email-templates.edit', $template)
            ->with('status', 'Email template created successfully.');
    }

    public function edit(Request $request, EmailTemplate $template): View
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        // Auto-update template if it contains the old checklist HTML structure
        if ($template->type === 'download' && str_contains($template->body_html, 'Roadmap is Ready!')) {
            $newHtml = $this->getDefaultTemplateHtml();
            $template->update([
                'body_html' => $newHtml,
                'builder_json' => null,
            ]);
            $template->refresh();
        }

        // Convert raw HTML templates to visual builder structure for editing where possible
        $builderJson = $template->builder_json;
        if (empty($builderJson)) {
            $builderJson = \App\Support\EmailTemplateMigrator::convert($template->body_html);
        }

        $revisions = $template->revisions()->with('creator')->latest()->get();

        return view('admin.email-templates.edit', [
            'metaTitle' => "Edit: {$template->name} | Admin",
            'template' => $template,
            'builderJson' => $builderJson,
            'revisions' => $revisions,
            'starterTemplates' => EmailStarterTemplates::list(),
            'types' => EmailTemplate::types(),
            'typeLabels' => EmailTemplate::typeLabels(),
            'availableVariables' => [
                'name', 'email', 'download_link', 'download_url', 'ebook_name',
                'company_name', 'company_logo', 'website', 'support_email',
                'current_year', 'unsubscribe', 'expires_at', 'expires_in_hours',
                'view_url', 'form_type', 'enquiry_type', 'response_time',
                'ebook_description', 'days_since_download', 'download_count',
            ],
        ]);
    }

    public function update(StoreEmailTemplateRequest $request, EmailTemplate $template): RedirectResponse
    {
        $validated = $request->validated();
        if (!empty($validated['builder_json'])) {
            $builderArray = is_array($validated['builder_json']) ? $validated['builder_json'] : json_decode($validated['builder_json'], true);
            $validated['body_html'] = \App\Services\EmailTemplateRenderer::render($builderArray);
        }

        $dto = EmailTemplateDTO::fromRequest($validated);
        $template->update($dto->toArray());

        return redirect()->route('admin.email-templates.edit', $template)
            ->with('status', 'Email template updated successfully.');
    }

    public function destroy(Request $request, EmailTemplate $template): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $template->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('status', 'Email template deleted successfully.');
    }

    public function renderPreview(Request $request): \Illuminate\Http\JsonResponse
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $request->validate([
            'builder_json' => ['required', 'string'],
            'preheader' => ['nullable', 'string'],
        ]);

        $builderArray = json_decode($request->input('builder_json'), true);
        $preheader = $request->input('preheader');
        
        $compiledHtml = \App\Services\EmailTemplateRenderer::render($builderArray, $preheader);

        return response()->json([
            'html' => $compiledHtml
        ]);
    }

    public function sendTestEmail(Request $request, EmailTemplate $template): \Illuminate\Http\JsonResponse
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $request->validate([
            'email' => ['required', 'email'],
            'subject' => ['nullable', 'string', 'max:500'],
            'body_html' => ['nullable', 'string'],
        ]);

        $email = $request->input('email');
        $subject = $request->input('subject') ?: $template->subject;
        $bodyHtml = $request->input('body_html') ?: $template->body_html;

        // Populate mock variables
        $search = [
            '{{name}}', '{{lead_name}}', '{{email}}', '{{lead_email}}',
            '{{download_url}}', '{{download_link}}', '{{expires_at}}', '{{expires_in_hours}}',
            '{{ebook_name}}', '{{ebook_title}}', '{{company_name}}', '{{website}}',
            '{{support_email}}', '{{current_year}}', '{{unsubscribe}}', '{{unsubscribe_url}}',
            '{{view_url}}', '{{form_type}}', '{{enquiry_type}}', '{{response_time}}',
            '{{ebook_description}}', '{{days_since_download}}', '{{download_count}}',
            '{{ name }}', '{{ lead_name }}', '{{ email }}', '{{ lead_email }}',
            '{{ download_url }}', '{{ download_link }}', '{{ expires_at }}', '{{ expires_in_hours }}',
            '{{ ebook_name }}', '{{ ebook_title }}', '{{ company_name }}', '{{ website }}',
            '{{ support_email }}', '{{ current_year }}', '{{ unsubscribe }}', '{{ unsubscribe_url }}',
            '{{ view_url }}', '{{ form_type }}', '{{ enquiry_type }}', '{{ response_time }}',
            '{{ ebook_description }}', '{{ days_since_download }}', '{{ download_count }}'
        ];
        $replace = [
            'Test Recipient', 'Test Recipient', $email, $email,
            url('/ebook/download-test'), '<a href="'.url('/ebook/download-test').'">Click here to download</a>', now()->addDays(3)->format('F j, Y \a\t g:i A'), '72',
            'Relocation Masterclass Guide', 'Relocation Masterclass Guide', config('app.name'), url('/'),
            'support@settleanz.com', date('Y'), url('/unsubscribe-test'), url('/unsubscribe-test'),
            url('/ebook/view-test'), 'contact-page', 'Contact enquiry', '24 hours',
            'A practical guide for your first steps after arrival.', '3', '1',
            'Test Recipient', 'Test Recipient', $email, $email,
            url('/ebook/download-test'), '<a href="'.url('/ebook/download-test').'">Click here to download</a>', now()->addDays(3)->format('F j, Y \a\t g:i A'), '72',
            'Relocation Masterclass Guide', 'Relocation Masterclass Guide', config('app.name'), url('/'),
            'support@settleanz.com', date('Y'), url('/unsubscribe-test'), url('/unsubscribe-test'),
            url('/ebook/view-test'), 'contact-page', 'Contact enquiry', '24 hours',
            'A practical guide for your first steps after arrival.', '3', '1',
        ];

        $subject = str_replace($search, $replace, $subject);
        $bodyHtml = str_replace($search, $replace, $bodyHtml);

        // Convert any logo webp inside bodyHtml to png
        $bodyHtml = str_replace('logo.webp', 'email_logo.png', $bodyHtml);

        try {
            $emailService = app(\App\Services\EmailService::class);
            $emailService->sendCustomEmail(
                to: $email,
                subject: '[TEST] ' . $subject,
                bodyHtml: $bodyHtml,
                templateId: $template->id,
            );

            return response()->json(['success' => true, 'message' => 'Test email sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send: ' . $e->getMessage()], 500);
        }
    }

    public function duplicate(Request $request, EmailTemplate $template): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $newTemplate = EmailTemplate::create([
            'name' => $template->name . ' Copy',
            'subject' => $template->subject,
            'body_html' => $template->body_html,
            'body_text' => $template->body_text,
            'builder_json' => $template->builder_json,
            'variables' => $template->variables,
            'type' => $template->type,
            'is_active' => false,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.email-templates.edit', $newTemplate)
            ->with('status', 'Email template duplicated successfully.');
    }

    public function restoreRevision(Request $request, EmailTemplate $template, \App\Models\EmailTemplateRevision $revision): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('marketing_mail.view'), 403);

        $template->update([
            'name' => $revision->name,
            'subject' => $revision->subject,
            'body_html' => $revision->body_html,
            'body_text' => $revision->body_text,
            'builder_json' => $revision->builder_json,
        ]);

        return redirect()->route('admin.email-templates.edit', $template)
            ->with('status', 'Email template restored to revision from ' . $revision->created_at->format('M d, Y H:i') . ' successfully.');
    }
}
