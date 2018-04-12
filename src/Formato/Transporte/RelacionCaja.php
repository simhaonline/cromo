<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteRelacionCaja;
use App\Entity\Transporte\TteRecibo;

class RelacionCaja extends \FPDF {
    public static $em;
    public static $codigoRelacionCaja;

    public function Generar($em, $codigoRelacionCaja) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRelacionCaja = $codigoRelacionCaja;
        $pdf = new RelacionCaja();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("RelacionCaja$codigoRelacionCaja.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->impresionFormato();
        //{{ asset('../assets/css/general.css') }}
        $this->Image('../assets/img/empresa/logo.jpeg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(150, 7, utf8_decode("RELACION CAJA"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, 'EMPRESA:', 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode($arConfiguracion['nombre']), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion['nit'] . " - " . $arConfiguracion['digitoVerificacion'], 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion['direccion'], 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion['telefono'], 0, 0, 'L', 0);
/*
        $arDespacho = new TteDespacho();
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arDespacho->getCodigoDespachoPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CONDUCTOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 6, utf8_decode($arDespacho->getConductorRel()->getNombreCorto()), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arDespacho->getFechaRegistro()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "VEHICULO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5, $arDespacho->getCodigoVehiculoFk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
*/
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'FECHA', 'FLETE', 'MANEJO', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 15, 15, 15, 15);
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
        $arRecibos = self::$em->getRepository(TteRecibo::class)->relacionCaja(self::$codigoRelacionCaja);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arRecibos) {
            $flete = 0;
            $manejo = 0;
            $total = 0;
            foreach ($arRecibos as $arRecibo) {
                $pdf->Cell(15, 4, $arRecibo['codigoReciboPk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arRecibo['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arRecibo['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arRecibo['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arRecibo['vrTotal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
            $pdf->Cell(30, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(15, 4, $flete, 1, 0, 'R');
            $pdf->Cell(15, 4, $manejo, 1, 0, 'R');
            $pdf->Cell(15, 4, $total, 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>