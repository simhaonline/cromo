<?php


namespace App\Formato\RecursoHumano;


use App\Entity\Inventario\InvMovimiento;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use function Complex\ln;

class Vacaciones extends \FPDF
{
    public static $em;
    public static $codigoVacaciones;

    /**
     * @param $em ObjectManager
     * @param $codigoVacaciones integer
     */
    public function Generar($em, $codigoVacaciones)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoVacaciones = $codigoVacaciones;
        $pdf = new Vacaciones();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $codigoVacaciones);
        $pdf->Output("Vacaciones.pdf", 'D');
    }

    public function Header()
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo


        Estandares::generarEncabezado($this, 'PAGO DE VACACIONES', self::$em);

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

    public function Body($pdf, $codigoVacaciones)
    {
        $em = BaseDatos::getEm();
        $pdf->SetXY(74, 34);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(20, 4, "", 0, 0, 'L', 1);
        $pdf->SetXY(10, 50);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);

        $arVacaciones = $em->getRepository(RhuVacacion::class)->find($codigoVacaciones);
        $arEmpleado = $arVacaciones->getEmpleadoRel();
        //tabla vacaciones
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, "ID", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arVacaciones->getCodigoVacacionPk()), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("FECHA"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arVacaciones->getFecha(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, "NUMERO", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arVacaciones->getNumero()), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("CONTRATO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getCodigoContratoFk(), 1, 0, 'L', 1);
        $pdf->ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, "EMPLEADO", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arEmpleado->getNombreCorto()), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("IDENTIFICACIÓN"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("PERIODO DESDE"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arVacaciones->getFechaDesdePeriodo(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("GRUPO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arVacaciones->getGrupoRel() != null ? $arVacaciones->getGrupoRel()->getNombre() : ""), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("PERIODO HASTA"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arVacaciones->getFechahastaPeriodo(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DÍAS VACACIONES"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getDias(), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DISFRUTE DESDE"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arVacaciones->getFechaDesdeDisfrute(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DISFRUTE HASTA"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getFechaHastaDisfrute()->format('Y-m-d'), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DIAS DISFRUTE"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getDiasDisfrutados(), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DIAS PAGADOS"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getDiasPagados(), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("TOTAL DISFRUTADAS"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, number_format($arVacaciones->getVrDisfrute()), 1, 0, 'R', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("TOTAL PAGADAS"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, number_format($arVacaciones->getVrDinero()), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("BANCO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, utf8_decode($arVacaciones->getEmpleadoRel()->getBancoRel() ? $arVacaciones->getEmpleadoRel()->getBancoRel()->getNombre() : ''), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("SALARIO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, number_format($arVacaciones->getVrSalarioActual(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("CUENTA"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arEmpleado->getCuenta(), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("SALARIO VACACIONES"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, number_format($arVacaciones->getVrSalarioPromedio(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("DÍAS AUSENTISMO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getDiasAusentismo(), 1, 0, 'R', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("INICIO LABORES"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, date_format($arVacaciones->getFechaInicioLabor(), "Y/m/d"), 1, 0, 'R', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("TIEMPO TIEMPO:"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, $arVacaciones->getContratoRel()->getTiempoRel()->getNombre(), 1, 0, 'L', 1);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode(""), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(48.75, 5, "", 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(48.75, 5, utf8_decode("COMENTARIO:"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(146.25, 5, substr(utf8_encode($arVacaciones->getComentarios()), 0, 135), 1, 0, 'L', 1);


        $posicionX = 128;
        $posicionY = 128;
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetFillColor(217, 217, 217);
        $pdf->SetXY($posicionX, $posicionY);
        $pdf->Cell(48.75, 5, "TOTAL VACACIONES:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 6);
        $pdf->Cell(48.75, 5, "VR. SALUD:", 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 12);
        $pdf->Cell(48.75, 5, utf8_decode("VR. PENSIÓN:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 18);
        $pdf->Cell(48.75, 5, utf8_decode("FONDO SOLIDARIDAD:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 24);
        $pdf->Cell(48.75, 5, utf8_decode("VR. DEDUCCIONES:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 30);
        $pdf->Cell(48.75, 5, utf8_decode("TOTAL DEDUCCIONES:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 36);
        $pdf->Cell(48.75, 5, utf8_decode("TOTAL BONIFICACIÓN:"), 1, 0, 'L', 1);
        $pdf->SetXY($posicionX, $posicionY + 42);
        $pdf->Cell(48.75, 5, "TOTAL A PAGAR:", 1, 0, 'L', 1);
        $posicionX = 160;
        $posicionX = 160;
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetXY($posicionX, $posicionY);
        $pdf->Cell(40, 5, number_format($arVacaciones->getVrBruto(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 6);
        $pdf->Cell(40, 5, "(" . number_format($arVacaciones->getVrSalud(), 0, '.', ',') . ")", 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 12);
        $pdf->Cell(40, 5, "(" . number_format($arVacaciones->getVrPension(), 0, '.', ',') . ")", 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 18);
        $pdf->Cell(40, 5, "(" . number_format($arVacaciones->getVrFondoSolidaridad(), 0, '.', ',') . ")", 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 24);
        $pdf->Cell(40, 5, "(" . number_format($arVacaciones->getVrDeduccion(), 0, '.', ',') . ")", 1, 0, 'R', 1);
        $floTotalDeducciones = $arVacaciones->getVrSalud() + $arVacaciones->getVrPension() + $arVacaciones->getVrDeduccion() + $arVacaciones->getVrFondoSolidaridad();
        $pdf->SetXY($posicionX, $posicionY + 30);
        $pdf->Cell(40, 5, number_format($floTotalDeducciones, 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 36);
        $pdf->Cell(40, 5, number_format($arVacaciones->getVrBonificacion(), 0, '.', ','), 1, 0, 'R', 1);
        $pdf->SetXY($posicionX, $posicionY + 42);
        $pdf->Cell(40, 5, number_format($arVacaciones->getVrTotal(), 0, '.', ','), 1, 0, 'R', 1);
        //ADICIONALES
        $arVacacionAdicionales = self::$em->getRepository(RhuVacacionAdicional::class)->findBy(array('codigoVacacionFk' => $arVacaciones->getCodigoVacacionPk()));
        if ($arVacacionAdicionales) {
            $intX = 10;
            $intY = 190;
            $pdf->SetXY($intX, $intY);
            $pdf->SetFillColor(217, 217, 217);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(185, 5, utf8_decode("ADICIONALES"), 1, 0, 'C', 1);
            $intX = 10;
            $incremento = 4;
            $pdf->SetXY($intX, $intY += $incremento);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode("CREDITO"), 1, 0, 'C', 1);
            $pdf->Cell(20, 4, utf8_decode("CODIGO"), 1, 0, 'L', 1);
            $pdf->Cell(80, 4, utf8_decode("CONCEPTO"), 1, 0, 'L', 1);
            $pdf->Cell(32, 4, utf8_decode("BONIFICACION"), 1, 0, 'R', 1);
            $pdf->Cell(33, 4, utf8_decode("DEDUCCION"), 1, 0, 'R', 1);
            foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                $intY = $intY + $incremento;
                $pdf->SetXY($intX, $intY);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(20, 4, $arVacacionAdicional->getCodigoCreditoFk(), 1, 0, 'L', 1);
                $pdf->Cell(20, 4, $arVacacionAdicional->getCodigoConceptoFk(), 1, 0, 'L', 1);
                $pdf->Cell(80, 4, utf8_decode($arVacacionAdicional->getConceptoRel()->getNombre()), 1, 0, 'L', 1);
                $pdf->Cell(32, 4, number_format($arVacacionAdicional->getVrBonificacion(), 0, '.', ','), 1, 0, 'R', 1);
                $pdf->Cell(33, 4, number_format($arVacacionAdicional->getVrDeduccion(), 0, '.', ','), 1, 0, 'R', 1);
                //$incremento = $incremento + 4;
            }
        }
    }

    public function Footer()
    {
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 230, "FIRMA: _____________________________________________");
        $this->Text(10, 237, "C.C.:     ______________________ de ____________________");
        $this->Text(10, 260, "ELABORADO POR: __________________________________ ");
        $this->Text(105, 260, "REVISADO POR: ___________________________________ ");
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}