<?php


namespace App\Formato\RecursoHumano;


use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;

class Liquidacion extends \FPDF
{
    public static $em;
    public static $codigoLiquidacion;

    /**
     * @param $em ObjectManager
     * @param $codigoLiquidacion integer
     */
    public function Generar($em, $codigoLiquidacion)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoLiquidacion = $codigoLiquidacion;
        $pdf = new Liquidacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $codigoLiquidacion);
        $pdf->Output("liquidacion.pdf", 'D');
    }

    /**
     * @throws \Exception
     */
    public function Header()
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo


        Estandares::generarEncabezado($this,'LIQUIDACION', self::$em);

        $this->SetXY(53, 34);
        $this->Cell(20, 4, 'FECHA:', 0, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(8);
    }

    public function Body($pdf, $codigoliquidacion)
    {
        $em = BaseDatos::getEm();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoliquidacion);
        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arLiquidacion->getCodigoEmpleadoFk());
        $arContrato = $em->getRepository(RhuContrato::class)->find($arLiquidacion->getCodigoContratoFk());
        $posicionY = 42;
        //tabla vacaciones
        //linea 1
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetXY(10, $posicionY);
        $pdf->Cell(195, 5, utf8_decode("INFORMACÍON LIQUIDACIÓN"), 1, 0, 'C', 1);
        $pdf->Ln(5);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, "ID", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arLiquidacion->getCodigoLiquidacionPk()), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, "FECHA", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arLiquidacion->getFecha(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->Ln();
        //linea 2
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("EMPLEADO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arEmpleado->getNombreCorto()), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("IDENTIFICACIÓN"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $pdf->Ln();
        //linea 4
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("CONTRATO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arContrato->getCodigoContratoPk()), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("CONTRATO TIPO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arContrato->getContratoTipoRel()->getNombre()), 1, 0, 'L', 1);
        $pdf->Ln();
        //linea 5
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("FECHA INGRESO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, $arLiquidacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("NÚMERO CUENTA"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, $arEmpleado->getCuenta(), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("DIAS LABORADOS"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 1, 0, 'L', 1);
        $pdf->Ln();
        //linea 6
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("FECHA RETIRO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, $arLiquidacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("BANCO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, $arEmpleado->getBancoRel()->getNombre(), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("SALARIO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, number_format($arContrato->getVrSalario(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->Ln();
        //linea 7
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("GRUPO DE PAGO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, $arLiquidacion->getCodigoGrupoFk(), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode(""), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 0, '.', ',', 1, 0, 'R', 1);
        $pdf->Ln();
        //linea 8
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode("MOTIVO RETIRO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, "", 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode(""), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 0, '.', ',', 1, 0, 'R', 1);
        $pdf->Ln();


        $posicionY = 150;
        $posicionX = 5;
        //BLOQUE TOTALES
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetXY($posicionX + 95, 90);
        $pdf->Cell(15, 5, utf8_decode("DIAS"), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + 111, 90);
        $pdf->Cell(13, 5, utf8_decode("D.AUS"), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + 125, 90);
        $pdf->Cell(25, 5, utf8_decode("BASE"), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + 150, 90);
        $pdf->Cell(25, 5, utf8_decode("ULT.PAGO"), 1, 0, 'C', 1);
        $pdf->SetXY($posicionX + 175, 90);
        $pdf->Cell(25, 5, utf8_decode("TOTAL"), 1, 0, 'R', 1);

        $posicionXlinea = 40;
        $posicionY = 100;
        $pdf->SetXY($posicionX+ $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "INTERESES:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX+$posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, utf8_decode("CESANTÍAS (ANT):"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "INTERESES (ANT):", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "VACACIONES:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "INDEMNIZACION:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "BONIFICACIONES:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "TOTAL DEVENGADO:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "DEDUCCIONES", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "DEDUCCIONES PRIMAS:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "TOTAL DEDUCCIONES:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, $posicionY);
        $posicionY += 6;
        $pdf->Cell(43, 5, "NETO A PAGAR:", 1, 0, 'L', 1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(272, 272, 272);
        $posicionXlinea = 95;
        $pdf->SetXY($posicionX + $posicionXlinea, 100);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 106);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 112);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasCesantiasAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 118);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasCesantiasAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 124);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 130);
        $pdf->Cell(15, 5, number_format($arLiquidacion->getDiasVacacion()   , 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 135);
        $pdf->Cell(15, 5, "", 0, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 138);
        $pdf->Cell(15, 5, "", 0, 0, 'R', 1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(272, 272, 272);
        $posicionXlinea = 111;
        $pdf->SetXY($posicionX + $posicionXlinea, 100);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasCesantiasAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 106);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasCesantiasAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 112);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasCesantiasAusentismoAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 118);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasCesantiasAusentismoAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 124);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasPrimaAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 130);
        $pdf->Cell(13, 5, number_format($arLiquidacion->getDiasVacacionAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 135);
        $pdf->Cell(13, 5, "", 0, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 138);
        $pdf->Cell(13, 5, "", 0, 0, 'R', 1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(272, 272, 272);
        $posicionXlinea = 125;
        $pdf->SetXY($posicionX + $posicionXlinea, 100);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrSalarioPromedioCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 106);
        $pdf->Cell(25, 5, number_format(0, 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 112);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrSalarioPromedioCesantiasAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 118);
        $pdf->Cell(25, 5, number_format(0, 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 124);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrSalarioPromedioPrimas(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 130);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrSalarioVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 135);
        $pdf->Cell(25, 5, "", 0, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 137);
        $pdf->Cell(25, 5, "", 0, 0, 'R', 1);

        $this->SetFillColor(272, 272, 272);
        $posicionXlinea = 150;
        $pdf->SetXY($posicionX + $posicionXlinea, 100);
        $pdf->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 106);
        $pdf->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 112);
        $fechaUltimoPagoCesantiasAnterior = '';
        if ($arLiquidacion->getFechaUltimoPagoCesantiasAnterior() != NULL) {
            $fechaUltimoPagoCesantiasAnterior = $arLiquidacion->getFechaUltimoPagoCesantiasAnterior()->format('Y-m-d');
        }
        $pdf->Cell(25, 5, $fechaUltimoPagoCesantiasAnterior, 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 118);
        $fechaUltimoPagoCesantiasAnterior = '';
        if ($arLiquidacion->getFechaUltimoPagoCesantiasAnterior() != NULL){
            $fechaUltimoPagoCesantiasAnterior = $arLiquidacion->getFechaUltimoPagoCesantiasAnterior()->format('Y-m-d');
        }
        $pdf->Cell(25, 5, $fechaUltimoPagoCesantiasAnterior, 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 124);
        $pdf->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoPrima()->format('Y-m-d'), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 130);
        $pdf->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoVacacion() == ""?"":$arLiquidacion->getFechaUltimoPagoVacacion()->format('Y-m-d'), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 135);
        $pdf->Cell(25, 5, "", 0, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 137);
        $pdf->Cell(25, 5, "", 0, 0, 'R', 1);

        $this->SetFillColor(272, 272, 272);
        $posicionXlinea = 175;
        $pdf->SetXY($posicionX + $posicionXlinea, 100);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 106);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 112);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrCesantiasAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 118);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrInteresesCesantiasAnterior(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 124);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 130);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrVacacion(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 136);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrIndemnizacion(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 142);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrBonificaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 148);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrCesantias()+$arLiquidacion->getVrInteresesCesantias()+$arLiquidacion->getVrCesantiasAnterior()+$arLiquidacion->getVrInteresesCesantiasAnterior()+$arLiquidacion->getVrPrima()+$arLiquidacion->getVrVacacion()+$arLiquidacion->getVrIndemnizacion()+$arLiquidacion->getVrBonificaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 154);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrDeducciones(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 160);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrDeduccionPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 166);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrDeducciones()+$arLiquidacion->getVrDeduccionPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX + $posicionXlinea, 172);
        $pdf->Cell(25, 5, number_format($arLiquidacion->getVrTotal(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->Ln(15);


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->Cell(195, 7, "DESCUENTOS - BONIFICACIONES", 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('Arial', 'B', 7);
        $header = array(utf8_decode('CÓDIGO'), 'CONCEPTO', 'BONIFICACION', 'DEDUCCION','OBSERVACIONES');
        $w = array(12, 81, 19, 20,63);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln(4);
        $pdf->Text(10, 240, "FIRMA: _____________________________________________");
        $pdf->Text(10, 247, "");
        $pdf->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $pdf->Text(105, 240, "FIRMA: _____________________________________________");
        $pdf->Text(105, 247, "");
        $pdf->Text(105, 254, "NIT: ".""." - ". "");
    }

    public function Footer()
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }



}