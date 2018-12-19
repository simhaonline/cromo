<?php

namespace App\Formato\Cartera;

use App\Entity\Cartera\CarRecibo;
use App\Utilidades\BaseDatos;


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
//        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
//        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
//        $this->Image('../public/img/empresa/logo.jpg', 10, 8, 40, 18);
//        $this->Image('../public/img/empresa/veritas.jpeg', 110, 8, 50, 25);
//        $this->SetFont('Arial', 'b', 9);
//        $this->SetFillColor(272, 272, 272);
//        $this->Text(15, 25, '');
//
//        $this->SetFont('Arial', 'b', 13);
//        $this->SetXY(57, 12);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'l', 1);
//        $this->SetFont('Arial', '', 10);
//        $this->SetXY(64, 17);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'l', 1);
//        $this->SetXY(72, 22);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getTelefono()), 0, 0, 'l', 1);
//        $this->SetXY(10, 29);
//        $this->SetFont('Arial', 'b', 10);
//        $this->Cell(25, 5, utf8_decode("NIT."), 0, 0, 'l', 1);
//        $this->SetXY(20, 29);
//        $this->SetFont('Arial', 'b', 10);
//        $this->Cell(25, 5, utf8_decode($arConfiguracion->getNit()."-".$arConfiguracion->getDigitoVerificacion()), 0, 0, 'l', 1);
//        $this->SetFont('Arial', '', 8);
//        $this->SetXY(50, 30);
//        $this->Cell(25, 5, utf8_decode("SOMOS REGIMEN COMUN NO RESPONSABLES DE IVA"), 0, 0, 'l', 1);
//
//        $y = 20;
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y);
//        $this->Cell(39, 6, "FACTURA DE VENTA", 1, 0, 'l', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+6);
//        $this->Cell(39, 6, "No". "  ". $arFactura->getNumero(), 1, 0, 'l', 1);
//
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+15);
//        $this->Cell(39, 6, "FECHA", 1, 0, 'C', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+20);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('d'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('m'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('Y'), 1, 0, 'C', 1);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+27);
//        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+33);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('d'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('m'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('Y'), 1, 0, 'C', 1);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+40);
//        $this->Cell(13, 6, "DIA", 1, 0, 'C', 1);
//        $this->Cell(13, 6, "MES", 1, 0, 'C', 1);
//        $this->Cell(13, 6, utf8_decode("AÑO"), 1, 0, 'C', 1);
//
//        $arFactura = new TteFactura();
//        $arFactura = self::$em->getRepository(TteFactura::class)->find(self::$codigoFactura);
//        $this->SetFont('Arial', '', 10);
//        $y = 42;
//        $this->Rect(10, 36, 140, 30);
//        $this->Text(12, $y, utf8_decode("SEÑOR(ES):"));
//        $this->Text(45, $y, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
//        $this->Text(12, $y+5, utf8_decode("NIT:"));
//        $this->Text(45, $y+5, utf8_decode($arFactura->getClienteRel()->getNumeroIdentificacion()));
//        $this->Text(12, $y+10, utf8_decode("DIRECCION:"));
//        $this->Text(45, $y+10, utf8_decode($arFactura->getClienteRel()->getDireccion()));
//        $this->Text(12, $y+15, utf8_decode("CIUDAD:"));
//        $this->Text(45, $y+15, "MEDELLIN");
//        $this->Text(12, $y+20, utf8_decode("TELEFONO:"));
//        $this->Text(45, $y+20, utf8_decode($arFactura->getClienteRel()->getTelefono()));
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(1);
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