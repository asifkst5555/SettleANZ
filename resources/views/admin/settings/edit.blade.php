@php
    $customKnowledgeCount = \Illuminate\Support\Facades\Schema::hasTable('ai_knowledge_entries') 
        ? \App\Models\AiKnowledgeEntry::query()->where('is_active', true)->count() 
        : 0;
@endphp
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
                    </div>
                </div>
            </section>

            {{-- AI Response Behavior Controls --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>AI Response Behavior Controls</h3>
                        <p>Fine-tune how the AI assistant answers questions. These settings control answer length, format, and style.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label>
                            <span>Max Bullet Points per Response</span>
                            <input type="number" name="ai_max_bullets" value="{{ old('ai_max_bullets', $settings['ai_max_bullets'] ?? 5) }}" min="1" max="10">
                            <small style="display:block;margin-top:6px;color:#667788;">Maximum number of bullet points in each AI response. Default: 5.</small>
                        </label>
                        <label>
                            <span>Max Answer Length (characters)</span>
                            <input type="number" name="ai_max_length" value="{{ old('ai_max_length', $settings['ai_max_length'] ?? 900) }}" min="200" max="2000">
                            <small style="display:block;margin-top:6px;color:#667788;">Maximum characters per AI response. Shorter = faster reading. Default: 900.</small>
                        </label>
                        <label>
                            <span>Response Tone</span>
                            <select class="pro-select" name="ai_response_tone">
                                <option value="professional" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'professional')>Professional — Experienced, calm, trustworthy</option>
                                <option value="friendly" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'friendly')>Friendly — Warm, approachable, conversational</option>
                                <option value="concise" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'concise')>Concise — Direct, minimal, straight to the point</option>
                                <option value="detailed" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'detailed')>Detailed — Thorough explanations with examples</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">Sets the overall personality and speaking style of the AI assistant.</small>
                        </label>
                        <label>
                            <span>Include Page Links in Responses</span>
                            <select class="pro-select" name="ai_include_page_links">
                                <option value="1" @selected(old('ai_include_page_links', $settings['ai_include_page_links'] ?? '1') === '1')>Yes — Suggest relevant SettleANZ pages</option>
                                <option value="0" @selected(old('ai_include_page_links', $settings['ai_include_page_links'] ?? '1') === '0')>No — Answer without page suggestions</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When enabled, AI suggests relevant pages like /contact, /banking, /housing etc.</small>
                        </label>
                        <label>
                            <span>Show Web Search Sources</span>
                            <select class="pro-select" name="ai_show_sources">
                                <option value="1" @selected(old('ai_show_sources', $settings['ai_show_sources'] ?? '1') === '1')>Yes — Show source links at end of response</option>
                                <option value="0" @selected(old('ai_show_sources', $settings['ai_show_sources'] ?? '1') === '0')>No — Hide source links</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When web search is used, show or hide the source URLs in responses.</small>
                        </label>
                        <label>
                            <span>Response Language</span>
                            <select class="pro-select" name="ai_response_language">
                                <option value="en" @selected(old('ai_response_language', $settings['ai_response_language'] ?? 'en') === 'en')>English</option>
                                <option value="hi" @selected(old('ai_response_language', $settings['ai_response_language'] ?? 'en') === 'hi')>Hindi</option>
                                <option value="es" @selected(old('ai_response_language', $settings['ai_response_language'] ?? 'en') === 'es')>Spanish</option>
                                <option value="ar" @selected(old('ai_response_language', $settings['ai_response_language'] ?? 'en') === 'ar')>Arabic</option>
                                <option value="zh" @selected(old('ai_response_language', $settings['ai_response_language'] ?? 'en') === 'zh')>Chinese</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">Language the AI uses to respond. Note: AI knowledge base is primarily in English.</small>
                        </label>
                        <label>
                            <span>Response Format</span>
                            <select class="pro-select" name="ai_response_format">
                                <option value="bullets" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'bullets')>Bullet Points Only</option>
                                <option value="mixed" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'mixed')>Mixed (Bullets + Paragraphs)</option>
                                <option value="paragraphs" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'paragraphs')>Paragraphs Only</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">How the AI structures its answers. Bullets are easiest to read in chat.</small>
                        </label>
                        <label>
                            <span>Stay Focused on Question</span>
                            <select class="pro-select" name="ai_stay_focused">
                                <option value="1" @selected(old('ai_stay_focused', $settings['ai_stay_focused'] ?? '1') === '1')>Strict — Only answer exactly what's asked</option>
                                <option value="0" @selected(old('ai_stay_focused', $settings['ai_stay_focused'] ?? '1') === '0')>Flexible — Can provide broader context</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When strict, AI won't expand into unrelated advice or add random suggestions.</small>
                        </label>
                        <label>
                            <span>Avoid AI-Style Phrases</span>
                            <select class="pro-select" name="ai_avoid_ai_phrases">
                                <option value="1" @selected(old('ai_avoid_ai_phrases', $settings['ai_avoid_ai_phrases'] ?? '1') === '1')>Yes — Block "Absolutely", "Great question", etc.</option>
                                <option value="0" @selected(old('ai_avoid_ai_phrases', $settings['ai_avoid_ai_phrases'] ?? '1') === '0')>No — Allow natural conversational phrases</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">Blocks robotic phrases like "Absolutely", "Great question", "I understand your concern", "I'd be happy to help".</small>
                        </label>
                        <label>
                            <span>Use Real-World Examples</span>
                            <select class="pro-select" name="ai_use_real_examples">
                                <option value="1" @selected(old('ai_use_real_examples', $settings['ai_use_real_examples'] ?? '1') === '1')>Yes — Include actual migrant experiences</option>
                                <option value="0" @selected(old('ai_use_real_examples', $settings['ai_use_real_examples'] ?? '1') === '0')>No — Stick to factual information only</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When enabled, AI uses contextual language like "Many newcomers attend multiple inspections..." instead of generic statements.</small>
                        </label>
                        <label>
                            <span>Professional Disclaimer for Legal/Visa</span>
                            <select class="pro-select" name="ai_professional_disclaimer">
                                <option value="1" @selected(old('ai_professional_disclaimer', $settings['ai_professional_disclaimer'] ?? '1') === '1')>Yes — Recommend licensed professionals for legal/visa/tax</option>
                                <option value="0" @selected(old('ai_professional_disclaimer', $settings['ai_professional_disclaimer'] ?? '1') === '0')>No — Answer directly without disclaimer</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">When uncertain about visa, legal, or tax matters, AI recommends consulting a licensed professional.</small>
                        </label>
                        <label>
                            <span>Uncertainty Handling</span>
                            <select class="pro-select" name="ai_uncertainty_handling">
                                <option value="say_uncertain" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'say_uncertain')>Say "I'm not certain" and skip</option>
                                <option value="recommend" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'recommend')>Recommend checking with professional</option>
                                <option value="best_guess" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'best_guess')>Provide best available information</option>
                            </select>
                            <small style="display:block;margin-top:6px;color:#667788;">How AI handles questions when it doesn't have confident information.</small>
                        </label>
                        <label class="admin-form-grid__full">
                            <span>Custom Follow-up Phrase</span>
                            <input type="text" name="ai_follow_up_phrase" value="{{ old('ai_follow_up_phrase', $settings['ai_follow_up_phrase'] ?? 'Let me know if you want more detailed information on this.') }}" placeholder="Let me know if you want more detailed information on this.">
                            <small style="display:block;margin-top:6px;color:#667788;">Phrase added at end of responses when more detail might be needed. Leave blank to disable.</small>
                        </label>
                        <label class="admin-form-grid__full">
                            <span>Custom Closing Phrase</span>
                            <input type="text" name="ai_closing_phrase" value="{{ old('ai_closing_phrase', $settings['ai_closing_phrase'] ?? '') }}" placeholder="e.g., Hope this helps with your move!">
                            <small style="display:block;margin-top:6px;color:#667788;">Optional closing phrase added to every response. Leave blank for no closing.</small>
                        </label>
                        <label class="admin-form-grid__full">
                            <span>Custom System Prompt (Full Control)</span>
                            <textarea name="ai_assistant_system_prompt" rows="8" placeholder="Write complete instructions for the AI. This replaces the default behavior rules. Example: 'You are a friendly migration advisor. Always answer in 3 bullet points. Never suggest contacting humans unless asked.'">{{ old('ai_assistant_system_prompt', $settings['ai_assistant_system_prompt'] ?? '') }}</textarea>
                            <small style="display:block;margin-top:6px;color:#e8773a;font-weight:600;">⚠️ If filled, this REPLACES ALL settings above. Use only if you want complete custom control.</small>
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
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                                <h4 style="margin:0;color:#0b7a75;font-size:1rem;">Custom AI Knowledge Entries</h4>
                                <a href="{{ route('admin.ai-knowledge.index') }}" class="button" style="padding:0.4rem 0.8rem;font-size:0.85rem;">Manage Entries ({{ $customKnowledgeCount }} active)</a>
                            </div>
                            <p style="font-size:0.9rem;color:#2c3a47;margin:0;">You have <strong>{{ $customKnowledgeCount }}</strong> active custom knowledge entries. These are used alongside built-in website content to train the AI assistant.</p>
                        </div>
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
                        <label><span>Reddit link</span><input type="text" name="social_reddit" value="{{ old('social_reddit', $settings['social_reddit'] ?? '') }}"></label>
                        <label><span>TikTok link</span><input type="text" name="social_tiktok" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"></label>
                        <label><span>YouTube link</span><input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"></label>
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
