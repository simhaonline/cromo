<?php

namespace App\Formato\Turno;

use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvTercero;
use App\Entity\Seguridad\Usuario;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFacturaTipo;
use Doctrine\Common\Persistence\ObjectManager;

class Factura1 extends \FPDF
{

    public static $em;
    public static $codigoFactura;
    public static $numeroRegistros;
    public static $arUsuario;

    /**
     * @param $em ObjectManager
     * @param $codigoMovimiento integer
     */
    public function Generar($em, $codigoFactura, $intNumero, $arUsuario)
    {
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        self::$arUsuario = $arUsuario;
        $arFactura = $em->getRepository(TurFactura::class)->find($codigoFactura);
        $valor = round($arFactura->getVrTotal());
        ob_clean();
        $pdf = new Factura1('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arFactura->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arFactura->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Factura_{$arFactura->getNumero()}.pdf", 'D');
    }

    public function Header()
    {
        $this->GenerarEncabezadoFactura(self::$em);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        //Columna izquierda
        $this->SetXY(110, 90);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->SetXY(12, 84);
        $header = array('Concepto', 'Descripcion', 'Cant', 'Vr. Unitario', 'Desc.', 'Iva.', 'Valor Total');
        $this->SetLineWidth(.2);
        $this->SetFont('', '', 10);

        //creamos la cabecera de la tabla.
        $w = array(20, 82, 12, 25, 12, 15, 26);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 4.5, $header[$i], 1, 0, 'C', 0);
        }

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->SetY(88.5);

    }

    /**
     * @param $pdf
     */
    public function Body($pdf)
    {
        /**
         * @var $arFacturaDetalle TurFacturaDetalle
         * @var $arFactura TurFactura
         */
        $pdf->SetFont('Arial', '', 10);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arFacturaDetalles = self::$em->getRepository(TurFacturaDetalle::class)->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        self::$numeroRegistros = count($arFacturaDetalles);
        $cont = 0;
        $pagina = 1;
        $used = 0;
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(12);
            if ($pagina == $pdf->PageNo()) {
                if ($used < $pagina) {
                    $pdf->Rect(12, 88.5, 192, 77.5);
                    $pdf->Rect(32, 84, 82, 82);
                    $pdf->Rect(126, 84, 25, 82);
                    $pdf->Rect(163, 84, 15, 82);
                    $used = $pagina;
                }
            }
            $arFacturaDetalle->getCodigoPedidoDetalleFk() == null ?
                $pdf->Cell(20, 4.5, '', 0, 0, 'C')
                :
                $pdf->Cell(20, 4.5, $arFacturaDetalle->getCodigoPedidoDetalleFk(), 0, 0, 'C');
            $pdf->Cell(82, 0, '', 0, 'L');
            $pdf->Cell(12, 4.5, $arFacturaDetalle->getCantidad(), 0, 0, 'C');
            $pdf->Cell(25, 4.5, number_format($arFacturaDetalle->getVrPrecio(), 2, '.', ','), 0, 0, 'R');
            $pdf->Cell(12, 4.5, '', 0, 0, false);
            $pdf->Cell(15, 4.5, $arFacturaDetalle->getPorcentajeIva() . ' %', 0, 0, 'C');
            $pdf->Cell(26, 4.5, number_format($arFacturaDetalle->getVrTotal(), 2, '.', ','), 0, 0, 'R');
            $pdf->Ln(0);
            $pdf->SetX(12);
            $pdf->Cell(20, 0, '', 0, 0, 'L');
            $pdf->MultiCell(82, 4, utf8_decode($arFacturaDetalle->getItemRel()->getNombre()), 0, 'L');
            $pdf->Cell(22, 2, '', 0, 0, 'R');
            $pdf->Cell(22, 2, '', 0, 0, 'R');
            $pdf->SetAutoPageBreak(true, 120);
            $pdf->Ln();
            $cont++;
            if (($cont % 8) == 0) {
                $pagina++;
            }
        }
    }

    public function Footer()
    {
        /**
         * @var $arFactura TurFactura
         * @var $arUsuario Usuario
         */
        $arUsuario = self::$arUsuario;
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arFacturaTipo = self::$em->getRepository(TurFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $vrTotalLetras = self::devolverNumeroLetras($arFactura->getVrTotal());

        //Bloque 1
        $this->SetXY(12, 170);
        $this->Cell(192, 16.3, '', 1, 0, false);
        $this->Text(13, 179, 'SON: ');
        $this->SetXY(21, 176.5);
        $this->SetFont('Arial', '', 8);
        $this->MUltiCell(98, 3, ' ' . $vrTotalLetras, 0, 'L', false);
        $this->SetXY(120, 170);
        $this->SetFont('Arial', '', 10);
        $this->Cell(40, 4, 'TOTAL VENTA', 1, 0, 'L');
        $this->Cell(44, 4, number_format($arFactura->getVrSubtotal(), 2, '.', ','), 1, 0, 'R');
        $this->SetXY(120, 174);
        $this->Cell(40, 4, 'DESCUENTO', 1, 0, 'L');
        $this->Cell(44, 4, '0.00', 1, 0, 'R');
        $this->SetXY(120, 178);
        $this->Cell(40, 4, 'AIU', 1, 0, 'L');
        $this->Cell( 44, 4,number_format($arFactura->getVrBaseAIU(), 2, '.', ','),1,0,'R');
        $this->SetXY(120, 182.1);
        $this->Cell(40, 4, 'TOTAL VENTA NETA ', 1, 0, 'L');
        $this->Cell(44, 4, number_format($arFactura->getVrSubtotal(), 2, '.', ','), 1, 0, 'R');

        //Bloque 2
        $this->SetXY(12, 196);
        $this->MultiCell(105, 3, $arFactura->getComentarios(), 0, 'L', false);
        $this->SetXY(12, 190);
        $this->Text(13, 194, 'OBSERVACIONES: ');
        $this->Cell(192, 25, "", 1, 0, false);
        $this->SetXY(120, 190);
        $this->Cell(40, 5, 'IVA', 1, 0, 'L');
        $this->Cell(44, 5, number_format($arFactura->getVrIva(), 2, '.', ','), 1, 0, 'R');
        $this->SetXY(120, 195);
        $this->Cell(40, 5, 'TOTAL FACTURA', 1, 0, 'L');
        $this->Cell(44, 5, number_format($arFactura->getVrTotal(), 2, '.', ','), 1, 0, 'R');
        $this->SetXY(120, 200);
        $this->Cell(40, 5, 'RTE FTE', 1, 0, 'L');
        $this->Cell(44, 5, number_format($arFactura->getVrRetencionFuente(), 2, '.', ','), 1, 0, 'R');
        $this->SetXY(120, 205);
        $this->Cell(40, 5, 'RTE ICA', 1, 0, 'L');
        $this->Cell(44, 5, number_format('0', 2, '.', ','), 1, 0, 'R');
        $this->SetXY(120, 210);
        $this->Cell(40, 5, 'TOTAL A PAGAR', 1, 0, 'L');
        $this->Cell(44, 5, number_format($arFactura->getVrNeto(), 2, '.', ','), 1, 0, 'R');

        //Bloque 3
        $this->SetXY(12, 218);
        $this->Cell(192, 10, '', 1, 0, false);
        $this->SetXY(12, 218);
        $this->Cell(32, 5, 'FORMA DE PAGO:  ', 0, 'L', false);
        $this->Cell(23, 5, 'CREDITO', 0, 'L', false);
        $this->SetXY(12, 223);
        $this->Cell(53, 5, 'NO. CUOTA', 0, 'L', false);
        $this->Cell(112, 5, 'FECHA VENCIMIENTO', 0, 'L', false);
        $this->Cell(25, 5, 'VALOR CUOTA', 0, 'L', false);

        //Bloque 4
        $this->SetFont('Arial', '', 10);
        $this->SetXY(12, 230);
        $this->Cell(192, 5, '', 1, 0, false);
        $this->SetXY(12, 230);
        $this->Cell(53, 5, '1', 0, 'L', false);
        $this->Cell(110, 5, $arFactura->getFechaVence()->format('Y/m/d'), 0, 'L', false);
        $this->Cell(29, 5, number_format($arFactura->getVrNeto(), 2, '.', ','), 0, 0, 'R');

        //Bloque 5
        $this->SetXY(12, 237);
        $this->SetFont('Arial', '', 7.5);
        $this->MultiCell(192, 3, utf8_decode($arFacturaTipo->getInformacionLegalFactura()), 0, 'L');

        //Elaboro
        $this->SetXY(34, 255);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(25, 6, '_____________', 0, 0, 'L');
        $this->SetXY(34, 261);
        $this->Cell(25, 4, 'Elaboro', 0, 0, 'C');
        $this->SetXY(34, 265);
        $this->Cell(25,4,' '.  $arUsuario->getNombreCorto(),0,0,'C');

        //Firma autorizada
        $this->SetXY(85, 255);
        $this->Cell(35, 6, '__________________', 0, 0, 'L');
        $this->SetXY(85, 261);
        $this->Cell(35, 4, 'Firma Autorizada', 0, 0, 'C');

        //ACEPTADA - COMPRADOR
        $this->SetXY(142.5, 255);
        $this->Cell(50, 6, '___________________________', 0, 0, 'L');
        $this->SetXY(145, 261);
        $this->Cell(45, 4, 'ACEPTADA - COMPRADOR', 0, 0, 'C');
        $this->SetXY(142.5, 265);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 4, 'Nombre, C.C. y sello de quien recibe', 0, 0, 'L');
        $this->SetXY(142.5, 269);
        $this->Cell(50, 4, 'Fecha de Recibo:________________', 0, 0, 'L');
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

    public function devuelveMes($intMes)
    {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public function GenerarEncabezadoFactura()
    {
        /**
         * @var $arFactura TurFactura
         * @var $arFacturaTipo TurFacturaTipo
         */
        $em = self::$em;
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $arConfiguracionTurno = self::$em->getRepository(TurConfiguracion::class)->find(1);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arFacturaTipo = self::$em->getRepository(TurFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
        $fechaImpresion = new \DateTime('now');
        $fechaImpresion = $fechaImpresion->format('Y/m/d');
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 6);

        //Logo
        $this->SetXY(53, 10);
        try {
            $logo = $em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if ($logo) {
                $this->Image("data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen())), 15, 8, 50, 24, $logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->SetFont('Arial', '', 7);
        $this->Text(13, 34, 'Fecha de impresion: ' . $fechaImpresion);

        //Encabezado de pagina
        $this->SetXY(124, 10);
        $this->SetFont('Arial', '', 13);
        $this->Cell(15, 6, $arConfiguracion->getNombre(), 0, 0, 'C');
        $this->SetFont('Arial', '', 11);
        $this->SetXY(124, 17);
        $this->Cell(15, 1, 'NIT: ' . $arConfiguracion->getNit(), 0, 0, 'C');
        $this->SetXY(124, 21);
        $this->Cell(15, 1, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'C');
        $this->SetXY(124, 25);
        $this->Cell(15, 1, 'Telefono: ' . $arConfiguracion->getTelefono(), 0, 0, 'C');
        $this->SetXY(124, 29);
        $this->Cell(15, 1, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()), 0, 0, 'C');

        //Linea fuerte
        $this->SetLineWidth(1.2);
        $this->Line(13, 35, 203, 36);

        //Encabezado de factura
        $this->SetFont('Arial', '', 11);
        $this->SetLineWidth(0);
        $this->SetXY(12, 39);
        $this->Cell(50, 6, 'FACTURA DE VENTA:', 0, 0, 'L');
        $this->Cell(42, 6, $arFactura->getFacturaTipoRel()->getPrefijo() ? $arFactura->getFacturaTipoRel()->getPrefijo() . ' - 0000' . $arFactura->getNumero() : " " . ' - 0000' . $arFactura->getNumero(), 0, 0, 'L');
        $this->SetXY(12, 43);
        $this->Cell(50, 6, 'FECHA', 0, 0, 'L');
        $this->Cell(42, 6, $arFactura->getFecha()->format('Y/m/d'), 0, 0, 'L');
        $this->SetXY(12, 47);
        $this->Cell(50, 6, 'PAGINA', 0, 0, 'L');
        $this->Cell(42, 6, '{nb}', 0, 0, 'L');

        //Resolucion
        $this->SetXY(113, 39);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(90, 3, utf8_decode($arFacturaTipo->getInformacionResolucionDianFactura()), 0, 'L', false);

        //Encabezado detalles

        //Linea 1
        $this->SetXY(12, 53);
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'CLIENTE', 1, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(105, 4, utf8_decode($arFactura->getClienteRel()->getNombreCorto()), 1, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'NIT', 1, 0, 'L');
        $this->Cell(25, 4, $arFactura->getClienteRel()->getNumeroIdentificacion(), 1, 0, 'L');

        //Linea 2
        $this->SetXY(12, 57);
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'DIRECCION', 1, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(105, 4, utf8_decode($arFactura->getClienteRel()->getDireccion()), 1, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'TELEFONO', 1, 0, 'L');
        $this->Cell(25, 4, $arFactura->getClienteRel()->getTelefono(), 1, 0, 'L');

        //Linea 3
        $this->SetXY(12, 63);
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'VENDEDOR', 1, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(70,4,utf8_decode($arFactura->getClienteRel()->getAsesorRel() != null ? $arFactura->getClienteRel()->getAsesorRel()->getNombre():""),1,0,'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(31, 4, 'ZONA', 1, 0, 'L');
        $this->Cell(60, 4, 'CARTAGENA', 1, 0, 'L');

        //Linea 4 (Bloque grande)
        $this->SetXY(12, 69);
        $this->Text(13, 73, 'Detalle:');
        $this->Text(13, 77, 'FACTURA DE COMPRAVENTA: ');
        $this->Cell(192, 13, "", 1, 0, 'L');
    }


}

?>