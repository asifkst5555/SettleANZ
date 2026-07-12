<?php

namespace App\Support;

class SystemEmailTemplates
{
    public static function downloadSubject(): string
    {
        return 'Your Download: {{ ebook_title }}';
    }

    public static function downloadText(): string
    {
        return "Hi {{ lead_name }},\n\nThank you for your interest in {{ ebook_title }}.\n\nView online: {{ view_url }}\nDownload PDF: {{ download_url }}\n\nThis link expires on {{ expires_at }}. You can download it up to {{ expires_in_hours }} times within this period.\n\nYou received this email because you requested this ebook from {{ company_name }}.";
    }

    public static function downloadHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Your Download is Ready</title>
</head>
<body style="margin:0;padding:0;background:#F8F4EC;font-family:Arial,Helvetica,sans-serif;color:#1F2937;">
<center style="width:100%;background:#F8F4EC;padding:36px 12px 0;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;">
<tr>
<td align="center" style="padding:0 0 24px;">
<img src="{{ company_logo }}" alt="{{ company_name }}" width="180" style="display:block;width:180px;max-width:100%;height:auto;border:0;">
</td>
</tr>
</table>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;border:1px solid #E5E7EB;">
<tr>
<td style="padding:44px 36px 24px;">
<h1 style="margin:0 0 12px;font-size:25px;line-height:1.3;font-weight:700;color:#005F5B;">Your Download is Ready!</h1>
<p style="margin:0;font-size:14px;line-height:1.6;color:#334155;">Ebook access link inside</p>
</td>
</tr>
<tr>
<td style="padding:0 36px;">
<hr style="border:none;border-top:1px solid #D9E7E4;margin:0;">
</td>
</tr>
<tr>
<td style="padding:26px 36px 8px;font-size:16px;line-height:1.7;color:#0F172A;">
<p style="margin:0 0 18px;">Hi {{ lead_name }},</p>
<p style="margin:0 0 24px;">Thank you for your interest in <strong style="color:#00756F;">{{ ebook_title }}</strong>.</p>
</td>
</tr>
<tr>
<td align="center" style="padding:0 36px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td align="center" style="padding:0 6px 10px;">
<a href="{{ view_url }}" style="display:inline-block;min-width:105px;padding:15px 20px;background:#0F766E;color:#FFFFFF;text-decoration:none;border-radius:7px;font-size:15px;font-weight:700;">View Online</a>
</td>
<td align="center" style="padding:0 6px 10px;">
<a href="{{ download_url }}" style="display:inline-block;min-width:145px;padding:15px 24px;background:#EA7434;color:#FFFFFF;text-decoration:none;border-radius:7px;font-size:15px;font-weight:700;">Download PDF</a>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding:0 36px 24px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#E6F4F3;border-radius:7px;">
<tr>
<td style="padding:16px 20px;font-size:13px;line-height:1.55;color:#334155;">
<p style="margin:0 0 4px;font-weight:700;color:#00756F;">Expires {{ expires_at }}</p>
<p style="margin:0;">You can download it up to {{ expires_in_hours }} times within this period.</p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding:0 36px 40px;font-size:13px;line-height:1.6;color:#334155;">
<p style="margin:0 0 4px;">If the buttons above do not work, copy and paste this URL into your browser:</p>
<p style="margin:0;"><a href="{{ download_url }}" style="color:#00756F;text-decoration:underline;word-break:break-all;">{{ download_url }}</a></p>
</td>
</tr>
</table>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;">
<tr>
<td align="center" style="padding:22px 16px 10px;font-size:12px;line-height:1.5;color:#64748B;">
You received this email because you requested this ebook from {{ company_name }}.
</td>
</tr>
</table>
</center>
</body>
</html>
HTML;
    }

    public static function downloadBuilderJson(): array
    {
        return [
            'settings' => [
                'preheader' => 'Ebook access link inside',
                'theme' => [
                    'primaryColor' => '#0F766E',
                    'secondaryColor' => '#EA7434',
                    'backgroundColor' => '#F8F4EC',
                    'textColor' => '#1F2937',
                    'buttonRadius' => '7px',
                ],
            ],
            'blocks' => [
                ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => '180', 'paddingTop' => '0', 'paddingBottom' => '18']],
                ['type' => 'heading', 'properties' => ['text' => 'Your Download is Ready!', 'fontSize' => '25px', 'alignment' => 'left', 'fontWeight' => 'bold', 'color' => '#005F5B', 'paddingTop' => '18', 'paddingBottom' => '4']],
                ['type' => 'text', 'properties' => ['text' => 'Ebook access link inside', 'fontSize' => '14px', 'alignment' => 'left', 'lineHeight' => '1.6', 'color' => '#334155', 'paddingTop' => '0', 'paddingBottom' => '12']],
                ['type' => 'divider', 'properties' => ['height' => '1', 'color' => '#D9E7E4', 'margin' => '12']],
                ['type' => 'text', 'properties' => ['text' => "Hi {{lead_name}},\n\nThank you for your interest in **{{ebook_title}}**.", 'fontSize' => '16px', 'alignment' => 'left', 'lineHeight' => '1.7', 'color' => '#0F172A', 'paddingTop' => '12', 'paddingBottom' => '6']],
                ['type' => 'button', 'properties' => ['text' => 'View Online', 'url' => '{{view_url}}', 'background' => '#0F766E', 'radius' => '7', 'fontColor' => '#ffffff', 'alignment' => 'center', 'fontSize' => '15px', 'padding' => '6']],
                ['type' => 'button', 'properties' => ['text' => 'Download PDF', 'url' => '{{download_url}}', 'background' => '#EA7434', 'radius' => '7', 'fontColor' => '#ffffff', 'alignment' => 'center', 'fontSize' => '15px', 'padding' => '6']],
                ['type' => 'notice', 'properties' => ['title' => 'Expires {{expires_at}}', 'text' => 'You can download it up to {{expires_in_hours}} times within this period.', 'background' => '#E6F4F3', 'titleColor' => '#00756F', 'textColor' => '#334155', 'padding' => '16', 'radius' => '7']],
                ['type' => 'text', 'properties' => ['text' => "If the buttons above do not work, copy and paste this URL into your browser:\n{{download_url}}", 'fontSize' => '13px', 'alignment' => 'left', 'lineHeight' => '1.6', 'color' => '#334155', 'paddingTop' => '10', 'paddingBottom' => '18']],
                ['type' => 'footer', 'properties' => ['background' => '#F8F4EC', 'color' => '#64748B', 'padding' => '20', 'alignment' => 'center']],
            ],
        ];
    }
}
