@extends('admin.layouts.app')

@section('page-title', 'Content Rules')

@section('content')
    <div class="admin-main__inner" style="display:grid;grid-template-columns:240px 1fr;gap:2rem;">
        @include('admin.ai-settings.partials.sidebar', ['active' => 'content-rules'])

        <div>
            <section class="admin-topbar" style="margin-bottom:1.5rem;">
                <div>
                    <p class="eyebrow">AI Settings</p>
                    <h2>Content Rules</h2>
                    <p>Control what the AI includes in responses and how it handles uncertainty.</p>
                </div>
            </section>

            @if (session('status'))
                <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#c62828;">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-settings.update-content-rules') }}">
                @csrf
                @method('PUT')

                <section class="admin-panel-card">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Response Content</h3>
                            <p>Control what the AI includes in its answers.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label>
                                <span>Include Page Links</span>
                                <select class="pro-select" name="ai_include_page_links">
                                    <option value="1" @selected(old('ai_include_page_links', $settings['ai_include_page_links'] ?? '1') === '1')>Yes — Suggest relevant SettleANZ pages</option>
                                    <option value="0" @selected(old('ai_include_page_links', $settings['ai_include_page_links'] ?? '1') === '0')>No — Answer without page suggestions</option>
                                </select>
                                <small style="display:block;margin-top:6px;color:#667788;">When enabled, AI suggests pages like /contact, /banking, /housing etc.</small>
                            </label>
                            <label>
                                <span>Show Web Search Sources</span>
                                <select class="pro-select" name="ai_show_sources">
                                    <option value="1" @selected(old('ai_show_sources', $settings['ai_show_sources'] ?? '1') === '1')>Yes — Show source links at end</option>
                                    <option value="0" @selected(old('ai_show_sources', $settings['ai_show_sources'] ?? '1') === '0')>No — Hide source links</option>
                                </select>
                            </label>
                            <label>
                                <span>Stay Focused on Question</span>
                                <select class="pro-select" name="ai_stay_focused">
                                    <option value="1" @selected(old('ai_stay_focused', $settings['ai_stay_focused'] ?? '1') === '1')>Strict — Only answer exactly what's asked</option>
                                    <option value="0" @selected(old('ai_stay_focused', $settings['ai_stay_focused'] ?? '1') === '0')>Flexible — Can provide broader context</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="admin-panel-card" style="margin-top:1.5rem;">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Style Rules</h3>
                            <p>Control the writing style and examples used.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label>
                                <span>Avoid AI-Style Phrases</span>
                                <select class="pro-select" name="ai_avoid_ai_phrases">
                                    <option value="1" @selected(old('ai_avoid_ai_phrases', $settings['ai_avoid_ai_phrases'] ?? '1') === '1')>Yes — Block "Absolutely", "Great question", etc.</option>
                                    <option value="0" @selected(old('ai_avoid_ai_phrases', $settings['ai_avoid_ai_phrases'] ?? '1') === '0')>No — Allow natural phrases</option>
                                </select>
                                <small style="display:block;margin-top:6px;color:#667788;">Blocks robotic phrases to sound more human.</small>
                            </label>
                            <label>
                                <span>Use Real-World Examples</span>
                                <select class="pro-select" name="ai_use_real_examples">
                                    <option value="1" @selected(old('ai_use_real_examples', $settings['ai_use_real_examples'] ?? '1') === '1')>Yes — Include actual migrant experiences</option>
                                    <option value="0" @selected(old('ai_use_real_examples', $settings['ai_use_real_examples'] ?? '1') === '0')>No — Stick to factual info only</option>
                                </select>
                                <small style="display:block;margin-top:6px;color:#667788;">Uses contextual language like "Many newcomers attend multiple inspections..."</small>
                            </label>
                            <label>
                                <span>Professional Disclaimer</span>
                                <select class="pro-select" name="ai_professional_disclaimer">
                                    <option value="1" @selected(old('ai_professional_disclaimer', $settings['ai_professional_disclaimer'] ?? '1') === '1')>Yes — Recommend professionals for legal/visa/tax</option>
                                    <option value="0" @selected(old('ai_professional_disclaimer', $settings['ai_professional_disclaimer'] ?? '1') === '0')>No — Answer directly without disclaimer</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="admin-panel-card" style="margin-top:1.5rem;">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Uncertainty Handling</h3>
                            <p>How the AI handles questions when it's not confident.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label class="admin-form-grid__full">
                                <span>When AI is uncertain</span>
                                <select class="pro-select" name="ai_uncertainty_handling">
                                    <option value="say_uncertain" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'say_uncertain')>Say "I'm not certain" and skip</option>
                                    <option value="recommend" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'recommend')>Recommend checking with professional</option>
                                    <option value="best_guess" @selected(old('ai_uncertainty_handling', $settings['ai_uncertainty_handling'] ?? 'say_uncertain') === 'best_guess')>Provide best available information</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </section>

                <button class="button button--large" type="submit">Save Content Rules</button>
            </form>
        </div>
    </div>
@endsection
