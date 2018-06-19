<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvOrdenCompra;
use App\Entity\Inventario\InvOrdenCompraDetalle;
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
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("OrdenCompra$codigoOrdenCompra.pdf", 'I');
    }

    public function Header()
    {
        $arOrdenCompra = self::$em->getRepository('App:Inventario\InvOrdenCompra')->find(self::$codigoOrdenCompra);
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('../assets/img/empresa/logo.jpeg', 12, 13, 40, 25);
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
        $this->Cell(55, 4, utf8_decode($arOrdenCompra->getOrdenCompraTipoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, 'SOPORTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(55, 4, $arOrdenCompra->getSoporte(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 4, "FECHA ENTREGA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(150, 4, $arOrdenCompra->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 12);
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
        $header = array('COD', 'ITEM', 'CANT', '% IVA', 'VALOR', 'IVA', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 80, 15, 20, 20, 20,20);
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
        /**
         * @var $arOrdenCompra InvOrdenCompra
         * @var $arOrdenCompraDetalle InvOrdenCompraDetalle
         */
        $arOrdenCompra = self::$em->getRepository('App:Inventario\InvOrdenCompra')->find(self::$codigoOrdenCompra);
        $arOrdenCompraDetalles = self::$em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(array('codigoOrdenCompraFk' => self::$codigoOrdenCompra));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getCodigoOrdenCompraDetallePk(), 1, 0, 'L');
            $pdf->Cell(80, 4, utf8_decode($arOrdenCompraDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(20, 4, $arOrdenCompraDetalle->getPorcentajeIva(), 1, 0, 'C');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getValor(), 0, '.', ','), 1, 0, 'C');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrIva(), 0, '.', ','), 1, 0, 'C');
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

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
        $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrNeto(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln(-8);
    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}


?>