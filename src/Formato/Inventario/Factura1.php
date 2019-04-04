<?php

namespace App\Formato\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvTercero;
use Doctrine\Common\Persistence\ObjectManager;

class Factura1 extends \FPDF
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
        $pdf = new Factura1('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arMovimiento->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arMovimiento->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'PROFORMA', 45);
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
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoMovimiento);

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 25);
        $this->Cell(35, 4, 'FACTURA DE VENTA', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $arMovimiento->getNumero() ? $arMovimiento->getNumero() : $arMovimiento->getCodigoMovimientoPk(), 0, 0, 'R', 0);

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 28);
        $this->Cell(35, 4, 'Fecha emision:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $arMovimiento->getFecha()->format('d/m/Y'), 0, 0, 'R', 0);

        $stringFecha = $arMovimiento->getFecha()->format('Y-m-d');
        $plazo = $arMovimiento->getPlazoPago();
        $fechaVencimiento = date_create($stringFecha);
        $fechaVencimiento->modify("+ " . (string)$plazo . " day");

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 31);
        $this->Cell(35, 4, 'Fecha vencimiento:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $fechaVencimiento->format('d/m/Y'), 0, 0, 'R', 0);


        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 34);
        $this->Cell(35, 4, 'Forma pago:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $plazo == 0 ? 'CONTADO' : 'CREDITO', 0, 0, 'R', 0);

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 37);
        $this->Cell(35, 4, 'Plazo:', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $plazo, 0, 0, 'R', 0);

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(150.2, 40);
        $this->Cell(35, 4, 'Orden compra:', 0, 0, 'L', 0);

        $this->SetFont('Arial', '', 8);
        $this->Cell(17, 4, $arMovimiento->getSoporte(), 0, 0, 'R', 0);


        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        //$this->Image('../public/img/empresa/logo.jpg', 20, 13, 50, 30);
        try {
            $logo=$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if($logo ){
                $this->Image("data:image/'{$logo->getExtension()}';base64,".base64_encode(stream_get_contents($logo->getImagen())), 12, 13, 40, 25,$logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->SetFont('Arial', '', 5);
        $date = new \DateTime('now');
        $this->Text(170, 10, $date->format('Y-m-d H:i:s') . ' [Cromo | Inventario]');

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(19, 47);
        $this->Cell(15, 4, 'CLIENTE', 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $this->SetX(38);
        $this->Cell(75, 4, '', 0, 0, 'L', 0);

        $this->SetX(128);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, 'DIRECCION DE ENVIO', 0, 0, 'L', 0);
        $this->SetX(170);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 4, '', 0, 0, 'L', 0);

        $this->SetXY(19, 51);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 4, 'NOMBRE:', 0, 0, 'L', 0);
        $this->SetX(38);
        $this->SetFont('Arial', '', 8);
        $this->Cell(73, 4, utf8_decode($arMovimiento->getTerceroRel()->getNombreCorto()), 0, 0, 'L', 0);
        $this->SetX(128);
        $this->Cell(73, 4, $arMovimiento->getSucursalRel() ? $arMovimiento->getSucursalRel()->getNombre() : '', 0, 0, 'L', 0);

        $this->SetXY(19, 54.5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 4, 'NIT:', 0, 0, 'L', 0);
        $this->SetX(38);
        $this->SetFont('Arial', '', 8);
        $this->Cell(73, 4, $arMovimiento->getTerceroRel()->getNumeroIdentificacion() . ' - ' . $arMovimiento->getTerceroRel()->getDigitoVerificacion(), 0, 0, 'L', 0);
        $this->SetX(128);
        $this->Cell(73, 4, utf8_decode($arMovimiento->getSucursalRel() ? $arMovimiento->getSucursalRel()->getContacto() : ''), 0, 0, 'L', 0);

        $this->SetXY(19, 58);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 4, 'DIRECCION:', 0, 0, 'L', 0);
        $this->SetX(38);
        $this->SetFont('Arial', '', 8);
        $this->Cell(73, 4, $arMovimiento->getTerceroRel()->getDireccion(), 0, 0, 'L', 0);
        $this->SetX(128);
        $this->Cell(73, 4, $arMovimiento->getSucursalRel() ? $arMovimiento->getSucursalRel()->getDireccion() : '', 0, 0, 'L', 0);

        $this->SetXY(19, 61.5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 4, 'TELEFONO:', 0, 0, 'L', 0);
        $this->SetX(38);
        $this->SetFont('Arial', '', 8);
        $this->Cell(73, 4, $arMovimiento->getTerceroRel()->getTelefono(), 0, 0, 'L', 0);
        if ($arMovimiento->getSucursalRel()) {
            $direccion = $arMovimiento->getSucursalRel()->getDireccion();
        } else {
            $direccion = $arMovimiento->getTerceroRel()->getDireccion();
        }
        $this->SetX(128);
        $this->Cell(73, 4, $arMovimiento->getSucursalRel() ? $arMovimiento->getSucursalRel()->getTelefono() : '', 0, 0, 'L', 0);

        $this->SetXY(19, 64.5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 4, 'CIUDAD:', 0, 0, 'L', 0);
        $this->SetX(38);
        $this->SetFont('Arial', '', 8);
        $this->Cell(73, 4, $arMovimiento->getTerceroRel()->getCiudadRel()->getNombre(), 0, 0, 'L', 0);
        $this->SetX(128);
        $this->Cell(73, 4, $arMovimiento->getSucursalRel() ? $arMovimiento->getSucursalRel()->getCiudadRel()->getNombre() : '', 0, 0, 'L', 0);

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(6);
        $this->SetX(19.5);
        $header = array('ITEM', 'DESCRIPCION', 'BOD', 'UNIDAD', 'CANT', 'PRECIO', 'IVA', 'DCTO', 'TOTAL');
        $this->SetFillColor(225, 225, 225);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(10, 100, 8, 11, 10, 15, 7, 7.4, 15);
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
            $pdf->SetX(19.5);
            $pdf->Cell(10, 6, $arMovimientoDetalle->getCodigoItemFk(), 1, 0, 'L');
//            $pdf->Cell(12, 6, $arMovimientoDetalle->getPedidoDetalleRel() ? $arMovimientoDetalle->getPedidoDetalleRel()->getCodigoPedidoFk() : '0', 1, 0, 'L');
//            $pdf->Cell(12, 6, $arMovimientoDetalle->getRemisionDetalleRel() ? $arMovimientoDetalle->getRemisionDetalleRel()->getCodigoRemisionFk() : '0', 1, 0, 'L');
            $pdf->Cell(100, 6, utf8_decode($arMovimientoDetalle->getItemRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(8, 6, substr($arMovimientoDetalle->getCodigoBodegaFk(), 0, 3), 1, 0, 'R');
            $pdf->Cell(11, 6, 'UNIDAD', 1, 0, 'C');
            $pdf->Cell(10, 6, $arMovimientoDetalle->getCantidad(), 1, 0, 'R');
            $pdf->Cell(15, 6, number_format($arMovimientoDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(7, 6, number_format($arMovimientoDetalle->getPorcentajeIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(7.4, 6, number_format($arMovimientoDetalle->getPorcentajeDescuento(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 6, number_format($arMovimientoDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer()
    {
        /**
         * @var $arMovimiento InvMovimiento
         * @var $arMovimientoDetalles InvMovimientoDetalle
         */
        $arMovimiento = self::$em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoMovimiento);
        $arFacturaTipo = self::$em->getRepository(InvFacturaTipo::class)->find(['codigoFacturaTipoPk' => $arMovimiento->getCodigoFacturaTipoFk()]);
        $y = 178;
        $x = 181;

        $this->SetXY(151, 174.5);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 3, 'SUBTOTAL:', 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');

        $this->SetXY(151, $y);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 3, '(-)DESCUENTO:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrDescuento(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'TOTAL NETO:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrTotal() - $arMovimiento->getVrIva(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, '(+)IVA:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrIva(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'TOTAL:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrTotal(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'RTE CREE:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format(0, 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'RTE FUENTE:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrRetencionFuente(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'RTE IVA:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrRetencionIva(), 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'RETENCIONES:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format(0, 0, '.', ','), 0, 0, 'R');

        $y += 3.8;
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(151, $y);
        $this->Cell(30, 3, 'TOTAL GENERAL:', 0, 0, 'R');
        $this->SetX($x);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 3, number_format($arMovimiento->getVrNeto(), 0, '.', ','), 0, 0, 'R');
        if ($arMovimiento->getVrNeto() != 0) {
            $vrTotalLetras = self::devolverNumeroLetras($arMovimiento->getVrNeto());
        } else {
            $vrTotalLetras = 'CERO PESOS';
        }
        $this->SetXY(19, 209);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(20, 3, 'SON: ' . $vrTotalLetras, 0, 0, 'L');

        $this->Line(202.3, 174.2, 18.7, 174.2);
        $this->Line(202.3, 212, 18.7, 212);
        $this->Line(202.3, 244.8, 18.7, 244.8);
        $this->Line(202.3, 253.6, 18.7, 253.6);

        $this->Line(151.3, 239.2, 102.9, 239.2);
        $this->Line(202.2, 239.2, 154.5, 239.2);
        $this->Line(202.3, 253.6, 18.7, 253.6);
        $this->SetFont('Arial', 'B', 6);
        $this->Text(102.9, 242, 'AUTORIZADO');
        $this->Text(154.5, 242, 'FIRMA DE RECIBIDO');
        $this->SetFont('Arial', 'B', 7.5);

        $this->Text(19.7, 182, 'OBSERVACIONES:');
        $this->SetXY(19.7, 184);
        $this->SetFont('Arial', '', 7.5);
        $this->MultiCell(127, 3, utf8_decode(strtoupper($arMovimiento->getComentarios())), 0, 'L');
        $this->SetFont('Arial', 'B', 7.5);
        $this->Text(37.5, 248.5, 'REALIZAR PAGO EN LA' . $arFacturaTipo->getInformacionCuentaPago() . ' A NOMBRE DE  FILTRAMED S.A.S');

        $this->SetFont('Arial', '', 5.5);
        $this->Text(20.5, 220, '* IVA REGIMEN COMUN');
        $this->Text(20.5, 222, '* NO SOMOS AUTORETENEDORES');
        $this->Text(20.5, 224, '* NO SOMOS GRANDES CONTRIBUYENTES');
        $this->Text(20.5, 226, '* CODIGO CIIU 4645 - CREE 0.3%');
        $this->Text(20.5, 228, '* LA PRENSETE FACTURA PRESENTA MERITO EJECUTIVO COMO TITULO VALOR');
        $this->Text(20.5, 230, ' SEGUN LO ESTABLECIDO EN EL ART.3 DE LA LEY 1231 DE 2008');
        $this->Text(20.5, 232, '* RESOLUCION DIAN DE AUTORIZACION PARA FACTURACION POR COMPUTADOR');
        $this->Text(20.5, 234, ' No ' . $arFacturaTipo->getNumeroResolucionDianFactura() . ' DESDE ' . $arFacturaTipo->getFechaDesdeVigencia()->format('Y-m-d'). ' HASTA ' . $arFacturaTipo->getFechaHastaVigencia()->format('Y-m-d') . ' . FACTURAS ' . $arFacturaTipo->getNumeracionDesde().  ' AL '. $arFacturaTipo->getNumeracionHasta());
        $this->SetFont('Arial', '', 6.5);
        $this->Text(65, 251.5, 'CRA 90 CL 65C-10 APTO 1917 MEDELLIN - CEL 300 448 02 19 - E-MAIL: comercial@filtramed.com');
        $this->Text(188, 257, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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