<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class OrdenCompra extends \FPDF
{

    public static $em;
    public static $codigoOrdenCompra;

    /**
     * @param $em ObjectManager
     * @param $codigoOrdenCompra integer
     */
    public function Generar($em, $codigoOrdenCompra)
    {
        self::$em = $em;
        self::$codigoOrdenCompra = $codigoOrdenCompra;
        ob_clean();
        $pdf = new OrdenCompra('P', 'mm', 'letter');
        $arOrdenCompra = $em->getRepository(InvOrden::class)->find($codigoOrdenCompra);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255,220,220);
        if ($arOrdenCompra->getEstadoAnulado()) {
            $pdf->RotatedText(90,150,'ANULADO',45);
        } elseif(!$arOrdenCompra->getEstadoAprobado()) {
            $pdf->RotatedText(90,150,'SIN APROBAR',45);
        }
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("OrdenCompra_$codigoOrdenCompra.pdf", 'D');
    }

    public function Header()
    {

        $arOrdenCompra = self::$em->getRepository(InvOrden::class)->find(self::$codigoOrdenCompra);
        Estandares::generarEncabezado($this, 'ORDEN DE COMPRA');
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        try {
            $this->Image('../public/assets/img/empresa/logo.jpg', 12, 13, 40, 25);
        } catch (\Exception $exception) {
        }
        //INFORMACIÓN EMPRESA
        $this->Cell(147, 7, utf8_decode("ORDEN DE COMPRA"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode(''), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, '', 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode(''), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, '', 0, 0, 'L', 0);

        //ENCABEZADO ORDEN DE COMPRA
        $intY = 40;
//        $this->SetFillColor(272, 272, 272);
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, $arOrdenCompra->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, $arOrdenCompra->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "TIPO ORDEN COMPRA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, utf8_decode($arOrdenCompra->getOrdenTipoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, $arOrdenCompra->getSoporte(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "TERCERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, utf8_decode($arOrdenCompra->getTerceroRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, $arOrdenCompra->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(150, 4, $arOrdenCompra->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
//        $this->Cell(160, 4, $arOrdenCompra->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->MultiCell(150, 4, $arOrdenCompra->getComentarios(), 1, 'L');


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(14);
        $header = ['COD', 'ITEM', 'CANT', '% IVA', 'VALOR', 'IVA', 'TOTAL'];
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 80, 15, 20, 20, 20, 20);
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

        $arOrdenCompra = self::$em->getRepository(InvOrden::class)->find(self::$codigoOrdenCompra);
        $arOrdenCompraDetalles = self::$em->getRepository(InvOrdenDetalle::class)->findBy(['codigoOrdenFk' => self::$codigoOrdenCompra]);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetTextColor(0);
        foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getCodigoOrdenDetallePk(), 1, 0, 'L');
            $pdf->Cell(80, 4, utf8_decode($arOrdenCompraDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(20, 4, $arOrdenCompraDetalle->getPorcentajeIva(), 1, 0, 'C');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');
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
        $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(145, 4, "", 0, 0, 'R');
        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrTotal(), 0, '.', ','), 1, 0, 'R');
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

    function RotatedText($x,$y,$txt,$angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

}


?>