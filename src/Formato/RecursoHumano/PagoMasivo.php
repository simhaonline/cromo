<?php

namespace App\Formato\RecursoHumano;

use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\ORM\EntityManager;

class PagoMasivo extends \FPDF
{

    /**
     * @var EntityManager
     */
    public static $em;
    public static $codigoProgramacionPago;
    public static $codigoPago;
    public static $codigoZona;
    public static $codigoSubzona;
    public static $porFecha;
    public static $fechaDesde;
    public static $fechaHasta;
    public static $dato;
    public static $codigoCentroCosto;
    public static $codigoPagoTipo;
    public static $identificacionEmpleado;
    public static $codigoSucursal;
    public static $codigoArea;
    public static $codigoProyecto;
    public static $codigoClienteTurno;
    public static $codigoZonaPuesto;
    public static $codigoGrupoPago;

    public function Generar($em, $codigoProgramacionPago = "", $porFecha = false, $fechaDesde = "", $fechaHasta = "",  $pagoTipo = "", $codigoGrupoPago = "")
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProgramacionPago = $codigoProgramacionPago;
        self::$porFecha = $porFecha;
        self::$fechaDesde = $fechaDesde;
        self::$fechaHasta = $fechaHasta;
        self::$codigoPagoTipo = $pagoTipo;
        self::$codigoGrupoPago = $codigoGrupoPago;

        //$pdf = new FormatoPagoMasivo('P', 'mm', array(215, 147));
        $pdf = new PagoMasivo('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);
        $pdf->Output("Pagos.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 3);
//        $this->Image('imagenes/logos/logo.jpg', 12, 5, 28, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "COMPROBANTE PAGO NOMINA", 0, 0, 'C', 1); //$this->Cell(150, 7, utf8_decode("COMPROBANTE PAGO ". $arPago->getPagoTipoRel()->getNombre().""), 0, 0, 'C', 1);
        $this->SetXY(53, 11);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombre() . "NIT:" . $arConfiguracion->getNit() . " - " . $arConfiguracion->getDigitoVerificacion(), 0, 0, 'L', 0);
        $this->SetXY(53, 15);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccion(), 0, 0, 'L', 0);
        $this->SetXY(53, 19);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefono(), 0, 0, 'L', 0);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->SetXY(10, 53);
        $header = array('COD', 'CONCEPTO', 'HORAS', 'DIAS', 'VR.HORA', '%', 'DEVENGADO', 'DEDUCCION');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //Creamos la cabecera de la tabla.
        $w = array(15, 80, 12, 10, 25, 6, 21, 21);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1) {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            } else {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            }

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf)
    {
        /**
         * @var RhuPago $arPago
         */
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(200, 200, 200);
        $arConfiguracion = self::$em->getRepository(RhuConfiguracion::class)->find(1);
        $dql = self::$em->getRepository(RhuPago::class)->listaImpresionDql(self::$codigoProgramacionPago,  self::$porFecha, self::$fechaDesde, self::$fechaHasta, self::$codigoPagoTipo, self::$codigoGrupoPago);
        $query = self::$em->createQuery($dql);
        $arPagos = $query->getResult();
        $numeroPagos = count($arPagos);
        $contador = 1;
        foreach ($arPagos as $arPago) {
            $y = 25;
            //FILA 1
            $intY = 29;
            $pdf->SetXY(10, $intY);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 4, "NUMERO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(50, 4, $arPago->getNumero(), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "CUENTA:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getEmpleadoRel()->getCuenta(), 1, 0, 'L', 1);


            $pdf->SetXY(10, $intY + 4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "EMPLEADO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(50, 4, utf8_decode($arPago->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, 'IDENTIFICACION:', 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, 'BANCO:', 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, utf8_decode($arPago->getEmpleadoRel()->getBancoRel() ? $arPago->getEmpleadoRel()->getBancoRel()->getNombre() : ''), 1, 0, 'L', 1);

            $pdf->SetXY(10, $intY + 8);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "CARGO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(50, 4, utf8_decode($arPago->getContratoRel()->getCargoRel()->getNombre()), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, "DESDE:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getFechaDesde()->format('Y-m-d'), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "PENSION:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getContratoRel()->getEntidadPensionRel() ? $arPago->getContratoRel()->getEntidadPensionRel()->getNombre() : '', 1, 0, 'L', 1);


            $pdf->SetXY(10, $intY + 12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(50, 4, '', 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, "HASTA:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "SALUD:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, $arPago->getContratoRel()->getEntidadSaludRel() ? $arPago->getContratoRel()->getEntidadSaludRel()->getNombre() : '', 1, 0, 'L', 1);


            $pdf->SetXY(10, $intY + 16);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "COMENTARIO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(110, 4, utf8_decode($arPago->getComentario()), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(25, 4, "SALARIO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(30, 4, number_format($arPago->getContratoRel()->getVrSalario(), 0, '.', ','), 1, 0, 'R', 1);
            $pdf->Ln(12);
            $totalExtras = 0;
            $totalCompensado = 0;
            $totalHorasCompensado = 0;
            $dql = self::$em->getRepository(RhuPagoDetalle::class)->listaDql($arPago->getCodigoPagoPk());
            $query = self::$em->createQuery($dql);
            $arPagoDetalles = $query->getResult();
            /** @var  $arPagoDetalle RhuPagoDetalle */
            foreach ($arPagoDetalles as $arPagoDetalle) {
                $pdf->Cell(15, 4, $arPagoDetalle->getCodigoPagoDetallePk(), 1, 0, 'L');
                $pdf->Cell(80, 4, utf8_decode($arPagoDetalle->getConceptoRel()->getNombre()), 1, 0, 'L');
                $pdf->Cell(12, 4, $arPagoDetalle->getHoras(), 1, 0, 'R');
                $pdf->Cell(10, 4, $arPagoDetalle->getDias(), 1, 0, 'R');
                $pdf->Cell(25, 4, number_format($arPagoDetalle->getVrHora(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(6, 4, $arPagoDetalle->getPorcentaje(), 1, 0, 'R');
                $pdf->Cell(21, 4, number_format($arPagoDetalle->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(21, 4, number_format($arPagoDetalle->getVrDeduccion(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln(4);
                $pdf->SetAutoPageBreak(true, 15);
            }

            //TOTALES
//            $pdf->Ln(2);
//            $boolSoportePago = false;
//            if ($arPago->getCodigoSoportePagoFk() && $arPago->getCentroCostoRel()->getImprimirProgramacionFormato()) {
//                $desde = $arPago->getFechaDesde()->format('j');
//                $hasta = $arPago->getFechaHasta()->format('j');
//                if ($hasta == 30) {
//                    $hasta = 31;
//                }
//
//                $arSoportePago = self::$em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arPago->getCodigoSoportePagoFk());
//                if ($arSoportePago) {
//                    $boolSoportePago = true;
//                    $header = array('D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8', 'D9', 'D10', 'D11', 'D12', 'D13', 'D14', 'D15', 'D16', 'D17', 'D18', 'D19', 'D20', 'D21', 'D22', 'D23', 'D24', 'D25', 'D26', 'D27', 'D28', 'D29', 'D30', 'D31');
//                    $pdf->SetFillColor(200, 200, 200);
//                    $pdf->SetTextColor(0);
//                    $pdf->SetDrawColor(0, 0, 0);
//                    $pdf->SetLineWidth(.2);
//                    $pdf->SetFont('', 'B', 6.8);
//
//                    //creamos la cabecera de la tabla.
//                    $w = array(6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2, 6.2);
//                    for ($i = $desde; $i <= $hasta; $i++) {
//                        $pdf->Cell(6.2, 4, "D" . $i, 1, 0, 'L', 1);
//                    }
//                    $pdf->Ln();
//                    $arSoportePagoProgramaciones = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();
//                    $arSoportePagoProgramaciones = self::$em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->findBy(array('codigoSoportePagoFk' => $arPago->getCodigoSoportePagoFk()));
//                    foreach ($arSoportePagoProgramaciones as $arSoportePagoProgramacion) {
//                        $detalle = $this->convertirArray($arSoportePagoProgramacion);
//                        $pdf->SetFont('Arial', '', 5);
//                        for ($j = $desde; $j <= $hasta; $j++) {
//                            $pdf->Cell(6.2, 4, $detalle[$j], 1, 0, 'L');
//                        }
//                        $pdf->Ln();
//                        $pdf->SetAutoPageBreak(true, 15);
//                    }
//                }
//            } else {
////                $pdf->Ln(8);
//                $boolSoportePago = false;
//            }
//            if ($boolSoportePago) {
//                $pdf->Ln(5);
//            }
            $pdf->Ln(4);
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, "TOTAL DEVENGADO:", 1, 0, 'R', true);
            $pdf->Cell(20, 4, number_format($arPago->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->Cell(30, 4, "TOTAL DEDUCCIONES:", 1, 0, 'R', true);
            $pdf->Cell(20, 4, "-" . number_format($arPago->getVrDeduccion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(140, 4, "", 0, 0, 'R');
            $pdf->Cell(30, 4, "NETO PAGAR", 1, 0, 'R', true);
            $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln(8);

            if ($contador < $numeroPagos) {
                $pdf->AddPage();
            }
            $contador++;
        }


        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 7);
    }

    public function Footer()
    {

        //$this->SetFont('Arial','', 8);  
        //$this->Text(185, 140, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

    private function convertirArray($arSoportePagoProgramacion)
    {
        $arrProgramacionDetalle = array();
        if ($arSoportePagoProgramacion) {
            $arrProgramacionDetalle[1] = $arSoportePagoProgramacion->getDia1();
            $arrProgramacionDetalle[2] = $arSoportePagoProgramacion->getDia2();
            $arrProgramacionDetalle[3] = $arSoportePagoProgramacion->getDia3();
            $arrProgramacionDetalle[4] = $arSoportePagoProgramacion->getDia4();
            $arrProgramacionDetalle[5] = $arSoportePagoProgramacion->getDia5();
            $arrProgramacionDetalle[6] = $arSoportePagoProgramacion->getDia6();
            $arrProgramacionDetalle[7] = $arSoportePagoProgramacion->getDia7();
            $arrProgramacionDetalle[8] = $arSoportePagoProgramacion->getDia8();
            $arrProgramacionDetalle[9] = $arSoportePagoProgramacion->getDia9();
            $arrProgramacionDetalle[10] = $arSoportePagoProgramacion->getDia10();
            $arrProgramacionDetalle[11] = $arSoportePagoProgramacion->getDia11();
            $arrProgramacionDetalle[12] = $arSoportePagoProgramacion->getDia12();
            $arrProgramacionDetalle[13] = $arSoportePagoProgramacion->getDia13();
            $arrProgramacionDetalle[14] = $arSoportePagoProgramacion->getDia14();
            $arrProgramacionDetalle[15] = $arSoportePagoProgramacion->getDia15();
            $arrProgramacionDetalle[16] = $arSoportePagoProgramacion->getDia16();
            $arrProgramacionDetalle[17] = $arSoportePagoProgramacion->getDia17();
            $arrProgramacionDetalle[18] = $arSoportePagoProgramacion->getDia18();
            $arrProgramacionDetalle[19] = $arSoportePagoProgramacion->getDia19();
            $arrProgramacionDetalle[20] = $arSoportePagoProgramacion->getDia20();
            $arrProgramacionDetalle[21] = $arSoportePagoProgramacion->getDia21();
            $arrProgramacionDetalle[22] = $arSoportePagoProgramacion->getDia22();
            $arrProgramacionDetalle[23] = $arSoportePagoProgramacion->getDia23();
            $arrProgramacionDetalle[24] = $arSoportePagoProgramacion->getDia24();
            $arrProgramacionDetalle[25] = $arSoportePagoProgramacion->getDia25();
            $arrProgramacionDetalle[26] = $arSoportePagoProgramacion->getDia26();
            $arrProgramacionDetalle[27] = $arSoportePagoProgramacion->getDia27();
            $arrProgramacionDetalle[28] = $arSoportePagoProgramacion->getDia28();
            $arrProgramacionDetalle[29] = $arSoportePagoProgramacion->getDia29();
            $arrProgramacionDetalle[30] = $arSoportePagoProgramacion->getDia30();
            $arrProgramacionDetalle[31] = $arSoportePagoProgramacion->getDia31();
        }
        return $arrProgramacionDetalle;
    }

}
