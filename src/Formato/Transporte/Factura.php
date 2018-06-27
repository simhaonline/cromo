<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;

class Factura extends \FPDF {
    public static $em;
    public static $codigoFactura;

    public function Generar($em, $codigoFactura) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new Factura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteFactura$codigoFactura.pdf", 'I');
    }

    public function Header() {
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $this->Image('../public/img/empresa/logo.jpeg', 10, 8, 40, 18);
        $this->Image('../public/img/empresa/veritas.jpeg', 110, 8, 50, 25);
        $this->SetFont('Arial', 'b', 9);
        $this->SetFillColor(272, 272, 272);
        $this->Text(15, 25, '');

        $this->SetFont('Arial', 'b', 13);
        $this->SetXY(57, 12);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(64, 17);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'l', 1);
        $this->SetXY(72, 22);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getTelefono()), 0, 0, 'l', 1);
        $this->SetXY(10, 29);
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(25, 5, utf8_decode("NIT."), 0, 0, 'l', 1);
        $this->SetXY(20, 29);
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(25, 5, utf8_decode($arConfiguracion->getNit()."-".$arConfiguracion->getDigitoVerificacion()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetXY(50, 30);
        $this->Cell(25, 5, utf8_decode("SOMOS REGIMEN COMUN NO RESPONSABLES DE IVA"), 0, 0, 'l', 1);

        $y = 20;
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y);
        $this->Cell(39, 6, "FACTURA DE VENTA", 1, 0, 'l', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y+6);
        $this->Cell(39, 6, "No". "  ". $arFactura->getNumero(), 1, 0, 'l', 1);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y+15);
        $this->Cell(39, 6, "FECHA", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y+20);
        $this->Cell(13, 7, "25", 1, 0, 'C', 1);
        $this->Cell(13, 7, "04", 1, 0, 'C', 1);
        $this->Cell(13, 7, "2018", 1, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y+27);
        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y+33);
        $this->Cell(13, 7, "25", 1, 0, 'C', 1);
        $this->Cell(13, 7, "04", 1, 0, 'C', 1);
        $this->Cell(13, 7, "2018", 1, 0, 'C', 1);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y+40);
        $this->Cell(13, 6, "DIA", 1, 0, 'C', 1);
        $this->Cell(13, 6, "MES", 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode("AÑO"), 1, 0, 'C', 1);

        $arFactura = new TteFactura();
        $arFactura = self::$em->getRepository(TteFactura::class)->find(self::$codigoFactura);
        $this->SetFont('Arial', '', 10);
        $y = 40;
        $this->Rect(10, 35, 140, 30);
        $this->Text(12, $y, utf8_decode("SEÑOR(ES):"));
        $this->Text(45, $y, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
        $this->Text(12, $y+5, utf8_decode("NIT:"));
        $this->Text(45, $y+5, utf8_decode($arFactura->getClienteRel()->getNumeroIdentificacion()));
        $this->Text(12, $y+10, utf8_decode("DIRECCION:"));
        $this->Text(45, $y+10, utf8_decode($arFactura->getClienteRel()->getDireccion()));
        $this->Text(12, $y+15, utf8_decode("CIUDAD:"));
        $this->Text(45, $y+15, "MEDELLIN");
        $this->Text(12, $y+20, utf8_decode("TELEFONO:"));
        $this->Text(45, $y+20, utf8_decode($arFactura->getClienteRel()->getTelefono()));
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(20);
        $header = array('GUIA', 'DOCUMENTO','DESTINATARIO', 'DESTINO', 'UND', 'KF', 'DECLARA', 'FLETE', 'MANEJO', 'TOTAL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(20, 20, 50, 20, 10, 10, 15, 15, 15, 15);
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
        $arGuias = self::$em->getRepository(TteGuia::class)->formatoFactura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(20, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(50, 4, $arGuia['nombreDestinatario'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arGuia['ciudadDestino'], 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoFacturado'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrTotal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
        }
    }

    public function Footer() {
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $this->SetFont('Arial','', 8);
        $this->SetXY(10, 200);
        $this->Line(10, 260,60,260);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(18, 267, "FIRMA AUTORIZADA");
        $this->Line(140, 260,200,260);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(155, 267, "FIRMA RECIBIDA");
        $this->SetFont('Arial', '', 7);
        $this->Text(10, 272, utf8_decode("Esta factura de venta se asimila, para sus efectos legales, a una letra de cambio según artículo 776 del código de comercio. En caso de mora, esta factura causará interés"));
        $this->Text(85, 275, utf8_decode("a la tasa máxima legal permitida"));
        $this->Text(25, 278, utf8_decode("Todo pago debe hacerse a la orden del primer beneficiario LOGICUARTAS S.A.S consignar en BANCOLOMBIA la cuenta corriente 01882109665"));
        $this->SetXY(7, 279);
        $this->MultiCell(193,3,  utf8_decode("Factura impresa en computador por LOGICUARTAS S.A.S NIT 900486121-3 SEGÚN".$arFactura->getFacturaTipoRel()->getResolucionFacturacion()),0,'C');
        $this->Text(65, 287, utf8_decode("Pasados 10 días no se aceptan devoluciones y/o reclamos de esta factura"));
        $this->SetFont('Arial', '', 8);
        $this->Text(165, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}
?>