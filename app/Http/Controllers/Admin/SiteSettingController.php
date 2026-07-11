<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $settings = SiteSetting::keyValueMap();

        return view('admin.settings.edit', [
            'metaTitle' => 'API Integration Settings | SettleANZ Admin',
            'settings' => $settings,
            'hasAiApiKey' => filled($settings['ai_openai_api_key'] ?? ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $validated = $request->validate([
            'contact_email' => ['required', 'email'],
            'contact_whatsapp' => ['required', 'string', 'max:255'],
            'contact_response_time' => ['required', 'string', 'max:255'],
            'footer_whatsapp' => ['required', 'string', 'max:255'],
            'founder_story_link' => ['nullable', 'string', 'max:255'],
            'migration_cta_label' => ['required', 'string', 'max:120'],
            'directory_apply_link' => ['required', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => (string) ($value ?? '')],
            );
        }

        return back()->with('status', 'Site settings updated successfully.');
    }

    public function socialEdit(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $settings = SiteSetting::keyValueMap();

        return view('admin.social-settings.index', [
            'metaTitle' => 'Social Media Settings | SettleANZ Admin',
            'settings' => $settings,
        ]);
    }

    public function socialUpdate(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('settings.view'), 403);

        $validated = $request->validate([
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_pinterest' => ['nullable', 'string', 'max:255'],
            'social_reddit' => ['nullable', 'string', 'max:255'],
            'social_tiktok' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => (string) ($value ?? '')],
            );
        }

        return back()->with('status', 'Social media settings updated successfully.');
    }
}
