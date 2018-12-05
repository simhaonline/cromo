<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvRemision;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Remision2 extends \FPDF
{

    public static $em;
    public static $codigoRemision;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoPedido integer
     */
    public function Generar($em, $codigoRemision)
    {
        self::$em = $em;
        self::$codigoRemision = $codigoRemision;
        ob_clean();
        $pdf = new Remision2('P', 'mm', 'letter');
        $arRemision = $em->getRepository(InvRemision::class)->find($codigoRemision);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arRemision->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arRemision->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Remision_$codigoRemision.pdf", 'D');
    }

    public function Header()
    {
        $arRemision = self::$em->getRepository(InvRemision::class)->find(self::$codigoRemision);
        Estandares::generarEncabezado($this, 'REMISION',self::$em);
        $this->SetXY(165, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(35, 5.333333, "CODIGO: F-CO-05", 0, 0, 'L', 1);
        $this->SetXY(165, 23.5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(35, 5.333333, "VERSION: 05", 0, 0, 'L', 1);
        $this->SetXY(165, 29);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(35, 5.333333, "FECHA: 03/18/16", 0, 0, 'L', 1);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRemision->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRemision->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TERCERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRemision->getTerceroRel()->getNombreCorto()), 1,0 ,'L');
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRemision->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CIUDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRemision->getTerceroRel()->getCiudadRel()->getNombre()), 1,0 ,'L');
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'DIRECCION:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRemision->getTerceroRel()->getDireccion()), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRemision->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRemision->getSoporte()), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "ASESOR", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRemision->getAsesorRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
//        $this->Cell(160, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->MultiCell(160, 4, utf8_decode($arRemision->getComentario()), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(14);
        $header = array('REF', 'ITEM', 'CANT', 'LOTE', 'PRECIO', 'IVA', 'SUBTOTAL', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 70, 10, 15, 20, 15, 20, 20);
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

        $arRemision = self::$em->getRepository(InvRemision::class)->find(self::$codigoRemision);
        $arRemisionDetalles = self::$em->getRepository(InvRemisionDetalle::class)->findBy(array('codigoRemisionFk' => self::$codigoRemision));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        //$w = array(15, 35, 15, 30, 30, 15, 25, 25);
        //$header = array('COD', 'ITEM', 'CANT', 'MARCA', 'PRECIO', 'IVA', 'SUBTOTAL', 'TOTAL');
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            $pdf->Cell(20, 4, $arRemisionDetalle->getItemRel()->getReferencia(), 1, 0, 'L');
            $pdf->Cell(70, 4, utf8_decode($arRemisionDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(10, 4, $arRemisionDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(15, 4, $arRemisionDetalle->getLoteFk(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arRemisionDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arRemisionDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arRemisionDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arRemisionDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

        $pdf->SetFont('Arial', '', 7);
        //TOTALES
        $pdf->Ln(2);
        $pdf->Cell(150, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($arRemision->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(150, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($arRemision->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(150, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($arRemision->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln(-8);
    }

    public function Footer()
    {
        $arRemision = self::$em->getRepository(InvRemision::class)->find(self::$codigoRemision);
        $arUsuario = self::$em->getRepository(Usuario::class)->findOneBy(array('username' => $arRemision->getUsuario()));
        $this->Ln();
        $this->SetFont('Arial', '', '8.2');
        $this->Text(10, 180, utf8_decode('OBSERVACIONES: A partir de la fecha de recibido los productos, el cliente expresa responsabilidad sobre los mismos  y se compromete a'));
        $this->Text(10, 184, utf8_decode('mantenerlos en condiciones optimas para su uso. El daño o perdida de los elementos aquí remisionados genera facturación a nombre del cliente'));
        //Bloque firmas Fila 1
        $this->Text(50, 200, utf8_decode('ENTREGA'));
        $this->Text(150, 200, utf8_decode('RECIBE'));
        //Bloque firmas Fila 2
        $this->Text(20, 214, utf8_decode('FIRMA:_________________________________'));
        $this->Text(120, 214, utf8_decode('FIRMA:_________________________________'));
        //Bloque firmas Fila 3
        $this->Text(16, 220, utf8_decode('NOMBRE:'.'    '. $arUsuario->getNombreCorto()));
        $this->Text(30, 220, utf8_decode('_________________________________'));
        $this->Text(116, 220, utf8_decode('NOMBRE:_________________________________'));
        //Bloque firmas Fila 4
        $this->Text(17, 226, utf8_decode('CEDULA:'. '    '. $arUsuario->getNumeroIdentificacion()));
        $this->Text(30, 226, utf8_decode('_________________________________'));
        $this->Text(117, 226, utf8_decode('CEDULA:_________________________________'));
        //Bloque firmas Fila 5
        $this->Text(18, 232, utf8_decode('CARGO:'. '    '. $arUsuario->getCargo()));
        $this->Text(30, 232, utf8_decode('_________________________________'));
        $this->Text(118, 232, utf8_decode('CARGO:_________________________________'));
        //Blque firmas Fila 6
        $this->Text(19, 238, utf8_decode('FECHA:'. '    '. $arRemision->getFecha()->format('Y-m-d')));
        $this->Text(30, 238, utf8_decode('_________________________________'));
        $this->Text(119, 238, utf8_decode('FECHA:_________________________________'));
        //Bloque logos
        $this->Image('../public/img/empresa/iso9001.jpg', 140, 245, 12, 18);
        $this->Image('../public/img/empresa/iqnet.jpg', 155, 245, 20, 18);

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