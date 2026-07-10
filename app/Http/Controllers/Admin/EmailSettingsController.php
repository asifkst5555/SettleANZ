<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class EmailSettingsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $settings = SiteSetting::keyValueMap();

        return view('admin.email-settings.index', [
            'metaTitle' => 'Email Settings | SettleANZ Admin',
            'settings' => $settings,
            'hasSmtpPassword' => filled($settings['smtp_password'] ?? ''),
            'currentMailer' => config('mail.default'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'mail_mailer' => ['required', 'string', 'in:smtp,sendmail,mailgun,ses,postmark,log'],
            'smtp_host' => ['required_if:mail_mailer,smtp', 'string', 'max:255'],
            'smtp_port' => ['required_if:mail_mailer,smtp', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_password_clear' => ['nullable', 'in:0,1'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl,null'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
            'email_theme_primary_color' => ['nullable', 'string', 'max:50'],
            'email_theme_secondary_color' => ['nullable', 'string', 'max:50'],
            'email_theme_background' => ['nullable', 'string', 'max:50'],
            'email_theme_text_color' => ['nullable', 'string', 'max:50'],
            'email_theme_button_radius' => ['nullable', 'string', 'max:50'],
            'email_theme_default_font' => ['nullable', 'string', 'max:100'],
            'email_theme_footer' => ['nullable', 'string'],
            'email_theme_address' => ['nullable', 'string'],
            'email_theme_support_email' => ['nullable', 'email', 'max:255'],
            'email_theme_website' => ['nullable', 'url', 'max:255'],
        ]);

        $existing = SiteSetting::getValue('smtp_password', '');
        $submitted = trim((string) ($validated['smtp_password'] ?? ''));
        $clear = $request->boolean('smtp_password_clear');

        if ($clear) {
            $validated['smtp_password'] = '';
        } elseif ($submitted === '' && filled($existing)) {
            $validated['smtp_password'] = $existing;
        } else {
            $validated['smtp_password'] = $submitted;
        }

        $mailEncryption = $validated['mail_encryption'] ?? '';
        $validated['mail_encryption'] = $mailEncryption === 'null' ? '' : $mailEncryption;

        $keys = [
            'mail_mailer', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'email_theme_primary_color', 'email_theme_secondary_color', 'email_theme_background',
            'email_theme_text_color', 'email_theme_button_radius', 'email_theme_default_font',
            'email_theme_footer', 'email_theme_address', 'email_theme_support_email',
            'email_theme_website',
        ];

        $hasThemeChanges = false;
        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) ($validated[$key] ?? '')],
                );
                if (str_starts_with($key, 'email_theme_')) {
                    $hasThemeChanges = true;
                }
            }
        }

        if ($hasThemeChanges) {
            $templates = \App\Models\EmailTemplate::whereNotNull('builder_json')->get();
            foreach ($templates as $t) {
                $t->body_html = \App\Services\EmailTemplateRenderer::render($t->builder_json);
                $t->save();
            }
        }

        Config::set('mail.default', $validated['mail_mailer']);
        Config::set('mail.mailers.smtp.host', $validated['smtp_host'] ?? '');
        Config::set('mail.mailers.smtp.port', (int) ($validated['smtp_port'] ?? 587));
        Config::set('mail.mailers.smtp.username', $validated['smtp_username'] ?? '');
        Config::set('mail.mailers.smtp.password', $validated['smtp_password'] ?? '');
        Config::set('mail.mailers.smtp.encryption', $validated['mail_encryption'] ?: null);
        Config::set('mail.from.address', $validated['mail_from_address']);
        Config::set('mail.from.name', $validated['mail_from_name'] ?? '');

        return back()->with('status', 'Email settings updated successfully.');
    }
}
