@extends('admin.layouts.app')

@section('page-title', 'Response Behavior')

@section('content')
    <div class="admin-main__inner" style="display:block;">
        @include('admin.settings.partials.tabs')

        <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;margin-top:1.5rem;">
            {{-- Sidebar --}}
            @include('admin.ai-settings.partials.sidebar', ['active' => 'response-behavior'])

            {{-- Main Content --}}
            <div>

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

            <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-settings.update-response-behavior') }}">
                @csrf
                @method('PUT')

                <section class="admin-panel-card">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Response Structure</h3>
                            <p>Control how responses are formatted and sized.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label>
                                <span>Max Bullet Points</span>
                                <input type="number" name="ai_max_bullets" value="{{ old('ai_max_bullets', $settings['ai_max_bullets'] ?? 5) }}" min="1" max="10">
                                <small style="display:block;margin-top:6px;color:#667788;">Maximum bullets per response. Default: 5.</small>
                            </label>
                            <label>
                                <span>Max Answer Length (characters)</span>
                                <input type="number" name="ai_max_length" value="{{ old('ai_max_length', $settings['ai_max_length'] ?? 900) }}" min="200" max="2000">
                                <small style="display:block;margin-top:6px;color:#667788;">Character limit per response. Default: 900.</small>
                            </label>
                            <label>
                                <span>Response Format</span>
                                <select class="pro-select" name="ai_response_format">
                                    <option value="bullets" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'bullets')>Bullet Points Only</option>
                                    <option value="mixed" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'mixed')>Mixed (Bullets + Paragraphs)</option>
                                    <option value="paragraphs" @selected(old('ai_response_format', $settings['ai_response_format'] ?? 'bullets') === 'paragraphs')>Paragraphs Only</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="admin-panel-card" style="margin-top:1.5rem;">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Tone & Language</h3>
                            <p>Set the personality and language of responses.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label>
                                <span>Response Tone</span>
                                <select class="pro-select" name="ai_response_tone">
                                    <option value="professional" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'professional')>Professional — Experienced, calm, trustworthy</option>
                                    <option value="friendly" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'friendly')>Friendly — Warm, approachable, conversational</option>
                                    <option value="concise" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'concise')>Concise — Direct, minimal, straight to the point</option>
                                    <option value="detailed" @selected(old('ai_response_tone', $settings['ai_response_tone'] ?? 'professional') === 'detailed')>Detailed — Thorough explanations with examples</option>
                                </select>
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
                                <small style="display:block;margin-top:6px;color:#667788;">Note: AI knowledge base is primarily in English.</small>
                            </label>
                        </div>
                    </div>
                </section>

                <button class="button button--large" type="submit">Save Behavior Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
