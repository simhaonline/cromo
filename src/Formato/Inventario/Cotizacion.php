<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionDetalle;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Cotizacion extends \FPDF
{

    public static $em;
    public static $codigoCotizacion;
    public static $strLetras;
    /**
     * @param $em ObjectManager
     * @param $codigoPedido integer
     */
    public function Generar($em, $codigoCotizacion)
    {
        self::$em = $em;
        self::$codigoCotizacion = $codigoCotizacion;
        ob_clean();
        $pdf = new Cotizacion('P', 'mm', 'letter');
        $arCotizacion = $em->getRepository(InvCotizacion::class)->find($codigoCotizacion);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arCotizacion->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arCotizacion->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Cotizacion$codigoCotizacion.pdf", 'D');
    }

    public function Header()
    {

        $arCotizacion = self::$em->getRepository(InvCotizacion::class)->find(self::$codigoCotizacion);
        Estandares::generarEncabezado($this, 'COTIZACION', self::$em, 3);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, $arCotizacion->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, $arCotizacion->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TERCERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, utf8_decode(substr($arCotizacion->getTerceroRel()->getNombreCorto() . '-' . $arCotizacion->getTerceroRel()->getDigitoVerificacion(), 0 , 55)));
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, $arCotizacion->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "DIRECCION:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, $arCotizacion->getTerceroRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'TELEFONO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, $arCotizacion->getTerceroRel()->getTelefono(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TIEMPO ENTREGA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, $arCotizacion->getTiempoEntrega() ? : 'NO ESTABLECIDO', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'VENCIMIENTO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, $arCotizacion->getVencimiento() != null ? $arCotizacion->getVencimiento()->format('Y-m-d'): " ", 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FORMA PAGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, utf8_decode($arCotizacion->getFormaPagoRel() ? $arCotizacion->getFormaPagoRel()->getNombre() : ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'ASESOR:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, utf8_decode($arCotizacion->getAsesorRel() ? $arCotizacion->getAsesorRel()->getNombre() : ''), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CONTACTO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, utf8_decode($arCotizacion->getContactoRel() ? $arCotizacion->getContactoRel()->getNombreCorto() : ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'TELEFONO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, utf8_decode($arCotizacion->getContactoRel() ? $arCotizacion->getContactoRel()->getTelefono() : ''), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "SOPORTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(85, 4, utf8_decode($arCotizacion->getSoporte()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 4, "", 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 28);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160, 6, utf8_decode($arCotizacion->getComentarios()), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(14);
        $header = array('COD', 'ITEM', 'CANT', 'MARCA', 'PRECIO', 'IVA', 'SUBTOTAL', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 85, 15, 20, 15, 15, 15, 15);
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

    /**
     * @param $pdf
     */
    public function Body($pdf)
    {

        $arCotizacion = self::$em->getRepository(InvCotizacion::class)->find(self::$codigoCotizacion);
        $arCotizacionDetalles = self::$em->getRepository(InvCotizacionDetalle::class)->findBy(array('codigoCotizacionFk' => self::$codigoCotizacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        //$w = array(15, 35, 15, 30, 30, 15, 25, 25);
        //$header = array('COD', 'ITEM', 'CANT', 'MARCA', 'PRECIO', 'IVA', 'SUBTOTAL', 'TOTAL');
        foreach ($arCotizacionDetalles as $arCotizacionDetalle) {
            $pdf->Cell(10, 4, $arCotizacionDetalle->getCodigoCotizacionDetallePk(), 1, 0, 'L');
            $pdf->Cell(85, 4, utf8_decode($arCotizacionDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arCotizacionDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(20, 4, $arCotizacionDetalle->getItemRel()->getMarcaRel()->getNombre(), 1, 0, 'C');
            $pdf->Cell(15, 4, number_format($arCotizacionDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arCotizacionDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arCotizacionDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arCotizacionDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

        $pdf->SetFont('Arial', '', 7);
        //TOTALES
        $pdf->Ln(2);
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arCotizacion->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arCotizacion->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "COSTO ENVIO:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arCotizacion->getCostoEnvio(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arCotizacion->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln(-8);
    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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