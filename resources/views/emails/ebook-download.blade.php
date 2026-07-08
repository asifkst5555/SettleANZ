<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #1a73e8;">{{ $companyName }}</h1>
    </div>
    <div style="background: #f8f9fa; border-radius: 8px; padding: 30px; margin-bottom: 20px;">
        <h2 style="color: #1a73e8; margin-top: 0;">Your Download is Ready!</h2>
        <p>Hi {{ $leadName }},</p>
        <p>Thank you for your interest in <strong>{{ $ebookTitle }}</strong>.</p>
        @if($ebookDescription)
        <p>{{ $ebookDescription }}</p>
        @endif
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $downloadUrl }}" style="background: #1a73e8; color: white; padding: 14px 32px; border-radius: 6px; text-decoration: none; font-size: 16px; font-weight: bold; display: inline-block;">
                Download Your Ebook
            </a>
        </div>
        <p style="font-size: 14px; color: #666;">
            This download link will expire on <strong>{{ $expiresAt }}</strong>.
            You can download it up to {{ $expiresInHours }} times within this period.
        </p>
        <p style="font-size: 14px; color: #666;">
            If the button above doesn't work, copy and paste this URL into your browser:<br>
            <a href="{{ $downloadUrl }}" style="color: #1a73e8;">{{ $downloadUrl }}</a>
        </p>
    </div>
    <div style="text-align: center; font-size: 12px; color: #999; margin-top: 20px;">
        <p>You received this email because you requested this ebook from {{ $companyName }}.</p>
        <p>&copy; {{ $companyName }} {{ date('Y') }}. All rights reserved.</p>
    </div>
</body>
</html>
