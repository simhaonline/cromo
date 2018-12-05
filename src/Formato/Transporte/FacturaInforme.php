<?php

namespace App\Formato\Transporte;
use App\Entity\Transporte\TteFactura;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;

class FacturaInforme extends \FPDF {

    public static $em;
    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FacturaInforme();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("FacturaInforme.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'FACTURTA INFORME', self::$em);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('TIPO', 'NUMERO', 'FECHA','CLIENTE', 'CANT', 'FLETE', 'MANEJO', 'SUBTOTAL', 'TOTAL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(22, 15, 15, 60, 10, 15, 15, 15, 15 );
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $em = BaseDatos::getEm();
        $arFacturas = $em->getRepository(TteFactura::class)->listaInforme()->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $unidades = 0;
        $flete = 0;
        $manejo = 0;
        $subTotal = 0;
        $total = 0;
        foreach ($arFacturas as $arFactura) {
            $pdf->Cell(22, 4, $arFactura['facturaTipo'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['numero'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($arFactura['clienteNombre']), 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $arFactura['guias'], 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrFlete']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrManejo']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrSubtotal']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrTotal']), 'LRB', 0, 'R');
            $unidades += $arFactura['guias'];
            $flete += $arFactura['vrFlete'];
            $manejo += $arFactura['vrManejo'];
            $subTotal += $arFactura['vrSubtotal'];
            $total += $arFactura['vrTotal'];
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetX(10);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(112, 4, 'TOTALES', 1, 0, 'L');
        $pdf->Cell(10, 4,  number_format($unidades), 1, 0, 'R');
        $pdf->Cell(15, 4,  number_format($flete), 1, 0, 'R');
        $pdf->Cell(15, 4,  number_format($manejo), 1, 0, 'R');
        $pdf->Cell(15, 4,  number_format($subTotal), 1, 0, 'R');
        $pdf->Cell(15, 4,  number_format($total), 1, 0, 'R');
        $pdf->SetX(5);
        $pdf->Ln();
        $pdf->SetAutoPageBreak(true, 15);
    }
}
?> 