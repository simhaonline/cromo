<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;


class PendienteDespachoRuta extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new PendienteDespachoRuta();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PendienteDespachoRuta.pdf", 'D');
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
        $header = array('ID', 'OI','OC', 'NUMERO', 'FECHA', 'CLIENTE', 'DESTINO', 'DECLARA', 'FLETE', 'UND', 'PES','VOL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(12, 8, 8, 15, 15, 45, 35, 15, 15, 8,8,8);
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
        $arGuias = $em->getRepository(TteGuia::class)->informeDespachoPendienteRuta();
        $vrTotalDeclara = 0;
        $vrTotalFlete = 0;
        $vrTotalUnidad = 0;
        $vrTotalPeso = 0;
        $vrTotalVolumen = 0;
        $primeraRuta = true;
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            foreach ($arGuias as $arGuia) {
                if($primeraRuta){
                    $pdf->Cell(192,4,$arGuia['nombreRuta'],1,0,'L');
                    $primeraRuta = false;
                    $ruta = $arGuia['codigoRutaFk'];
                    $pdf->Ln(4);
                }
                if($arGuia['codigoRutaFk'] != $ruta){
                    $pdf->SetX(148);
                    $pdf->Cell(15,4,$vrTotalDeclara,1,0,'R');
                    $pdf->Cell(15,4,$vrTotalFlete,1,0,'R');
                    $pdf->Cell(8,4,$vrTotalUnidad,1,0,'R');
                    $pdf->Cell(8,4,$vrTotalPeso,1,0,'R');
                    $pdf->Cell(8,4,$vrTotalVolumen,1,0,'R');
                    $pdf->Ln(4);
                    $pdf->Cell(192,4,$arGuia['nombreRuta'],1,0,'L');
                    $ruta = $arGuia['codigoRutaFk'];
                    $pdf->Ln(4);
                }
                $pdf->Cell(12, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(8, 4, $arGuia['codigoOperacionIngresoFk'], 1, 0, 'L');
                $pdf->Cell(8, 4, $arGuia['codigoOperacionCargoFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(45, 4, substr($arGuia['clienteNombreCorto'],0,25), 1, 0, 'L');
                $pdf->Cell(35, 4, substr($arGuia['ciudadDestino'], 0, 22), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
                $vrTotalDeclara += $arGuia['vrDeclara'];
                $vrTotalFlete += $arGuia['vrFlete'];
                $vrTotalUnidad += $arGuia['unidades'];
                $vrTotalPeso += $arGuia['pesoReal'];
                $vrTotalVolumen += $arGuia['pesoVolumen'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 25);
            }
        }
    }

}
?>