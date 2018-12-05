<?php

namespace App\Formato\Compra;


use App\Entity\Compra\ComCompra;
use App\Entity\Compra\ComCompraDetalle;
use App\Entity\Compra\ComCuentaPagar;
use App\Utilidades\Estandares;

class Compras extends \FPDF
{
    public static $em;
    public static $codigoCompra;

    public function Generar($em, $codigoCompra)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoCompra = $codigoCompra;
        $pdf = new Compras();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $arCompra = $em->getRepository(ComCompra::class)->find($codigoCompra);
        $pdf->SetTextColor(255, 220, 220);
        if ($arCompra->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arCompra->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Recibo$codigoCompra.pdf", 'D');
    }

    public function Header()
    {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this, 'Compra', self::$em);
        $arCompra = self::$em->getRepository(ComCompra::class)->find(self::$codigoCompra);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("PROVEEDOR:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, utf8_decode($arCompra->getProveedorRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, 'NUMERO COMPRA:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arCompra->getNumeroCompra(), 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("IDENTIFICACION:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arCompra->getProveedorRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "NUMERO FACTURA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arCompra->getNumeroFactura(), 1, 0, 'R', 1);
        //linea 3
        $this->SetXY(10, 52);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'DIRECCION', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arCompra->getProveedorRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA FACTURA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arCompra->getFechaFactura()->format('Y-m-d'), 1, 0, 'R', 1);
        //linea 4
        $this->SetXY(10, 58);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'TELEFONO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arCompra->getProveedorRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA VENCE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arCompra->getFechaVencimiento()->format('Y-m-d'), 1, 0, 'R', 1);

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
        $this->Cell(30, 6, "SUB-TOTAL", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arCompra->getVrSubTotal()), 1, 0, 'R', 1);

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
        $this->Cell(30, 6, "DESCUENTO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arCompra->getVrDescuento(), 0, ".", ","), 1, 0, 'R', 1);

        //linea 7
        $this->SetXY(10, 76);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "IVA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arCompra->getVrIva(), 0, ".", ","), 1, 0, 'R', 1);
        //linea 8
        $this->SetXY(10, 82);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "TOTAL", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arCompra->getVrTotal(), 0, ".", ","), 1, 0, 'R', 1);

        //linea 9
        $this->SetXY(10, 88);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(161, 4, utf8_decode($arCompra->getComentarios()), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(12);
        $header = array('ID', 'CONCEPTO', 'CANT', 'PRECIO', 'SUB-TOTAL', '%DESC', 'DESC', '%IVA', 'IVA', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 40, 10, 20, 20, 10, 20, 20, 20, 20);
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
        $arComprasDetalle = self::$em->getRepository(ComCompraDetalle::class)->listaFormato(self::$codigoCompra);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arComprasDetalle) {
            foreach ($arComprasDetalle as $arCompraDetalle) {
                $pdf->Cell(10, 4, $arCompraDetalle['codigoCompraDetallePk'], 1, 0, 'L');
                $pdf->Cell(40, 4, $arCompraDetalle['concepto'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arCompraDetalle['cantidad'], 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['vrPrecioUnitario'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['vrSubtotal'], 0, '0', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arCompraDetalle['porDescuento'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['vrDescuento'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['porIva'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['vrIva'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arCompraDetalle['vrTotal'], 0, '.', ','), 1, 0, 'R');
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