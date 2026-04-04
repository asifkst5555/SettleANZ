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
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.settings.edit', [
            'metaTitle' => 'API Integration Settings | SettleANZ Admin',
            'settings' => SiteSetting::keyValueMap(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'contact_email' => ['required', 'email'],
            'contact_whatsapp' => ['required', 'string', 'max:255'],
            'contact_response_time' => ['required', 'string', 'max:255'],
            'footer_whatsapp' => ['required', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_pinterest' => ['nullable', 'string', 'max:255'],
            'founder_story_link' => ['nullable', 'string', 'max:255'],
            'migration_cta_label' => ['required', 'string', 'max:120'],
            'directory_apply_link' => ['required', 'string', 'max:255'],
            'ai_assistant_enabled' => ['nullable', 'in:0,1'],
            'ai_assistant_title' => ['required', 'string', 'max:120'],
            'ai_assistant_subtitle' => ['required', 'string', 'max:255'],
            'ai_assistant_greeting' => ['required', 'string', 'max:500'],
            'ai_openai_api_key' => ['nullable', 'string', 'max:255'],
            'ai_openai_base_url' => ['required', 'string', 'max:255'],
            'ai_openai_model' => ['required', 'string', 'max:120'],
            'ai_web_search_enabled' => ['nullable', 'in:0,1'],
        ]);

        $validated['ai_assistant_enabled'] = $request->boolean('ai_assistant_enabled') ? '1' : '0';
        $validated['ai_web_search_enabled'] = $request->boolean('ai_web_search_enabled') ? '1' : '0';

        foreach (array_keys(SiteDefaults::siteSettings()) as $key) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $validated[$key] ?? ''],
            );
        }

        return back()->with('status', 'API integration settings updated successfully.');
    }
}
