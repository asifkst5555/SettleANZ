<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f5f0e8; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f0e8;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    <tr>
                        <td align="center" style="padding: 30px 0 20px 0;">
                            <a href="{{ config('app.url') }}">
                                <img src="{{ $companyLogo ?? asset('media/logo/logo.webp') }}" alt="{{ $companyName }}" style="height: 44px; width: auto; display: block; margin: 0 auto; border: 0;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #ffffff; border-radius: 12px; padding: 40px 35px; box-shadow: 0 2px 12px rgba(11, 122, 117, 0.08);">
                            <h2 style="color: #065e5b; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">Your Download is Ready!</h2>
                            <p style="color: #607080; font-size: 14px; margin: 0 0 24px 0;">Ebook access link inside</p>

                            <hr style="border: none; border-top: 1px solid #e6f4f3; margin: 0 0 24px 0;">

                            <p style="color: #2c3a47; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0;">Hi {{ $leadName }},</p>
                            <p style="color: #2c3a47; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0;">Thank you for your interest in <strong style="color: #0b7a75;">{{ $ebookTitle }}</strong>.</p>

                            @if($ebookDescription)
                            <p style="color: #607080; font-size: 15px; line-height: 1.6; margin: 0 0 20px 0; font-style: italic;">{{ $ebookDescription }}</p>
                            @endif

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $viewUrl ?? '#' }}" style="background: #0f766e; color: #ffffff; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-size: 15px; font-weight: bold; display: inline-block; margin: 0 8px 10px 0; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);">View Online</a>
                                        <a href="{{ $downloadUrl }}" style="background: #e8773a; color: #ffffff; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: bold; display: inline-block; box-shadow: 0 4px 12px rgba(232, 119, 58, 0.3);">Download PDF</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #e6f4f3; border-radius: 8px; margin: 0 0 20px 0;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="font-size: 13px; color: #0b7a75; margin: 0 0 4px 0; font-weight: 600;">&#9200; Expires {{ $expiresAt }}</p>
                                        <p style="font-size: 13px; color: #607080; margin: 0;">You can download it up to {{ $expiresInHours }} times within this period.</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; color: #607080; margin: 0 0 0 0;">If the button above doesn't work, copy and paste this URL into your browser:</p>
                            <p style="font-size: 13px; margin: 4px 0 0 0;"><a href="{{ $downloadUrl }}" style="color: #0b7a75; word-break: break-all;">{{ $downloadUrl }}</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 24px 20px;">
                            <p style="font-size: 12px; color: #607080; line-height: 1.5; margin: 0 0 6px 0;">You received this email because you requested this ebook from {{ $companyName }}.</p>
                            <p style="font-size: 12px; color: #607080; line-height: 1.5; margin: 0;">&copy; {{ $companyName }} {{ date('Y') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
