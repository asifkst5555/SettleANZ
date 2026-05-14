<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentationController extends Controller
{
    /**
     * Generate and download the SEO System documentation PDF
     */
    public function seoSystemPdf(): Response
    {
        // Check if user is admin
        $user = auth()->user();
        abort_unless($user?->is_admin, 403);

        // Generate PDF from blade view
        $pdf = Pdf::loadView('seo-documentation');
        
        // Set PDF options for better rendering
        $pdf->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Segoe UI',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15,
            'dpi' => 300,
        ]);

        // Return the PDF as a downloadable file
        return $pdf->download('SettleANZ-SEO-System-Documentation.pdf');
    }
}
