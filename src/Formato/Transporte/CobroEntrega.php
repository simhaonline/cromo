<?php

namespace App\Formato\Transporte;

use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class CobroEntrega extends \FPDF {
    public static $em;
    public static $codigoDespacho;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new CobroEntrega();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteCobro$codigoDespacho.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'COBRO ENTREGA');
        $arDespacho = new TteDespacho();
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(5, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arDespacho->getCodigoDespachoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CONDUCTOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 6, utf8_decode($arDespacho->getConductorRel() ? $arDespacho->getConductorRel()->getNombreCorto()  : ''), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(5, 45);
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
        $this->Cell(103, 5, $arDespacho->getCodigoVehiculoFk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $this->SetX(5);
        $header = array('TIPO', 'GUIA', 'NUMERO', 'FECHA','CLIENTE','DESTINATARIO', 'DIRECCION',  'COBRO ENTREGA');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(8, 16, 16, 10, 38, 43, 35, 28);
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
        $arGuias = self::$em->getRepository(TteGuia::class)->despachoCobroEntrega(self::$codigoDespacho);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            $numeroGuias = count($arGuias);
            $indiceGuia = 0;
            $imprimirTotalGrupo = false;
            $cobroTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->SetX(5);
                $pdf->Cell(8, 4, $arGuia['codigoGuiaTipoFk'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['fechaIngreso']->format('m-d'), 1, 0, 'L');
                $pdf->Cell(38, 4, substr(utf8_decode(utf8_decode($arGuia['clienteNombreCorto'])),0,20), 1, 0, 'L');
                $pdf->Cell(43, 4, substr(utf8_decode(utf8_decode($arGuia['nombreDestinatario'])),0,20), 1, 0, 'L');
                $pdf->Cell(35, 4, substr(utf8_decode(utf8_decode($arGuia['direccionDestinatario'])),0,20), 1, 0, 'L');
                $pdf->Cell(28, 4, number_format($arGuia['vrCobroEntrega'], 0, '.', ','), 1, 0, 'R');
                $cobroTotal += $arGuia['vrCobroEntrega'];
                $pdf->Ln();
                $pdf->SetX(5);
                $pdf->SetAutoPageBreak(true, 15);

            }
            $pdf->SetX(5);
            $pdf->Cell(166, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(28, 4, number_format($cobroTotal), 1, 0, 'R');
            $pdf->SetX(5);
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
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