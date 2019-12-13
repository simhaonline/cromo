<?php


namespace App\Formato\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;

class Factura3 extends \FPDF
{
    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($em, $codigoFactura)
    { //1teg

        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = $em->getRepository(TurFactura::class)->find($codigoFactura);
        $valor = round($arFactura->getVrNeto());
        $strLetras = FuncionesController::devolverNumeroLetras($valor);
        self::$strLetras = $strLetras;
        ob_clean();
        $pdf = new Factura3('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura_{$arFactura->getCodigoFacturaPk()}.pdf", 'D');

    }

    public function Header()
    {

        $this->GenerarEncabezadoFactura(self::$em);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $this->Image('../public/img/recursos/vigilancia/burea.png', 165, 12, 20, 15);
        $this->Image('../public/img/recursos/vigilancia/certificacion.png', 185, 12, 20, 15);
        $this->SetXY(15, 38);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(50, 4, "FACTURA DE VENTA", 0, 0, 'L', 0);
        $this->SetXY(178, 38);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20, 4, utf8_decode("N°.  ") . $arFactura->getNumero(), 0, 0, 'L', 0);//$arFactura->getCodigoFacturaPk(), 0, 0, 'L', 0);
        //
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(15, 43);
        $this->Cell(25, 5, "CLIENTE:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7.7);
        $this->Cell(115, 5, utf8_decode($arFactura->getClienteRel()->getNombreCorto()), 0, 0, 'L', 1);
        $this->SetXY(156, 43);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "FECHA EMISION:", 0, 0, 'C', 1);
        $this->Cell(25, 5, "FECHA VENCE:", 0, 0, 'C', 1);
        $this->SetXY(15, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "NIT:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, utf8_decode($arFactura->getClienteRel()->getNumeroIdentificacion()), 0, 0, 'L', 1);
        $this->SetXY(156, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, $arFactura->getFecha()->format('Y-m-d'), 0, 0, 'C', 1);
        $this->Cell(25, 5, $arFactura->getFechaVence()->format('Y-m-d'), 0, 0, 'C', 1);
        $this->SetXY(15, 53);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, utf8_decode($arFactura->getClienteRel()->getDireccion()), 0, 0, 'L', 1);
        $this->SetXY(156, 53);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("PEDIDO N°"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, "0", 0, 0, 'L', 1);
        $this->SetXY(15, 58);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "TELEFONO:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(50, 5, $arFactura->getClienteRel()->getTelefono(), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, "CIUDAD:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 5, utf8_decode($arFactura->getClienteRel()->getCiudadRel()->getNombre()), 0, 0, 'L', 1);
        $this->SetXY(156, 58);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "FORMA PAGO", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, $arFactura->getClienteRel()->getFormaPagoRel()->getNombre(), 0, 0, 'L', 1);
        $this->Text(45, 70, utf8_decode($arFactura->getClienteRel()->getNombreExtendido()));
        $this->ln();
        /*$this->SetXY(44, 68);
        $this->MultiCell(90, 4, $arFactura->getClienteRel()->getNombreCompleto(), 0, 'L');
//        $this->Text(135, 70, "Nit");
//        $this->Text(170, 70, $arFactura->getClienteRel()->getNit(). "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
//        $this->Text(15, 80, "Direccion");
//        //$this->Text(45, 80, utf8_decode($arFactura->getClienteRel()->getDireccion()));
//        $this->SetXY(44, 77);
//        $this->MultiCell(90, 4,  $arFactura->getClienteRel()->getDireccion(), 0, 'L');
//        $this->Text(135, 80, "Telefono");
//        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());                */
//
        $this->SetXY(110, 63);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(8);
        $this->SetX(15);
        $header = array('DESCRIPCION', 'CANT', 'VR. UNITARIO', 'VR. TOTAL');
        $this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(139, 8, 22, 22);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf)
    {
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arFacturaDetalles = self::$em->getRepository(TurFacturaDetalle::class)->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->Ln(0);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(15);
            $modalidad = "";
            $detalle = "";
            if ($arFacturaDetalle->getPedidoDetalleRel()) {
                $modalidad = "-" . utf8_decode($arFacturaDetalle->getPedidoDetalleRel()->getModalidadRel()->getNombre());
            }
            if ($arFacturaDetalle->getDetalle()) {
                $detalle = "-" . utf8_decode($arFacturaDetalle->getDetalle());
            }
            $pdf->Cell(139, 4, substr(utf8_decode($arFacturaDetalle->getItemRel()->getNombre()) . $modalidad .$detalle, 0, 80), 0, 0, 'L');
            $pdf->Cell(8, 4, number_format($arFacturaDetalle->getCantidad(), 1, '.', ','), 0, 0, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getvrSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');
//                    if($arFacturaDetalle->getTipoPedido() == 'FIJO') {
//                        $strCampo = $arFacturaDetalle->getConceptoRel()->getNombreFacturacion() . " " . $arFacturaDetalle->getDetalle();
//                    } else {
//                        $strCampo = $arFacturaDetalle->getConceptoRel()->getNombreFacturacionAdicional() . " " . $arFacturaDetalle->getDetalle();
//                    }

//            $pdf->Cell(124, 4, $arFacturaDetalle->getConceptoRel()->getNombre(), 0, 'L');
            //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
            $pdf->Cell(28, 4, '', 0, 0, 'R');
            $pdf->Cell(28, 4, '', 0, 0, 'R');
            $pdf->Ln(-1);
            $pdf->SetAutoPageBreak(true, 88);
        }
//            } else {
//                $strSql = "SELECT tur_puesto.nombre AS puesto, tur_modalidad_servicio.nombre AS modalidadServicio, tur_concepto_servicio.nombre_facturacion AS conceptoServicio, cantidad  AS cantidad, vr_precio AS precio
//                            FROM
//                            tur_factura_detalle
//                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
//                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
//                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk
//                            WHERE codigo_factura_fk = " . self::$codigoFactura . " AND codigo_grupo_facturacion_fk IS NULL";
//                $connection = self::$em->getConnection();
//                $statement = $connection->prepare($strSql);
//                $statement->execute();
//                $results = $statement->fetchAll();
//                foreach ($results as $arFacturaDetalle) {
//                    $pdf->SetX(15);
//                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
//                    $pdf->SetFont('Arial', 'B', 9);
//                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']) . '-'  . $arFacturaDetalle['modalidadServicio'], 0, 61), 0, 0, 'L');
//                    $pdf->SetFont('Arial', '', 9);
//
//                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
//                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
//                    $pdf->Ln();
//                    $pdf->SetX(15);
//                    $pdf->Cell(10, 4, '', 0, 0, 'R');
//                    $strCampo = $arFacturaDetalle['conceptoServicio'];
//                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
//                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
//                    $pdf->Cell(28, 4, '', 0, 0, 'R');
//                    $pdf->Cell(28, 4, '', 0, 0, 'R');
//                    $pdf->Ln(2);
//                    $pdf->SetAutoPageBreak(true, 15);
//                }
//
//                $strSql = "SELECT tur_grupo_facturacion.nombre as puesto, tur_grupo_facturacion.concepto as conceptoServicio, SUM(cantidad)  AS cantidad, SUM(vr_precio) AS precio
//                            FROM
//                            tur_factura_detalle
//                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
//                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
//                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk
//                            LEFT JOIN tur_grupo_facturacion ON tur_factura_detalle.codigo_grupo_facturacion_fk = tur_grupo_facturacion.codigo_grupo_facturacion_pk
//                            WHERE codigo_factura_fk = " . self::$codigoFactura . "  AND codigo_grupo_facturacion_fk IS NOT NULL
//                        GROUP BY tur_factura_detalle.codigo_grupo_facturacion_fk ";
//                $connection = self::$em->getConnection();
//                $statement = $connection->prepare($strSql);
//                $statement->execute();
//                $results = $statement->fetchAll();
//                foreach ($results as $arFacturaDetalle) {
//                    $pdf->SetX(15);
//                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
//                    $pdf->SetFont('Arial', 'B', 9);
//                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']), 0, 61), 0, 0, 'L');
//                    $pdf->SetFont('Arial', '', 9);
//                    if($arFacturaDetalle['cantidad'] > 0) {
//                        $precioUnitario = $arFacturaDetalle['precio'] / $arFacturaDetalle['cantidad'];
//                    }
//                    $pdf->Cell(28, 4, number_format($precioUnitario, 0, '.', ','), 0, 0, 'R');
//                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
//                    $pdf->Ln();
//                    $pdf->SetX(15);
//                    $pdf->Cell(10, 4, '', 0, 0, 'R');
//                    $strCampo = $arFacturaDetalle['conceptoServicio'];
//                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
//                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
//                    $pdf->Cell(28, 4, '', 0, 0, 'R');
//                    $pdf->Cell(28, 4, '', 0, 0, 'R');
//                    $pdf->Ln(2);
//                    $pdf->SetAutoPageBreak(true, 15);
//                }
//            }
//        } else {
//            $pdf->SetX(15);
//            $pdf->SetFont('Arial', 'B', 9);
//            $pdf->Cell(124, 4, utf8_decode($arFactura->getTituloRelacion()), 0, 0, 'L');
//            $pdf->SetFont('Arial', '', 9);
//            $pdf->Cell(22, 4, number_format(1, 0, '.', ','), 0, 0, 'R');
//            $pdf->Cell(22, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
//            $pdf->Cell(22, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
//            $pdf->Ln();
//            $pdf->SetX(15);
//            $pdf->Cell(10, 4, '', 0, 0, 'R');
//            $pdf->MultiCell(124, 4, utf8_decode($arFactura->getDetalleRelacion()), 0, 'L');
//            //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
//            $pdf->Cell(28, 4, '', 0, 0, 'R');
//            $pdf->Cell(28, 4, '', 0, 0, 'R');
//            $pdf->Ln(2);
    }

    public function Footer()
    {
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $arConfiguracion = self::$em->getRepository(TurConfiguracion::class)->find(1);
        $this->Rect(15, 77, 139, 115);
        $this->Rect(154, 77, 8, 115);
        $this->Rect(162, 77, 22, 115);
        $this->Rect(184, 77, 22, 115);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(15, 192);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, 'OBSERVACIONES:', 1, 0, 'L');
        $this->Cell(35, 6, 'RETE FUENTE:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->Cell(28, 6, 'BASE GRAVABLE:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrBaseAIU(), 0, '.', ','), 1, 0, 'R');
        $this->Cell(22, 6, 'SUBTOTAL', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15, 198);
        $this->Cell(147, 12, $arFactura->getComentarios(), 1, 0, 'L');
        $this->Cell(22, 6, 'IVA', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162, 204);
        $this->Cell(22, 6, 'TOTAL', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15, 210);
        $this->Cell(147, 6, substr(strtoupper(self::$strLetras), 0, 96), 1, 0, 'L', 1);
        $this->Cell(22, 6, '', 1, 0, 'L', 1);
        $this->Cell(22, 6, number_format($arFactura->getVrNeto(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY(15, 216);
        $this->Cell(64, 6, 'RECIBIDO POR', 1, 0, 'L');
        $this->Cell(64, 6, 'ACEPTADO POR', 1, 0, 'L');
        $this->Cell(63, 6, '', 1, 0, 'L');
        $this->SetXY(15, 222);
        $this->Cell(32, 6, 'NIT/C.C', 1, 0, 'L');
        $this->Cell(32, 6, 'FECHA', 1, 0, 'L');
        $this->Cell(32, 6, 'NIT/C.C', 1, 0, 'L');
        $this->Cell(32, 6, 'FECHA', 1, 0, 'L');
        $this->Cell(63, 6, 'FIRMA DEL EMISOR', 1, 0, 'L');
        $this->SetXY(15, 228);
        $this->SetFont('Arial', 'B', 7.7);
        $this->MultiCell(191, 3.6, utf8_decode($arFactura->getFacturaTipoRel()->getInformacionLegalFactura()), 1, 'L');
        $this->SetXY(15, 246);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(191, 3.9, utf8_decode($arFactura->getFacturaTipoRel()->getInformacionPagoFactura()), 1, 'C');
        $this->SetXY(15, 261.5);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(191, 3.5, utf8_decode('Autorizo a la entidad 1 TEG SEGURIDAD LTDA o a quien represente la calidad de acreedor, a reportar, procesar, solicitar o divulgar a cualquier entidad que maneje o administre base de datos la información referente a mi comportamiento comercial.'), 1, 'L');

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em)
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $arConfiguracionTurno = self::$em->getRepository(TurConfiguracion::class)->find(1);
        $arFactura = self::$em->getRepository(TurFactura::class)->find(self::$codigoFactura);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        //Logo
//        $this->Image('imagenes/logos/logo.jpg', 20, 8, 30, 25);
//        $this->Image('imagenes/logos/veritas.jpg', 165, 11, 17, 22);
//        $this->Image('imagenes/logos/iso.jpg', 185, 11, 24, 24);
        try {
            $logo = $em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if ($logo) {
                $this->Image("data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen())), 15, 8, 30, 24, $logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
//        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 5);
        $this->Cell(120, 4, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'C', 0);
        $this->SetXY(50, 9);
        $this->Cell(120, 4, "NIT.: " . $arConfiguracion->getNit() . " - " . $arConfiguracion->getDigitoVerificacion(), 0, 0, 'C', 0);
        $this->SetXY(50, 13);
        $this->Cell(120, 4, $arConfiguracion->getDireccion(), 0, 0, 'C', 0);
        $this->SetXY(50, 18);
        $this->Cell(120, 4, "Telefono: " . $arConfiguracion->getTelefono(), 0, 0, 'C', 0);
        $this->SetXY(50, 22);
        $this->Cell(120, 4, $arConfiguracion->getCiudadRel()->getNombre(), 0, 0, 'C', 0);
        $this->SetXY(50, 26);
        $this->SetFont('Arial', '', 8);
        $this->Cell(120, 4, "IVA REGIMEN COMUN", 0, 0, 'C', 0);
        $this->SetXY(50, 30);
        $this->Cell(120, 4, utf8_decode($arFactura->getFacturaTipoRel()->getInformacionResolucionDianFactura()), 0, 0, 'C', 0);
        $this->SetXY(50, 33);
        $this->Cell(120, 4, "__________________________________________________________________________________________________________________________", 0, 0, 'C', 0);
    }


}