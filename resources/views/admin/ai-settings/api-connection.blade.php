@extends('admin.layouts.app')

@section('page-title', 'AI API Connection')

@section('content')
    <div class="admin-main__inner" style="display:block;">
        @include('admin.settings.partials.tabs')

        <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;margin-top:1.5rem;">
            {{-- Sidebar --}}
            @include('admin.ai-settings.partials.sidebar', ['active' => 'api-connection'])

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

            <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-settings.update-api-connection') }}">
                @csrf
                @method('PUT')

                <section class="admin-panel-card">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>API Configuration</h3>
                            <p>Choose your AI provider and enter API credentials.</p>
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
                                <small style="display:block;margin-top:6px;color:#667788;">When enabled, the AI searches the web for recent policy changes, current facts, and external context.</small>
                            </label>
                        </div>
                    </div>
                </section>

                <button class="button button--large" type="submit">Save API Settings</button>
            </form>
        </div>
    </div>
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
