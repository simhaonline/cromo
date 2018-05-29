<?php

namespace App\Formato\Inventario;

use App\Entity\General\TteConfiguracion;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;

class Solicitud extends \FPDF
{
    public static $em;
    public static $codigoSolicitud;

    public function Generar($em, $codigoSolicitud)
    {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoSolicitud = $codigoSolicitud;
        $pdf = new Solicitud();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("InvSolicitud$codigoSolicitud.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arSolicitud InvSolicitud */
        $arSolicitud = self::$em->getRepository('App:Inventario\InvSolicitud')->find(self::$codigoSolicitud);
//        $arConfiguracion = self::$em->getRepository(TteConfiguracion::class)->impresionFormato();
        $this->Image('../assets/img/empresa/logo.jpeg', 12, 13, 35, 17);
        $this->SetFont('Arial', 'b', 9);
        $this->SetFillColor(272, 272, 272);
        $this->Text(15, 25, '');

        $this->SetFont('Arial', 'b', 14);
        $this->SetXY(60, 15);
        $this->Cell(30, 6, '', 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 12);
        $this->SetXY(60, 20);
        $this->Cell(30, 6, '', 0, 0, 'l', 1);
        $this->SetXY(60, 25);
        $this->Cell(30, 6, '', 0, 0, 'l', 1);

        $y = 20;
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(160, $y);
        $this->Cell(39, 6, "SOLICITUD ", 1, 0, 'l', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 5);
        $this->Cell(39, 6, "No: " . $arSolicitud->getCodigoSolicitudPk(), 1, 0, 'l', 1);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(160, $y + 15);
        $this->Cell(39, 6, "FECHA", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 20);
        $fecha = $arSolicitud->getFecha();
        $this->Cell(13, 6, $fecha->format('j'), 1, 0, 'C', 1);
        $this->Cell(13, 6, $fecha->format('m'), 1, 0, 'C', 1);
        $this->Cell(13, 6, $fecha->format('Y'), 1, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(160, $y + 25);
//        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+30);
//        $this->Cell(13, 6, "25", 1, 0, 'C', 1);
//        $this->Cell(13, 6, "04", 1, 0, 'C', 1);
//        $this->Cell(13, 6, "2018", 1, 0, 'C', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(160, $y + 35);
//        $this->Cell(13, 6, "DIA", 1, 0, 'C', 1);
//        $this->Cell(13, 6, "MES", 1, 0, 'C', 1);
//        $this->Cell(13, 6, utf8_decode("AÑO"), 1, 0, 'C', 1);

        $arSolicitud = new InvSolicitud();
        $arSolicitud = self::$em->getRepository(InvSolicitud::class)->find(self::$codigoSolicitud);
        $this->SetFont('Arial', '', 10);
        $y = 40;
        $this->Rect(10, 35, 140, 30);
        $this->Text(15, $y, utf8_decode("SEÑOR(ES):"));
        $this->Text(50, $y, "EL CLIENTE SAS");
        $this->Text(15, $y + 5, utf8_decode("NIT:"));
        $this->Text(50, $y + 5, "800145742-4");
        $this->Text(15, $y + 10, utf8_decode("DIRECCION:"));
        $this->Text(50, $y + 10, "CRA 25 NRO 35-58");
        $this->Text(15, $y + 15, utf8_decode("CIUDAD:"));
        $this->Text(50, $y + 15, "MEDELLIN");
        $this->Text(15, $y + 20, utf8_decode("TELEFONO:"));
        $this->Text(50, $y + 20, "4587478");
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(20);
        $header = array('ID', 'ITEM', 'CANTIDAD');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 85, 89);
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
        $arSolicitudDetalles = self::$em->getRepository(InvSolicitudDetalle::class)->findBy(['codigoSolicitudFk' => self::$codigoSolicitud]);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if (count($arSolicitudDetalles) > 0) {
            /** @var  $arSolicitudDetalle InvSolicitudDetalle */
            foreach ($arSolicitudDetalles as $arSolicitudDetalle) {
                $pdf->Cell(15, 4, $arSolicitudDetalle->getCodigoSolitudDetallePk(), 1, 0, 'L');
                $pdf->Cell(85, 4, $arSolicitudDetalle->getItemRel()->getNombre(), 1, 0, 'L');
                $pdf->Cell(89, 4, $arSolicitudDetalle->getCantidad(), 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
        }
    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->Text(10, 260, "CONDUCTOR: _____________________________________________");
        $this->Text(10, 267, "");
        $this->Text(10, 274, "C.C.:     ______________________ de ____________________");

        $this->Text(105, 260, "EMPRESA: _____________________________________________");

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>