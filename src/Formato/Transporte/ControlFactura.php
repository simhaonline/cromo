<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;

class ControlFactura extends \FPDF {
    public static $em;
    public static $fecha;

    public function Generar($em, $fecha) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$fecha = $fecha;
        $pdf = new ControlFactura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $fecha);
        $pdf->Output("ControlFactura.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo


        Estandares::generarEncabezado($this,'CONTROL FACTURAS');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {

        $this->Ln(12);
    }

    public function Body($pdf, $fecha) {
        $em = BaseDatos::getEm();

        $header = array('ID', 'TIPO', 'DESDE','HASTA');
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 40, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln();

        $arFacturasTipo = $em->getRepository(TteFacturaTipo::class)->controlFactura($fecha);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturasTipo as $arFacturaTipo) {
            $pdf->Cell(15, 4, $arFacturaTipo['codigoFacturaTipoPk'], 'LRB', 0, 'L');
            $pdf->Cell(40, 4, utf8_decode($arFacturaTipo['nombre']), 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arFacturaTipo['desde'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arFacturaTipo['hasta'], 'LRB', 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

        $arFacturas = $em->getRepository(TteFactura::class)->controlFactura();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Ln();

        $header = array('ID', 'FACTURA', 'TIPO', 'FECHA','CLIENTE', 'FLETE', 'MANEJO', 'TOTAL');
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 15, 20, 15, 60, 15, 15, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln();

        foreach ($arFacturas as $arFactura) {
            $pdf->Cell(15, 4, $arFactura['codigoFacturaPk'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['numero'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, utf8_decode($arFactura['nombre']), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($arFactura['nombreCorto']), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, number_format($arFactura['vrFlete']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrManejo']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrTotal']), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

}
?>