@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Configuration</p>
                <h2>AI Assistant & Integration Settings</h2>
                <p>Manage AI chat behavior, API connection, knowledge base training, and website contact settings.</p>
            </div>
        </section>

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        <form class="admin-edit-form" method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            {{-- AI API Connection --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>AI API Connection</h3>
                        <p>Connect to OpenAI, Groq, or OpenRouter for intelligent AI responses. Without an API key, the AI uses the built-in knowledge base only.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label>
                            <span>API Provider</span>
                            <select class="pro-select" id="api-provider-select">
                                <option value="openai" @selected(Str::contains(old('ai_openai_base_url', $settings['ai_openai_base_url']), 'api.openai.com'))>OpenAI (api.openai.com)</option>
                                <option value="groq" @selected(Str::contains(old('ai_openai_base_url', $settings['ai_openai_base_url']), 'api.groq.com'))>Groq (api.groq.com)</option>
                                <option value="openrouter" @selected(Str::contains(old('ai_openai_base_url', $settings['ai_openai_base_url']), 'openrouter.ai'))>OpenRouter (openrouter.ai)</option>
                                <option value="custom" @selected(!Str::contains(old('ai_openai_base_url', $settings['ai_openai_base_url']), ['api.openai.com', 'api.groq.com', 'openrouter.ai']))>Custom URL</option>
                            </select>
                        </label>
                        <label>
                            <span>API Base URL</span>
                            <input type="text" name="ai_openai_base_url" id="ai-base-url" value="{{ old('ai_openai_base_url', $settings['ai_openai_base_url']) }}" required>
                        </label>
                        <label>
                            <span>API Key</span>
                            <input type="password" name="ai_openai_api_key" value="" placeholder="{{ $hasAiApiKey ? 'Stored — leave blank to keep or paste new to replace' : 'sk-...' }}" autocomplete="new-password">
                            @if ($hasAiApiKey)
                                <small style="display:block;margin-top:6px;color:#667788;">A key is already stored. Leave blank to keep it.</small>
                            @else
                                <small style="display:block;margin-top:6px;color:#e8773a;font-weight:600;">No API key stored — AI uses knowledge base fallback only.</small>
                            @endif
                        </label>
                        <label>
                            <span>Clear stored API key</span>
                            <select class="pro-select" name="ai_openai_api_key_clear">
                                <option value="0" selected>No</option>
                                <option value="1">Yes, remove it</option>
                            </select>
                        </label>
                        <label>
                            <span>AI Model</span>
                            <input type="text" name="ai_openai_model" value="{{ old('ai_openai_model', $settings['ai_openai_model']) }}" required>
                            <small style="display:block;margin-top:6px;color:#667788;">
                                OpenAI: <code>gpt-4o-mini</code> or <code>gpt-4o</code> | Groq: <code>llama-3.3-70b-versatile</code> | OpenRouter: check available models
                            </small>
                        </label>
                        <label>
                            <span>Enable web search</span>
                            <select class="pro-select" name="ai_web_search_enabled">
                                <option value="1" @selected(old('ai_web_search_enabled', $settings['ai_web_search_enabled'] ?? '1') === '1')>Enabled — AI can search the web for current facts</option>
                                <option value="0" @selected(old('ai_web_search_enabled', $settings['ai_web_search_enabled'] ?? '1') === '0')>Disabled — AI uses knowledge base only</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When enabled, the AI searches the web for recent policy changes, current facts, and external context. Requires a compatible model.</small>
                        </label>
                    </div>
                </div>
            </section>

            {{-- AI Chat Appearance & Behavior --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>AI Chat Appearance & Behavior</h3>
                        <p>Control how the chat widget looks and behaves on the website.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label>
                            <span>Enable AI assistant</span>
                            <select class="pro-select" name="ai_assistant_enabled">
                                <option value="1" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '1')>Enabled — Show chat widget on website</option>
                                <option value="0" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '0')>Disabled — Hide chat widget</option>
                            </select>
                        </label>
                        <label><span>Assistant title</span><input type="text" name="ai_assistant_title" value="{{ old('ai_assistant_title', $settings['ai_assistant_title']) }}" required></label>
                        <label><span>Assistant subtitle</span><input type="text" name="ai_assistant_subtitle" value="{{ old('ai_assistant_subtitle', $settings['ai_assistant_subtitle']) }}" required></label>
                        <label class="admin-form-grid__full"><span>Assistant greeting message</span><textarea name="ai_assistant_greeting" rows="3" required>{{ old('ai_assistant_greeting', $settings['ai_assistant_greeting']) }}</textarea></label>
                        <label class="admin-form-grid__full">
                            <span>Custom training instructions</span>
                            <textarea name="ai_assistant_system_prompt" rows="5" placeholder="Add tone, domain constraints, answer format rules, or any extra instructions for the AI.">{{ old('ai_assistant_system_prompt', $settings['ai_assistant_system_prompt'] ?? '') }}</textarea>
                            <small style="display:block;margin-top:6px;color:#667788;">These instructions are appended to the AI system prompt. Use this to set tone, add disclaimers, or constrain answer style.</small>
                        </label>
                    </div>
                </div>
            </section>

            {{-- Knowledge Base Overview --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Knowledge Base (Built-in Training)</h3>
                        <p>The AI is pre-trained on all SettleANZ website content. This knowledge is always active — even without an API key.</p>
                    </div>
                    <div style="display:grid;gap:1rem;">
                        <div style="background:#f0f7f6;border:1px solid #b9cfcb;border-radius:12px;padding:1.25rem;">
                            <h4 style="margin:0 0 0.75rem;color:#0b7a75;font-size:1rem;">Pages the AI Knows About</h4>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.5rem;font-size:0.9rem;color:#2c3a47;">
                                <div><strong>/</strong> — Homepage</div>
                                <div><strong>/about</strong> — About SettleANZ</div>
                                <div><strong>/new-to-australia</strong> — Arrival Guide</div>
                                <div><strong>/settlement-services</strong> — Settlement Hub</div>
                                <div><strong>/housing</strong> — Housing Guide</div>
                                <div><strong>/banking</strong> — Banking Guide</div>
                                <div><strong>/migration-services</strong> — Migration Services</div>
                                <div><strong>/blog</strong> — Blog Index</div>
                                <div><strong>/directory</strong> — Directory</div>
                                <div><strong>/contact</strong> — Contact Page</div>
                                <div><strong>/privacy-policy</strong> — Privacy Policy</div>
                                <div><strong>/terms-of-service</strong> — Terms of Service</div>
                            </div>
                        </div>
                        <div style="background:#fff8f0;border:1px solid #f0d9b5;border-radius:12px;padding:1.25rem;">
                            <h4 style="margin:0 0 0.75rem;color:#d86424;font-size:1rem;">FAQ Topics the AI Can Answer</h4>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.5rem;font-size:0.9rem;color:#2c3a47;">
                                <div>What does SettleANZ help with?</div>
                                <div>How should visitors use the site?</div>
                                <div>When should visitors contact a human?</div>
                                <div>How do business listings work?</div>
                                <div>What is on the migration page?</div>
                                <div>What is in the directory?</div>
                                <div>What topics are covered in the blog?</div>
                                <div>What do the main guides cover?</div>
                                <div>How fast does SettleANZ respond?</div>
                                <div>How can someone book migration help?</div>
                                <div>What is the Settlement Services page?</div>
                                <div>What is SettleANZ?</div>
                                <div>Is the AI assistant accurate?</div>
                                <div>What should I do before arriving?</div>
                                <div>How do I find housing?</div>
                                <div>How do I open a bank account?</div>
                                <div>What visa options are available?</div>
                            </div>
                        </div>
                        <div style="background:#f5f3ff;border:1px solid #c4b5fd;border-radius:12px;padding:1.25rem;">
                            <h4 style="margin:0 0 0.75rem;color:#6d28d9;font-size:1rem;">Dynamic Content (Auto-loaded from Database)</h4>
                            <ul style="margin:0;padding-left:1.25rem;font-size:0.9rem;color:#2c3a47;line-height:1.7;">
                                <li><strong>Blog posts</strong> — Latest 20 published posts with titles, excerpts, and content</li>
                                <li><strong>Directory listings</strong> — Latest 30 published listings with names, categories, cities, and descriptions</li>
                                <li><strong>Blog categories</strong> — Auto-generated from published posts</li>
                                <li><strong>Directory categories & cities</strong> — Auto-generated from published listings</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- WhatsApp & Contact --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>WhatsApp & Contact Settings</h3>
                        <p>Manage WhatsApp links and contact details used across the website.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label><span>Contact email</span><input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required></label>
                        <label><span>Contact WhatsApp URL</span><input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}" required></label>
                        <label><span>Footer WhatsApp URL</span><input type="text" name="footer_whatsapp" value="{{ old('footer_whatsapp', $settings['footer_whatsapp']) }}" required></label>
                        <label><span>Contact response note</span><input type="text" name="contact_response_time" value="{{ old('contact_response_time', $settings['contact_response_time']) }}" required></label>
                    </div>
                </div>
            </section>

            {{-- Social Links & CTAs --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Website Links & CTA Settings</h3>
                        <p>Update social links and key CTA destinations.</p>
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
            </section>

            <button class="button button--large" type="submit">Save all settings</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var providerSelect = document.getElementById('api-provider-select');
    var baseUrlInput = document.getElementById('ai-base-url');
    var providerUrls = {
        openai: 'https://api.openai.com/v1',
        groq: 'https://api.groq.com/openai/v1',
        openrouter: 'https://openrouter.ai/api/v1',
        custom: baseUrlInput.value
    };

    if (providerSelect && baseUrlInput) {
        providerSelect.addEventListener('change', function() {
            var url = providerUrls[this.value];
            if (url && this.value !== 'custom') {
                baseUrlInput.value = url;
            }
        });
    }
});
</script>
@endpush
