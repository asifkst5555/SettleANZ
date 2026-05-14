<?php

// Standalone script to generate legal PDF
// Usage: php generate-legal-pdf.php

require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', false);
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);

// Read the Blade files and extract content
$privacyPath = __DIR__ . '/resources/views/legal/privacy-policy.blade.php';
$termsPath = __DIR__ . '/resources/views/legal/terms-of-service.blade.php';

$privacyContent = file_exists($privacyPath) ? file_get_contents($privacyPath) : '';
$termsContent = file_exists($termsPath) ? file_get_contents($termsPath) : '';

// Extract content sections from Blade files
function extractContent($content) {
    // Find content between @section('content') and @endsection
    if (preg_match('/@section\([\'"]content[\'"]\)(.*?)@endsection/s', $content, $matches)) {
        $content = $matches[1];
    }

    // Remove @php blocks
    $content = preg_replace('/@php.*?@endphp/s', '', $content);

    // Convert blade variables to plain text or remove
    $content = preg_replace('/\{\{\s*\$[^}]+\s*\}\}/', '', $content);
    $content = preg_replace('/\{!!\s*[^!]+\s*!!\}/', '', $content);

    // Remove style blocks
    $content = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $content);

    // Clean up class attributes for PDF
    $content = preg_replace('/class="[^"]*"/', '', $content);

    // Remove empty div wrappers
    $content = preg_replace('/<div>\s*<\/div>/', '', $content);

    return trim($content);
}

$privacyBody = extractContent($privacyContent);
$termsBody = extractContent($termsContent);

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SettleANZ - Privacy Policy and Terms of Service</title>
    <style>
        @page { margin: 20mm 18mm; }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 10.5pt;
            line-height: 1.55;
            color: #2a3640;
        }
        .cover {
            text-align: center;
            padding-top: 150px;
            page-break-after: always;
        }
        .cover h1 {
            font-size: 32pt;
            color: #0f8b8d;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }
        .cover .tagline {
            font-size: 14pt;
            color: #5e707b;
            margin-top: 20px;
        }
        .cover .date {
            font-size: 11pt;
            color: #888;
            margin-top: 40px;
        }
        h1 {
            font-size: 18pt;
            color: #0f8b8d;
            margin-top: 0;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f27d2d;
        }
        h2 {
            font-size: 13pt;
            color: #123247;
            margin-top: 20px;
            margin-bottom: 8px;
        }
        h3 {
            font-size: 11pt;
            color: #0a4f51;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        p {
            margin-bottom: 10px;
            text-align: justify;
        }
        ul, ol {
            margin-left: 18px;
            margin-bottom: 12px;
            padding-left: 5px;
        }
        li {
            margin-bottom: 5px;
        }
        strong {
            color: #0f8b8d;
            font-weight: 600;
        }
        a {
            color: #0f8b8d;
            text-decoration: none;
        }
        .page-break {
            page-break-before: always;
        }
        .doc-header {
            background: #f6f8fb;
            padding: 15px 18px;
            border-left: 4px solid #f27d2d;
            margin-bottom: 20px;
        }
        .doc-header h1 {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 5px;
        }
        .last-updated {
            font-size: 10pt;
            color: #666;
            font-style: italic;
        }
        .footer {
            text-align: center;
            font-size: 9pt;
            color: #888;
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }
        .toc {
            page-break-after: always;
        }
        .toc h2 {
            color: #0f8b8d;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        .toc ul {
            list-style: none;
            margin-left: 0;
        }
        .toc li {
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover">
        <h1>SettleANZ</h1>
        <div class="tagline">Legal Documents</div>
        <p style="margin-top: 60px; font-size: 12pt;">Privacy Policy &amp; Terms of Service</p>
        <div class="date">Effective Date: January 2026</div>
    </div>

    <!-- Table of Contents -->
    <div class="toc">
        <h2>Table of Contents</h2>
        <ul>
            <li><strong>Part 1: Privacy Policy</strong></li>
            <li style="margin-left: 20px;">1. Introduction</li>
            <li style="margin-left: 20px;">2. Information We Collect</li>
            <li style="margin-left: 20px;">3. How We Use Your Information</li>
            <li style="margin-left: 20px;">4. Sharing Your Information</li>
            <li style="margin-left: 20px;">5. Cookies</li>
            <li style="margin-left: 20px;">6. Data Retention</li>
            <li style="margin-left: 20px;">7. Your Rights</li>
            <li style="margin-left: 20px;">8. Security</li>
            <li style="margin-left: 20px;">9. Third-Party Links</li>
            <li style="margin-left: 20px;">10. Children</li>
            <li style="margin-left: 20px;">11. Changes to this Policy</li>
            <li style="margin-left: 20px;">12. Contact</li>
            <li style="margin-top: 15px;"><strong>Part 2: Terms of Service</strong></li>
            <li style="margin-left: 20px;">1. Agreement</li>
            <li style="margin-left: 20px;">2. Who We Are</li>
            <li style="margin-left: 20px;">3. Informational Content</li>
            <li style="margin-left: 20px;">4. Services and Bookings</li>
            <li style="margin-left: 20px;">5. Third-Party Partners</li>
            <li style="margin-left: 20px;">6. Directory Listings</li>
            <li style="margin-left: 20px;">7. Intellectual Property</li>
            <li style="margin-left: 20px;">8. User Conduct</li>
            <li style="margin-left: 20px;">9. Limitation of Liability</li>
            <li style="margin-left: 20px;">10. Privacy</li>
            <li style="margin-left: 20px;">11. Changes to Terms</li>
            <li style="margin-left: 20px;">12. Governing Law</li>
            <li style="margin-left: 20px;">13. Contact</li>
        </ul>
    </div>

    <!-- Privacy Policy -->
    <div class="doc-header">
        <h1>PART 1: PRIVACY POLICY</h1>
        <div class="last-updated">Last updated: January 2026</div>
    </div>

    {$privacyBody}

    <div class="page-break"></div>

    <!-- Terms of Service -->
    <div class="doc-header">
        <h1>PART 2: TERMS OF SERVICE</h1>
        <div class="last-updated">Last updated: January 2026</div>
    </div>

    {$termsBody}

    <div class="footer">
        SettleANZ &copy; 2026. All rights reserved.<br>
        For questions: hello@settleanz.com | www.settleanz.com
    </div>
</body>
</html>
HTML;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$outputPath = __DIR__ . '/SettleANZ_Legal_Documents.pdf';
file_put_contents($outputPath, $dompdf->output());

echo "PDF generated successfully!\n";
echo "Location: {$outputPath}\n";
echo "File size: " . number_format(filesize($outputPath) / 1024, 2) . " KB\n";
