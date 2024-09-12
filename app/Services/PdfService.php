<?php
namespace App\Services;

interface PdfService
{
    
public function generatePdf(string $view, array $data, string $pdfPath);
}