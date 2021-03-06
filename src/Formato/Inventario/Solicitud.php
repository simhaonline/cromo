<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Solicitud extends \FPDF
{

    public static $em;
    public static $codigoSolicitud;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoSolicitud integer
     */
    public function Generar($em, $codigoSolicitud)
    {
        self::$em = $em;
        self::$codigoSolicitud = $codigoSolicitud;
        ob_clean();
        $pdf = new Pago('P', 'mm', 'letter');
        $arSolicitud = $em->getRepository(InvSolicitud::class)->find($codigoSolicitud);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255,220,220);
        if ($arSolicitud->getEstadoAnulado()) {
            $pdf->RotatedText(90,150,'ANULADO',45);
        } elseif(!$arSolicitud->getEstadoAprobado()) {
            $pdf->RotatedText(90,150,'SIN APROBAR',45);
        }
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Solicitud_$codigoSolicitud.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arSolicitud InvSolicitud */
        $arSolicitud = self::$em->getRepository(InvSolicitud::class)->find(self::$codigoSolicitud);
        Estandares::generarEncabezado($this, 'SOLICITUD', self::$em);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arSolicitud->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TIPO SOLICITUD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arSolicitud->getSolicitudTipoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arSolicitud->getSoporte(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(160, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
//        $this->Cell(160, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->MultiCell(160, 4, $arSolicitud->getComentarios(), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(14);
        $header = array('COD', 'ITEM', 'CANT');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 160, 15);
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
        $arSolicitud = self::$em->getRepository(InvSolicitud::class)->find(self::$codigoSolicitud);
        $arSolicitudDetalles = self::$em->getRepository(InvSolicitudDetalle::class)->findBy(array('codigoSolicitudFk' => self::$codigoSolicitud));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSolicitudDetalles as $arSolicitudDetalle) {
            $pdf->Cell(15, 4, $arSolicitudDetalle->getCodigoSolicitudDetallePk(), 1, 0, 'L');
            $pdf->Cell(160, 4, utf8_decode($arSolicitudDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arSolicitudDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
//        //TOTALES
//        $pdf->Ln(2);
//        $pdf->Cell(145, 4, "", 0, 0, 'R');
//        $pdf->SetFont('Arial', 'B', 7);
//        $pdf->SetFillColor(236, 236, 236);
//        $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R', true);
//        $pdf->Cell(25, 4, '', 1, 0, 'R');
//        $pdf->Ln();
//        $pdf->Cell(145, 4, "", 0, 0, 'R');
//        $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
//        $pdf->Cell(25, 4, '', 1, 0, 'R');
//        $pdf->Ln();
//        $pdf->Cell(145, 4, "", 0, 0, 'R');
//        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
//        $pdf->Cell(25, 4, '', 1, 0, 'R');
//        $pdf->Ln(-8);

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
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
}

?>