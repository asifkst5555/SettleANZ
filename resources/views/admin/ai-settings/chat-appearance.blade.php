@extends('admin.layouts.app')

@section('page-title', 'Chat Appearance')

@section('content')
    <div class="admin-main__inner" style="display:block;">
        @include('admin.settings.partials.tabs')

        <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;margin-top:1.5rem;">
            {{-- Sidebar --}}
            @include('admin.ai-settings.partials.sidebar', ['active' => 'chat-appearance'])

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

            <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-settings.update-chat-appearance') }}">
                @csrf
                @method('PUT')

                <section class="admin-panel-card">
                    <div class="admin-settings-section">
                        <div class="admin-settings-section__head">
                            <h3>Widget Settings</h3>
                            <p>Configure the chat widget visibility and display text.</p>
                        </div>
                        <div class="admin-form-grid">
                            <label>
                                <span>Enable AI assistant</span>
                                <select class="pro-select" name="ai_assistant_enabled">
                                    <option value="1" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '1')>Enabled — Show chat widget on website</option>
                                    <option value="0" @selected(old('ai_assistant_enabled', $settings['ai_assistant_enabled']) === '0')>Disabled — Hide chat widget</option>
                                </select>
                            </label>
                            <label>
                                <span>Assistant title</span>
                                <input type="text" name="ai_assistant_title" value="{{ old('ai_assistant_title', $settings['ai_assistant_title']) }}" required>
                            </label>
                            <label>
                                <span>Assistant subtitle</span>
                                <input type="text" name="ai_assistant_subtitle" value="{{ old('ai_assistant_subtitle', $settings['ai_assistant_subtitle']) }}" required>
                            </label>
                            <label class="admin-form-grid__full">
                                <span>Assistant greeting message</span>
                                <textarea name="ai_assistant_greeting" rows="3" required>{{ old('ai_assistant_greeting', $settings['ai_assistant_greeting']) }}</textarea>
                                <small style="display:block;margin-top:6px;color:#667788;">This message appears when the user first opens the chat.</small>
                            </label>
                        </div>
                    </div>
                </section>

                <button class="button button--large" type="submit">Save Appearance Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
