<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EbookSettingsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $siteSettings = SiteSetting::keyValueMap();

        return view('admin.settings.ebook', [
            'metaTitle' => 'Ebook System Settings | Admin',
            'settings' => Setting::getGroup('ebook'),
            'storageDisks' => ['local' => 'Local Storage', 's3' => 'Amazon S3'],
            'aiProvider' => $siteSettings['ai_openai_base_url'] ?? config('ebook.ai.providers.openai.url'),
            'aiModel' => $siteSettings['ai_openai_model'] ?? config('ebook.ai.model'),
            'hasAiKey' => filled($siteSettings['ai_openai_api_key'] ?? ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $validated = $request->validate([
            'token_expiry_hours' => ['required', 'integer', 'min:1', 'max:720'],
            'max_downloads_per_token' => ['required', 'integer', 'min:1', 'max:100'],
            'require_email_verification' => ['boolean'],
            'max_file_size' => ['required', 'integer', 'min:1048576', 'max:524288000'],
        ]);

        Setting::setValue('ebook_token_expiry_hours', $validated['token_expiry_hours'], 'ebook', 'integer', 'Download token expiration in hours');
        Setting::setValue('ebook_max_downloads_per_token', $validated['max_downloads_per_token'], 'ebook', 'integer', 'Maximum downloads per token');
        Setting::setValue('ebook_require_email_verification', $validated['require_email_verification'] ?? false, 'ebook', 'boolean', 'Require email verification before download');
        Setting::setValue('ebook_max_file_size', $validated['max_file_size'], 'ebook', 'integer', 'Maximum file size in bytes');

        return redirect()->route('admin.ebook-settings.index')
            ->with('status', 'Settings updated successfully.');
    }
}
