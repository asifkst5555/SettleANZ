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

        $settings = SiteSetting::keyValueMap();

        return view('admin.settings.edit', [
            'metaTitle' => 'API Integration Settings | SettleANZ Admin',
            'settings' => $settings,
            'hasAiApiKey' => filled($settings['ai_openai_api_key'] ?? ''),
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
            'ai_assistant_system_prompt' => ['nullable', 'string', 'max:5000'],
            'ai_openai_api_key' => ['nullable', 'string', 'max:255'],
            'ai_openai_base_url' => ['required', 'string', 'max:255'],
            'ai_openai_model' => ['required', 'string', 'max:120'],
            'ai_web_search_enabled' => ['nullable', 'in:0,1'],
            'ai_max_bullets' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ai_max_length' => ['nullable', 'integer', 'min:200', 'max:2000'],
            'ai_response_tone' => ['nullable', 'in:professional,friendly,concise,detailed'],
            'ai_include_page_links' => ['nullable', 'in:0,1'],
            'ai_show_sources' => ['nullable', 'in:0,1'],
            'ai_response_language' => ['nullable', 'in:en,hi,es,ar,zh'],
            'ai_response_format' => ['nullable', 'in:bullets,mixed,paragraphs'],
            'ai_stay_focused' => ['nullable', 'in:0,1'],
            'ai_avoid_ai_phrases' => ['nullable', 'in:0,1'],
            'ai_use_real_examples' => ['nullable', 'in:0,1'],
            'ai_professional_disclaimer' => ['nullable', 'in:0,1'],
            'ai_uncertainty_handling' => ['nullable', 'in:say_uncertain,recommend,best_guess'],
            'ai_follow_up_phrase' => ['nullable', 'string', 'max:255'],
            'ai_closing_phrase' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['ai_assistant_enabled'] = $request->boolean('ai_assistant_enabled') ? '1' : '0';
        $validated['ai_web_search_enabled'] = $request->boolean('ai_web_search_enabled') ? '1' : '0';
        $validated['ai_include_page_links'] = $request->boolean('ai_include_page_links') ? '1' : '0';
        $validated['ai_show_sources'] = $request->boolean('ai_show_sources') ? '1' : '0';
        $validated['ai_stay_focused'] = $request->boolean('ai_stay_focused') ? '1' : '0';
        $validated['ai_avoid_ai_phrases'] = $request->boolean('ai_avoid_ai_phrases') ? '1' : '0';
        $validated['ai_use_real_examples'] = $request->boolean('ai_use_real_examples') ? '1' : '0';
        $validated['ai_professional_disclaimer'] = $request->boolean('ai_professional_disclaimer') ? '1' : '0';
        $validated['ai_max_bullets'] = (string) ($validated['ai_max_bullets'] ?? 5);
        $validated['ai_max_length'] = (string) ($validated['ai_max_length'] ?? 900);
        $validated['ai_response_tone'] = $validated['ai_response_tone'] ?? 'professional';
        $validated['ai_response_language'] = $validated['ai_response_language'] ?? 'en';
        $validated['ai_response_format'] = $validated['ai_response_format'] ?? 'bullets';
        $validated['ai_uncertainty_handling'] = $validated['ai_uncertainty_handling'] ?? 'say_uncertain';
        $validated['ai_follow_up_phrase'] = $validated['ai_follow_up_phrase'] ?? 'Let me know if you want more detailed information on this.';
        $validated['ai_closing_phrase'] = $validated['ai_closing_phrase'] ?? '';

        $existingApiKey = SiteSetting::getValue('ai_openai_api_key', '');
        $submittedApiKey = trim((string) ($validated['ai_openai_api_key'] ?? ''));
        $clearApiKey = $request->boolean('ai_openai_api_key_clear');

        if ($clearApiKey) {
            $validated['ai_openai_api_key'] = '';
        } elseif ($submittedApiKey === '' && filled($existingApiKey)) {
            $validated['ai_openai_api_key'] = $existingApiKey;
        } else {
            $validated['ai_openai_api_key'] = $submittedApiKey;
        }

        foreach (array_keys(SiteDefaults::siteSettings()) as $key) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $validated[$key] ?? ''],
            );
        }

        return back()->with('status', 'API integration settings updated successfully.');
    }
}
