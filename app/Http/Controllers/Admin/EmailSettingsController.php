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

        $validated['mail_encryption'] = $validated['mail_encryption'] === 'null' ? '' : ($validated['mail_encryption'] ?? '');

        $keys = [
            'mail_mailer', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $validated[$key]],
                );
            }
        }

        Config::set('mail.default', $validated['mail_mailer']);
        Config::set('mail.mailers.smtp.host', $validated['smtp_host']);
        Config::set('mail.mailers.smtp.port', (int) $validated['smtp_port']);
        Config::set('mail.mailers.smtp.username', $validated['smtp_username']);
        Config::set('mail.mailers.smtp.password', $validated['smtp_password']);
        Config::set('mail.mailers.smtp.encryption', $validated['mail_encryption'] ?: null);
        Config::set('mail.from.address', $validated['mail_from_address']);
        Config::set('mail.from.name', $validated['mail_from_name']);

        return back()->with('status', 'Email settings updated successfully.');
    }
}
