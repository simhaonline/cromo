<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;

class Factura2 extends \FPDF
{
    public static $em;
    public static $codigoFactura;
    public static $imagen;
    public static $extension;

    public function Generar($em, $codigoFactura)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $logo = self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
        self::$imagen = "data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen()));
        self::$extension = $logo->getExtension();
        $pdf = new Factura2();
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
        try {
            if (self::$imagen) {
                $this->Image(self::$imagen, 10, 8, 30, 18, self::$extension);
            }
        } catch (\Exception $exception) {
        }
        $this->Image('../public/img/empresa/iso 39001.png', 160, 2, 15, 18);
        $this->Image('../public/img/empresa/iso-9001_cotrascal.png', 175, 2, 25, 18);
        $this->SetFont('Arial', 'b', 9);
        $this->SetFillColor(272, 272, 272);
        $this->Text(15, 25, '');
        $this->SetFont('Arial', 'b', 9);
        $this->SetXY(90, 10);
        $this->Cell(10, 4, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'C', 1);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(90, 14);
        $this->Cell(10, 4, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'C', 1);
        $this->SetXY(90, 18);
        $this->Cell(10, 4, utf8_decode($arConfiguracion->getTelefono()), 0, 0, 'C', 1);
        $this->SetXY(10, 29);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode("NIT."), 0, 0, 'L', 1);
        $this->SetXY(20, 29);
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(10, 5, utf8_decode($arConfiguracion->getNit() . "-" . $arConfiguracion->getDigitoVerificacion()), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetXY(90, 27);
        $this->Cell(10, 4, $arFactura->getFacturaTipoRel()->getResolucionFacturacion(), 0, 0, 'C', 1);
        $this->SetXY(85, 31);
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(25, 4, utf8_decode('Regimen comun'), 0, 0, 'l', 1);

        $y = 22;
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y);
        $this->Cell(39, 6, "FACTURA DE VENTA", 1, 0, 'l', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 6);
        $this->Cell(39, 6, " " . $arFactura->getFacturaTipoRel()->getPrefijo() . " " . $arFactura->getNumero(), 1, 0, 'l', 1);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(115, $y + 16);
        $this->Cell(39, 6, "FECHA", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(115, $y + 22);
        $this->Cell(13, 5, $arFactura->getFecha()->format('d'), 1, 0, 'C', 1);
        $this->Cell(13, 5, $arFactura->getFecha()->format('m'), 1, 0, 'C', 1);
        $this->Cell(13, 5, $arFactura->getFecha()->format('Y'), 1, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(115, $y + 27);
        $this->Cell(13, 6, 'DIA', 1, 0, 'C', 1);
        $this->Cell(13, 6, 'MES', 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode('AÑO'), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(115, $y + 35);
        $this->Cell(39, 6, "FORMA PAGO", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(115, $y + 41);
        $this->Cell(39, 5, $arFactura->getFacturaTipoRel() ? $arFactura->getFacturaTipoRel()->getNombre(): '', 1, 0, 'C', 1);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 16);
        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(160, $y + 22);
        $this->Cell(13, 5, $arFactura->getFechaVence()->format('d'), 1, 0, 'C', 1);
        $this->Cell(13, 5, $arFactura->getFechaVence()->format('m'), 1, 0, 'C', 1);
        $this->Cell(13, 5, $arFactura->getFechaVence()->format('Y'), 1, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 27);
        $this->Cell(13, 6, 'DIA', 1, 0, 'C', 1);
        $this->Cell(13, 6, 'MES', 1, 0, 'C', 1);
        $this->Cell(13, 6, utf8_decode('AÑO'), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(170, 170, 170);
        $this->SetXY(160, $y + 35);
        $this->Cell(39, 6, "PLAZO", 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 9);
        $this->SetXY(160, $y + 41);
        $this->Cell(39, 5, $arFactura->getPlazoPago() . " DIAS", 1, 0, 'C', 1);

        $arFactura = new TteFactura();
        $arFactura = self::$em->getRepository(TteFactura::class)->find(self::$codigoFactura);
        $this->SetFont('Arial', '', 8);
        $y = 44;
        $this->Rect(10, 38, 100, 30);
        $this->Text(12, $y, utf8_decode("SEÑOR(ES):"));
        $this->Text(35, $y, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
        $this->Text(12, $y + 5, utf8_decode("NIT:"));
        $this->Text(35, $y + 5, $arFactura->getClienteRel()->getNumeroIdentificacion() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
        $this->Text(12, $y + 10, utf8_decode("DIRECCION:"));
        $this->Text(35, $y + 10, utf8_decode(substr($arFactura->getClienteRel()->getDireccion(), 0, 43)));
        $this->Text(12, $y + 15, utf8_decode("CIUDAD:"));
        $this->Text(35, $y + 15, utf8_decode($arFactura->getClienteRel()->getCiudadRel() ? $arFactura->getClienteRel()->getCiudadRel()->getNombre() : ''));
        $this->Text(12, $y + 20, utf8_decode("TELEFONO:"));
        $this->Text(35, $y + 20, utf8_decode($arFactura->getClienteRel()->getTelefono()));
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(10);
        $header = array('NUMERO', 'FECHA', 'DOCUMENTO', 'ORIGEN', 'DESTINO', 'UND', 'MANEJO', 'FLETE', 'SUBTOTAL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(20, 19, 30, 30, 30, 15, 15, 15, 15);
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

        $arGuias = self::$em->getRepository(TteFacturaDetalle::class)->formatoFactura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arGuias) {
            $totalUnidades = 0;
            $totalManejo = 0;
            $totalFlete = 0;
            $totalSubTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(20, 4, $arGuia['codigoGuiaFk'], 1, 0, 'L');
                $pdf->Cell(19, 4, $arGuia['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(30, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(30, 4, substr(utf8_decode($arGuia['ciudadOrigen']), 0, 27), 1, 0, 'L');
                $pdf->Cell(30, 4, substr(utf8_decode($arGuia['ciudadDestino']), 0, 15), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'] + $arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $totalUnidades += $arGuia['unidades'];
                $totalManejo += $arGuia['vrManejo'];
                $totalFlete += $arGuia['vrFlete'];
                $totalSubTotal += $arGuia['vrFlete'] + $arGuia['vrManejo'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
            $pdf->Cell(129, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(15, 4,  $totalUnidades, 1, 0,'R');
            $pdf->Cell(15, 4,  number_format($totalManejo), 1, 0,'R');
            $pdf->Cell(15, 4,  number_format($totalFlete), 1, 0,'R');
            $pdf->Cell(15, 4,  number_format($totalSubTotal), 1, 0,'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer()
    {
        $arFactura = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoFactura);
        $this->SetXY(10, 214);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(62, 3, utf8_decode("EL RETRASO EN EL PAGO OCASIONARA LOS INTERESES MAXIMOS DE MORA QUE AUTORIZA LA SUPERINTENDENCIA BANCARIA, FAVOR CANCELAR CON CHEQUE CRUZADO A NOMBRE DE COTRASCAL S.A.S O CANCELAR A CUENTA CORRIENTE 30265690912 de BANCOLOMBIA O A LA CUENTA CORRIENTE 657-04856-7 de BANCO DE OCCIDENTE DE BUCARAMANGA"), 1, 'L');
        $this->SetXY(75, 214);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(58, 3, utf8_decode("OBSERVACIONES: " . substr($arFactura->getComentario(), 0, 40)), 0, 'L');
        $this->SetXY(75, 214);
        $this->Cell(57, 24, '', 1, 2, 'C');
        $this->SetXY(135, 218);
        $this->SetFont('Arial', 'b', 8.5);
        $this->Cell(57, 0, 'TOTAL FLETE: ', 0, 2, 'L');
        $this->SetXY(175, 218);
        $this->Cell(23, 0, number_format($arFactura->getVrFlete()), 0, 2, 'R');
        $this->SetXY(135, 225);
        $this->SetFont('Arial', 'b', 8.5);
        $this->Cell(57, 0, 'TOTAL MANEJO: ', 0, 2, 'L');
        $this->SetXY(175, 225);
        $this->Cell(23, 0, number_format($arFactura->getVrManejo()), 0, 2, 'R');
        $this->SetXY(135, 232);
        $this->SetFont('Arial', 'b', 8.5);
        $this->Cell(57, 0, 'TOTAL NETO: ', 0, 2, 'L');
        $this->SetXY(175, 232);
        $this->Cell(23, 0, number_format($arFactura->getVrTotal()), 0, 2, 'R');
        $this->SetXY(135, 214);
        $this->Cell(64, 24, '', 1, 2, 'C');
        $this->SetXY(10, 239);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(189, 4, utf8_decode("El periodo de reclamacion de la factura es de 8 dias calendario despues de radicada la misma: Autorizo a COTRASCAL S.A.S a consultar, en cualquier tiempo, en DATACREDITO, toda la informacion relevante para conocer mi desempeño como deudor y mi capacidad de pago, ademas de reportar a DATACREDITO, sobre el cumplimiento o incumplimiento de mis obligaciones crediticias."), 1, 'L');
        $this->SetFont('Arial', 'b', 9);
        $this->Text(28, 255, 'FIRMA CLIENTE');
        $this->Text(12, 267, '_________________________________');
        $this->SetXY(10, 252);
        $this->Cell(62, 18, '', 1, 2, 'C');
        $this->SetFont('Arial', 'b', 9);
        $this->Text(97, 255, 'APROBADO');
        $this->Text(77, 267, '_________________________________');
        $this->SetXY(75, 252);
        $this->Cell(62, 18, '', 1, 2, 'C');
        $this->SetFont('Arial', 'b', 9);
        $this->Text(164, 255, 'REVISO');
        $this->Text(141, 267, '________________________________');
        $this->SetXY(140, 252);
        $this->Cell(59, 18, '', 1, 2, 'C');
        $this->SetFont('Arial', '', 9);
        $this->Text(30, 274, 'La presente factura de transporte se asimila en todos sus efectos legales a la Letra de Cambio segun ');
        $this->Text(40, 278, 'Articulo 621, 775 y 778 del Codigo de Comercio ');
        $this->SetFont('Arial', 'b', 8);
        $this->Text(110, 278, 'FACTURA POR COMPUTADOR ');

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