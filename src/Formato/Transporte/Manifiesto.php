<?php

namespace App\Formato\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;

class Manifiesto extends \FPDF {

    public static $em;
    public static $codigoDespacho;
    public static $imagen;
    public static $extension;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $logo = self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
        self::$imagen = "data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen()));
        self::$extension = $logo->getExtension();
        $pdf = new Manifiesto('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Manifiesto$codigoDespacho.pdf", 'D');
    }

    public function Header() {
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        /** @var  $arConfiguracion GenConfiguracion */
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $this->Image('../public/img/recursos/transporte/mintransporte.png', 15, 15, 62, 15);
        $this->Image('../public/img/recursos/transporte/supertransporte.jpeg', 15, 32, 62, 15);
        try {
            if (self::$imagen) {
                $this->Image(self::$imagen, 90, 30, 40, 15, self::$extension);
            }
        } catch (\Exception $exception) {
        }
        $contenido = $this->contenidoQr($arDespacho , $arConfiguracion->getNombre());
        $this->Image(FuncionesController::codigoQr($contenido), 244, 5, 33, 33);
        $this->SetFont('Arial', 'b', 14);
        $this->Text(90, 15, "MANIFIESTO ELECTRONICO DE CARGA");
        $this->Text(90, 20, $arConfiguracion->getNombre());
        $this->Text(90, 25, "NIT: " . $arConfiguracion->getNit());
        $this->SetFont('Arial', 'b', 9);
        $this->Text(138, 38, substr($arConfiguracion->getDireccion(),0, 55));
        $this->Text(138, 43, $arConfiguracion->getTelefono());
        $this->Text(138, 48, utf8_decode($arConfiguracion->getCiudadRel()->getNombre() . " - " . $arConfiguracion->getCiudadRel()->getDepartamentoRel()->getNombre()));

        $this->SetFont('Arial', 'b', 5);
        $this->SetXY(190, 10);
        $texto = "La impresión en soporte cartular (papel) de este acto administrativo " .
        "producido por medios electrónicos en cumplimiento de la ley 527 de 1999 (Articulos 6 al 13) " .
        " y de la ley 962 de 2005 (Articulo 6), es una reproducción del documento original que se " .
        " encuentra en formato electrónico firmado digitalmente, cuya representación digital goza de " .
        " autenticidad, integridad y no repudio";
        $this->MultiCell(50, 2, utf8_decode($texto));

        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'b', 8);
        $this->SetXY(211, 39);
        $this->Cell(30, 3.5, "MANIFIESTO:", 1, 0, 'L', 1);
        $this->Cell(34, 3.5, $arDespacho->getNumero(), 1, 0, 'R', 1);

        $this->SetXY(211, 42.5);
        $this->Cell(30, 3.5, "AUTORIZACION:", 1, 0, 'L', 1);
        $this->Cell(34, 3.5, $arDespacho->getNumeroRndc(), 1, 0, 'R', 1);
//        $this->Text(245, 24, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');

        $this->SetXY(211, 46);
        $this->Cell(30, 3.5, "NUMERO:", 1, 0, 'L', 1);
        $this->Cell(34, 3.5, $arDespacho->getCodigoDespachoPk(), 1, 0, 'R', 1);
//        $this->Text(245, 24, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
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

        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(25, $yt, "FECHA EXPEDICION");
        $pdf->Text(80, $yt, "TIPO MANIFIESTO");
        $pdf->Text(137, $yt, "ORIGEN DEL VIAJE");
        $pdf->Text(215, $yt, "DESTINO DEL VIAJE");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(26, $yt+4, $arDespacho['fechaSalida']->format('Y-m-d'));
        $pdf->Text(80, $yt+4, "VIAJE");
        $pdf->Text(137, $yt+4, $arDespacho['ciudadOrigen']);
        $pdf->Text(215, $yt+4, $arDespacho['ciudadDestino']);


        $y += 10;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 10;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(110, $yt, "INFORMACION DEL VEHICULO Y CONDUCTOR");

        $y += 5;
        $pdf->Rect($x, $y, 70, $alto1);
        $pdf->Rect($x+70, $y, 50, $alto1);
        $pdf->Rect($x+120, $y, 60, $alto1);
        $pdf->Rect($x+180, $y, 40, $alto1);
        $pdf->Rect($x+220, $y, 40, $alto1);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(33, $yt, "TITULAR MANIFIESTO");
        $pdf->Text(90, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(155, $yt, "DIRECCION");
        $pdf->Text(205, $yt, "TELEFONO");
        $pdf->Text(245, $yt, "CIUDAD");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(18, $yt+5, utf8_decode($arDespacho['conductorNombre']));
        $pdf->Text(90, $yt+5, $arDespacho['conductorIdentificacion']);
        $pdf->Text(155, $yt+5, $arDespacho['conductorDireccion']);
        $pdf->Text(205, $yt+5, $arDespacho['conductorTelefono']);
        $pdf->Text(245, $yt+5, $arDespacho['conductorCiudad']);

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
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(23, $yt, "PLACA");
        $pdf->Text(49, $yt, "MARCA");
        $pdf->Text(70, $yt, "PLACA SEMI REMOLQUE");
        $pdf->Text(115, $yt, "CONFIGURACION");
        $pdf->Text(142, $yt, "PESO VACIO");
        $pdf->Text(170, $yt, "N POLIZA");
        $pdf->Text(194, $yt, utf8_decode("COMPAÑIA SEGUROS SOAT"));
        $pdf->Text(245, $yt, "F/VENCE SOAT");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(23, $yt+5, $arDespacho['codigoVehiculoFk']);
        $pdf->Text(49, $yt+5, $arDespacho['vehiculoMarca']);
        $pdf->Text(70, $yt+5, $arDespacho['vehiculoPlacaRemolque']);
        $pdf->Text(125, $yt+5, $arDespacho['vehiculoConfiguracion']);
        $pdf->Text(142, $yt+5, $arDespacho['vehiculoPesoVacio']);
        $pdf->Text(170, $yt+5, $arDespacho['vehiculoNumeroPoliza']);
        $pdf->Text(194, $yt+5, utf8_decode(substr($arDespacho['aseguradoraNombre'],0,24)));
        $pdf->Text(245, $yt+5, $arDespacho['vehiculoFechaVencePoliza']->format('Y-m-d'));

        $y += 10;
        $pdf->Rect($x, $y, 50, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 80, $alto1);
        $pdf->Rect($x+180, $y, 20, $alto1);
        $pdf->Rect($x+200, $y, 20, $alto1);
        $pdf->Rect($x+220, $y, 40, $alto1);
        $yt += 10;
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Text(25, $yt, "CONDUCTOR");
        $pdf->Text(70, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(147, $yt, "DIRECCION");
        $pdf->Text(198, $yt, "TELEFONO");
        $pdf->Text(216, $yt, "N LICENCIA");
        $pdf->Text(247, $yt, "CIUDAD");
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(16, $yt+5, substr(utf8_decode($arDespacho['conductorNombre']),0, 30));
        $pdf->Text(70, $yt+5, $arDespacho['conductorIdentificacion']);
        $pdf->Text(147, $yt+5, $arDespacho['conductorDireccion']);
        $pdf->Text(198, $yt+5, $arDespacho['conductorTelefono']);
        $pdf->Text(216, $yt+5, $arDespacho['conductorNumeroLicencia']);
        $pdf->Text(247, $yt+5, $arDespacho['conductorCiudad']);

        $y += 10;
        $pdf->Rect($x, $y, 50, $alto1);
        $pdf->Rect($x+50, $y, 50, $alto1);
        $pdf->Rect($x+100, $y, 80, $alto1);
        $pdf->Rect($x+180, $y, 20, $alto1);
        $pdf->Rect($x+200, $y, 60, $alto1);
        $yt += 10;
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Text(16, $yt, "POSEEDOR O TENEDOR VEHICULO");
        $pdf->Text(70, $yt, "DOCUMENTO IDENTIFICACION");
        $pdf->Text(147, $yt, "DIRECCION");
        $pdf->Text(198, $yt, "TELEFONO");
        $pdf->Text(238, $yt, "CIUDAD");
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(16, $yt+5, substr(utf8_decode($arDespacho['poseedorNombre']),0,30));
        $pdf->Text(70, $yt+5, $arDespacho['poseedorNumeroIdentificacion']);
        $pdf->Text(147, $yt+5, $arDespacho['poseedorDireccion']);
        $pdf->Text(198, $yt+5, $arDespacho['poseedorTelefono']);
        $pdf->Text(238, $yt+5, $arDespacho['poseedorCiudad']);
        $y += 10;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 10;
        $pdf->SetFont('Arial', 'b', 8);
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



        $pdf->Rect(211, $y+9, 28, 4);
        $pdf->Text(212, $y+12, "UNIDADES:");
        $pdf->setXY(239, $yt+10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 4, $arDespacho['pesoReal'], 1, 0, 'R');

        $pdf->Rect(211, $y+13, 28, 4);
        $pdf->Text(212, $y+16, "PESO:");
        $pdf->setXY(239, $yt+6);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 4, $arDespacho['unidades'], 1, 0, 'R');


        $pdf->Rect(211, $y+17, 28, 4);
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(212, $y+20, "GUIAS:");
        $pdf->setXY(239, $yt+18);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 4, number_format($arDespacho['vrCobroEntrega'], 0, '.', ','), 1, 0, 'R');

        $y += 5;
        $pdf->Rect($x, $y, 260, $alto3);
        $pdf->Rect(211, $y+16, 28, 4);
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(212, $y+19, "COBRO ENTREGA:");
        $pdf->setXY(239, $yt+14);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 4, $arDespacho['cantidad'], 1, 0, 'R');

        $y += 20;
        $pdf->Rect($x, $y, 100, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $pdf->Rect($x+180, $y, 80, $alto2);
        $yt += 25;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(60, $yt, "VALORES");
        $pdf->Text(137, $yt, "CARGUE/DESCARGUE");
        $pdf->Text(215, $yt, "OBSERVACIONES");

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 20, $alto1);
        $pdf->Rect($x+120, $y, 20, $alto1);
        $pdf->Rect($x+140, $y, 20, $alto1);
        $pdf->Rect($x+160, $y, 20, $alto1);
        $pdf->Rect($x+180, $y, 80, 30);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->setXY(195, $y);
        $pdf->MultiCell(80, 8, substr($arDespacho['comentario'],0,230).(strlen($arDespacho['comentario'])>230?"...":""), 0, 'L');

        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "VALOR PAGO:");
        $pdf->Text(120, $yt, "LUGAR:");
        $pdf->Text(160, $yt, "FECHA:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $pdf->Cell(25, 4,  number_format($arDespacho['vrFletePago']) , 0, 0, 'R');


        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "RET FUENTE:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrRetencionFuente']), 0, 0, 'R');

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "RET ICA:");
        $pdf->Text(120, $yt, "CARGUE PAGADO POR:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(120, $yt+5, utf8_decode($arConfiguracion->getNombre()));
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrIndustriaComercio']), 0, 0, 'R');

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "TOTAL:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $neto = $arDespacho['vrFletePago']-($arDespacho['vrRetencionFuente'] + $arDespacho['vrIndustriaComercio']);
        $pdf->Cell(25, 4, number_format($neto), 0, 0, 'R');

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+50, $y, 25, $alto2);
        $pdf->Rect($x+75, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "ANTICIPO:");
        $pdf->Text(65, $yt, "C_ENTREGA:");
        $pdf->Text(120, $yt, "DESCARGUE PAGADO POR:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(120, $yt+5, utf8_decode($arConfiguracion->getNombre()));
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrAnticipo']), 0, 0, 'R');
        $pdf->setXY(85, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrCobroEntrega']), 0, 0, 'R');

        $y += 5;
        $pdf->Rect($x, $y, 25, $alto2);
        $pdf->Rect($x+25, $y, 25, $alto2);
        $pdf->Rect($x+50, $y, 25, $alto2);
        $pdf->Rect($x+75, $y, 25, $alto2);
        $pdf->Rect($x+100, $y, 80, $alto2);
        $yt += 5;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "NETO:");
        $pdf->Text(65, $yt, "SALDO:");
        $pdf->SetFont('Arial', '', 8);
        $pdf->setXY(40, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrTotal']), 0, 0, 'R');
        $pdf->setXY(85, $yt-3);
        $pdf->Cell(25, 4, number_format($arDespacho['vrSaldo']), 0, 0, 'R');

        $y += 8;
        $pdf->Rect($x, $y, 260, $alto2);
        $yt += 8;
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Text(20, $yt, "VALOR TOTAL DEL EN LETRAS:");

        $y += 5;
        $pdf->Rect($x, $y, 86, $alto1);
        $pdf->Rect($x+86, $y, 86, $alto1);
        $pdf->Rect($x+172, $y, 88, $alto1);
        $yt += 5;
        $pdf->Text(120, $yt, "FIRMA Y HUELLA TITULAR MANIFIESTO");
        $pdf->Text(200, $yt, "FIRMA Y HUELLA DEL CONDUCTOR");

        $pdf->AddPage();



        $arGuias = self::$em->getRepository(TteGuia::class)->despachoOrden(self::$codigoDespacho);
        $pdf->SetXY(15, 65);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            $numeroGuias = count($arGuias);
            $contador = 0;
            $indice = -1;
            foreach ($arGuias as $arGuia) {
                if($indice == 30 || $indice == -1) {
                    $x=15;
                    $y=40;
                    $yt = 44;
                    $pdf->SetFont('Arial', 'b', 8);
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
                    $pdf->Rect($x, $y, 17, $alto2);
                    $pdf->Rect($x+17, $y, 23, $alto2);
                    $pdf->Rect($x+40, $y, 10, $alto2);
                    $pdf->Rect($x+50, $y, 14, $alto2);
                    $pdf->Rect($x+74, $y, 10, $alto2);
                    $pdf->Rect($x+84, $y, 25, $alto2);
                    $pdf->Rect($x+110, $y, 20, $alto2);
                    $pdf->Rect($x+130, $y, 65, $alto2);
                    $pdf->Rect($x+195, $y, 45, $alto2);
                    $pdf->Rect($x+240, $y, 20, $alto2);
                    $yt += 5;
                    $pdf->Text(16, $yt, "REMESA");
                    $pdf->Text(34, $yt, utf8_decode("NUMERO"));
                    $pdf->Text(58, $yt, "UM");
                    $pdf->Text(70, $yt, "FEC");
                    $pdf->Text(81, $yt, "UND");
                    $pdf->Text(90, $yt, "PES");
                    $pdf->Text(100, $yt, "DESTINO");
                    $pdf->Text(125, $yt, "EMPAQUE");
                    $pdf->Text(155, $yt, "NIT/CC Nombre/Razon Social");
                    $pdf->Text(212, $yt, "NIT/CC Nombre/Razon Social");
                    $pdf->SetFont('Arial', '', 7);
                    $indice = 0;

                }
                $pdf->Cell(17, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(23, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(10, 4, "KILO", 1, 0, 'L');
                $pdf->Cell(14, 4, $arGuia['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'L');
                $pdf->Cell(26, 4, substr(utf8_decode($arGuia['ciudadDestino']),0,20), 1, 0, 'L');
                $pdf->Cell(20, 4, 'VARIOS', 1, 0, 'L');
                $pdf->Cell(65, 4, substr(utf8_decode($arGuia['clienteNombre']),0,20), 1, 0, 'L');
                $pdf->Cell(45, 4, substr(utf8_decode($arGuia['nombreDestinatario']),0,28), 1, 0, 'L');
                $pdf->Cell(20, 4, "054125", 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->SetAutoPageBreak(true, 15);
                $contador++;
                $indice++;
                if($indice == 30) {
                    $pdf->AddPage();
                    $pdf->SetXY(15, 65);
                }

            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }


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

    /**
     * @param $arDespacho TteDespacho
     * @return string
     */
    private function contenidoQr($arDespacho, $nombreEmpresa){
        $contenido =  "MEC:{$arDespacho->getNumeroRndc()}\n";
        $contenido .= "Fecha:{$arDespacho->getFechaRegistro()->format('Y-m-d')}\n";
        $contenido .= "Placa:{$arDespacho->getVehiculoRel()->getPlaca()}\n";
        $contenido .= "Remolque:{$arDespacho->getVehiculoRel()->getPlacaRemolque()}\n";
        $contenido .= "Orig:{$arDespacho->getCiudadOrigenRel()->getNombre()}\n";
        $contenido .= "Dest:{$arDespacho->getCiudadDestinoRel()->getNombre()}\n";
        $contenido .= "Mercancia:'VARIOS'\n";
        $contenido .= "Conductor:{$arDespacho->getConductorRel()->getNombreCorto()}\n";
        $contenido .= "Empresa:{$nombreEmpresa}\n";
        $contenido .= "Obs:".substr($arDespacho->getComentario(),0, 20)."\n";
        $contenido .= "Seguro:\n";

        return $contenido;
    }
}

?>