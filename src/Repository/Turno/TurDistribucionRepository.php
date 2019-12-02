<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurDistribucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurDistribucion::class);
    }

    public function generar($anio, $mes)
    {
        $em = $this->getEntityManager();
        $dateFechaDesde = date_create($anio . "/" . $mes . "/01");
        $arFestivos = $em->getRepository(TurFestivo::class)->festivos($dateFechaDesde->format('Y-m-') . '01', $dateFechaDesde->format('Y-m-t'));
        $dql = "SELECT p.codigoEmpleadoFk "
            . "FROM App\Entity\Turno\TurProgramacion p "
            . "WHERE p.anio =  {$anio} AND p.mes =  {$mes} AND p.codigoEmpleadoFk <> ''"
            . "GROUP BY p.codigoEmpleadoFk";
        $query = $em->createQuery($dql);
        $arEmpleados = $query->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            //$arRecursoAct = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arRecurso['codigoRecursoFk']);
            $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array('codigoEmpleadoFk' => $arEmpleado['codigoEmpleadoFk'], "anio" => $anio, "mes" => $mes));
            if ($arProgramaciones) {
                //$arRecursoAct = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arRecurso['codigoRecursoFk']);
                for ($i = 1; $i <= 31; $i++) {
                    $strFecha = $anio . "/" . $mes . "/" . $i;
                    $dateFecha = date_create($strFecha);
                    $nuevafecha = strtotime('+1 day', strtotime($strFecha));
                    $dateFecha2 = date('Y/m/j', $nuevafecha);
                    $dateFecha2 = date_create($dateFecha2);
                    $boolFestivo = $this->festivo($arFestivos, $dateFecha);
                    $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
                    $arrTurnos = array();
                    foreach ($arProgramaciones as $arProgramacion) {
                        $turno = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
                        if ($turno != "") {
                            $arTurno = $em->getRepository(TurTurno::class)->find($turno);
                            if ($arTurno) {
                                $arrTurnos[] = array(
                                    'horaDesde' => $arTurno->getHoraDesde(),
                                    'horaHasta' => $arTurno->getHoraHasta(),
                                    'turno' => $arTurno->getCodigoTurnoPk(),
                                    'codigoPedidoDetalle' => $arProgramacion->getCodigoPedidoDetalleFk());
                            }
                        }
                    }
                    asort($arrTurnos);
                    $arrHoras = null;
                    $horasIniciales = 0;

                    foreach ($arrTurnos as $arrTurno) {
                        $strTurno = $arrTurno['turno'];
                        if ($strTurno) {
                            $horario = $arrTurno['horaDesde']->format('H:i') . " A " . $arrTurno['horaHasta']->format('H:i');
                            $arrHoras = $this->getHoras($strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2, $horasIniciales);
                            $horasIniciales += $arrHoras['horas'];
                            $arDistribucion = new TurDistribucion();
                            $arDistribucion->setCodigoEmpleadoFk($arEmpleado['codigoEmpleadoFk']);
                            $arDistribucion->setCodigoTurnoFk($strTurno);
                            $arDistribucion->setCodigoPedidoDetalleFk($arrTurno['codigoPedidoDetalle']);
                            $arDistribucion->setFecha($dateFecha);
                            $arDistribucion->setAnio($anio);
                            $arDistribucion->setMes($mes);
                            $arDistribucion->setHorario($horario);
                            $arDistribucion->setHoras($arrHoras['horas']);
                            $arDistribucion->setHorasDescanso($arrHoras['horasDescanso']);
                            $arDistribucion->setHorasDiurnas($arrHoras['horasDiurnas']);
                            $arDistribucion->setHorasNocturnas($arrHoras['horasNocturnas']);
                            $arDistribucion->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
                            $arDistribucion->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);
                            $arDistribucion->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
                            $arDistribucion->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
                            $arDistribucion->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
                            $arDistribucion->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
                            $arDistribucion->setHorasRecargoNocturno($arrHoras['horasRecargoNocturno']);
                            $arDistribucion->setHorasRecargoFestivoDiurno($arrHoras['horasRecargoFestivoDiurno']);
                            $arDistribucion->setHorasRecargoFestivoNocturno($arrHoras['horasRecargoFestivoNocturno']);
                            $em->persist($arDistribucion);
                        }
                    }
                }
            }

        }
        $em->flush();
    }

    public function getHoras($codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2, $horasIniciales)
    {
        $em = $this->getEntityManager();
        $arTurno = $em->getRepository(TurTurno::class)->find($codigoTurno);

        $intDias = 0;
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100) / 60) / 100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100) / 60) / 100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if ($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }
        if ($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if ($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }
        $arrHoras1 = null;
        $arrTotalHoras = null;
        if (($intHoraInicio + $intMinutoInicio) <= $intHoraFinal && $arTurno->getCompleto() == 0) {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $horasIniciales, $arTurno->getNovedad(), $arTurno->getDescanso(), $dateFecha);
            $horasTotales = $arrHoras['horas'] + $arrHoras1['horas'];
            $arrTotalHoras = $arrHoras;
        } else {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, $horasIniciales, $arTurno->getNovedad(), $arTurno->getDescanso(), $dateFecha);
            $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso(), $dateFecha);
            $arrTotalHoras = $this->getSumarHoras($arrHoras, $arrHoras1);
            $horasTotales = $arrHoras1['horas'];
        }

        return $arrTotalHoras;
    }

    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0, $dateFecha)
    {
        if ($boolNovedad == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if ($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if ($intHorasJornada >= 1) {
                    $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                    $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                    $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                } else {
                    $intHorasExtrasNocturnas = $intHorasNocturnas;
                    $intHorasNocturnas = 0;
                }
            }
            $fechaDecreto = date_create('2017-07-19');
            if ($dateFecha >= $fechaDecreto) {
                $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 21);
            } else {
                $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);
            }

            $intHorasExtrasDiurnas = 0;
            $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
            if ($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);
                if ($intHorasJornada > 1) {
                    $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                    $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                    $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                } else {
                    $intHorasExtrasDiurnas = $intHorasDiurnas;
                    $intHorasDiurnas = 0;
                }
            }
            if ($dateFecha >= $fechaDecreto) {
                $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 21, 24);
            } else {
                $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24);
            }

            $intHorasExtrasNocturnasNoche = 0;
            $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;
            if ($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);
                if ($intHorasJornada > 1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
            $IntHorasRecargoNocturno = 0;
            $IntHorasRecargoFestivoDiurno = 0;
            $IntHorasRecargoFestivoNocturno = 0;
            $intHorasNocturnas += $intHorasNocturnasNoche;
            $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;

            $intHorasFestivasDiurnas = 0;
            $intHorasFestivasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = 0;
            if ($boolFestivo == 1) {
                $intHorasFestivasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
                $intHorasFestivasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
                $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
                $intHorasExtrasDiurnas = 0;
                $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
                $intHorasExtrasNocturnas = 0;
            }
            $intTotalHoras = $intHorasDiurnas + $intHorasNocturnas + $intHorasExtrasDiurnas + $intHorasExtrasNocturnas + $intHorasFestivasDiurnas + $intHorasFestivasNocturnas + $intHorasExtrasFestivasDiurnas + $intHorasExtrasFestivasNocturnas;
            if ($boolDescanso == 1) {
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0,
                    'horasNocturnas' => 0,
                    'horasExtrasDiurnas' => 0,
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0,
                    'horasFestivasNocturnas' => 0,
                    'horasExtrasFestivasDiurnas' => 0,
                    'horasExtrasFestivasNocturnas' => 0,
                    'horasRecargoNocturno' => 0,
                    'horasRecargoFestivoDiurno' => 0,
                    'horasRecargoFestivoNocturno' => 0,
                    'horas' => $intTotalHoras);
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas,
                    'horasNocturnas' => $intHorasNocturnas,
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas,
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas,
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas,
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas,
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horasRecargoNocturno' => $IntHorasRecargoNocturno,
                    'horasRecargoFestivoDiurno' => $IntHorasRecargoFestivoDiurno,
                    'horasRecargoFestivoNocturno' => $IntHorasRecargoFestivoNocturno,
                    'horas' => $intTotalHoras);
            }
        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0,
                'horasNocturnas' => 0,
                'horasExtrasDiurnas' => 0,
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0,
                'horasFestivasNocturnas' => 0,
                'horasExtrasFestivasDiurnas' => 0,
                'horasExtrasFestivasNocturnas' => 0,
                'horasRecargoNocturno' => 0,
                'horasRecargoFestivoDiurno' => 0,
                'horasRecargoFestivoNocturno' => 0,
                'horas' => 0);
        }

        return $arrHoras;
    }

    private function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal)
    {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if ($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if ($intFinal > $intParametroFinal) {
            if ($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if ($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }

    public function festivo($arFestivos, $dateFecha)
    {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if ($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    public function getSumarHoras($arrHoras1, $arrHoras2)
    {
        $arrTotal = array(
            'horasDescanso' => $arrHoras1['horasDescanso'] + $arrHoras2['horasDescanso'],
            'horasNovedad' => $arrHoras1['horasNovedad'] + $arrHoras2['horasNovedad'],
            'horasDiurnas' => $arrHoras1['horasDiurnas'] + $arrHoras2['horasDiurnas'],
            'horasNocturnas' => $arrHoras1['horasNocturnas'] + $arrHoras2['horasNocturnas'],
            'horasExtrasDiurnas' => $arrHoras1['horasExtrasDiurnas'] + $arrHoras2['horasExtrasDiurnas'],
            'horasExtrasNocturnas' => $arrHoras1['horasExtrasNocturnas'] + $arrHoras2['horasExtrasNocturnas'],
            'horasFestivasDiurnas' => $arrHoras1['horasFestivasDiurnas'] + $arrHoras2['horasFestivasDiurnas'],
            'horasFestivasNocturnas' => $arrHoras1['horasFestivasNocturnas'] + $arrHoras2['horasFestivasNocturnas'],
            'horasExtrasFestivasDiurnas' => $arrHoras1['horasExtrasFestivasDiurnas'] + $arrHoras2['horasExtrasFestivasDiurnas'],
            'horasExtrasFestivasNocturnas' => $arrHoras1['horasExtrasFestivasNocturnas'] + $arrHoras2['horasExtrasFestivasNocturnas'],
            'horasRecargoNocturno' => $arrHoras1['horasRecargoNocturno'] + $arrHoras2['horasRecargoNocturno'],
            'horasRecargoFestivoDiurno' => $arrHoras1['horasRecargoFestivoDiurno'] + $arrHoras2['horasRecargoFestivoDiurno'],
            'horasRecargoFestivoNocturno' => $arrHoras1['horasRecargoFestivoNocturno'] + $arrHoras2['horasRecargoFestivoNocturno'],
            'horas' => $arrHoras1['horas'] + $arrHoras2['horas']);
        return $arrTotal;
    }

    /**
     *
     * @param type $codigoRecurso
     * @param type $strAnio
     * @param type $strMes
     */
    public function listaTiempoLaboradoDql($codigoRecurso = "", $strAnio = "", $strMes = "")
    {
        $em = $this->getEntityManager();
        $dql = $em->createQueryBuilder()->from("BrasaTurnoBundle:TurDistribucionDetalle","dd")
            ->join("dd.recursoRel","r")
            ->select("dd")
            ->where("dd.codigoDistribucionDetallePk <> 0");
        if ($codigoRecurso != "") {
            $dql->andWhere("dd.codigoRecursoFk = {$codigoRecurso} ")
                ->orWhere("r.numeroIdentificacion = '{$codigoRecurso}'");
        }
        if ($strAnio != "") {
            $dql->andWhere("dd.anio = '{$strAnio}'");
        }
        if ($strMes != "") {
            $dql->andWhere("dd.mes = '{$strMes}'");
        }
        return $dql->getDQL();
    }


}
