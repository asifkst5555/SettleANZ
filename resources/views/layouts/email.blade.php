<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'SettleANZ' }}</title>
    <!--[if mso]>
    <noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
    <style>
        * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }
        html, body { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
        img { -ms-interpolation-mode: bicubic; }
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .email-padding { padding: 24px 20px !important; }
            .email-logo { height: 36px !important; }
            .email-heading { font-size: 24px !important; }
            .email-body { font-size: 15px !important; }
            .stack { display: block !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;mso-line-height-rule:exactly;background-color:#F8F4EC;font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#1F2937;">
    @hasSection('preheader')
    <div style="display:none;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;font-size:1px;line-height:1px;color:#F8F4EC;">
        @yield('preheader')
    </div>
    @endif
    <center style="width:100%;background-color:#F8F4EC;min-width:100%;">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F8F4EC;">
            <tr>
                <td align="center" valign="top" style="padding:32px 16px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
                        {{-- Header / Logo --}}
                        <tr>
                            <td align="center" style="padding:36px 40px 8px 40px;">
                                <a href="{{ $website ?? url('/') }}" target="_blank">
                                    <img src="{{ $logo ?? asset('media/logo/email_logo.png') }}" alt="{{ $companyName ?? config('app.name') }}" width="150" class="email-logo" style="height:auto;display:block;border:0;max-width:100%;">
                                </a>
                            </td>
                        </tr>

                        {{-- Heading --}}
                        @hasSection('heading')
                        <tr>
                            <td align="left" class="email-padding" style="padding:24px 40px 4px 40px;">
                                <h1 class="email-heading" style="margin:0;font-size:28px;font-weight:700;color:#1F2937;line-height:1.25;letter-spacing:-0.3px;">@yield('heading')</h1>
                            </td>
                        </tr>
                        @endif

                        {{-- Content Area --}}
                        <tr>
                            <td align="left" class="email-padding" style="padding:16px 40px 32px 40px;font-size:16px;line-height:1.6;color:#1F2937;">
                                @yield('content')
                            </td>
                        </tr>

                        {{-- CTA Button --}}
                        @hasSection('button')
                        <tr>
                            <td align="center" style="padding:0 40px 32px 40px;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" bgcolor="#0F766E" style="border-radius:8px;background-color:#0F766E;">
                                            <a href="{{ $buttonUrl ?? '#' }}" target="_blank" style="background-color:#0F766E;border:1px solid #0F766E;border-radius:8px;color:#FFFFFF;display:inline-block;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:600;line-height:1.5;padding:14px 36px;text-decoration:none;text-align:center;mso-hide:none;">
                                                @yield('button')
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @endif

                        {{-- Divider --}}
                        <tr>
                            <td style="padding:0 40px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="border-top:1px solid #E5E7EB;font-size:1px;line-height:1px;">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        {{-- Footer --}}
                        <tr>
                            <td align="center" style="padding:24px 40px 32px 40px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#6B7280;line-height:1.6;">
                                <p style="margin:0 0 8px 0;">{{ $companyName ?? config('app.name') }}</p>
                                @if(isset($address))
                                <p style="margin:0 0 8px 0;">{{ $address }}</p>
                                @endif
                                <p style="margin:0 0 8px 0;">
                                    <a href="mailto:{{ $supportEmail ?? 'hello@settleanz.com' }}" style="color:#0F766E;text-decoration:none;">{{ $supportEmail ?? 'hello@settleanz.com' }}</a>
                                </p>
                                @hasSection('footer')
                                <p style="margin:0 0 8px 0;">@yield('footer')</p>
                                @endif
                                <p style="margin:8px 0 0 0;">
                                    <a href="{{ $unsubscribeUrl ?? '#' }}" style="color:#9CA3AF;text-decoration:underline;font-size:12px;">Unsubscribe</a>
                                </p>
                                <p style="margin:4px 0 0 0;font-size:11px;color:#9CA3AF;">&copy; {{ $currentYear ?? date('Y') }} {{ $companyName ?? config('app.name') }}. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
