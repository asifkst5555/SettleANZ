<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\SiteDefaults;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@SettleANZ.com'],
            [
                'name' => 'SettleANZ Admin',
                'password' => Hash::make('admin@1234'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        foreach (SiteDefaults::blogPosts() as $post) {
            BlogPost::query()->updateOrCreate(['slug' => $post['slug']], $post);
        }

        foreach (SiteDefaults::directoryListings() as $listing) {
            DirectoryListing::query()->updateOrCreate(['slug' => $listing['slug']], $listing);
        }

        foreach (SiteDefaults::siteSettings() as $key => $value) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->call([
            PermissionGroupSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            FeatureFlagSeeder::class,
        ]);

        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@settleanz.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin@1234'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $superAdminRole = \App\Models\Role::where('is_super', true)->first();
        if ($superAdminRole && !$superAdminUser->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $superAdminUser->roles()->attach($superAdminRole->id);
        }

        if (\App\Models\EmailTemplate::count() === 0) {
            \App\Models\EmailTemplate::create([
                'name' => 'Download Email - New Arrival Checklist',
                'type' => 'download',
                'subject' => 'Your 90-Day Roadmap: {{ ebook_title }}',
                'body_html' => <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Download Ready</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#f5f7fa;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;">
<tr>
<td align="center" style="padding:36px 30px 20px;">
<img src="{{ company_logo }}" alt="{{ company_name }}" width="170" style="display:block;width:170px;max-width:100%;height:auto;border:0;">
</td>
</tr>

<tr>
<td style="padding:0 40px 10px;">
<h1 style="margin:0;font-size:28px;line-height:36px;font-weight:700;color:#0f172a;">
Your 90-Day Roadmap is Ready
</h1>
</td>
</tr>

<tr>
<td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {{ lead_name }},</p>

<p style="margin:0 0 18px;">
Thank you for downloading <strong>{{ ebook_title }}</strong>.
Your download is now ready.
</p>

<p style="margin:0 0 18px;">
This secure link expires on <strong>{{ expires_at }}</strong>.<br>
You can download your file up to <strong>{{ expires_in_hours }}</strong> times during this period.
</p>
</td>
</tr>

<tr>
<td align="center" style="padding:14px 40px 26px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0">
<tr>
<td bgcolor="#0f766e" style="border-radius:8px;">
<a href="{{ download_url }}" style="display:inline-block;padding:14px 34px;font-size:16px;font-weight:bold;color:#ffffff;text-decoration:none;background:#0f766e;border-radius:8px;">
Download Your Roadmap
</a>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding:0 40px 28px;font-size:14px;line-height:24px;color:#64748b;">
If the button doesn't work, copy and paste this link into your browser:
<br><br>
<a href="{{ download_url }}" style="color:#0f766e;word-break:break-all;text-decoration:none;">
{{ download_url }}
</a>
</td>
</tr>

<tr>
<td style="padding:0 40px;">
<hr style="border:none;border-top:1px solid #e5e7eb;margin:0;">
</td>
</tr>

<tr>
<td style="padding:28px 40px;font-size:14px;line-height:24px;color:#64748b;">
If you have any questions, simply reply to this email or contact us at
<a href="mailto:{{ support_email }}" style="color:#0f766e;text-decoration:none;">{{ support_email }}</a>.
</td>
</tr>

<tr>
<td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{{ company_name }}</div>
<div style="margin-bottom:12px;">© {{ current_year }} {{ company_name }}. All rights reserved.</div>
<div>
<a href="mailto:{{ support_email }}" style="color:#64748b;text-decoration:none;">Support</a>
&nbsp;&nbsp;•&nbsp;&nbsp;
<a href="{{ unsubscribe }}" style="color:#64748b;text-decoration:none;">Unsubscribe</a>
</div>
</td>
</tr>

</table>
</center>
</body>
</html>
HTML
            ]);
        }
    }
}
