@extends('admin.layouts.app')

@section('page-title', 'Social Media Settings')

@section('content')
<div class="admin-main__inner">
    @include('admin.settings.partials.tabs')

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

    <form class="admin-edit-form" method="POST" action="{{ route('admin.social-settings.update') }}">
        @csrf
        @method('PUT')

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Social Media Links</h3>
                    <p>Set the URLs for your social media profiles. These appear in the website footer and contact page. Leave blank to hide a platform.</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>📘 Facebook</span>
                        <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" placeholder="https://www.facebook.com/profile.php?id=61590926305491">
                    </label>
                    <label>
                        <span>📸 Instagram</span>
                        <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" placeholder="https://www.instagram.com/settleanzofficial/">
                    </label>
                    <label>
                        <span>💼 LinkedIn</span>
                        <input type="text" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/settleanz">
                    </label>
                    <label>
                        <span>📌 Pinterest</span>
                        <input type="text" name="social_pinterest" value="{{ old('social_pinterest', $settings['social_pinterest'] ?? '') }}" placeholder="https://www.pinterest.com/SettleANZ/">
                    </label>
                    <label>
                        <span>👽 Reddit</span>
                        <input type="text" name="social_reddit" value="{{ old('social_reddit', $settings['social_reddit'] ?? '') }}" placeholder="https://www.reddit.com/user/settleANZ_official/">
                    </label>
                    <label>
                        <span>🎵 TikTok</span>
                        <input type="text" name="social_tiktok" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}" placeholder="https://www.tiktok.com/@settleanz">
                    </label>
                    <label class="admin-form-grid__full">
                        <span>▶️ YouTube</span>
                        <input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" placeholder="https://www.youtube.com/@SettleANZ">
                    </label>
                </div>
            </div>
        </section>

        <button class="button button--large" style="margin-top:1.5rem;" type="submit">Save Social Media Settings</button>
    </form>
</div>
@endsection