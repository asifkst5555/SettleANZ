@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Configuration</p>
                <h2>API Integration Settings</h2>
                <p>Manage AI assistant behavior, WhatsApp destinations, and shared website contact settings.</p>
            </div>
        </section>

        <section class="admin-panel-card">
            <form class="admin-edit-form" method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>AI Assistance Settings</h3>
                        <p>Control the website AI chat box content, model connection, and live web search behavior.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label>
                            <span>Enable AI assistant</span>
                            <select class="pro-select" name="ai_assistant_enabled">
                                <option value="1" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '1')>Enabled</option>
                                <option value="0" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '0')>Disabled</option>
                            </select>
                        </label>
                        <label>
                            <span>Enable web search</span>
                            <select class="pro-select" name="ai_web_search_enabled">
                                <option value="1" @selected(old('ai_web_search_enabled', $settings['ai_web_search_enabled'] ?? '1') === '1')>Enabled</option>
                                <option value="0" @selected(old('ai_web_search_enabled', $settings['ai_web_search_enabled'] ?? '1') === '0')>Disabled</option>
                            </select>
                        </label>
                        <label><span>Assistant title</span><input type="text" name="ai_assistant_title" value="{{ old('ai_assistant_title', $settings['ai_assistant_title']) }}" required></label>
                        <label><span>Assistant subtitle</span><input type="text" name="ai_assistant_subtitle" value="{{ old('ai_assistant_subtitle', $settings['ai_assistant_subtitle']) }}" required></label>
                        <label class="admin-form-grid__full"><span>Assistant greeting</span><textarea name="ai_assistant_greeting" rows="4" required>{{ old('ai_assistant_greeting', $settings['ai_assistant_greeting']) }}</textarea></label>
                        <label class="admin-form-grid__full">
                            <span>Assistant custom instruction (training)</span>
                            <textarea name="ai_assistant_system_prompt" rows="5" placeholder="Optional: add tone, domain constraints, or answer format rules.">{{ old('ai_assistant_system_prompt', $settings['ai_assistant_system_prompt'] ?? '') }}</textarea>
                        </label>
                        <label>
                            <span>OpenAI API key</span>
                            <input type="password" name="ai_openai_api_key" value="" placeholder="{{ $hasAiApiKey ? 'Stored key is kept unless you replace it' : 'sk-...' }}" autocomplete="new-password">
                            @if ($hasAiApiKey)
                                <small style="display:block;margin-top:6px;color:#667788;">A key is already stored. Leave this blank to keep it, or paste a new one to replace it.</small>
                            @else
                                <small style="display:block;margin-top:6px;color:#667788;">No API key is stored yet.</small>
                            @endif
                        </label>
                        <label>
                            <span>Clear stored API key</span>
                            <select class="pro-select" name="ai_openai_api_key_clear">
                                <option value="0" selected>No</option>
                                <option value="1">Yes, remove it</option>
                            </select>
                        </label>
                        <label><span>OpenAI base URL</span><input type="text" name="ai_openai_base_url" value="{{ old('ai_openai_base_url', $settings['ai_openai_base_url']) }}" required></label>
                        <label>
                            <span>OpenAI model</span>
                            <input type="text" name="ai_openai_model" value="{{ old('ai_openai_model', $settings['ai_openai_model']) }}" required>
                            <small style="display:block;margin-top:6px;color:#667788;">
                                Recommended for Groq blog drafts: <strong>`qwen/qwen3-32b`</strong> or <strong>`llama-3.3-70b-versatile`</strong>.
                                Avoid <strong>`openai/gpt-oss-20b`</strong> here if drafts keep failing with JSON or rate-limit errors.
                            </small>
                        </label>
                    </div>
                </div>

                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>WhatsApp Settings</h3>
                        <p>Manage WhatsApp links used across the contact page, footer, and floating actions.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label><span>Contact WhatsApp URL</span><input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}" required></label>
                        <label><span>Footer WhatsApp URL</span><input type="text" name="footer_whatsapp" value="{{ old('footer_whatsapp', $settings['footer_whatsapp']) }}" required></label>
                        <label><span>Contact response note</span><input type="text" name="contact_response_time" value="{{ old('contact_response_time', $settings['contact_response_time']) }}" required></label>
                        <label><span>Contact email</span><input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required></label>
                    </div>
                </div>

                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Website Links and CTA Settings</h3>
                        <p>Update shared social links and key website CTA destinations.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label><span>Facebook link</span><input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}"></label>
                        <label><span>Instagram link</span><input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}"></label>
                        <label><span>LinkedIn link</span><input type="text" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin']) }}"></label>
                        <label><span>Pinterest link</span><input type="text" name="social_pinterest" value="{{ old('social_pinterest', $settings['social_pinterest']) }}"></label>
                        <label><span>Founder story link</span><input type="text" name="founder_story_link" value="{{ old('founder_story_link', $settings['founder_story_link']) }}"></label>
                        <label><span>Migration CTA label</span><input type="text" name="migration_cta_label" value="{{ old('migration_cta_label', $settings['migration_cta_label']) }}" required></label>
                        <label><span>Directory apply link</span><input type="text" name="directory_apply_link" value="{{ old('directory_apply_link', $settings['directory_apply_link']) }}" required></label>
                    </div>
                </div>

                <button class="button button--large" type="submit">Save integration settings</button>
            </form>
        </section>
    </div>
@endsection
