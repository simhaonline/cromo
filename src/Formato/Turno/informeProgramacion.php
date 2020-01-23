<?php


namespace App\Formato\Turno;


use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Estandares;

class informeProgramacion extends \FPDF
{
    public static $em;
    public static $arprogramaciones;

    public function Generar($em, $programaciones)
    {
        ob_clean();
        self::$em = $em;
        self::$arprogramaciones = $programaciones;
        $pdf = new informeProgramacion('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Programacion.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        Estandares::generarEncabezado($this, 'PROGRAMACION DE TURNOS', self::$em);
        $this->Ln(2);

    }

    public function Body($pdf)
    {
        $arrTurnos = [];
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $numero = 0;
        $codigoPuesto = 0;
        foreach (self::$arprogramaciones as $arProgramacion) {
            $pdf->SetFont('Arial', '', 7);
            $numero++;
            if ($codigoPuesto != $arProgramacion['codigoPuestoFk']) {
                if ($arProgramacion['codigoPuestoFk']) {
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(20, 4, "CLIENTE:", 1, 0, 'L');
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(265, 4, $arProgramacion['cliente'], 1, 0, 'L');
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(20, 4, "COD PUESTO", 1, 0, 'L');
                    $pdf->Cell(140, 4, "PUESTO", 1, 0, 'L');
                    $pdf->Cell(30, 4, "ZONA", 1, 0, 'L');
                    $pdf->Cell(95, 4, "DIRECCION", 1, 0, 'L');
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(20, 4, utf8_decode($arProgramacion['codigoPuestoFk']), 1, 0, 'L');
                    $pdf->Cell(140, 4, utf8_decode(substr($arProgramacion['puestoNombre'] . " (" . $arProgramacion['itemNombre'] . ")", 0, 103)), 1, 0, 'L');
                    $zona = "";
                    if ($arProgramacion['codigoZona']) {
                        $zona = $arProgramacion['zonaNombre'];
                    }
                    $pdf->Cell(30, 4, utf8_decode($zona), 1, 0, 'L');
                    $pdf->Cell(95, 4, utf8_decode($arProgramacion['puestoDireccion']), 1, 0, 'L');
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->EncabezadoDetalles($arProgramacion);
                }
            }
            $pdf->Cell(20, 4, $arProgramacion['codigoPuestoFk'], 1, 0, 'L');
            if ($arProgramacion['codigoPuestoFk']) {
                $pdf->Cell(20, 4, substr($arProgramacion['numeroIdentificacion'], 0, 30), 1, 0, 'L');
                $pdf->Cell(50, 4, utf8_decode(substr($arProgramacion['empleadoNombreCorto'], 0, 30)), 1, 0, 'L');
            } else {
                $pdf->Cell(100, 4, "", 1, 0, 'L');
            }

            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(10, 4, $arProgramacion['anio'], 1, 0, 'C', 1);
            $pdf->Cell(8, 4, str_pad($arProgramacion['mes'], 2, '0', STR_PAD_LEFT), 1, 0, 'C', 1);

            for ($i = 1; $i <= 31; $i++) {
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetFillColor(255, 255, 255);
                $turno =  $arProgramacion['dia'.$i];
                if ($turno) {
                    $arTurno = self::$em->getRepository(TurTurno::class)->find($turno);
                    if ($arTurno->getDescanso()) {
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetFillColor(236, 236, 236);
                    }
                }
                $pdf->Cell(5, 4, $turno, 1, 0, 'L', 1);
                $pdf->SetFillColor(255, 255, 255);
            }
            $pdf->Cell(22, 4, "", 1, 0, 'L', 1);
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 30);
            if ($codigoPuesto != $arProgramacion['codigoPuestoFk']) {
                if ($arProgramacion['codigoPuestoFk']) {
                    $codigoPuesto = $arProgramacion['codigoPuestoFk'];
                }
            }
        }

        //Se imprimen los turnos
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "CODIGO", 1, 0, 'L');
        $pdf->Cell(95, 4, "NOMBRE", 1, 0, 'L');
        $pdf->Cell(15, 4, "H. DESDE", 1, 0, 'L');
        $pdf->Cell(15, 4, "H. HASTA", 1, 0, 'L');
        $pdf->Ln();
        $arrTurnos = $this->turnos(self::$arprogramaciones);
        foreach ($arrTurnos as $arrTurno) {
            $arTurno = self::$em->getRepository(TurTurno::class)->find($arrTurno['turno']);
            if ($arTurno) {
                //if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(20, 4, $arrTurno['turno'], 1, 0, 'L');
                $pdf->Cell(95, 4, $arTurno->getNombre(), 1, 0, 'L');
                $pdf->Cell(15, 4, $arTurno->getHoraDesde()->format('H:s'), 1, 0, 'L');
                $pdf->Cell(15, 4, $arTurno->getHoraHasta()->format('H:s'), 1, 0, 'L');
                $pdf->Ln();
                //}
            }
        }
    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->Text(10, 197, "_________________________________");
        $this->Text(10, 200, "    FIRMA SUPERVISOR DE ZONA");
        $this->Text(100, 197, "_________________________________");
        $this->Text(100, 200, "    FIRMA JEFE DE PROGRAMACION");
        $this->Text(278, 208, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        $this->Text(238, 200, utf8_decode("esta programación se imprimió"));
        $dateFecha = new \DateTime('now');
        $this->Text(250, 205, $dateFecha->format('d/m/Y H:i:s'));
    }

    private function turnos($arprogramaciones)
    {
        $arrTurnos = array();
        foreach ($arprogramaciones as $arProgramacion) {
            $arrTurnos[] = array('turno' => $arProgramacion['dia1']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia2']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia3']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia4']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia5']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia6']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia7']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia8']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia9']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia10']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia11']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia12']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia13']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia14']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia15']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia16']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia17']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia18']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia19']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia20']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia21']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia22']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia23']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia24']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia25']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia26']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia27']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia28']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia29']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia30']);
            $arrTurnos[] = array('turno' => $arProgramacion['dia31']);
            $i = 0;
        }
        $arrTurnosResumen = array();
        foreach ($arrTurnos as $arrTurno) {
            $boolExiste = FALSE;
            foreach ($arrTurnosResumen as $arrTurnoResumen) {
                if ($arrTurno['turno'] == $arrTurnoResumen['turno']) {
                    $boolExiste = TRUE;
                }
            }
            if ($boolExiste == FALSE) {
                if ($arrTurno['turno'] != "") {
                    $arrTurnosResumen[] = array('turno' => $arrTurno['turno']);
                }
            }
        }
        return $arrTurnosResumen;
    }

    public function EncabezadoDetalles($arProgramacion)
    {
        $dateRaw = "{$arProgramacion['anio']}-{$arProgramacion['mes']}-01";
        $fechaProgramacion = new \DateTime($dateRaw);
        $festivos = self::$em->getRepository(TurFestivo::class)->fecha($fechaProgramacion->format('Y-m-d'), $fechaProgramacion->format('Y-m-t'));
        $arrDiasSemana = FuncionesController::diasMes($fechaProgramacion, $festivos);
        $header = ['CEDULA' => 20, 'EMPLEADO' => 50, utf8_decode('AÑO') => 10, 'MES' => 8];
        $anchoCelda = 5;
        $dias = [];
        foreach ($arrDiasSemana as $arrDiaSemana) {
            $header[$arrDiaSemana["dia"] . strtoupper($arrDiaSemana["diaSemana"])] = $anchoCelda;
        }
        $header['FIRMA'] = 22;
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);
        $this->Ln();
        $izquierda = ['CEDULA', 'EMPLEADO'];
        $this->Cell(20, 4, "", 1, 0, 'L', 1);
        $diasEspanol = array(
            'Monday' => 'L',
            'Tuesday' => 'M',
            'Wednesday' => 'X',
            'Thursday' => 'J',
            'Friday' => 'V',
            'Saturday' => 'S',
            'Sunday' => 'D',
        );

        foreach ($header AS $titulo => $ancho) {
            $alineamiento = in_array($titulo, $izquierda) ? 'L' : 'C';
            $this->Cell($ancho, 4, $titulo, 1, 0, $alineamiento, 1);
        }

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

}