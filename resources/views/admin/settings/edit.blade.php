@php
    $customKnowledgeCount = \Illuminate\Support\Facades\Schema::hasTable('ai_knowledge_entries') 
        ? \App\Models\AiKnowledgeEntry::query()->where('is_active', true)->count() 
        : 0;
@endphp
@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        @include('admin.settings.partials.tabs')

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        <form class="admin-edit-form" method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

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

            {{-- CTA Settings --}}
            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Website CTA Settings</h3>
                        <p>Update key CTA destinations and links. Social media links are now managed in the <a href="{{ route('admin.social-settings.index') }}" style="color:#0b7a75;font-weight:600;">Social Media</a> tab.</p>
                    </div>
                    <div class="admin-form-grid">
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
