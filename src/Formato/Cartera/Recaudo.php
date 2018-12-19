<?php

namespace App\Formato\Cartera;

use App\Entity\Cartera\CarRecibo;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class Recaudo extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new Recaudo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("RecaudoPorAsesor.pdf", 'D');
    }

    public function Header() {

        Estandares::generarEncabezado($this,'RECAUDO POR ASESOR', self::$em);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'RC', 'FAC', 'TIPO', 'FECHA', 'FECHA PAGO', 'NIT', 'NOMBRE', 'PAGO');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        $this->SetX(10);
        //creamos la cabecera de la tabla.
        $w = array(10, 10, 10, 23, 18, 18, 18, 50, 15);
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
        $arRecaudos = $em->getRepository(CarRecibo::class)->recaudo()->getQuery()->getResult();
        $vrTotalPago = 0;
        $primerAsesor = true;
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arRecaudos) {
            foreach ($arRecaudos as $arRecaudo) {
                if($primerAsesor){
                    $pdf->SetX(10);
                    $pdf->SetFont('Arial', 'b', 7);
                    $pdf->Cell(172,4,utf8_decode($arRecaudo['asesor']),1,0,'L');
                    $pdf->SetFont('Arial', '', 7);
                    $primerAsesor = false;
                    $asesor = $arRecaudo['codigoAsesorFk'];
                    $pdf->Ln(4);
                }
                if($arRecaudo['codigoAsesorFk'] != $asesor){
                    $pdf->SetX(167);
                    $pdf->SetFont('Arial', 'b', 7);
                    $pdf->Cell(15,4,number_format($vrTotalPago) ,1,0,'R');
                    $vrTotalPago = 0;
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Ln(4);
                    $pdf->SetX(10);
                    $pdf->SetFont('Arial', 'b', 7);
                    $pdf->Cell(172,4,utf8_decode($arRecaudo['asesor']),1,0,'L');
                    $pdf->SetFont('Arial', '', 7);
                    $asesor = $arRecaudo['codigoAsesorFk'];
                    $pdf->Ln(4);
                }
                $pdf->SetX(10);
                $pdf->Cell(10, 4, $arRecaudo['codigoReciboPk'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arRecaudo['numero'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arRecaudo['numeroFactura'], 1, 0, 'L');
                $pdf->Cell(23, 4, $arRecaudo['tipo'], 1, 0, 'L');
                $pdf->Cell(18, 4, $arRecaudo['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(18, 4, $arRecaudo['fechaPago']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(18, 4, $arRecaudo['nit'], 1, 0, 'L');
                $pdf->Cell(50, 4, substr($arRecaudo['clienteNombre'],0,30), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arRecaudo['vrPago']), 1, 0, 'R');
                $vrTotalPago += $arRecaudo['vrPago'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 25);
            }
        }
    }

}
?>