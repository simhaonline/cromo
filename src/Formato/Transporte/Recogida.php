<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteRecogida;

class Recogida extends \FPDF {

    public static $em;
    public static $codigoRecogida;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoRecogida integer
     */
    public function Generar($em, $codigoRecogida)
    {
        self::$em = $em;
        self::$codigoRecogida = $codigoRecogida;
        ob_clean();
        $pdf = new Recogida('P', 'mm', 'letter');
        $arRecogida = $em->getRepository(TteRecogida::class)->find($codigoRecogida);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arRecogida->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arRecogida->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Movimiento$codigoRecogida.pdf", 'D');
    }

    public function Header() {

        $arRecogida = self::$em->getRepository('App:Transporte\TteRecogida')->find(self::$codigoRecogida);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('../public/img/empresa/logo.jpg', 12, 13, 40, 25);
        $this->Cell(147, 7, utf8_decode("RECOGIDA"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode('EMPRESA DE TRANSPORTE S.A.S'), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, '800123456-1', 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, utf8_decode('CRA 45 NRO 20-58'), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, '4587478', 0, 0, 'L', 0);

        //ENCABEZADO ORDEN DE COMPRA
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NIT:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRecogida->getClienteRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRecogida->getFecha()->format('Y/m/d/ H:i:s'), 1, 0, 'L', 1);
        //BLOQUE 2
        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CLIENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'UNIDAD:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRecogida->getUnidades(), 1, 0, 'L', 1);
        //BLOQUE 3
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "ANUNCIANTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getAnunciante()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'PESO', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRecogida->getPesoReal(), 1, 0, 'L', 1);
        //BLOQUE 4
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "DIRECCION:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getDireccion()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'VOLUMEN', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arRecogida->getPesoVolumen(), 1, 0, 'L', 1);
        //BLOQUE 5
        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TELEFONO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getTelefono()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);
        //BLOQUE 6
        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "RUTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);
        //BLOQUE 7 informacion conductor y vehiculo
        $intY2 = 68;
        $this->SetXY(10, $intY2);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CONDUCTOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getCodigoConductorFk() ? $arRecogida->getConductorRel()->getNombreCorto(): ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'IDENTIFICACION', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getCodigoConductorFk() ? $arRecogida->getConductorRel()->getNumeroIdentificacion(): ''), 1, 0, 'L', 1);
        //BLOQUE 8
        $this->SetXY(10, $intY2 + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "LICENCIA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getConductorRel() ? $arRecogida->getConductorRel()->getNumeroLicencia(): ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'PLACA', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getVehiculoRel() ? $arRecogida->getVehiculoRel()->getPlaca(): ''), 1, 0, 'L', 1);
        //BLOQUE 9
        $this->SetXY(10, $intY2 + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "MARCA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $marca = "";

        $this->Cell(65, 4, utf8_decode($arRecogida->getCodigoVehiculoFk() ? $arRecogida->getVehiculoRel()->getMarcaRel()->getCodigoMarcaPk(): ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'COLOR', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getCodigoVehiculoFk() ? $arRecogida->getVehiculoRel()->getColorRel()->getNombre(): ''), 1, 0, 'L', 1);
        //BLOQUE 10
        $this->SetXY(10, $intY2 + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "MODELO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arRecogida->getCodigoVehiculoFk() ? $arRecogida->getVehiculoRel()->getModelo(): ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);
        //BLOQUE 11
        $this->SetXY(10, $intY2 + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160,4,utf8_decode($arRecogida->getComentario()),1,'L');
        //BLOQUE FIRMAS
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, $intY2 + 30, "_________________________________");
        $this->Text(75, $intY2 + 30, "_________________________________");
        $this->Text(140, $intY2 + 30, "_________________________________");
        $this->Text(25, $intY2 + 34, "FIRMA EMPRESA");
        $this->Text(90, $intY2 + 34, "FIRMA CONDUCTOR");
        $this->Text(158, $intY2 + 34, "FIRMA CLIENTE");



    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {

    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
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