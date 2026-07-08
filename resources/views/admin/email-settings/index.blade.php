@extends('admin.layouts.app')

@section('page-title', 'Email Settings')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar" style="margin-bottom:1.5rem;">
        <div>
            <p class="eyebrow">System Settings</p>
            <h2>Email Settings</h2>
            <p>Configure your SMTP server or mail driver. Current mailer: <strong>{{ $currentMailer }}</strong></p>
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

        <button class="button button--large" type="submit">Save Email Settings</button>
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
