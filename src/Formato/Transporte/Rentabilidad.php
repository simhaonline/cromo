<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteDespacho;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class Rentabilidad extends \FPDF {

    public static $em;
    public static $fechaDesde;
    public static $fechaHasta;


    public function Generar($em, $fechaDesde, $fechaHasta) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new Rentabilidad();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $fechaDesde, $fechaHasta);
        $pdf->Output("Rentabilidad.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'RENTABILIDAD DESPACHO');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'TIPO', 'NUM', 'FECHA','PLACA', 'CONDUCTOR', 'DEST', 'RUTA', 'GUIAS', 'UND', 'PESO', 'MANJ', 'FLETE', 'TOTAL', 'PAGO', '%UT');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(8, 12, 10 ,13 ,12 ,25, 15, 12, 10, 10, 12, 12, 12, 12, 12, 10 );
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

    public function Body($pdf, $fechaDesde ,$fechaHasta) {
        $em = BaseDatos::getEm();
        $arDespachos = $em->getRepository(TteDespacho::class)->rentabilidad($fechaDesde, $fechaHasta)->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 6);
        foreach ($arDespachos as $arDespacho) {

            $pdf->Cell(8, 4, $arDespacho['codigoDespachoPk'], 'LRB', 0, 'L');
            $pdf->Cell(12, 4, $arDespacho['despachoTipo'], 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $arDespacho['numero'], 'LRB', 0, 'L');
            $pdf->Cell(13, 4, $arDespacho['fechaSalida']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(12, 4, utf8_decode($arDespacho['codigoVehiculoFk']), 'LRB', 0, 'L');
            $pdf->Cell(25, 4, substr(utf8_decode($arDespacho['conductorNombre']),0,17), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, substr(utf8_decode($arDespacho['ciudadDestino']),0,8), 'LRB', 0, 'L');
            $pdf->Cell(12, 4, substr(utf8_decode($arDespacho['ruta']),0,7), 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $arDespacho['cantidad'], 'LRB', 0, 'R');
            $pdf->Cell(10, 4, $arDespacho['unidades'], 'LRB', 0, 'R');
            $pdf->Cell(12, 4, number_format($arDespacho['pesoReal']), 'LRB', 0, 'R');
            $pdf->Cell(12, 4, number_format($arDespacho['vrManejo']), 'LRB', 0, 'R');
            $pdf->Cell(12, 4, number_format($arDespacho['vrFlete']), 'LRB', 0, 'R');
            $pdf->Cell(12, 4, number_format($arDespacho['vrTotalIngreso']), 'LRB', 0, 'R');
            $pdf->Cell(12, 4, number_format($arDespacho['vrFletePago']), 'LRB', 0, 'R');
            $pdf->Cell(10, 4, number_format($arDespacho['porcentajeRentabilidad']), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

}
?>