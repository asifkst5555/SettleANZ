@extends('layouts.email', [
    'subject' => 'Your Download: ' . ($ebookTitle ?? 'Ebook'),
    'companyName' => $companyName ?? config('app.name'),
    'logo' => $companyLogo ?? asset('media/logo/email_logo.png'),
    'website' => url('/'),
    'buttonUrl' => $viewUrl ?? '#',
    'supportEmail' => 'hello@settleanz.com',
])

@section('preheader')
    Your ebook "{{ $ebookTitle ?? 'Ebook' }}" is ready to download and view.
@endsection

@section('heading')
    Your Download is Ready
@endsection

@section('content')
    <p style="margin:0 0 16px 0;">Hi {{ $leadName ?? 'Valued Reader' }},</p>
    <p style="margin:0 0 16px 0;">Thank you for your interest in <strong style="color:#0F766E;">{{ $ebookTitle ?? 'Ebook' }}</strong>.</p>

    @if(!empty($ebookDescription))
    <p style="margin:0 0 20px 0;color:#6B7280;font-style:italic;">{{ $ebookDescription }}</p>
    @endif

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0;">
        <tr>
            <td align="center">
                <a href="{{ $viewUrl ?? '#' }}" style="background:#0F766E;color:#FFFFFF;padding:15px 30px;border-radius:8px;text-decoration:none;font-size:15px;font-weight:600;display:inline-block;margin:0 8px 10px 0;box-shadow:0 4px 12px rgba(15,118,110,0.25);">View Online</a>
                <a href="{{ $downloadUrl ?? '#' }}" style="background:#F97316;color:#FFFFFF;padding:15px 40px;border-radius:8px;text-decoration:none;font-size:16px;font-weight:600;display:inline-block;box-shadow:0 4px 12px rgba(249,115,22,0.3);">Download PDF</a>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F8F4EC;border-radius:8px;margin:0 0 20px 0;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="font-size:13px;color:#0F766E;margin:0 0 4px 0;font-weight:600;">&#9200; Expires {{ $expiresAt ?? 'soon' }}</p>
                <p style="font-size:13px;color:#6B7280;margin:0;">You can download it up to {{ $expiresInHours ?? 72 }} times within this period.</p>
            </td>
        </tr>
    </table>

    <p style="font-size:13px;color:#6B7280;margin:0 0 4px 0;">If the buttons above don't work, copy this URL into your browser:</p>
    <p style="font-size:13px;margin:0;"><a href="{{ $downloadUrl ?? '#' }}" style="color:#0F766E;word-break:break-all;">{{ $downloadUrl ?? '#' }}</a></p>
@endsection

@section('button')
    View Online
@endsection

@section('footer')
    You received this email because you requested this ebook from {{ $companyName ?? config('app.name') }}.
@endsection
