<?php

namespace App\Console\Commands;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;

class GenerateClientGuidePdf extends Command
{
    protected $signature = 'docs:client-guide {--output= : Optional output path}';

    protected $description = 'Generate SettleANZ-Client-Guide.pdf in the project root';

    public function handle(): int
    {
        $output = $this->option('output')
            ?: base_path('SettleANZ-Client-Guide.pdf');

        $pdf = Pdf::loadView('client-guide-documentation');
        $pdf->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'margin_top' => 12,
            'margin_bottom' => 12,
            'margin_left' => 14,
            'margin_right' => 14,
            'dpi' => 150,
        ]);

        $pdf->save($output);

        $this->info('Client guide PDF saved to: ' . $output);

        return self::SUCCESS;
    }
}
