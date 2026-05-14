<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class LegalPdfController extends Controller
{
    public function generate(): Response
    {
        $privacyPolicy = view('legal.privacy-policy', [
            'metaTitle' => 'Privacy Policy | SettleANZ',
            'metaDescription' => 'How SettleANZ collects, uses, and protects your personal information.',
        ])->render();

        $termsOfService = view('legal.terms-of-service', [
            'metaTitle' => 'Terms of Service | SettleANZ',
            'metaDescription' => 'Terms and conditions for using SettleANZ settlement services.',
        ])->render();

        // Clean up the HTML - remove scripts and inline styles for PDF
        $privacyPolicy = $this->cleanHtmlForPdf($privacyPolicy);
        $termsOfService = $this->cleanHtmlForPdf($termsOfService);

        $combinedHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SettleANZ Legal Documents</title>
    <style>
        @page { margin: 25mm 20mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11pt; line-height: 1.6; color: #333; }
        h1 { font-size: 22pt; color: #0f8b8d; margin-bottom: 8px; border-bottom: 2px solid #f27d2d; padding-bottom: 10px; }
        h2 { font-size: 14pt; color: #123247; margin-top: 25px; margin-bottom: 10px; }
        h3 { font-size: 12pt; color: #0a4f51; margin-top: 18px; margin-bottom: 8px; }
        p { margin-bottom: 10px; }
        ul, ol { margin-left: 20px; margin-bottom: 12px; }
        li { margin-bottom: 5px; }
        strong { color: #0f8b8d; }
        a { color: #0f8b8d; text-decoration: none; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 3px solid #0f8b8d; }
        .header-logo { font-size: 24pt; font-weight: bold; color: #0f8b8d; }
        .page-break { page-break-before: always; }
        .section-title { background: #f6f8fb; padding: 15px; border-left: 4px solid #f27d2d; margin-bottom: 20px; }
        .last-updated { font-style: italic; color: #666; margin-bottom: 25px; }
        .footer { text-align: center; font-size: 9pt; color: #888; margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">SettleANZ</div>
        <p>Legal Documents</p>
    </div>

    <div class="section-title">
        <h1>PRIVACY POLICY</h1>
        <p class="last-updated">Last updated: January 2026</p>
    </div>

    {$privacyPolicy}

    <div class="page-break"></div>

    <div class="section-title">
        <h1>TERMS OF SERVICE</h1>
        <p class="last-updated">Last updated: January 2026</p>
    </div>

    {$termsOfService}

    <div class="footer">
        SettleANZ &copy; 2026. All rights reserved.<br>
        For questions: hello@settleanz.com
    </div>
</body>
</html>
HTML;

        $pdf = Pdf::loadHtml($combinedHtml);
        $pdf->setPaper('A4', 'portrait');

        $outputPath = base_path('SettleANZ_Legal_Documents.pdf');
        $pdf->save($outputPath);

        return response()->download($outputPath, 'SettleANZ_Legal_Documents.pdf');
    }

    private function cleanHtmlForPdf(string $html): string
    {
        // Remove @section directives
        $html = preg_replace('/@section\([^)]+\)/', '', $html);
        $html = preg_replace('/@endsection/', '', $html);

        // Remove style blocks (we have custom PDF styles)
        $html = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $html);

        // Remove @php blocks
        $html = preg_replace('/@php.*?@endphp/s', '', $html);

        // Remove blade comments
        $html = preg_replace('/{{--.*?--}}/s', '', $html);

        // Remove class attributes for cleaner PDF
        $html = preg_replace('/class="[^"]*"/', '', $html);

        // Extract only the content from the article/div body
        if (preg_match('/<article[^>]*>(.*?)<\/article>/s', $html, $matches)) {
            return $matches[1];
        }
        if (preg_match('/<div[^>]*>(.*?)<\/div>/s', $html, $matches)) {
            return $matches[1];
        }

        return strip_tags($html, '<h1><h2><h3><p><ul><ol><li><strong><em><a><br>');
    }
}
