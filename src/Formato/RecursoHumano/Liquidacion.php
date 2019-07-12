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
        $pdf = new Vacaciones();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $codigoLiquidacion);
        $pdf->Output("liquidacion.pdf", 'D');
    }

    public function Header()
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo


        Estandares::generarEncabezado($this, 'Cliente', self::$em);

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
        $intY = 42;
        //tabla vacaciones
        //linea 1
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetXY(10, $intY);
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
        $pdf->Cell(32.5, 5, $arLiquidacion->getCodigoGrupoFk(), 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode(""), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 1, 0, 'L', 1);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(32.5, 5, utf8_decode(""), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(32.5, 5, "", 0, '.', ','), 1, 0, 'R', 1);
        $pdf->Ln();

    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }



}