@extends('admin.layouts.app')

@section('page-title', 'Email Settings')

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

    <form class="admin-edit-form" method="POST" action="{{ route('admin.email-settings.update') }}">
        @csrf
        @method('PUT')

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Mail Driver</h3>
                    <p>Choose how your application sends emails.</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>Mail Driver</span>
                        <select class="pro-select" name="mail_mailer">
                            <option value="smtp" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'smtp')>SMTP — Send via a mail server</option>
                            <option value="sendmail" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'sendmail')>Sendmail — Local mail binary</option>
                            <option value="mailgun" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'mailgun')>Mailgun — API-based</option>
                            <option value="ses" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'ses')>Amazon SES — API-based</option>
                            <option value="postmark" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'postmark')>Postmark — API-based</option>
                            <option value="log" @selected(old('mail_mailer', $settings['mail_mailer'] ?? 'log') === 'log')>Log — Write to log file (testing)</option>
                        </select>
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card" id="smtp-settings">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>SMTP Configuration</h3>
                    <p>Enter your SMTP server details. These are used when mail driver is set to SMTP.</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>SMTP Host</span>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="smtp.yourprovider.com">
                    </label>
                    <label>
                        <span>SMTP Port</span>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}" placeholder="587">
                    </label>
                    <label>
                        <span>Encryption</span>
                        <select class="pro-select" name="mail_encryption">
                            <option value="tls" @selected(old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === 'tls')>TLS (recommended)</option>
                            <option value="ssl" @selected(old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === 'ssl')>SSL</option>
                            <option value="null" @selected(old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === '' || old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === 'null')>None</option>
                        </select>
                    </label>
                    <label>
                        <span>Username</span>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" placeholder="you@yourbusiness.com">
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="smtp_password" value="" placeholder="{{ $hasSmtpPassword ? 'Stored — leave blank to keep' : 'Enter password' }}" autocomplete="new-password">
                        @if ($hasSmtpPassword)
                            <small style="display:block;margin-top:6px;color:#667788;">A password is stored. Leave blank to keep it.</small>
                        @endif
                    </label>
                    <label>
                        <span>Clear stored password</span>
                        <select class="pro-select" name="smtp_password_clear">
                            <option value="0" selected>No</option>
                            <option value="1">Yes, remove it</option>
                        </select>
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Sender Details</h3>
                    <p>Default "From" address used for all outgoing emails.</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>From Address</span>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="hello@settleanz.com" required>
                    </label>
                    <label>
                        <span>From Name</span>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" placeholder="SettleANZ">
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card" style="margin-top:1.5rem;">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Email Theme & Visual Branding Settings</h3>
                    <p>Global colors, styles, variables and contact info that automatically apply to all visual builder email templates.</p>
                </div>
                <div class="admin-form-grid">
                    <label>
                        <span>Primary Brand Color</span>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="color" value="{{ old('email_theme_primary_color', $settings['email_theme_primary_color'] ?? '#065e5b') }}" onchange="this.nextElementSibling.value = this.value; this.nextElementSibling.name = 'email_theme_primary_color';" style="width:40px;height:40px;padding:0;border:none;border-radius:6px;cursor:pointer;">
                            <input type="text" name="email_theme_primary_color" value="{{ old('email_theme_primary_color', $settings['email_theme_primary_color'] ?? '#065e5b') }}" oninput="this.previousElementSibling.value = this.value" style="flex:1;">
                        </div>
                    </label>
                    <label>
                        <span>Secondary/Accent Color</span>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="color" value="{{ old('email_theme_secondary_color', $settings['email_theme_secondary_color'] ?? '#e8773a') }}" onchange="this.nextElementSibling.value = this.value; this.nextElementSibling.name = 'email_theme_secondary_color';" style="width:40px;height:40px;padding:0;border:none;border-radius:6px;cursor:pointer;">
                            <input type="text" name="email_theme_secondary_color" value="{{ old('email_theme_secondary_color', $settings['email_theme_secondary_color'] ?? '#e8773a') }}" oninput="this.previousElementSibling.value = this.value" style="flex:1;">
                        </div>
                    </label>
                    <label>
                        <span>Email Background Color</span>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="color" value="{{ old('email_theme_background', $settings['email_theme_background'] ?? '#f5f0e8') }}" onchange="this.nextElementSibling.value = this.value; this.nextElementSibling.name = 'email_theme_background';" style="width:40px;height:40px;padding:0;border:none;border-radius:6px;cursor:pointer;">
                            <input type="text" name="email_theme_background" value="{{ old('email_theme_background', $settings['email_theme_background'] ?? '#f5f0e8') }}" oninput="this.previousElementSibling.value = this.value" style="flex:1;">
                        </div>
                    </label>
                    <label>
                        <span>Main Text Color</span>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="color" value="{{ old('email_theme_text_color', $settings['email_theme_text_color'] ?? '#2c3a47') }}" onchange="this.nextElementSibling.value = this.value; this.nextElementSibling.name = 'email_theme_text_color';" style="width:40px;height:40px;padding:0;border:none;border-radius:6px;cursor:pointer;">
                            <input type="text" name="email_theme_text_color" value="{{ old('email_theme_text_color', $settings['email_theme_text_color'] ?? '#2c3a47') }}" oninput="this.previousElementSibling.value = this.value" style="flex:1;">
                        </div>
                    </label>
                    <label>
                        <span>Button Border Radius</span>
                        <input type="text" name="email_theme_button_radius" value="{{ old('email_theme_button_radius', $settings['email_theme_button_radius'] ?? '8px') }}" placeholder="8px">
                    </label>
                    <label>
                        <span>Default Font Stack</span>
                        <select class="pro-select" name="email_theme_default_font">
                            <option value="Arial, 'Helvetica Neue', Helvetica, sans-serif" @selected(old('email_theme_default_font', $settings['email_theme_default_font'] ?? '') === "Arial, 'Helvetica Neue', Helvetica, sans-serif")>Sans-Serif: Arial, Helvetica</option>
                            <option value="'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif" @selected(old('email_theme_default_font', $settings['email_theme_default_font'] ?? '') === "'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif")>Modern: Helvetica Neue</option>
                            <option value="Georgia, Cambria, 'Times New Roman', Times, serif" @selected(old('email_theme_default_font', $settings['email_theme_default_font'] ?? '') === "Georgia, Cambria, 'Times New Roman', Times, serif")>Serif: Georgia, Times</option>
                            <option value="Trebuchet MS, 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif" @selected(old('email_theme_default_font', $settings['email_theme_default_font'] ?? '') === "Trebuchet MS, 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif")>Tech: Trebuchet MS, Tahoma</option>
                            <option value="'Courier New', Courier, monospace" @selected(old('email_theme_default_font', $settings['email_theme_default_font'] ?? '') === "'Courier New', Courier, monospace")>Monospace: Courier New</option>
                        </select>
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Company Address</span>
                        <input type="text" name="email_theme_address" value="{{ old('email_theme_address', $settings['email_theme_address'] ?? 'SettleANZ, Australia') }}" placeholder="123 Relocation Ave, Sydney, NSW 2000">
                    </label>
                    <label>
                        <span>Support Email Address</span>
                        <input type="email" name="email_theme_support_email" value="{{ old('email_theme_support_email', $settings['email_theme_support_email'] ?? 'hello@settleanz.com') }}" placeholder="hello@settleanz.com">
                    </label>
                    <label>
                        <span>Company Website</span>
                        <input type="url" name="email_theme_website" value="{{ old('email_theme_website', $settings['email_theme_website'] ?? 'https://settleanz.com') }}" placeholder="https://settleanz.com">
                    </label>
                    @php $defaultFooter = '&copy; {{current_year}} {{company_name}}. All rights reserved.'; @endphp
                    <label class="admin-form-grid__full">
                        <span>Default Footer Copyright / Legal Text</span>
                        <textarea name="email_theme_footer" rows="2" style="font-family:inherit;">{{ old('email_theme_footer', $settings['email_theme_footer'] ?? $defaultFooter) }}</textarea>
                    </label>
                </div>

            </div>
        </section>

        <button class="button button--large" style="margin-top:1.5rem;" type="submit">Save Email Settings</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var mailerSelect = document.querySelector('select[name="mail_mailer"]');
    var smtpSection = document.getElementById('smtp-settings');

    function toggleSmtpSection() {
        if (mailerSelect.value === 'smtp') {
            smtpSection.style.display = '';
        } else {
            smtpSection.style.display = 'none';
        }
    }

    if (mailerSelect && smtpSection) {
        mailerSelect.addEventListener('change', toggleSmtpSection);
        toggleSmtpSection();
    }
});
</script>
@endpush
