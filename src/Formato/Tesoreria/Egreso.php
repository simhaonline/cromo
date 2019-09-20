<?php

namespace App\Formato\Tesoreria;


use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Utilidades\Estandares;

class Egreso extends \FPDF
{
    public static $em;
    public static $codigoEgreso;

    public function Generar($em, $codigoEgreso)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoEgreso = $codigoEgreso;
        $pdf = new Egreso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $arEgreso = $em->getRepository(TesEgreso::class)->find($codigoEgreso);
        $pdf->SetTextColor(255, 220, 220);
        if ($arEgreso->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arEgreso->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Egreso$codigoEgreso.pdf", 'D');
    }

    public function Header()
    {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this, 'EGRESO', self::$em);
        $arEgreso = self::$em->getRepository(TesEgreso::class)->find(self::$codigoEgreso);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("TERCERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, utf8_decode($arEgreso->getTerceroRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, 'NUMERO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arEgreso->getNumero(), 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("IDENTIFICACION:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arEgreso->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arEgreso->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 52);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'DIRECCION', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arEgreso->getTerceroRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA PAGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arEgreso->getFechaPago()->format('Y-m-d'), 1, 0, 'L', 1);
        //linea 4
        $this->SetXY(10, 58);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'TELEFONO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arEgreso->getTerceroRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "PAGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arEgreso->getVrPago()), 1, 0, 'R', 1);

        //linea 5
        $this->SetXY(10, 64);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'CUENTA', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "TOTAL", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arEgreso->getVrPagoTotal()), 1, 0, 'R', 1);

        //linea 6
        $this->SetXY(10, 70);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'SOPORTE', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, '', 1, 0, 'R', 1);

        //linea 7
        $this->SetXY(10, 76);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'NUM DOCUMENTO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arEgreso->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, '', 1, 0, 'R', 1);
        //linea 8
        $this->SetXY(10, 82);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(161, 4, utf8_decode($arEgreso->getComentarios()), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(12);
        $header = array('ID', 'TIPO', 'TERCERO', 'IDENTIFICACION','FECHA', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 20, 40, 25, 20,  20);
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

    public function Body($pdf)
    {
        $arEgresosDetalle = self::$em->getRepository(TesEgresoDetalle::class)->listaFormato(self::$codigoEgreso);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arEgresosDetalle) {
            foreach ($arEgresosDetalle as $arEgresoDetalle) {
                $pdf->Cell(15, 4, $arEgresoDetalle['codigoEgresoDetallePk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arEgresoDetalle['codigoCuentaPagarFk'], 1, 0, 'L');
                $pdf->Cell(40, 4, $arEgresoDetalle['tercero'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arEgresoDetalle['numeroIdentificacion'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arEgresoDetalle['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arEgresoDetalle['vrPagoAfectar'], 0, '.', ','), 1, 0, 'R');
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