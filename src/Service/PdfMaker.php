<?php

namespace App\Service;

use Dompdf\Dompdf;

class PdfMaker
{
    private Dompdf $pdf;
    private $html;

    function __construct()
    {
        $this->pdf = new Dompdf();
    }

    public function show()
    {
        # 
        return $this;
    }
    
    public function sethtml($html):self
    {
        # guardamos el valor
        $this->html = $html;

        $this->pdf->loadHtml($html);

        return $this;
    }
    
    public function setCSS($css):self
    {
        $this->pdf->setCss($css);

        return $this;
    }
}
