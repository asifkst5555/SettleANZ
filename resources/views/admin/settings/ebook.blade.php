@extends('admin.layouts.app')

@section('page-title', 'Ebook System Settings')

@section('content')
<div class="admin-main__inner">
    @include('admin.settings.partials.tabs')

    <form action="{{ route('admin.ebook-settings.update') }}" method="POST" class="admin-edit-form">
        @csrf
        @method('PUT')

        @if(session('status'))
        <div style="background:#d1fae5;border:1px solid #a7f3d0;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#065f46;font-weight:600;">
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#7f1d1d;">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Download Settings</h3>
                    <p>Control how download tokens behave and file upload limits</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>Token Expiry (hours)</span>
                        <input type="number" name="token_expiry_hours"
                            value="{{ old('token_expiry_hours', $settings['ebook_token_expiry_hours']->value ?? config('ebook.download.token_expiry_hours')) }}"
                            required min="1" max="720" class="w-full border rounded px-3 py-2">
                        <small style="display:block;margin-top:6px;color:#667788;">How long download tokens remain valid.</small>
                    </label>
                    <label>
                        <span>Max Downloads Per Token</span>
                        <input type="number" name="max_downloads_per_token"
                            value="{{ old('max_downloads_per_token', $settings['ebook_max_downloads_per_token']->value ?? config('ebook.download.max_downloads_per_token')) }}"
                            required min="1" max="100" class="w-full border rounded px-3 py-2">
                        <small style="display:block;margin-top:6px;color:#667788;">Maximum times a token can be used.</small>
                    </label>
                    <label>
                        <span>Max File Size (bytes)</span>
                        <input type="number" name="max_file_size"
                            value="{{ old('max_file_size', $settings['ebook_max_file_size']->value ?? config('ebook.storage.max_file_size')) }}"
                            required min="1048576" max="524288000" class="w-full border rounded px-3 py-2">
                        <small style="display:block;margin-top:6px;color:#667788;">Default: 50MB (52428800). Max: 500MB.</small>
                    </label>
                    <label>
                        <span>Require Email Verification</span>
                        <div style="padding-top:0.375rem;">
                            <input type="hidden" name="require_email_verification" value="0">
                            <input type="checkbox" name="require_email_verification" value="1"
                                @checked(old('require_email_verification', $settings['ebook_require_email_verification']->value ?? config('ebook.download.require_email_verification')))
                                style="accent-color:#0f8b8d;width:1.25rem;height:1.25rem;">
                            <span style="margin-left:0.5rem;font-size:0.875rem;color:#6b7280;">Yes, verify email before download</span>
                        </div>
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>AI Provider</h3>
                    <p>The AI service used by the AI Assistant, email generator, and content tools</p>
                </div>
                <div style="padding:0 1.25rem 1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;padding:1rem;background:#f9fafb;border-radius:0.75rem;">
                        <div>
                            <div style="font-size:0.8125rem;color:#6b7280;margin-bottom:0.25rem;">API Provider</div>
                            <div style="font-weight:600;color:#1f2937;">
                                @if(str_contains($aiProvider, 'api.openai.com'))
                                    OpenAI
                                @elseif(str_contains($aiProvider, 'api.groq.com'))
                                    Groq
                                @elseif(str_contains($aiProvider, 'openrouter.ai'))
                                    OpenRouter
                                @else
                                    Custom ({{ $aiProvider }})
                                @endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.8125rem;color:#6b7280;margin-bottom:0.25rem;">Model</div>
                            <div style="font-weight:600;color:#1f2937;">{{ $aiModel }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8125rem;color:#6b7280;margin-bottom:0.25rem;">API Key</div>
                            <div style="font-weight:600;">
                                @if($hasAiKey)
                                    <span style="color:#059669;">&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679; (stored)</span>
                                @else
                                    <span style="color:#dc2626;">Not configured</span>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex;align-items:end;justify-content:end;">
                            <a href="{{ route('admin.ai-settings.api-connection') }}" class="button button--small" style="background:#dbeafe;color:#0c4a6e;text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.375rem;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Change in AI Settings
                            </a>
                        </div>
                    </div>
                    <p style="font-size:0.8125rem;color:#6b7280;margin-top:0.75rem;">
                        The AI Assistant, email generator, content tools, and website assistant all share this configuration.
                    </p>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Save Settings</button>
            <a href="{{ route('admin.dashboard') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
