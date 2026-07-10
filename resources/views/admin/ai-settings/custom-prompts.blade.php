@extends('admin.layouts.app')

@section('page-title', 'Custom Prompts')

@section('content')
    <div class="admin-main__inner" style="display:block;">
        @include('admin.settings.partials.tabs')

        <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;margin-top:1.5rem;">
            {{-- Sidebar --}}
            @include('admin.ai-settings.partials.sidebar', ['active' => 'custom-prompts'])

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

            <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-settings.update-custom-prompts') }}">
                @csrf
                @method('PUT')

                <section class="admin-panel-card">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Response Phrases</h3>
                            <p>Customize the phrases the AI uses in responses.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label class="admin-form-grid__full">
                                <span>Custom Follow-up Phrase</span>
                                <input type="text" name="ai_follow_up_phrase" value="{{ old('ai_follow_up_phrase', $settings['ai_follow_up_phrase'] ?? 'Let me know if you want more detailed information on this.') }}" placeholder="Let me know if you want more detailed information on this.">
                                <small style="display:block;margin-top:6px;color:#667788;">Added at end of responses when more detail might be needed. Leave blank to disable.</small>
                            </label>
                            <label class="admin-form-grid__full">
                                <span>Custom Closing Phrase</span>
                                <input type="text" name="ai_closing_phrase" value="{{ old('ai_closing_phrase', $settings['ai_closing_phrase'] ?? '') }}" placeholder="e.g., Hope this helps with your move!">
                                <small style="display:block;margin-top:6px;color:#667788;">Optional closing phrase added to every response. Leave blank for no closing.</small>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="admin-panel-card" style="margin-top:1.5rem;">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Custom System Prompt</h3>
                            <p>Complete override of AI behavior.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label class="admin-form-grid__full">
                                <span>System Prompt (Full Control)</span>
                                <textarea name="ai_assistant_system_prompt" rows="12" placeholder="Write complete instructions for the AI. This replaces ALL default behavior rules.&#10;&#10;Example:&#10;You are a friendly migration advisor for SettleANZ.&#10;Always answer in exactly 3 bullet points.&#10;Never suggest contacting humans unless explicitly asked.&#10;Use simple English suitable for non-native speakers.">{{ old('ai_assistant_system_prompt', $settings['ai_assistant_system_prompt'] ?? '') }}</textarea>
                                <small style="display:block;margin-top:6px;color:#e8773a;font-weight:600;">⚠️ If filled, this REPLACES ALL settings on other tabs. Use only if you want complete custom control over AI behavior.</small>
                            </label>
                        </div>
                    </div>
                </section>

                <button class="button button--large" type="submit">Save Custom Prompts</button>
            </form>
        </div>
    </div>
</div>
@endsection
