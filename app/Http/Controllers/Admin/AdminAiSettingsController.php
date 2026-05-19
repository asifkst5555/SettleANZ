<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledgeEntry;
use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminAiSettingsController extends Controller
{
    private function getSettings(): array
    {
        return SiteSetting::keyValueMap();
    }

    private function saveSettings(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }
                SiteSetting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $value],
                );
            }
        }
    }

    public function apiConnection(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);
        return view('admin.ai-settings.api-connection', [
            'metaTitle' => 'AI API Connection | SettleANZ Admin',
            'settings' => $this->getSettings(),
            'hasAiApiKey' => filled($this->getSettings()['ai_openai_api_key'] ?? ''),
        ]);
    }

    public function updateApiConnection(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ai_openai_base_url' => ['required', 'string', 'max:255'],
            'ai_openai_api_key' => ['nullable', 'string', 'max:255'],
            'ai_openai_model' => ['required', 'string', 'max:120'],
            'ai_web_search_enabled' => ['nullable', 'in:0,1'],
            'ai_openai_api_key_clear' => ['nullable', 'in:0,1'],
        ]);

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

        $validated['ai_web_search_enabled'] = $request->boolean('ai_web_search_enabled') ? '1' : '0';

        $this->saveSettings($request, [
            'ai_openai_base_url',
            'ai_openai_api_key',
            'ai_openai_model',
            'ai_web_search_enabled',
        ]);

        return back()->with('status', 'AI API settings updated successfully.');
    }

    public function chatAppearance(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);
        return view('admin.ai-settings.chat-appearance', [
            'metaTitle' => 'Chat Appearance | SettleANZ Admin',
            'settings' => $this->getSettings(),
        ]);
    }

    public function updateChatAppearance(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ai_assistant_enabled' => ['nullable', 'in:0,1'],
            'ai_assistant_title' => ['required', 'string', 'max:120'],
            'ai_assistant_subtitle' => ['required', 'string', 'max:255'],
            'ai_assistant_greeting' => ['required', 'string', 'max:500'],
        ]);

        $validated['ai_assistant_enabled'] = $request->boolean('ai_assistant_enabled') ? '1' : '0';

        $this->saveSettings($request, [
            'ai_assistant_enabled',
            'ai_assistant_title',
            'ai_assistant_subtitle',
            'ai_assistant_greeting',
        ]);

        return back()->with('status', 'Chat appearance settings updated successfully.');
    }

    public function responseBehavior(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);
        return view('admin.ai-settings.response-behavior', [
            'metaTitle' => 'Response Behavior | SettleANZ Admin',
            'settings' => $this->getSettings(),
        ]);
    }

    public function updateResponseBehavior(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ai_max_bullets' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ai_max_length' => ['nullable', 'integer', 'min:200', 'max:2000'],
            'ai_response_tone' => ['nullable', 'in:professional,friendly,concise,detailed'],
            'ai_response_format' => ['nullable', 'in:bullets,mixed,paragraphs'],
            'ai_response_language' => ['nullable', 'in:en,hi,es,ar,zh'],
        ]);

        $validated['ai_max_bullets'] = (string) ($validated['ai_max_bullets'] ?? 5);
        $validated['ai_max_length'] = (string) ($validated['ai_max_length'] ?? 900);
        $validated['ai_response_tone'] = $validated['ai_response_tone'] ?? 'professional';
        $validated['ai_response_format'] = $validated['ai_response_format'] ?? 'bullets';
        $validated['ai_response_language'] = $validated['ai_response_language'] ?? 'en';

        $this->saveSettings($request, [
            'ai_max_bullets',
            'ai_max_length',
            'ai_response_tone',
            'ai_response_format',
            'ai_response_language',
        ]);

        return back()->with('status', 'Response behavior settings updated successfully.');
    }

    public function contentRules(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);
        return view('admin.ai-settings.content-rules', [
            'metaTitle' => 'Content Rules | SettleANZ Admin',
            'settings' => $this->getSettings(),
        ]);
    }

    public function updateContentRules(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ai_include_page_links' => ['nullable', 'in:0,1'],
            'ai_show_sources' => ['nullable', 'in:0,1'],
            'ai_stay_focused' => ['nullable', 'in:0,1'],
            'ai_avoid_ai_phrases' => ['nullable', 'in:0,1'],
            'ai_use_real_examples' => ['nullable', 'in:0,1'],
            'ai_professional_disclaimer' => ['nullable', 'in:0,1'],
            'ai_uncertainty_handling' => ['nullable', 'in:say_uncertain,recommend,best_guess'],
        ]);

        $validated['ai_include_page_links'] = $request->boolean('ai_include_page_links') ? '1' : '0';
        $validated['ai_show_sources'] = $request->boolean('ai_show_sources') ? '1' : '0';
        $validated['ai_stay_focused'] = $request->boolean('ai_stay_focused') ? '1' : '0';
        $validated['ai_avoid_ai_phrases'] = $request->boolean('ai_avoid_ai_phrases') ? '1' : '0';
        $validated['ai_use_real_examples'] = $request->boolean('ai_use_real_examples') ? '1' : '0';
        $validated['ai_professional_disclaimer'] = $request->boolean('ai_professional_disclaimer') ? '1' : '0';
        $validated['ai_uncertainty_handling'] = $validated['ai_uncertainty_handling'] ?? 'say_uncertain';

        $this->saveSettings($request, [
            'ai_include_page_links',
            'ai_show_sources',
            'ai_stay_focused',
            'ai_avoid_ai_phrases',
            'ai_use_real_examples',
            'ai_professional_disclaimer',
            'ai_uncertainty_handling',
        ]);

        return back()->with('status', 'Content rules updated successfully.');
    }

    public function customPrompts(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);
        return view('admin.ai-settings.custom-prompts', [
            'metaTitle' => 'Custom Prompts | SettleANZ Admin',
            'settings' => $this->getSettings(),
        ]);
    }

    public function updateCustomPrompts(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ai_follow_up_phrase' => ['nullable', 'string', 'max:255'],
            'ai_closing_phrase' => ['nullable', 'string', 'max:255'],
            'ai_assistant_system_prompt' => ['nullable', 'string', 'max:5000'],
        ]);

        $this->saveSettings($request, [
            'ai_follow_up_phrase',
            'ai_closing_phrase',
            'ai_assistant_system_prompt',
        ]);

        return back()->with('status', 'Custom prompts updated successfully.');
    }

    public function knowledgeBase(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $customKnowledgeCount = Schema::hasTable('ai_knowledge_entries')
            ? AiKnowledgeEntry::query()->where('is_active', true)->count()
            : 0;

        return view('admin.ai-settings.knowledge-base', [
            'metaTitle' => 'Knowledge Base | SettleANZ Admin',
            'settings' => $this->getSettings(),
            'customKnowledgeCount' => $customKnowledgeCount,
        ]);
    }
}
