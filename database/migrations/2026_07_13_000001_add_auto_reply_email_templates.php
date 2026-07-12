<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ($this->templates() as $template) {
            DB::table('email_templates')->updateOrInsert(
                ['type' => $template['type'], 'name' => $template['name']],
                array_merge($template, [
                    'variables' => json_encode($template['variables']),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }
    }

    public function down(): void
    {
        DB::table('email_templates')
            ->whereIn('type', ['contact_auto_reply', 'booking_auto_reply', 'follow_up'])
            ->whereIn('name', ['Contact Form Auto Reply', 'Booking Request Auto Reply', 'Ebook Follow Up'])
            ->delete();
    }

    private function templates(): array
    {
        return [
            [
                'name' => 'Contact Form Auto Reply',
                'type' => 'contact_auto_reply',
                'subject' => 'Thanks for contacting SettleANZ',
                'body_html' => $this->contactHtml(),
                'body_text' => "Hi {{ lead_name }},\n\nThank you for reaching out to SettleANZ.\n\nWe have received your message and will review it carefully. A member of our team will get back to you within {{ response_time }}.\n\nWarm regards,\nThe SettleANZ Team\n\nP.S. If your question is urgent, you can reply directly to this email and we will do our best to help.",
                'variables' => ['lead_name', 'lead_email', 'form_type', 'enquiry_type', 'response_time', 'company_name', 'support_email', 'current_year'],
            ],
            [
                'name' => 'Booking Request Auto Reply',
                'type' => 'booking_auto_reply',
                'subject' => 'We received your booking request',
                'body_html' => $this->bookingHtml(),
                'body_text' => "Hi {{ lead_name }},\n\nThank you for reaching out to SettleANZ.\n\nWe have received your booking request and will confirm the session details shortly. A member of our team will get back to you within {{ response_time }}.\n\nWarm regards,\nThe SettleANZ Team\n\nP.S. If your question is urgent, you can reply directly to this email and we will do our best to help.",
                'variables' => ['lead_name', 'lead_email', 'form_type', 'enquiry_type', 'response_time', 'company_name', 'support_email', 'current_year'],
            ],
            [
                'name' => 'Ebook Follow Up',
                'type' => 'follow_up',
                'subject' => 'How was {{ ebook_title }}, {{ lead_name }}?',
                'body_html' => $this->followUpHtml(),
                'body_text' => "Hi {{ lead_name }},\n\nI hope {{ ebook_title }} has been helpful as you plan your next steps.\n\nIf you have questions after reading it, simply reply to this email. Our team can point you toward the right settlement resources and practical next steps.\n\nWarm regards,\nThe SettleANZ Team",
                'variables' => ['lead_name', 'lead_email', 'ebook_title', 'ebook_description', 'days_since_download', 'download_count', 'company_name', 'support_email', 'current_year'],
            ],
        ];
    }

    private function contactHtml(): string
    {
        return $this->html(
            'Thanks, {{ lead_name }}',
            'We have received your message and will review it carefully. A member of our team will get back to you within <strong>{{ response_time }}</strong>.',
        );
    }

    private function bookingHtml(): string
    {
        return $this->html(
            'Thanks, {{ lead_name }}',
            'We have received your booking request and will confirm the session details shortly. A member of our team will get back to you within <strong>{{ response_time }}</strong>.',
        );
    }

    private function followUpHtml(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>How was {{ ebook_title }}?</title>
</head>
<body style="margin:0;padding:0;background:#F8F4EC;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#F8F4EC;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #E5E7EB;border-radius:12px;">
<tr><td style="padding:36px 40px 8px;">
<div style="font-size:14px;line-height:20px;font-weight:700;letter-spacing:.02em;color:#0F766E;text-transform:uppercase;">{{ company_name }}</div>
<h1 style="margin:12px 0 0;font-size:26px;line-height:34px;font-weight:700;color:#0f172a;">How was {{ ebook_title }}?</h1>
</td></tr>
<tr><td style="padding:10px 40px 28px;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {{ lead_name }},</p>
<p style="margin:0 0 18px;">I hope {{ ebook_title }} has been helpful as you plan your next steps.</p>
<p style="margin:0 0 18px;">If you have questions after reading it, simply reply to this email. Our team can point you toward the right settlement resources and practical next steps.</p>
<p style="margin:0;">Warm regards,<br>The {{ company_name }} Team</p>
</td></tr>
<tr><td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{{ company_name }}</div>
<div>&copy; {{ current_year }} {{ company_name }}. All rights reserved.</div>
</td></tr>
</table>
</center>
</body>
</html>
HTML;
    }

    private function html(string $heading, string $bodyLine): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{{ company_name }}</title>
</head>
<body style="margin:0;padding:0;background:#F8F4EC;font-family:Arial,Helvetica,sans-serif;color:#334155;">
<center style="width:100%;background:#F8F4EC;padding:32px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border:1px solid #E5E7EB;border-radius:12px;">
<tr><td style="padding:36px 40px 8px;">
<div style="font-size:14px;line-height:20px;font-weight:700;letter-spacing:.02em;color:#0F766E;text-transform:uppercase;">{{ company_name }}</div>
<h1 style="margin:12px 0 0;font-size:26px;line-height:34px;font-weight:700;color:#0f172a;">{$heading}</h1>
</td></tr>
<tr><td style="padding:10px 40px 0;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Hi {{ lead_name }},</p>
<p style="margin:0 0 18px;">Thank you for reaching out to {{ company_name }}.</p>
<p style="margin:0 0 18px;">{$bodyLine}</p>
</td></tr>
<tr><td style="padding:0 40px 28px;font-size:16px;line-height:28px;color:#475569;">
<p style="margin:0 0 18px;">Warm regards,<br>The {{ company_name }} Team</p>
<p style="margin:0;color:#64748b;">P.S. If your question is urgent, you can reply directly to this email and we will do our best to help.</p>
</td></tr>
<tr><td style="background:#fafafa;border-top:1px solid #e5e7eb;padding:24px 40px;text-align:center;font-size:13px;line-height:22px;color:#94a3b8;">
<div style="color:#475569;font-weight:bold;margin-bottom:6px;">{{ company_name }}</div>
<div>&copy; {{ current_year }} {{ company_name }}. All rights reserved.</div>
</td></tr>
</table>
</center>
</body>
</html>
HTML;
    }
};
