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

class Factura2 extends \FPDF
{
    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($em, $codigoFactura)
    {

        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = $em->getRepository(TurFactura::class)->find($codigoFactura);
        ob_clean();
        $pdf = new Factura2();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura_{$arFactura->getCodigoFacturaPk()}.pdf", 'D');

    }

    public function Header()
    {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $arConfiguracionTurno = self::$em->getRepository(TurConfiguracion::class)->find(1);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);


        $this->SetFont('Arial', '', 9);
        $this->Text(15, 65, "Fecha Factura");
        $this->Text(45, 65, ucwords(strtolower(FuncionesController::devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->Text(135, 65, "Fecha Vence");
        $this->Text(170, 65, ucwords(strtolower(FuncionesController::devuelveMes($arFactura->getFechaVence()->format('m')))) . " " . $arFactura->getFechaVence()->format('d') . " de " . $arFactura->getFechaVence()->format('Y'));
        $this->Text(15, 70, utf8_decode("Señores"));
        $this->SetXY(44, 68);
        $this->MultiCell(90, 4, $arFactura->getClienteRel()->getNombreExtendido(), 0, 'L');
        $this->Text(135, 70, "Nit");
        $this->Text(170, 70, $arFactura->getClienteRel()->getNumeroIdentificacion() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
        $this->Text(15, 80, "Direccion");
        $this->SetXY(44, 77);
        $this->MultiCell(90, 4, $arFactura->getClienteRel()->getDireccion(), 0, 'L');
        $this->Text(135, 80, "Telefono");
        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());
        $this->SetXY(110, 75);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(14);
        $this->SetX(15);
        $header = array('CANT', 'DETALLE', 'Vr. UNITARIO', 'Vr. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 9);

        //creamos la cabecera de la tabla.
        $w = array(10, 124, 28, 28);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L');
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf)
    {
        $pdf->Rect(15, 96, 10, 100);
        $pdf->Rect(25, 96, 124, 100);
        $pdf->Rect(149, 96, 28, 100);
        $pdf->Rect(177, 96, 28, 100);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arFacturaDetalles = self::$em->getRepository(TurFacturaDetalle::class)->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $arrMeses = array();
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $strMes = FuncionesController::devuelveMes($arFacturaDetalle->getFechaOperacion()->format('m')) . $arFacturaDetalle->getFechaOperacion()->format('/Y');
            $nuevo = true;
            foreach ($arrMeses as $mes) {
                if ($mes['mes'] == $strMes) {
                    $nuevo = false;
                }
            }
            if ($nuevo == true) {
                $arrMeses[] = array('mes' => $strMes);
            }
        }
        $strMeses = "";
        foreach ($arrMeses as $mes) {
            $strMeses .= $mes['mes'];
        }
        $pdf->SetX(15);
        $pdf->Cell(10, 4, '', 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(120, 4, utf8_decode($arFactura->getDescripcion()), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 4, '', 0, 0, 'R');
        $pdf->Cell(30, 4, '', 0, 0, 'R');
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 9);
        if ($arFactura->getImprimirRelacion() == false) {
            if ($arFactura->getImprimirAgrupada() == 0) {
                foreach ($arFacturaDetalles as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 9);
                    $modalidad = "";
                    if ($arFacturaDetalle->getCodigoModalidadFk()) {
                        $modalidad = "-" . utf8_decode($arFacturaDetalle->getModalidadRel()->getNombre());
                    }
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle->getPuestoRel()->getNombre()) . $modalidad, 0, 61), 0, 0, 'L');
                    $pdf->Cell(124, 4, substr(utf8_decode('') , 0, 61), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    //if ($arFacturaDetalle->getTipoPedido() == 'FIJO') {
                        $strCampo = $arFacturaDetalle->getConceptoRel()->getNombreFacturacion() . " " . $arFacturaDetalle->getDetalle();
                    //} else {
                    //    $strCampo = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacionAdicional() . " " . $arFacturaDetalle->getDetalle();
                    //}

                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    $pdf->MultiCell(124, 4, $arFacturaDetalle->getItemRel()->getNombre(), 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }
            } else {
                $strSql = "SELECT tur_puesto.nombre AS puesto, tur_modalidad.nombre AS modalidadServicio, tur_concepto.nombre_facturacion AS conceptoServicio, cantidad  AS cantidad, vr_precio AS precio
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
                            LEFT JOIN tur_modalidad ON tur_factura_detalle.codigo_modalidad_fk = tur_modalidad.codigo_modalidad_pk
                            LEFT JOIN tur_concepto ON tur_factura_detalle.codigo_concepto_fk = tur_concepto.codigo_concepto_pk
                            WHERE codigo_factura_fk = " . self::$codigoFactura . " AND codigo_grupo_fk IS NULL";
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);
                $statement->execute();
                $results = $statement->fetchAll();
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']) . '-' . $arFacturaDetalle['modalidadServicio'], 0, 61), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);

                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    $strCampo = $arFacturaDetalle['conceptoServicio'];
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }

                $strSql = "SELECT tur_grupo.nombre as puesto, tur_grupo.concepto as conceptoServicio, SUM(cantidad)  AS cantidad, SUM(vr_precio) AS precio
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
                            LEFT JOIN tur_modalidad ON tur_factura_detalle.codigo_modalidad_fk = tur_modalidad.codigo_modalidad_pk
                            LEFT JOIN tur_concepto ON tur_factura_detalle.codigo_concepto_fk = tur_concepto.codigo_concepto_pk
                            LEFT JOIN tur_grupo ON tur_factura_detalle.codigo_grupo_fk = tur_grupo.codigo_grupo_pk
                            WHERE codigo_factura_fk = " . self::$codigoFactura . "  AND codigo_grupo_fk IS NOT NULL
                        GROUP BY tur_factura_detalle.codigo_grupo_fk ";
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);
                $statement->execute();
                $results = $statement->fetchAll();
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']), 0, 61), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);
                    $precioUnitario = $arFacturaDetalle['precio'];
                    if ($arFacturaDetalle['cantidad'] > 0) {
                        $precioUnitario = $arFacturaDetalle['precio'] / $arFacturaDetalle['cantidad'];
                    }
                    $pdf->Cell(28, 4, number_format($precioUnitario, 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    $strCampo = $arFacturaDetalle['conceptoServicio'];
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }
            }
        } else {
            $pdf->SetX(15);
            $pdf->Cell(10, 4, number_format(1, 0, '.', ','), 0, 0, 'C');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(124, 4, utf8_decode($arFactura->getTituloRelacion()), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');
            $pdf->MultiCell(124, 4, utf8_decode($arFactura->getDetalleRelacion()), 0, 'L');
            //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
            $pdf->Cell(28, 4, '', 0, 0, 'R');
            $pdf->Cell(28, 4, '', 0, 0, 'R');
            $pdf->Ln(2);
        }

    }


    public function Footer()
    {
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arConfiguracion = self::$em->getRepository(TurConfiguracion::class)->find(1);
        $this->SetXY(15, 196);
        $this->Cell(50, 21, '', 1, 0, 'R');
        $this->Cell(84, 21, '', 1, 0, 'R');
        $this->SetXY(15, 217);
        $this->Cell(134, 14, '', 1, 0, 'R');
        $this->SetXY(149, 196);
        $this->Cell(28, 7, 'SUB TOTAL', 1, 0, 'L');
        $this->Cell(28, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149, 203);
        $this->Cell(28, 7, 'Base Gravable', 1, 0, 'L');
        $this->Cell(28, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149, 210);
        $this->Cell(28, 7, 'IVA ' . "" . '% (BASE)', 1, 0, 'L');
        $this->Cell(28, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149, 217);
        $this->Cell(28, 7, 'RTE FUENTE', 1, 0, 'L');
        $this->Cell(28, 7, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149, 224);
        $this->Cell(28, 7, 'TOTAL', 1, 0, 'L');
        $this->Cell(28, 7, number_format($arFactura->getVrTotal() + $arFactura->getVrRetencionIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetFont('Arial', '', 8);
        $plazoPago = $arFactura->getClienteRel()->getPlazoPago();
        $this->Text(66, 201, "CONDICIONES DE PAGO: A $plazoPago DIAS A PARTIR");
        $this->Text(66, 205, "DE LA FECHA DE EXPEDICION");
        $this->SetXY(65, 207);
        $this->MultiCell(80, 4, $arFactura->getComentarios(), 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Text(20, 201, "Recibi conforme:");
        $this->Text(20, 206, "Fecha y Nombre:");
        $this->Text(20, 211, "Sello:");
        $this->Text(20, 221, "Actividad Comercial");
//        $this->Text(60, 221, utf8_decode($arFactura->getClienteRel()->getSectorComercialRel()->getNombre()));
        $this->Text(60, 221, utf8_decode(""));
        $this->Text(90, 221, "Estrato =");
        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        //$this->Text(20, $this->GetY($this->SetY(244)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetXY(30, 239);
//        $this->MultiCell(110, 5, $arConfiguracion->getInformacionPagoFactura(), 0, 'L');
        $this->MultiCell(110, 5,"", 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', 'B', 8);
        $this->Text(30, 254, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable");
        $this->SetFont('Arial', '', 7);
        $this->Text(50, 264, "Favor remitir copia de la consignacion a los correos a.mona@seracis.com y d.mejia@seracis.com");

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em)
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, '');
        try {
            $logo = $em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if ($logo) {
                $this->Image("data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen())), 15, 8, 50, 24, $logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        //$this->Text(21, 35, "NIT " . $arConfiguracion->getNit() . "-" . $arConfiguracion->getDigitoVerificacion());
        $this->SetXY(258, 18);
    }
}