<?php

namespace App\Formato\Inventario;

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
        $pdf = new Solicitud('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Movimiento$codigoSolicitud.pdf", 'D');
    }

    public function Header()
    {
        $arSolicitud = self::$em->getRepository('App:Inventario\InvSolicitud')->find(self::$codigoSolicitud);
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('../assets/img/empresa/logo.jpeg', 12, 13, 40, 25);
        //INFORMACIÓN EMPRESA
        $this->Cell(147, 7, utf8_decode("SOLICITUD"), 0, 0, 'C', 1);
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
        $this->MultiCell(160,4,$arSolicitud->getComentarios(),1,'L');




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
        $arSolicitud = self::$em->getRepository('App:Inventario\InvSolicitud')->find(self::$codigoSolicitud);
        $arSolicitudDetalles = self::$em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(array('codigoSolicitudFk' => self::$codigoSolicitud));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSolicitudDetalles as $arSolicitudDetalle) {
            $pdf->Cell(15, 4, $arSolicitudDetalle->getCodigoSolicitudDetallePk(), 1, 0, 'L');
            $pdf->Cell(160, 4, utf8_decode($arSolicitudDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arSolicitudDetalle->getCantidadSolicitada(), 1, 0, 'C');
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
}


?>