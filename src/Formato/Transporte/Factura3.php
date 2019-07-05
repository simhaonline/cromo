<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class Factura3 extends \FPDF {
    public static $em;
    public static $codigoFactura;

    public function Generar($em, $codigoFactura) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new Factura3();
        $pdf->AliasNbPages();
        $pdf->AddPage('L');
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $codigoBarras = new BarcodeGenerator();
        $codigoBarras->setText($arFactura->getNumero());
        $codigoBarras->setType(BarcodeGenerator::Code39);
        $codigoBarras->setScale(2);
        $codigoBarras->setThickness(25);
        $codigoBarras->setFontSize(0);
        $codigo = $codigoBarras->generate();
        $this->Image('data:image/png;base64,'.$codigo, 240, 25, 40, 10,'png');
        try {
            $logo=self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if($logo ){

                $this->Image("data:image/'{$logo->getExtension()}';base64,".base64_encode(stream_get_contents($logo->getImagen())), 10, 8, 40, 25,$logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->SetFont('Arial', 'b', 9);
        $this->SetFillColor(272, 272, 272);

        $this->SetFont('Arial', 'b', 10);
        $this->SetXY(120, 8);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'l', 1);
        $this->SetXY(128, 13);
        $this->SetFont('Arial', '', 9);
        $this->Cell(25, 5, utf8_decode("NIT." .$arConfiguracion->getNit(). "-" . $arConfiguracion->getDigitoVerificacion()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(123, 17);
        $this->Cell(25, 5, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(130, 21);
        $this->Cell(25, 5, utf8_decode("TEL" . ":" . $arConfiguracion->getTelefono()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(124, 25);
        $this->Cell(25, 5, utf8_decode($arConfiguracion->getCiudadRel()->getNombre() . "-" .  $arConfiguracion->getCiudadRel()->getDepartamentoRel()->getNombre()), 0, 0, 'l', 1);

        $this->SetFont('Arial', 'b', 10);
        $this->SetXY(250, 8);
        $this->Cell(30, 6, utf8_decode('FACTURA'), 0, 0, 'l', 1);
        $this->SetXY(250, 13);
        $this->Cell(30, 6, utf8_decode('DE VENTA'), 0, 0, 'l', 1);
        $this->SetXY(255, 18);
        $this->Cell(30, 6, utf8_decode($arFactura->getNumero()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Text(250, 45, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        $this->SetFont('Arial', 'b', 8);
        $this->SetXY(10, 30);
        $this->Cell(90, 5, utf8_decode('REMITENTE'), 'LTR', 0, 'l', 1);
        $this->Cell(40, 5, utf8_decode('NIT. O CC.'), 'LTR', 0, 'l', 1);
        $this->Cell(33, 5, utf8_decode('FECHA FACTURA'), 'LTR', 0, 'C', 1);
        $this->Cell(33, 5, utf8_decode('FECHA VENCIMIENTO'), 'LTR', 0, 'C', 1);
        $this->SetFont('Arial', 'b', 8);
        $this->SetXY(10, 35);
        $this->Cell(90, 5, utf8_decode($arFactura->getClienteRel()->getNombreCorto()), 'LBR', 0, 'l', 1);
        $this->Cell(40, 5, utf8_decode($arFactura->getClienteRel()->getNumeroIdentificacion()), 'LBR', 0, 'l', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFecha()->format('d')), 'LBR', 0, 'C', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFecha()->format('m')), 'LBR', 0, 'C', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFecha()->format('Y')), 'LBR', 0, 'C', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFechaVence()->format('d')), 'LBR', 0, 'C', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFechaVence()->format('m')), 'LBR', 0, 'C', 1);
        $this->Cell(11, 5, utf8_decode($arFactura->getFechaVence()->format('Y')), 'LBR', 0, 'C', 1);
        //
        $this->SetFont('Arial', 'b', 8);
        $this->SetXY(10, 40);
        $this->Cell(60, 5, utf8_decode('CIUDAD'), 'LTR', 0, 'l', 1);
        $this->Cell(92, 5, utf8_decode('DIRECCION'), 'LTR', 0, 'l', 1);
        $this->Cell(44, 5, utf8_decode('TELEFONO'), 'LTR', 0, 'l', 1);
        $this->SetFont('Arial', 'b', 8);
        $this->SetXY(10, 45);
        $this->Cell(60, 5, utf8_decode($arFactura->getClienteRel()->getCiudadRel()->getNombre()), 'LBR', 0, 'l', 1);
        $this->Cell(92, 5, utf8_decode($arFactura->getClienteRel()->getDireccion()), 'LBR', 0, 'l', 1);
        $this->Cell(44, 5, utf8_decode($arFactura->getClienteRel()->getTelefono()), 'LBR', 0, 'l', 1);
        $this->SetXY(150, 51);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode($arFactura->getFacturaTipoRel()->getResolucionFacturacion()), '', 0, 'C', 1);



        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(6);
        $header = array('REMESA', 'DOCUMENTO','ORIGEN', 'DESTINO', 'DESTINATARIO', 'PRODUCTO', 'EMP', 'INGRESO', 'ENTR', 'UND', 'PESO DECL', 'PESO COBR', 'DECLADO', 'FLETE', 'MANEJO', 'OTROS', 'TOTAL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);
        //creamos la cabecera de la tabla.
        $w = array(17, 20, 20, 20, 25, 15, 9, 18, 18, 10, 15, 15, 15, 15 , 15, 15, 15);
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

    public function Body($pdf) {

//        $arFacturaPlanillas = self::$em->getRepository(TteFacturaPlanilla::class)->formatoFactura(self::$codigoFactura);
//        $pdf->SetX(10);
//        $pdf->SetFont('Arial', '', 7);
//        if($arFacturaPlanillas) {
//            foreach ($arFacturaPlanillas as $arFacturaPlanilla) {
//                $pdf->Cell(17, 4, "Planilla:", 1, 0, 'L');
//                $pdf->Cell(24, 4, $arFacturaPlanilla['numero'], 1, 0, 'L');
//                $pdf->Cell(43, 4, "", 1, 0, 'L');
//                $pdf->Cell(26, 4, "Numero guias:", 1, 0, 'L');
//                $pdf->Cell(10, 4, number_format($arFacturaPlanilla['guias'], 0, '.', ','), 1, 0, 'R');
//                $pdf->Cell(25, 4, "", 1, 0, 'L');
//                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrFlete'], 0, '.', ','), 1, 0, 'R');
//                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrManejo'], 0, '.', ','), 1, 0, 'R');
//                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrTotal'], 0, '.', ','), 1, 0, 'R');
//
//                $pdf->Ln();
//                $pdf->SetAutoPageBreak(true, 85);
//            }
//        }

        $arGuias = self::$em->getRepository(TteFacturaDetalle::class)->formatoFactura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            $unidadesTotal = 0;
            $kilosFacturadosTotal = 0;
            $declaraTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(17, 5, $arGuia['numero'], 0, 0, 'L');
                $pdf->Cell(20, 5, $arGuia['documentoCliente'], 0, 0, 'L');
                $pdf->Cell(20, 5, substr(utf8_decode($arGuia['ciudadOrigen']),0,27), 0, 0, 'L');
                $pdf->Cell(20, 5, substr(utf8_decode($arGuia['ciudadDestino']),0,15), 0, 0, 'L');
                $pdf->Cell(25, 5, substr(utf8_decode($arGuia['nombreDestinatario']),0,15), 0, 0, 'L');
                $pdf->Cell(15, 5, substr(utf8_decode($arGuia['producto']),0,15), 0, 0, 'L');
                $pdf->Cell(9, 5, substr(utf8_decode(substr($arGuia['empaque'], 0, 3)),0,15), 0, 0, 'L');
                $pdf->Cell(18, 5, substr(utf8_decode($arGuia['fechaIngreso']->format('Y-m-d')),0,15), 0, 0, 'L');
                $pdf->Cell(18, 5, substr(utf8_decode($arGuia['fechaIngreso']->format('Y-m-d')),0,15), 0, 0, 'L');
                $pdf->Cell(10, 5, number_format($arGuia['unidades'], 0, '.', ','), 0, 0, 'C');
                $pdf->Cell(15, 5, number_format($arGuia['pesoReal'], 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(15, 5, number_format($arGuia['pesoFacturado'], 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(15, 5, number_format($arGuia['vrDeclara'], 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(15, 5, number_format($arGuia['vrFlete'], 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(15, 5, number_format($arGuia['vrManejo'], 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(15, 5, '', 0, 0, 'R');
                $pdf->Cell(15, 5, number_format($arGuia['vrTotal'], 0, '.', ','), 0, 0, 'R');
                $unidadesTotal += $arGuia['unidades'];
                $kilosFacturadosTotal += $arGuia['pesoFacturado'];
                $declaraTotal += $arGuia['vrDeclara'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
            $pdf->Ln();
            $pdf->SetFont('Arial', 'b', 8);
            $pdf->Cell(80, 4, 'TOTAL:', 'T', 0, 'R');
            $pdf->Cell(20, 4, 'Remesas:', 'T', 0, 'R');
            $pdf->Cell(10, 4,  $unidadesTotal, 'T', 0,'R');
            $pdf->Cell(30, 4, 'Unidades:', 'T', 0, 'R');
            $pdf->Cell(10, 4,  $unidadesTotal, 'T', 0,'R');
            $pdf->Cell(30, 4, 'Peso cobrado:', 'T', 0, 'R');
            $pdf->Cell(10, 4,  $kilosFacturadosTotal, 'T', 0,'R');
            $pdf->Cell(50, 4, 'Valor declarado total:', 'T', 0, 'R');
            $pdf->Cell(20, 4,  number_format($declaraTotal), 'T', 0,'R');
            $pdf->Cell(17, 4, '', 'T', 0, 'R');
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->Ln();
        }
    }

    public function Footer() {
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $this->SetFont('Arial', 'b', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(35,160);
        $this->Cell(200, 5, utf8_decode("ENERGY LOGISTICA S.A.S NO SOMOS RESPONSABLES DE IVA, EL TRANSPORTE ES UN SERVICIO EXCLUIDO DE IVA ART.25 LEY 6 DE 1992"), 0, 0, 'C', 1);
        $this->SetXY(10,165);
        $this->Cell(190, 5, utf8_decode("VALOR EN LETRAS"), 'LTR', 0, 'L', 1);
        $this->SetXY(10,170);
        $this->Cell(190, 5, strtoupper($vrTotalLetras = self::devolverNumeroLetras(round($arFactura->getVrTotal())) . " PESOS"), 'LBR', 0, 'L', 1);
        $this->SetFont('Arial', '', 7.4);
        $this->SetXY(10,175);
        $this->Cell(200, 5, utf8_decode("La presente factura se asimila en todos sus efectos legales a la letra de cambio, de conformidad con lo dispuesto en el articulo 776 del código de comercio"), 0, 0, 'L', 1);
        $this->SetXY(10,179);
        $this->Cell(200, 5, utf8_decode("Si la cancelación de la factura sobrepasa la fecha de vencimiento, se iniciara la causación del máximo de interés corriente vigente, hasta la fecha de la cancelación."), 0, 0, 'L', 1);

        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,165);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("PRECIO TRANSPORTE"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format($arFactura->getVrFlete(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,170);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("COBERTURA DE RIESGOS"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format($arFactura->getVrManejo(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,175);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("OTROS"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format('0', 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,180);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("SUBTOTAL"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,185);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("RETENCION EN LA FUENTE"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format('0', 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(205,190);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 5, utf8_decode("TOTAL"), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R', 1);

        $this->SetFillColor(272, 272, 272);
        $this->SetXY(20,196);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(10, 5, utf8_decode("Generado por software cromo."), 0, 0, 'C', 1);

        $this->SetFillColor(272, 272, 272);
        $this->SetXY(80,192);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode("_________________________________________"), 0, 0, 'C', 1);
        $this->SetXY(80,196);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode("ELABORADO POR"), 0, 0, 'C', 1);

        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160,192);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode("_________________________________________"), 0, 0, 'C', 1);
        $this->SetXY(160,196);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode("ACEPTANTE REMITENTE"), 0, 0, 'C', 1);

    }

    public static function devolverNumeroLetras($num, $fem = true, $dec = true)
    {

        //if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");

        $matuni[2] = "dos";

        $matuni[3] = "tres";

        $matuni[4] = "cuatro";

        $matuni[5] = "cinco";

        $matuni[6] = "seis";

        $matuni[7] = "siete";

        $matuni[8] = "ocho";

        $matuni[9] = "nueve";

        $matuni[10] = "diez";

        $matuni[11] = "once";

        $matuni[12] = "doce";

        $matuni[13] = "trece";

        $matuni[14] = "catorce";

        $matuni[15] = "quince";

        $matuni[16] = "dieciseis";

        $matuni[17] = "diecisiete";

        $matuni[18] = "dieciocho";

        $matuni[19] = "diecinueve";

        $matuni[20] = "veinte";

        $matunisub[2] = "dos";

        $matunisub[3] = "tres";

        $matunisub[4] = "cuatro";

        $matunisub[5] = "quin";

        $matunisub[6] = "seis";

        $matunisub[7] = "sete";

        $matunisub[8] = "ocho";

        $matunisub[9] = "nove";


        $matdec[2] = "veint";

        $matdec[3] = "treinta";

        $matdec[4] = "cuarenta";

        $matdec[5] = "cincuenta";

        $matdec[6] = "sesenta";

        $matdec[7] = "setenta";

        $matdec[8] = "ochenta";

        $matdec[9] = "noventa";

        $matsub[3] = 'mill';

        $matsub[5] = 'bill';

        $matsub[7] = 'mill';

        $matsub[9] = 'trill';

        $matsub[11] = 'mill';

        $matsub[13] = 'bill';

        $matsub[15] = 'mill';

        $matmil[4] = 'millones';

        $matmil[6] = 'billones';

        $matmil[7] = 'de billones';

        $matmil[8] = 'millones de billones';

        $matmil[10] = 'trillones';

        $matmil[11] = 'de trillones';

        $matmil[12] = 'millones de trillones';

        $matmil[13] = 'de trillones';

        $matmil[14] = 'billones de trillones';

        $matmil[15] = 'de billones de trillones';

        $matmil[16] = 'millones de billones de trillones';


        if ($num == '')
            $num = 0;

        $num = trim((string)@$num);

        if ($num[0] == '-') {

            $neg = 'menos ';

            $num = substr($num, 1);

        } else

            $neg = '';

        while ($num[0] == '0') $num = substr($num, 1);

        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;

        $zeros = true;

        $punt = false;

        $ent = '';

        $fra = '';

        for ($c = 0; $c < strlen($num); $c++) {

            $n = $num[$c];

            if (!(strpos(".,'''", $n) === false)) {

                if ($punt) break;

                else {

                    $punt = true;

                    continue;

                }


            } elseif (!(strpos('0123456789', $n) === false)) {

                if ($punt) {

                    if ($n != '0') $zeros = false;

                    $fra .= $n;

                } else



                    $ent .= $n;

            } else



                break;


        }

        $ent = '     ' . $ent;

        if ($dec and $fra and !$zeros) {

            $fin = ' coma';

            for ($n = 0; $n < strlen($fra); $n++) {

                if (($s = $fra[$n]) == '0')

                    $fin .= ' cero';

                elseif ($s == '1')

                    $fin .= $fem ? ' uno' : ' un';

                else

                    $fin .= ' ' . $matuni[$s];

            }

        } else

            $fin = '';

        if ((int)$ent === 0) return 'Cero ' . $fin;

        $tex = '';

        $sub = 0;

        $mils = 0;

        $neutro = false;

        while (($num = substr($ent, -3)) != '   ') {

            $ent = substr($ent, 0, -3);

            if (++$sub < 3 and $fem) {

//          $matuni[1] = 'uno';
                $matuni[1] = 'un';

                $subcent = 'os';

            } else {

                $matuni[1] = $neutro ? 'un' : 'uno';

                $subcent = 'os';

            }

            $t = '';

            $n2 = substr($num, 1);

            if ($n2 == '00') {

            } elseif ($n2 < 21)

                $t = ' ' . $matuni[(int)$n2];

            elseif ($n2 < 30) {

                $n3 = $num[2];

                if ($n3 != 0) $t = 'i' . $matuni[$n3];

                $n2 = $num[1];

                $t = ' ' . $matdec[$n2] . $t;

            } else {

                $n3 = $num[2];

                if ($n3 != 0) $t = ' y ' . $matuni[$n3];

                $n2 = $num[1];

                $t = ' ' . $matdec[$n2] . $t;

            }

            $n = $num[0];

            if ($n == 1) {

                $t = ' ciento' . $t;

            } elseif ($n == 5) {

                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;

            } elseif ($n != 0) {

                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;

            }

            if ($sub == 1) {

            } elseif (!isset($matsub[$sub])) {

                if ($num == 1) {

                    $t = ' mil';

                } elseif ($num > 1) {

                    $t .= ' mil';

                }

            } elseif ($num == 1) {

                $t .= ' ' . $matsub[$sub] . 'on';

            } elseif ($num > 1) {

                $t .= ' ' . $matsub[$sub] . 'ones';

            }

            if ($num == '000') $mils++;

            elseif ($mils != 0) {

                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];

                $mils = 0;

            }

            $neutro = true;

            $tex = $t . $tex;

        }

        $tex = $neg . substr($tex, 1) . $fin;

        return ucfirst($tex);

    }
}
?>