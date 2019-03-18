<?php

namespace App\Formato\Cartera;

use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCompromisoDetalle;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Utilidades\Estandares;

class Compromiso extends \FPDF {
    public static $em;
    public static $codigoCompromiso;

    public function Generar($em, $codigoCompromiso) {
        ob_clean();
        self::$em = $em;
        self::$codigoCompromiso = $codigoCompromiso;
        $pdf = new Compromiso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $arCompromiso = $em->getRepository(CarCompromiso::class)->find($codigoCompromiso);
        $pdf->SetTextColor(255, 220, 220);
        if ($arCompromiso->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arCompromiso->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Compromiso$codigoCompromiso.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'COMPROMISO PAGO', self::$em);
        $arCompromiso= self::$em->getRepository(CarCompromiso::class)->find(self::$codigoCompromiso);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arCompromiso->getCodigoCompromisoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, 'CLIENTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, utf8_decode($arCompromiso->getClienteRel()->getNombreCorto()) , 1, 0, 'L', 1);
//        linea 2
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arCompromiso->getFecha()->format('Y-m-d') , 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "COMPROMISO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6,  $arCompromiso->getFechaCompromiso()->format('Y-m-d') , 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "COMENTARIOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(161,6,utf8_decode($arCompromiso->getComentarios()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln();
        $header = array('ID', 'CUENTA COBRAR','TIPO', 'FECHA', 'VENCE', 'PLAZO', 'VALOR', 'ABONO', 'SALDO','SALDO(0)');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 25, 30, 18, 18, 15, 20, 20, 20, 20);
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
        $arCompromisosDetalle = self::$em->getRepository(CarCompromisoDetalle::class)->listaFormato(self::$codigoCompromiso);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arCompromisosDetalle) {
            foreach ($arCompromisosDetalle as $arCompromisoDetalle) {
                $pdf->Cell(10, 4, $arCompromisoDetalle['codigoCompromisoDetallePk'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arCompromisoDetalle['cuentaCobrar'], 1, 0, 'L');
                $pdf->Cell(30, 4, $arCompromisoDetalle['cuentaCobrarTipo'], 1, 0, 'L');
                $pdf->Cell(18, 4, $arCompromisoDetalle['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(18, 4, $arCompromisoDetalle['fechaVence']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(15, 4, $arCompromisoDetalle['plazo'], 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arCompromisoDetalle['vrSaldoOriginal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompromisoDetalle['vrAbono'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompromisoDetalle['vrSaldo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompromisoDetalle['vrSaldoOperado'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    var $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

}

?>