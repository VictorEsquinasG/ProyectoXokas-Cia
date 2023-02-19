<?php

namespace App\Service;

use Dompdf\Dompdf;

class PdfMaker
{
    private Dompdf $pdf;
    private $html;

    function __construct()
    {
        $this->pdf = new Dompdf(); // Seteamos el PEDEEFER
    }


    public function show()
    {
        # TODO
        $this->show();
        // return $this;
    }

    public function setPaper(string $tamaño, string $orientacion): self
    {
        # El tipo de papel ->Folio A4...
        $this->pdf->setPaper($tamaño, $orientacion);

        return $this;
    }

    public function sethtml($html): self
    {
        # guardamos el valor
        $this->html = $html;

        $this->pdf->loadHtml($this->html);

        return $this;
    }

    public function setCSS($css): self
    {
        $this->pdf->setCss($css);

        return $this;
    }

    public function renderizar(): self
    {
        $this->pdf->render();
        return $this;
    }

    public function recargaForzada(string $documento, ?array $opciones = null):self
    {
        return 
        $this->pdf->stream($documento, $opciones);
    }
}
