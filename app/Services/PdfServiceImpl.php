<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfServiceImpl implements PdfService
{
    public function generatePdf(string $view, array $data, string $filePath)
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->save($filePath);
    }
}
