<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;

class Manifiesto extends \FPDF {
    public static $em;
    public static $codigoDespacho;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new Manifiesto('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Manifiesto$codigoDespacho.pdf", 'D');
    }

    public function Header() {
        $this->SetFont('Arial', 'b', 14);
        $this->Text(90, 15, "MANIFIESTO ELECTRONICO DE CARGA");
        $this->Text(90, 20, "EMPRESA DE TRANSPORTE SAS");
        $this->Text(90, 25, "NIT: 890547458");

        $this->Text(90, 35, "CARRERA 14 NRO 25-45");
        $this->Text(90, 40, "254 5847");
        $this->Text(90, 45, "BUCARAMANGA - SANTANDER");

        $this->SetFont('Arial', 'b', 5);
        $this->SetXY(190, 10);
        $texto = "La impresión en soporte cartular (papel) de este acto administrativo " .
        "producido por medios electrónicos en cumplimiento de la ley 527 de 1999 (Articulos 6 al 13) " .
        " y de la ley 962 de 2005 (Articulo 6), es una reproducción del documento original que se " .
        " encuentra en formato electrónico firmado digitalmente, cuya representación digital goza de " .
        " autenticidad, integridad y no repudio";
        $this->MultiCell(50, 2, utf8_decode($texto));

        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', '', 14);
        $this->SetXY(190, 30);
        $this->Cell(50, 7, "MANIFIESTO:", 1, 0, 'L', 1);
        $this->Cell(35, 7, "000001", 1, 0, 'R', 1);

        $this->SetXY(190, 37);
        $this->Cell(50, 7, "AUTORIZADO:", 1, 0, 'L', 1);
        $this->Cell(35, 7, "000001", 1, 0, 'R', 1);


        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {
        $arDespacho = self::$em->getRepository(TteDespacho::class)->dqlImprimirManifiesto(self::$codigoDespacho);
        $pdf->SetFont('Arial', 'b', 8);
        $x = 15;
        $y = 50;
        $yt = 53;
        $alto1 = 10;
        $alto2 = 5;
        $alto3 = 20;
        $pdf->Rect($x, $y, 50, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 80, $alto1);
        $pdf->Rect($x+180, $y, 80, $alto1);
        $pdf->Text(25, $yt, "FECHA EXPEDICION");
        $pdf->Text(80, $yt, "TIPO MANIFIESTO");
        $pdf->Text(137, $yt, "ORIGEN DEL VIAJE");
        $pdf->Text(215, $yt, "DESTINO DEL VIAJE");

        $y += 10;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 10;
        $pdf->Text(110, $yt, "INFORMACION DEL VEHICULO Y CONDUCTOR");

        $y += 5;
        $pdf->Rect($x, $y, 70, $alto1);
        $pdf->Rect($x+70, $y, 50, $alto1);
        $pdf->Rect($x+120, $y, 60, $alto1);
        $pdf->Rect($x+180, $y, 40, $alto1);
        $pdf->Rect($x+220, $y, 40, $alto1);
        $yt += 5;
        $pdf->Text(33, $yt, "TITULAR MANIFIESTO");
        $pdf->Text(90, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(155, $yt, "DIRECCION");
        $pdf->Text(205, $yt, "TELEFONO");
        $pdf->Text(245, $yt, "CIUDAD");


        $y += 10;
        $pdf->Rect($x, $y, 25, $alto1);
        $pdf->Rect($x+25, $y, 25, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 25, $alto1);
        $pdf->Rect($x+125, $y, 25, $alto1);
        $pdf->Rect($x+150, $y, 25, $alto1);
        $pdf->Rect($x+175, $y, 45, $alto1);
        $pdf->Rect($x+220, $y, 40, $alto1);
        $yt += 10;
        $pdf->Text(23, $yt, "PLACA");
        $pdf->Text(49, $yt, "MARCA");
        $pdf->Text(70, $yt, "PLACA SEMI REMOLQUE");
        $pdf->Text(115, $yt, "CONFIGURACION");
        $pdf->Text(142, $yt, "PESO VACIO");
        $pdf->Text(170, $yt, "N POLIZA");
        $pdf->Text(194, $yt, utf8_decode("COMPAÑIA SEGUROS SOAT"));
        $pdf->Text(245, $yt, "F/VENCE SOAT");

        $y += 10;
        $pdf->Rect($x, $y, 50, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 80, $alto1);
        $pdf->Rect($x+180, $y, 20, $alto1);
        $pdf->Rect($x+200, $y, 20, $alto1);
        $pdf->Rect($x+220, $y, 40, $alto1);
        $yt += 10;
        $pdf->Text(25, $yt, "CONDUCTOR");
        $pdf->Text(70, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(147, $yt, "DIRECCION");
        $pdf->Text(198, $yt, "TELEFONO");
        $pdf->Text(216, $yt, "N LICENCIA");
        $pdf->Text(247, $yt, "CIUDAD");

        $y += 10;
        $pdf->Rect($x, $y, 50, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 80, $alto1);
        $pdf->Rect($x+180, $y, 20, $alto1);
        $pdf->Rect($x+200, $y, 60, $alto1);
        $yt += 10;
        $pdf->Text(15, $yt, "POSEEDOR O TENEDOR VEHICULO");
        $pdf->Text(70, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(147, $yt, "DIRECCION");
        $pdf->Text(198, $yt, "TELEFONO");
        $pdf->Text(238, $yt, "CIUDAD");

        $y += 10;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 10;
        $pdf->Text(110, $yt, "INFORMACION MERCANCIA TRANSPORTADA");

        $y += 5;
        $pdf->Rect($x, $y, 130, $alto2);
        $pdf->Rect($x+130, $y, 65, $alto2);
        $pdf->Rect($x+195, $y, 45, $alto2);
        $pdf->Rect($x+240, $y, 20, $alto2);
        $yt += 5;
        $pdf->Text(60, $yt, "Informacion mercancia");
        $pdf->Text(160, $yt, "Informacion remitente");
        $pdf->Text(213, $yt, "Informacion destinatario");
        $pdf->Text(256, $yt, utf8_decode("Dueño poliza"));

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+50, $y, 25, $alto2);
        $pdf->Rect($x+75, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 45, $alto2);
        $pdf->Rect($x+145, $y, 45, $alto2);
        $pdf->Rect($x+190, $y, 50, $alto2);
        $pdf->Rect($x+240, $y, 20, $alto2);
        $yt += 5;
        $pdf->Text(17, $yt, "Nro Remesa");
        $pdf->Text(43, $yt, "UnidadMedida");
        $pdf->Text(70, $yt, "Cantidad");
        $pdf->Text(92, $yt, "Naturaleza");
        $pdf->Text(118, $yt, "Empaque-Producto Transporta");
        $pdf->Text(165, $yt, "NIT/CC Nombre/Razon Social");
        $pdf->Text(208, $yt, "NIT/CC Nombre/Razon Social");


        $y += 5;
        $pdf->Rect($x, $y, 260, $alto3);

        $y += 20;
        $pdf->Rect($x, $y, 100, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $pdf->Rect($x+180, $y, 80, $alto2);
        $yt += 25;
        $pdf->Text(60, $yt, "VALORES");
        $pdf->Text(137, $yt, "CARGUE/DESCARGUE");
        $pdf->Text(215, $yt, "OBSERVACIONES");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $pdf->Rect($x+100, $y, 20, $alto1);
        $pdf->Rect($x+120, $y, 20, $alto1);
        $pdf->Rect($x+140, $y, 20, $alto1);
        $pdf->Rect($x+160, $y, 20, $alto1);
        $pdf->Rect($x+180, $y, 80, 30);
        $yt += 5;
        $pdf->Text(20, $yt, "VALOR TOTAL DEL VIAJE:");
        $pdf->Text(120, $yt, "LUGAR:");
        $pdf->Text(160, $yt, "FECHA:");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $yt += 5;
        $pdf->Text(20, $yt, "RETENCION EN LA FUENTE:");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->Text(20, $yt, "RETENCION ICA:");
        $pdf->Text(120, $yt, "CARGUE PAGADO POR:");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->Text(20, $yt, "VALOR NETO A PAGAR:");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->Text(20, $yt, "VALOR ANTICIPO:");
        $pdf->Text(120, $yt, "DESCARGUE PAGADO POR:");

        $y += 5;
        $pdf->Rect($x, $y, 50, $alto2);
        $pdf->Rect($x+50, $y, 50, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->Text(20, $yt, "SALDO A PAGAR:");

        $y += 8;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 8;
        $pdf->Text(20, $yt, "VALOR TOTAL DEL EN LETRAS:");

        $y += 5;
        $pdf->Rect($x, $y, 86, $alto1);
        $pdf->Rect($x+86, $y, 86, $alto1);
        $pdf->Rect($x+172, $y, 88, $alto1);
        $yt += 5;
        $pdf->Text(120, $yt, "FIRMA Y HUELLA TITULAR MANIFIESTO");
        $pdf->Text(200, $yt, "FIRMA Y HUELLA DEL CONDUCTOR");

        $pdf->AddPage();


    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
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