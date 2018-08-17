<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class Liquidacion extends \FPDF {
    public static $em;
    public static $codigoDespacho;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new Liquidacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteLiquidacion$codigoDespacho.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'LIQUIDACION');
        $arDespacho = new TteDespacho();
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        //linea1
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("CONDUCTOR:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, utf8_decode($arDespacho->getConductorRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "ORDEN:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(22, 6, $arDespacho->getCodigoDespachoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(20, 6, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(22, 6, $arDespacho->getNumero(), 1, 0, 'L', 1);

        //linea2
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("PLACA:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, $arDespacho->getCodigoVehiculoFk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(64, 6, $arDespacho->getFechaRegistro()->format('Y-m-d'), 1, 0, 'L', 1);

        //linea3
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 52);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("ACOMPAÑANTE:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "UNIDADES:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(64, 6, $arDespacho->getCantidad(), 1, 0, 'R', 1);

        //linea4
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 58);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("DESTINO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, utf8_decode($arDespacho->getCiudadDestinoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "PESO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(64, 6, $arDespacho->getPesoReal(), 1, 0, 'R', 1);
        //linea6
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 65);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(18, 5, utf8_decode("GUIA"), 1, 0, 'L', 1);
        $this->Cell(50, 5, utf8_decode('CLIENTE'), 1, 0, 'C', 1);
        $this->Cell(32, 5, utf8_decode('DESTINO'), 1, 0, 'C', 1);
        $this->Cell(30, 5, utf8_decode('REP. NOV'), 1, 0, 'C', 1);
        $this->Cell(64, 5, utf8_decode('NOVEDADES'), 1, 0, 'C', 1);
        //linea7
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 70);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(18, 5, '', 1, 0, 'L', 1);
        $this->Cell(50, 5, '', 1, 0, 'C', 1);
        $this->Cell(32, 5, '', 1, 0, 'C', 1);
        $this->Cell(30, 5, '', 1, 0, 'C', 1);
        $this->Cell(64, 5, '', 1, 0, 'C', 1);
        //linea8
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(5, 75);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(18, 5, '', 1, 0, 'L', 1);
        $this->Cell(50, 5, '', 1, 0, 'C', 1);
        $this->Cell(32, 5, '', 1, 0, 'C', 1);
        $this->Cell(30, 5, '', 1, 0, 'C', 1);
        $this->Cell(64, 5, '', 1, 0, 'C', 1);

        //linea9 Col1
        $this->SetXY(5, 81);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(18, 12, 'INGRESOS', 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 4, 'FLETE:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrFletePago()), 1, 0, 'R', 1);
        $this->SetXY(23, 85);
        $this->Cell(30, 4, 'AJUSTE PESO:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format('0'), 1, 0, 'R', 1);
        $this->SetXY(23, 89);
        $this->Cell(30, 4, '+ OTROS INGRESOS:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format('0'), 1, 0, 'R', 1);
        $this->SetXY(5, 93);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(48, 4, 'TOTAL INGRESOS', 1, 0, 'C', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrFletePago()), 1, 0, 'R', 1);

        //linea10
        $this->SetXY(5, 97);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(18, 16, 'DESCUENTOS', 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 4, '-DESCUENTOS LEY:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrIndustriaComercio() + $arDespacho->getVrRetencionFuente()), 1, 0, 'R', 1);
        $this->SetXY(23, 101);
        $this->Cell(30, 4, utf8_decode('-ACOMPAÑAMIENTO:'), 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrDescuentoSeguridad()), 1, 0, 'R', 1);
        $this->SetXY(23, 105);
        $this->Cell(30, 4, '-CARGUE:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrDescuentoCargue()), 1, 0, 'R', 1);
        $this->SetXY(23, 109);
        $this->Cell(30, 4, '-OTROS DESCUENTOS:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrDescuentoPapeleria() + $arDespacho->getVrDescuentoEstampilla()), 1, 0, 'R', 1);
        $this->SetXY(5, 113);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(48, 4, 'TOTAL DESCUENTOS', 1, 0, 'C', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrIndustriaComercio() + $arDespacho->getVrRetencionFuente() + $arDespacho->getVrDescuentoSeguridad() + $arDespacho->getVrDescuentoPapeleria() + $arDespacho->getVrDescuentoEstampilla()), 1, 0, 'R', 1);

        //linea11
        $this->SetXY(5, 117);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(18, 12, 'ANTICIPOS', 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 4, 'ANTICIPO 1:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrAnticipo()), 1, 0, 'R', 1);
        $this->SetXY(23, 121);
        $this->Cell(30, 4, 'ANTICIPO 2:', 1, 0, 'L', 1);
        $this->Cell(30, 4, number_format('0'), 1, 0, 'R', 1);
        $this->SetXY(5, 125);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(48, 4, 'TOTAL ANTICIPO', 1, 0, 'C', 1);
        $this->Cell(30, 4, number_format($arDespacho->getVrAnticipo()), 1, 0, 'R', 1);

        //linea12
        $this->SetXY(5, 129);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(48, 4, 'SALDO PLANILLA', 1, 0, 'C', 1);
        $this->SetXY(5, 133);
        $this->Cell(48, 4, 'PLANILLA-DESCUENTO-ANTICIPO', 1, 0, 'C', 1);
        $this->SetXY(53, 129);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 8, number_format($arDespacho->getVrSaldo()), 1, 0, 'R', 1);

        //linea9 Col2
        $this->SetXY(90, 81);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->MultiCell(18, 6, 'CONTRA ENTREGA', 1, 'L', false);
        $this->SetFont('Arial', '', 7);
        $this->SetXY(108, 81);
        $this->Cell(42, 4, 'TOTAL DESPACHOS:', 1, 0, 'L', 1);
        $this->Cell(49, 4, number_format($arDespacho->getVrCobroEntrega()), 1, 0, 'R', 1);
        $this->SetXY(108, 85);
        $this->Cell(42, 4, 'RECAUDO:', 1, 0, 'L', 1);
        $this->Cell(49, 4, number_format($arDespacho->getVrRecaudo()), 1, 0, 'R', 1);
        $this->SetXY(108, 89);
        $this->Cell(42, 4, 'FLETES NO PAGADOS:', 1, 0, 'L', 1);
        $this->Cell(49, 4, number_format('0'), 1, 0, 'R', 1);
        $this->SetXY(90, 93);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(60, 4, 'TOTAL REINTREGO CONTRAENTREGA', 1, 0, 'C', 1);
        $this->Cell(49, 4, number_format($arDespacho->getVrCobroEntrega() + $arDespacho->getVrRecaudo()), 1, 0, 'R', 1);

        //linea10 Col2
        $this->SetXY(90, 97);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->MultiCell(18, 4, 'SALDO NETO- PLANILLA:', 1, 'L', false);
        $this->SetFont('Arial', '', 7);
        $this->SetXY(108, 97);
        $this->MultiCell(42, 12, 'SALDO PLANILLAS - FLETES', 1, 'L', false);
        $this->SetXY(150, 97);
        $this->Cell(49, 12, number_format($arDespacho->getVrSaldo()), 1, 0, 'R', 1);

        //linea11 Col2
        $this->SetXY(90, 105);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(18, 8, 'ABONOS:', 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(42, 8, 'RECIBO DE CAJA No:', 1, 0, 'L', 1);
        $this->Cell(49, 8, number_format('0'), 1, 0, 'R', 1);

        //linea12 Col2
        $this->SetXY(90, 113);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(109, 20, '', 1, 0, 'L', 1);
        $this->SetXY(90, 113);
        $this->MultiCell(100, 6, $arDespacho->getComentario(), 0, 'L', false);

        //linea13 Col2
        $this->SetXY(90, 133);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 4, 'ELABORADO POR:', 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(84, 4, utf8_decode($arDespacho->getUsuario()), 1, 0, 'L', 1);



        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
    }

    public function Body($pdf) {
        $arGuias = self::$em->getRepository(TteGuia::class)->despachoCobroEntrega(self::$codigoDespacho);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 7);
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        //$this->Text(10, 260, "CONDUCTOR: _____________________________________________");
        //$this->Text(10, 267, "");
        //$this->Text(10, 274, "C.C.:     ______________________ de ____________________");

        //$this->Text(105, 260, "EMPRESA: _____________________________________________");

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>