<?php

namespace App\Formato\Inventario;

use App\Entity\General\GenConfiguracion;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class FormatoMovimientoTraslado extends \FPDF
{

    public static $em;
    public static $codigoMovimiento;
    public static $strLetras;
    public static $numeroRegistros;

    /**
     * @param $em ObjectManager
     * @param $codigoMovimiento integer
     */
    public function Generar($em, $codigoMovimiento)
    {
        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        ob_clean();
        $pdf = new FormatoMovimientoTraslado('P', 'mm', 'letter');
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($codigoMovimiento);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arMovimiento->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arMovimiento->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Movimiento_{$arMovimiento->getNumero()}.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arMovimiento InvMovimiento */
        $em = self::$em;
        $arMovimiento = self::$em->getRepository(InvMovimiento::class)->find(self::$codigoMovimiento);
        /** @var  $arConfiguracion GenConfiguracion */
        $this->SetFont('Arial', '', 5);
        $date = new \DateTime('now');
        $this->Text(168, 8, $date->format('Y-m-d H:i:s') . ' [Cromo | Inventario]');
        $this->SetFillColor(200, 200, 200);
        //Logo
        $this->SetXY(53, 10);
        try {
            $logo=$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if($logo ){

                $this->Image("data:image/'{$logo->getExtension()}';base64,".base64_encode(stream_get_contents($logo->getImagen())), 10, 13, 55, 25,$logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $intY = 40;
        $this->Cell(147, 7, utf8_decode('MOVIMIENTO'), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode($arMovimiento->getTerceroRel()->getNombreCorto() ?? ''), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arMovimiento->getTerceroRel()->getNumeroIdentificacion() ?? '', 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode($arMovimiento->getTerceroRel()->getDireccion()) ?? '', 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arMovimiento->getTerceroRel()->getTelefono() ?? '', 0, 0, 'L', 0);

        $this->SetXY(160, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "CODIGO:", 0, 0, 'L', 1);
        $this->Cell(10, 4, utf8_decode('F-AL-15'), 0, 0, 'L', 0);
        $this->SetXY(160, 22);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "VERSION:", 0, 0, 'L', 1);
        $this->Cell(10, 4, utf8_decode(01), 0, 0, 'L', 0);
        $this->SetXY(160, 26);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "FECHA:", 0, 0, 'L', 1);
        $this->Cell(10, 4, '29/04/2019', 0, 0, 'L', 0);


        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arMovimiento->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arMovimiento->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TIPO MOVIMIENTO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arMovimiento->getDocumentoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arMovimiento->getSoporte(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(160, 4, $arMovimiento->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
//        $this->Cell(160, 4, $arMovimiento->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->MultiCell(160, 4, $arMovimiento->getComentarios(), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(6);
        $this->SetX(10);
        $header = array('ITEM', 'DESCRIPCION', 'REF', 'LOTE', 'VENCE','B_OR', 'B_DES', 'UNIDAD', 'CANT', 'PRECIO', 'IVA', 'DCTO', 'TOTAL');
        $this->SetFillColor(225, 225, 225);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(10, 50, 15, 15, 15, 10, 10, 11, 10, 15, 7, 7.4, 15);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        }
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
        $arMovimiento = self::$em->getRepository(InvMovimiento::class)->find(self::$codigoMovimiento);
        $arMovimientoDetalles = self::$em->getRepository('App:Inventario\InvMovimientoDetalle')->listaDetalle(self::$codigoMovimiento, $arMovimiento->getCodigoDocumentoTipoFk());
        self::$numeroRegistros = count($arMovimientoDetalles);
        $pdf->SetFont('Arial', '', 6);
        /** @var  $arMovimientoDetalle InvMovimientoDetalle */
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $pdf->SetX(10);
            $pdf->Cell(10, 6, $arMovimientoDetalle['codigoItemFk'], 1, 0, 'L');
            $pdf->Cell(50, 6, substr(utf8_decode($arMovimientoDetalle['itemNombre']),0, 38 ), 1, 0, 'L');
            $pdf->Cell(15, 6, substr($arMovimientoDetalle['itemReferencia'], 0, 12), 1, 0, 'R');
            $pdf->Cell(15, 6, substr($arMovimientoDetalle['loteFk'], 0, 10), 1, 0, 'R');
            $pdf->Cell(15, 6, $arMovimientoDetalle['fechaVencimiento']->format('Y-m-d'), 1, 0, 'R');
            $pdf->Cell(10, 6, substr($arMovimientoDetalle['codigoBodegaFk'], 0, 4), 1, 0, 'R');
            $pdf->Cell(10, 6, substr($arMovimientoDetalle['codigoBodegaDestinoFk'], 0, 4), 1, 0, 'R');
            $pdf->Cell(11, 6, 'UNIDAD', 1, 0, 'C');
            $pdf->Cell(10, 6, $arMovimientoDetalle['cantidad'], 1, 0, 'R');
            $pdf->Cell(15, 6, number_format($arMovimientoDetalle['vrPrecio'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(7, 6, number_format($arMovimientoDetalle['porcentajeIva'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(7.4, 6, number_format($arMovimientoDetalle['porcentajeDescuento'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 6, number_format($arMovimientoDetalle['vrTotal'], 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 45);
        }
        $numeroPaginas = ceil(self::$numeroRegistros / 28);
        if ($pdf->PageNo() == $numeroPaginas) {
            /** @var  $arMovimiento InvMovimiento */

            $pdf->SetTextColor(0);
            $pdf->Ln();
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(236, 236, 236);
            $pdf->Cell(25, 4, "SUBTOTAL", 1, 0, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrSubtotal(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(236, 236, 236);
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->Cell(25, 4, "IVA", 1, 0, 'L', true);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrIva(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(236, 236, 236);
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->Cell(25, 4, "TOTAL NETO", 1, 0, 'L', true);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrTotal(), 2, '.', ','), 1, 0, 'R');
        }
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