<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaConceptoDetalle;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;

class Factura extends \FPDF
{
    public static $em;
    public static $codigoFactura;

    public function Generar($em, $codigoFactura)
    {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new Factura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        //$this->Image('../public/img/empresa/logo.jpg', 10, 8, 40, 18);
        try {
            $logo = self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if ($logo) {

                $this->Image("data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen())), 10, 8, 40, 18, $logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->Image('../public/img/recursos/transporte/veritas.jpeg', 110, 6, 50, 25);
        $this->SetFont('Arial', 'b', 9);
        $this->SetFillColor(272, 272, 272);
        $this->Text(15, 25, '');

        $this->SetFont('Arial', 'b', 13);
        $this->SetXY(57, 12);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(64, 17);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'l', 1);
        $this->SetXY(72, 22);
        $this->Cell(30, 6, utf8_decode($arConfiguracion->getTelefono()), 0, 0, 'l', 1);
        $this->SetXY(10, 29);
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(25, 5, utf8_decode("NIT."), 0, 0, 'l', 1);
        $this->SetXY(20, 29);
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(25, 5, utf8_decode($arConfiguracion->getNit() . "-" . $arConfiguracion->getDigitoVerificacion()), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetXY(88, 28);
        $this->Cell(25, 4, utf8_decode("RESPONSABLES DE IVA "), 0, 0, 'l', 1);
        $this->SetXY(45, 31);
        $this->Cell(25, 4, utf8_decode("CODIGO CIIU 4923 TRANSPORTE DE CARGA POR CARRETERA EXCLUIDA DE IVA"), 0, 0, 'l', 1);

        $y = 20;
        $y2 = 15;
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y2);
        $this->Cell(39, 6, "FACTURA DE VENTA", 1, 0, 'l', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y2 + 6);
        $this->Cell(39, 6, "No." . " " . $arFactura->getFacturaTipoRel()->getPrefijo() . " " . $arFactura->getNumero(), 1, 0, 'l', 1);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 15);
        $this->Cell(39, 6, "FECHA EXPEDICION", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 20);
        $this->Cell(13, 7, $arFactura->getFecha()->format('d'), 1, 0, 'C', 1);
        $this->Cell(13, 7, $arFactura->getFecha()->format('m'), 1, 0, 'C', 1);
        $this->Cell(13, 7, $arFactura->getFecha()->format('Y'), 1, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 27);
        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 33);
        $this->Cell(13, 7, $arFactura->getFechaVence()->format('d'), 1, 0, 'C', 1);
        $this->Cell(13, 7, $arFactura->getFechaVence()->format('m'), 1, 0, 'C', 1);
        $this->Cell(13, 7, $arFactura->getFechaVence()->format('Y'), 1, 0, 'C', 1);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 40);
        $this->Cell(13, 6, "DIA", 1, 0, 'C', 1);
        $this->Cell(13, 6, "MES", 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode("AÑO"), 1, 0, 'C', 1);

        $arFactura = new TteFactura();
        $arFactura = self::$em->getRepository(TteFactura::class)->find(self::$codigoFactura);
        $this->SetFont('Arial', '', 10);
        $y = 42;
        $this->Rect(10, 36, 140, 30);
        $this->Text(12, $y, utf8_decode("SEÑOR(ES):"));
        $this->Text(45, $y, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
        $this->Text(12, $y + 5, utf8_decode("NIT:"));
        $this->Text(45, $y + 5, $arFactura->getClienteRel()->getNumeroIdentificacion() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
        $this->Text(12, $y + 10, utf8_decode("DIRECCION:"));
        $this->Text(45, $y + 10, utf8_decode($arFactura->getClienteRel()->getDireccion()));
        $this->Text(12, $y + 15, utf8_decode("CIUDAD:"));
        $this->Text(45, $y + 15, "MEDELLIN");
        $this->Text(12, $y + 20, utf8_decode("TELEFONO:"));
        $this->Text(45, $y + 20, utf8_decode($arFactura->getClienteRel()->getTelefono()));
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(20);
        $arFacturaDetalleConceptos = self::$em->getRepository(TteFacturaDetalleConcepto::class)->listaFacturaDetalle(self::$codigoFactura)->getQuery()->getResult();
        if (!$arFacturaDetalleConceptos) {
            $header = array('GUIA', 'DOCUMENTO', 'DESTINATARIO', 'DESTINO', 'UND', 'KF', 'DECLARA', 'FLETE', 'MANEJO', 'TOTAL');
            $this->SetFillColor(170, 170, 170);
            $this->SetTextColor(0);
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(.2);
            $this->SetFont('', 'B', 7);
            //creamos la cabecera de la tabla.
            $w = array(17, 24, 43, 26, 10, 10, 15, 15, 15, 15);
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
        } else {
            $header = array('ID', 'CONCEPTO', 'CANT', 'PRECIO', '%IVA', 'IVA', 'TOTAL');
            $this->SetFillColor(170, 170, 170);
            $this->SetTextColor(0);
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(.2);
            $this->SetFont('', 'B', 7);
            //creamos la cabecera de la tabla.
            $w = array(17, 70, 15, 20, 20, 20, 20);
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

    }

    public function Body($pdf)
    {

        $arFacturaPlanillas = self::$em->getRepository(TteFacturaPlanilla::class)->formatoFactura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arFacturaPlanillas) {
            foreach ($arFacturaPlanillas as $arFacturaPlanilla) {
                $pdf->Cell(17, 4, "Planilla:", 1, 0, 'L');
                $pdf->Cell(24, 4, $arFacturaPlanilla['numero'], 1, 0, 'L');
                $pdf->Cell(43, 4, "", 1, 0, 'L');
                $pdf->Cell(26, 4, "Numero guias:", 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arFacturaPlanilla['guias'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(25, 4, "", 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arFacturaPlanilla['vrTotal'], 0, '.', ','), 1, 0, 'R');

                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
        }

        $arFacturaDetalleConceptos = self::$em->getRepository(TteFacturaDetalleConcepto::class)->listaFacturaDetalle(self::$codigoFactura)->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arFacturaDetalleConceptos) {
            foreach ($arFacturaDetalleConceptos as $arFacturaDetalleConcepto) {
                $pdf->Cell(17, 4, $arFacturaDetalleConcepto['codigoFacturaDetalleConceptoPk'], 1, 0, 'L');
                $pdf->Cell(70, 4, $arFacturaDetalleConcepto['concepto'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arFacturaDetalleConcepto['cantidad'], 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arFacturaDetalleConcepto['vrPrecio'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, $arFacturaDetalleConcepto['porcentajeIva'], 1, 0, 'C');
                $pdf->Cell(20, 4, number_format($arFacturaDetalleConcepto['vrIva'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arFacturaDetalleConcepto['vrTotal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
        }

        $arGuias = self::$em->getRepository(TteFacturaDetalle::class)->formatoFactura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arGuias) {
            $unidadesTotal = 0;
            $kilosFacturadosTotal = 0;
            $declaraTotal = 0;
            $fleteTotal = 0;
            $manejoTotal = 0;
            $total = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(17, 4, $arGuia['codigoGuiaFk'], 1, 0, 'L');
                $pdf->Cell(24, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(43, 4, substr(utf8_decode($arGuia['nombreDestinatario']), 0, 27), 1, 0, 'L');
                $pdf->Cell(26, 4, substr(utf8_decode($arGuia['ciudadDestino']), 0, 15), 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoFacturado'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrTotal'], 0, '.', ','), 1, 0, 'R');
                $unidadesTotal += $arGuia['unidades'];
                $kilosFacturadosTotal += $arGuia['pesoFacturado'];
                $declaraTotal += $arGuia['vrDeclara'];
                $fleteTotal += $arGuia['vrFlete'];
                $manejoTotal += $arGuia['vrManejo'];
                $total += $arGuia['vrTotal'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
            $pdf->Cell(110, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(10, 4, $unidadesTotal, 1, 0, 'R');
            $pdf->Cell(10, 4, $kilosFacturadosTotal, 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($declaraTotal), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($fleteTotal), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($manejoTotal), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($total), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer()
    {
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $this->SetXY(10, 220);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(40, 10, 'TOTAL FLETE:', 0, 0, 'L');
        $this->SetXY(23, 220);
        $this->SetFont('Arial', '', 9);
        $this->Cell(40, 10, number_format($arFactura->getVrFlete(), 0, '.', '.'), 0, 0, 'C');
        $this->SetXY(60, 220);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(40, 10, 'TOTAL MANEJO:', 0, 0, 'C');
        $this->SetXY(80, 220);
        $this->SetFont('Arial', '', 9);
        $this->Cell(40, 10, number_format($arFactura->getVrManejo(), 0, '.', '.'), 0, 0, 'C');
        $this->SetXY(110, 220);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(10, 10, 'IVA:', 0, 0, 'L');
        $this->SetXY(128, 220);
        $this->SetFont('Arial', '', 9);
        $this->Cell(10, 10, number_format($arFactura->getVrIva(), 0, '.', '.'), 0, 0, 'R');
        $this->SetXY(140, 220);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(40, 10, 'TOTAL FACTURA:', 0, 0, 'C');
        $this->SetXY(170, 220);
        $this->SetFont('Arial', '', 9);
        $this->Cell(40, 10, number_format($arFactura->getVrTotal(), 0, '.', '.'), 0, 0, 'C');
        $this->SetXY(10, 220);
        $this->Cell(190, 10, '', 1, 2, 'C');
        $this->SetXY(10, 230);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(190, 10, 'VALOR EN LETRAS:', 1, 2, 'L');
        $this->SetXY(45, 230);
        $this->SetFont('Arial', '', 9);
        $this->Cell(155, 10, strtoupper($vrTotalLetras = self::devolverNumeroLetras(round($arFactura->getVrTotal()))), 0, 2, 'L');
        $this->SetXY(10, 240);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(190, 10, 'OBSERVACIONES:', 1, 2, 'L');
        $this->SetXY(45, 240);
        $this->SetFont('Arial', '', 9);
        $this->Cell(155, 10, $arFactura->getComentario(), 0, 2, 'L');
        $this->SetFont('Arial', '', 8);
        $this->SetXY(10, 200);
        $this->Line(10, 260, 60, 260);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(18, 267, "FIRMA AUTORIZADA");
        $this->Line(140, 260, 200, 260);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(155, 267, "FIRMA RECIBIDA");
        $this->SetFont('Arial', '', 7);
        $this->Text(10, 272, utf8_decode("Esta factura de venta se asimila, para sus efectos legales, a una letra de cambio según artículo 776 del código de comercio. En caso de mora, esta factura causará interés"));
        $this->Text(85, 275, utf8_decode("a la tasa máxima legal permitida"));
        $this->Text(25, 278, utf8_decode("Todo pago debe hacerse a la orden del primer beneficiario LOGICUARTAS S.A.S consignar en BANCOLOMBIA la cuenta corriente 01882109665"));
        $this->SetXY(7, 279);
        $this->MultiCell(193, 3, utf8_decode("Factura impresa en computador por LOGICUARTAS S.A.S NIT 900486121-3 SEGÚN" . $arFactura->getFacturaTipoRel()->getResolucionFacturacion()), 0, 'C');
        $this->Text(65, 286, utf8_decode("Pasados 10 días no se aceptan devoluciones y/o reclamos de esta factura"));
        $this->SetFont('Arial', '', 8);
        $this->Text(165, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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