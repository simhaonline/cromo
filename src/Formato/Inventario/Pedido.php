<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Pedido extends \FPDF
{

    public static $em;
    public static $codigoPedido;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoPedido integer
     */
    public function Generar($em, $codigoPedido)
    {
        self::$em = $em;
        self::$codigoPedido = $codigoPedido;
        ob_clean();
        $pdf = new Pedido('P', 'mm', 'letter');
        $arPedido = $em->getRepository(InvPedido::class)->find($codigoPedido);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arPedido->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arPedido->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Pedido_$codigoPedido.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arPedido InvPedido */
        $arPedido = self::$em->getRepository(InvPedido::class)->find(self::$codigoPedido);
        Estandares::generarEncabezado($this, 'PEDIDO');
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arPedido->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arPedido->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TERCERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arPedido->getTerceroRel()->getNombreCorto()));
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arPedido->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arPedido->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arPedido->getSoporte(), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
//        $this->Cell(160, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->MultiCell(160, 4, $arPedido->getComentario(), 1, 'L');

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
        $w = array(15, 35, 15, 30, 30, 15, 25, 25);
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
        /** @var  $arPedido InvPedido */
        $arPedido = self::$em->getRepository(InvPedido::class)->find(self::$codigoPedido);
        $arPedidoDetalles = self::$em->getRepository(InvPedidoDetalle::class)->findBy(array('codigoPedidoFk' => self::$codigoPedido));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        //$w = array(15, 35, 15, 30, 30, 15, 25, 25);
        //$header = array('COD', 'ITEM', 'CANT', 'MARCA', 'PRECIO', 'IVA', 'SUBTOTAL', 'TOTAL');
        /** @var  $arPedidoDetalle InvPedidoDetalle */
        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            $pdf->Cell(15, 4, $arPedidoDetalle->getCodigoPedidoDetallePk(), 1, 0, 'L');
            $pdf->Cell(35, 4, utf8_decode($arPedidoDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arPedidoDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(30, 4, $arPedidoDetalle->getItemRel()->getMarcaRel()->getNombre(), 1, 0, 'C');
            $pdf->Cell(30, 4, number_format($arPedidoDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'C');
            $pdf->Cell(15, 4, number_format($arPedidoDetalle->getVrIva(), 0, '.', ','), 1, 0, 'C');
            $pdf->Cell(25, 4, number_format($arPedidoDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'C');
            $pdf->Cell(25, 4, number_format($arPedidoDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'C');
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
        $pdf->Cell(25, 4, number_format($arPedido->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arPedido->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, number_format($arPedido->getVrTotal(), 0, '.', ','), 1, 0, 'R');
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