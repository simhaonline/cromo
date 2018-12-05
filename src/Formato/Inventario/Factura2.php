<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvTercero;
use App\Utilidades\BaseDatos;
use App\Entity\General\GenConfiguracion;
use Doctrine\Common\Persistence\ObjectManager;

class Factura2 extends \FPDF
{

    public static $em;
    public static $codigoMovimiento;

    /**
     * @param $em ObjectManager
     * @param $codigoMovimiento integer
     */
    public function Generar($em, $codigoMovimiento)
    {
        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        /** @var  $arMovimiento InvMovimiento */
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($codigoMovimiento);
        ob_clean();
        $pdf = new Factura2('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arMovimiento->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arMovimiento->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Factura_{$arMovimiento->getNumero()}_{$arMovimiento->getTerceroRel()->getNombreCorto()}.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $em ObjectManager */
        $em = self::$em;
        /** @var  $arMovimiento InvMovimiento */
        $arConfiguracion = BaseDatos::getEm()->getRepository(GenConfiguracion::class)->find(1);
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoMovimiento);

        $this->SetFont('Arial', '', 5);
        $date = new \DateTime('now');
        $this->Text(170, 10, $date->format('Y-m-d H:i:s') . ' [Cromo | Inventario]');

        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(120, 30);
        $this->Cell(35, 4, 'FACTURA DE VENTA No.', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 14);
        $this->Cell(35, 4, $arMovimiento->getNumero(), 0, 0, 'R', 0);

        $stringFecha = $arMovimiento->getFecha()->format('Y-m-d');
        $plazo = $arMovimiento->getPlazoPago();
        $fechaVencimiento = date_create($stringFecha);
        $fechaVencimiento->modify("+ " . (string)$plazo . " day");

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        try {
            $logo=$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if($logo ){

                $this->Image("data:image/'{$logo->getExtension()}';base64,".base64_encode(stream_get_contents($logo->getImagen())), 20, 13, 65, 25,$logo->getExtension());
            }
        } catch (\Exception $exception) {
        }

        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(18.5, 44);
        $this->Cell(15, 4, 'INVIVO BIOINGENIERIA S.A.S', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);

        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(18.5, 48);
        $this->Cell(15, 4, 'NIT      811021556', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 55);
        $this->Cell(15, 4, 'CLIENTE:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getTerceroRel()->getNombreCorto()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 59);
        $this->Cell(15, 4, 'NIT:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getTerceroRel()->getNumeroIdentificacion()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 63);
        $this->Cell(15, 4, 'DIRECCION:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getTerceroRel()->getDireccion()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 67);
        $this->Cell(15, 4, 'CIUDAD:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getTerceroRel()->getCiudadRel()->getNombre()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 71);
        $this->Cell(15, 4, 'TELEFONO:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getTerceroRel()->getTelefono()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 75);
        $this->Cell(15, 4, 'VENDEDOR:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getAsesorRel()->getNombre()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 79);
        $this->Cell(15, 4, 'FECHA CREACION:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, $arMovimiento->getFecha()->format('Y-m-d'), 0, 0, 'L', 0);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(90, 79);
        $this->Cell(15, 4, 'FECHA VENCIMIENTO:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(127);
        $this->Cell(15, 4, $arMovimiento->getFechaVence()->format('Y-m-d'), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 83);
        $this->Cell(15, 4, 'FORMA PAGO:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, $arMovimiento->getTerceroRel()->getFormaPagoRel()->getNombre(), 0, 0, 'L', 0);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(90, 83);
        $this->Cell(15, 4, 'ESTADO DE LA FACTURA:', 0, 0, 'L', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(88, 34,34);
        $this->SetX(132);
        $this->Cell(15, 4, 'PENDIENTE DE REALIZAR PAGO', 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(0, 0,0);
        $this->SetXY(18.5, 87);
        $this->Cell(15, 4, 'SOPORTE:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->Cell(15, 4, utf8_decode($arMovimiento->getSoporte()), 0, 0, 'L', 0);

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(18.5, 91);
        $this->Cell(15, 4, 'POR CONCEPTO DE:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(52);
        $this->MultiCell(147, 4, utf8_decode($arMovimiento->getComentarios()), 0, 'L',  0);
        $this->Ln();

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(6);
        $this->SetX(10);
        $header = array('DESCRIPCION', 'REF', 'LOTE', 'CANT', 'VR UNIT', 'IVA', 'TOTAL', 'OBSERVACIONES');
        $this->SetFillColor(225, 225, 225);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(60, 20 , 15, 10, 15, 10, 15, 50);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        }
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
        /**
         * @var $arMovimiento InvMovimiento
         * @var $arMovimientoDetalles InvMovimientoDetalle
         */
        $arMovimiento = self::$em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoMovimiento);
        $arMovimientoDetalles = self::$em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => self::$codigoMovimiento]);
        $pdf->SetFont('Arial', '', 7);
        /** @var  $arMovimientoDetalle InvMovimientoDetalle */
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(60, 4, substr(utf8_decode($arMovimientoDetalle->getItemRel()->getNombre()),0,57), 1, 0, 'L');
            $pdf->Cell(20, 4, $arMovimientoDetalle->getItemRel()->getReferencia(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arMovimientoDetalle->getLoteFk(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arMovimientoDetalle->getCantidad(), 1, 0, 'C');
            $pdf->Cell(15, 4, number_format($arMovimientoDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(10, 4, $arMovimientoDetalle->getPorcentajeIva() . '%', 1, 0, 'C');
            $pdf->Cell(15, 4, number_format($arMovimientoDetalle->getVrTotal()), 1, 0, 'R');
            $pdf->Cell(50, 4, '', 1, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 105);
        }
        $pdf->Ln();
        $pdf->SetX(155);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('SUBTOTAL'), 1, 0, 'L');
        $pdf->Cell(20, 4, number_format($arMovimiento->getVrSubtotal()), 1, 0, 'R');
        $pdf->Ln();
        $pdf->SetX(155);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('IVA'), 1, 0, 'L');
        $pdf->Cell(20, 4, number_format($arMovimiento->getVrIva()), 1, 0, 'R');
        $pdf->Ln();
        $pdf->SetX(155);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('TOTAL FACTURADO'), 1, 0, 'L');
        $pdf->Cell(20, 4, number_format($arMovimiento->getVrTotal()), 1, 0, 'R');
    }

    public function Footer()
    {
        /**
         * @var $arMovimiento InvMovimiento
         * @var $arMovimientoDetalles InvMovimientoDetalle
         */
        $arMovimiento = self::$em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoMovimiento);
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $this->Ln();
        $this->SetFont('Arial', 'B', 7.5);
        //Bloque informacion de conformidad
        $this->Text(19.5, 180, utf8_decode('De conformidad a la ley 1231 de 2008 y art. 617 del estatuto tributario se seguirá entregando copia de la factura de venta.'));
        $this->Text(19.5, 184, utf8_decode('Pasados 10 días calendario contados a partir de la fecha de recepción de la factura, si no se ha recibido una reclamación'));
        $this->Text(19.5, 188, utf8_decode('por escrito, esta se entenderá irrevocablemente aceptada. El pago no oportuno causara intereses moratorios a la tasa'));
        $this->Text(19.5, 192, utf8_decode('máxima legal autorizada.'));
        $this->Text(19.5, 196, utf8_decode('Esta factura de venta se asimila en todos sus efectos a una letra de cambio (Art 779 código de comercio).'));
        $this->Text(19.5, 200, utf8_decode('Se hace constar que la firma de una persona diferente al comprador, Implica que dicha persona se entiende autorizada y'));
        $this->Text(19.5, 204, utf8_decode('facultada tacita y expresamente para aceptar y recibir esta factura. Dando cumplimiento a lo expresado en la ley 1231 del'));
        $this->Text(19.5, 208, utf8_decode('2008 se tomara para domicilio el cumplimiento de la obligación la ciudad de Medellín.'));
        //Bloque firmas
        $this->Text(24, 216, utf8_decode('ELABORADO POR:'));
        $this->Text(80, 216, utf8_decode('RECIBIDO POR:________________________'));
        $this->Text(140, 216, utf8_decode('ACEPTADO POR:_______________________'));
        $this->Text(24, 222, utf8_decode('__________________________________'));
        $this->Text(80, 222, utf8_decode('C.C / NIT:______________________________'));
        $this->Text(140, 222, utf8_decode('C.C / NIT:______________________________'));
        $this->Text(24, 228, utf8_decode('__________________________________'));
        $this->Text(80, 228, utf8_decode('FECHA:________________________________'));
        $this->Text(140, 228, utf8_decode('FECHA:________________________________'));
        //Bloque resolucion facturacion
        $this->Text(40,236, utf8_decode($arMovimiento->getFacturaTipoRel()->getNumeroResolucionDianFactura()) . ' Intervalo ' . $arMovimiento->getFacturaTipoRel()->getNumeracionDesde(). ' al '. $arMovimiento->getFacturaTipoRel()->getNumeracionHasta());
        $this->Text(32,240, utf8_decode($arMovimiento->getFacturaTipoRel()->getInformacionCuentaPago()));
        //Informacion final
        $this->Text(142, 246, utf8_decode('Impreso por computador'));
        $this->Text(130, 250, utf8_decode($arConfiguracion->getNombre() .' Nit: ') . $arConfiguracion->getNit() . '-' . $arConfiguracion->getDigitoVerificacion());
        $this->Text(120, 254, utf8_decode('Régimen Común. No retenedores del impuesto a las ventas.'));
        $this->Text(124, 258, utf8_decode($arConfiguracion->getDireccion()));
        $this->Text(126, 262, utf8_decode($arConfiguracion->getTelefono() .' E-mail: contacto@invivo.com.co'));
        $this->Text(134, 266, utf8_decode('ORIGINAL: EMISOR - COPIA: CLIENTE'));
        $this->Image('../public/img/empresa/iso9001.jpg', 40, 245, 12, 18);
        $this->Image('../public/img/empresa/iqnet.jpg', 55, 245, 20, 18);
        $this->SetFont('Arial', '', 6.5);
        $this->Text(188, 275, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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

