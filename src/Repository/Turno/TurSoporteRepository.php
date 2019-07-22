<?php

namespace App\Repository\Turno;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporte::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TurSoporte::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arSoporte TurSoporte
     */
    public function cargarContratos($arSoporte) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);

        $dateFechaDesde = $arSoporte->getFechaDesde();
        $dateFechaHasta = $arSoporte->getFechaHasta();
        $intDiaInicial = $dateFechaDesde->format('j');
        $intDiaFinal = $dateFechaHasta->format('j');
        //Genera los recursos del soporte pago
        $arEmpleadosResumen = $em->getRepository(TurProgramacion::class)->listaEmpleadoSoporte($arSoporte->getFechaDesde()->format('Y'), $arSoporte->getFechaDesde()->format('m'));

        $contratoTerminado = $arSoporte->getContratoTerminado();
        foreach ($arEmpleadosResumen as $arEmpleadoResumen) {
            $arContratos = $em->getRepository(RhuContrato::class)->soportePago($arEmpleadoResumen['codigoEmpleadoFk'], $dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $contratoTerminado, $arSoporte->getCodigoGrupoFk());
            $numeroContratos = count($arContratos);
            foreach ($arContratos as $arContrato) {
                if ($arContrato['estadoTerminado'] == 0 || $arContrato['fechaHasta'] >= $arSoporte->getFechaDesde()) {
                    if ($arContrato['fechaDesde'] <= $arSoporte->getFechaHasta()) {
                        $arSoporteContrato = new TurSoporteContrato();
                        $arSoporteContrato->setSoporteRel($arSoporte);
                        $arSoporteContrato->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arEmpleadoResumen['codigoEmpleadoFk']));
                        $arSoporteContrato->setContratoRel($em->getReference(RhuContrato::class, $arContrato['codigoContratoPk']));

                        $arSoporteContrato->setVrSalario($arContrato['vrSalario']);
                        //$arSoportePago->setAuxilioTransporte($arContrato->getAuxilioTransporte());
                        //$arSoportePago->setVrDevengadoPactado($arContrato->getVrDevengadoPactado());
                        $arSoporteContrato->setAnio($arSoporte->getFechaDesde()->format('Y'));
                        $arSoporteContrato->setMes($arSoporte->getFechaDesde()->format('m'));

                        /*$arSoporteContrato->setSecuencia($arContrato->getSecuencia());
                        if ($arRecurso->getCodigoClienteFk()) {
                            $arSoporteContrato->setPagarTransporteCompletoRecurso($arRecurso->getClienteRel()->getPagarTransporteCompletoRecurso());
                            $arSoporteContrato->setPagar31Completo($arRecurso->getClienteRel()->getPagar31CompletoRecurso());
                        }*/

                        /*if ($arCentroCosto->getCompensacionRecurso()) {
                            $arSoporteContrato->setCompensacionTipoRel($arRecurso->getCompensacionTipoRel());
                            $arSoporteContrato->setSalarioFijoRel($arRecurso->getSalarioFijoRel());
                        } else {
                            $arSoporteContrato->setCompensacionTipoRel($arCentroCosto->getCompensacionTipoRel());
                        }*/
                        if ($numeroContratos > 1) {
                            $arSoporte->setContratoMultiple(1);
                            if ($arContrato->getFechaDesde() > $arSoporte->getFechaDesde()) {
                                $arSoporteContrato->setFechaDesde($arContrato['fechaDesde']);
                            } else {
                                $arSoporteContrato->setFechaDesde($arSoporte->getFechaDesde());
                            }

                            if ($arContrato->getIndefinido() == 1) {
                                $arSoporteContrato->setFechaHasta($arSoporte->getFechaHasta());
                            } else {
                                if ($arContrato->getFechaHasta() < $arSoporte->getFechaHasta()) {
                                    $arSoporteContrato->setFechaHasta($arContrato['fechaHasta']);
                                } else {
                                    $arSoporteContrato->setFechaHasta($arSoporte->getFechaHasta());
                                }
                            }
                        } else {
                            $arSoporteContrato->setFechaDesde($arSoporte->getFechaDesde());
                            $arSoporteContrato->setFechaHasta($arSoporte->getFechaHasta());
                        }
                        /*if ($arContrato->getTurnoFijoOrdinario()) {
                            $arSoportePago->setTurnoFijo(1);
                        }*/
                        $em->persist($arSoporteContrato);
                    }
                }
            }
        }

        $em->flush();

    }

    /**
     * @param $arSoporte TurSoporte
     */
    public function autorizar($arSoporte) {
        $em = $this->getEntityManager();
        //$arTurConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);

        $arrFestivos = $em->getRepository(TurFestivo::class)->fecha($arSoporte->getFechaDesde()->format('Y-m-') . '01', $arSoporte->getFechaHasta()->format('Y-m-t'));
        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->listaHoras($arSoporte->getCodigoSoportePk());
        foreach ($arSoportesContratos as $arSoportesContrato) {
            $em->getRepository(TurSoporteContrato::class)->generarHoras($arSoporte, $arSoportesContrato, $arrFestivos);
        }
        $em->flush();

        //Genera soporte pago "programacion"
        /*foreach ($arSoportesPago as $arSoportePago) {
            $em->getRepository(TurSoporteContrato::class)->generarProgramacion($arSoportePago, $arSoportePagoPeriodo->getFechaDesde()->format('Y'), $arSoportePagoPeriodo->getFechaDesde()->format('m'));
        }
        $em->flush();*/

        //$arSoporte->setEstadoGenerado(1);
        //$arSoportePagoPeriodo->setFechaGenerado(new \DateTime('now'));
        //$em->persist($arSoporte);
        //$em->flush();

        $em->getRepository(TurSoporte::class)->resumen($arSoporte);

        //$em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->analizarInconsistencias($codigoSoportePagoPeriodo);
        //$em->flush();
        //$em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);
        /*if (!$arSoportePagoPeriodo->getHorasRecargoAgrupadas()) {
            $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->desagregarHoras(null, $codigoSoportePagoPeriodo);
        }*/
    }

    public function resumen($arSoporte) {
        $em = $this->getEntityManager();
        $intDias = $arSoporte->getFechaDesde()->diff($arSoporte->getFechaHasta());
        $diasRealesPeriodo = $intDias->format('%a') + 1;

        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->findBy(array('codigoSoporteFk' => $arSoporte->getCodigoSoporteFk()));
        foreach ($arSoportesContratos as $arSoporteContrato) {
            $dql = "SELECT sh.codigoSoporteHoraPk, "
                . "SUM(sh.descanso) as descanso, "
                . "SUM(sh.novedad) as novedad, "
                . "SUM(sh.incapacidad) as incapacidad, "
                . "SUM(sh.licencia) as licencia, "
                . "SUM(sh.licenciaNoRemunerada) as licenciaNoRemunerada, "
                . "SUM(sh.vacacion) as vacacion, "
                . "SUM(sh.ingreso) as ingreso, "
                . "SUM(sh.retiro) as retiro, "
                . "SUM(sh.induccion) as induccion, "
                . "SUM(sh.ausentismo) as ausentismo, "
                . "SUM(sh.dias) as dias, "
                . "SUM(sh.horasDescanso) as horasDescanso, "
                . "SUM(sh.horasNovedad) as horasNovedad, "
                . "SUM(sh.horasDiurnas) as horasDiurnas, "
                . "SUM(sh.horasNocturnas) as horasNocturnas, "
                . "SUM(sh.horasFestivasDiurnas) as horasFestivasDiurnas, "
                . "SUM(sh.horasFestivasNocturnas) as horasFestivasNocturnas, "
                . "SUM(sh.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(sh.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(sh.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(sh.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                . "SUM(sh.horasRecargoNocturno) as horasRecargoNocturno, "
                . "SUM(sh.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                . "SUM(sh.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno "
                . "FROM BrasaTurnoBundle:TurSoportePagoDetalle sh "
                . "WHERE sh.codigoSoporteContratoFk =  " . $arSoporteContrato->getCodigoSoporteContratoPk() . " "
                . "GROUP BY sh.codigoEmpleadoFk";
            $query = $em->createQuery($dql);
/*
            $arrayResultado = $query->getResult();
            for ($i = 0; $i < count($arrayResultado); $i++) {
                if ($arSoportePagoPeriodo->getDiasAdicionales() > 0 && $arSoportePago->getContratoMultiple() == 0) {
                    $novedades = $arrayResultado[$i]['incapacidad'] + $arrayResultado[$i]['incapacidadNoLegalizada'] + $arrayResultado[$i]['licencia'] + $arrayResultado[$i]['licenciaNoRemunerada'] + $arrayResultado[$i]['ausentismo'];
                    if ($arrayResultado[$i]['retiro'] <= 0 && $novedades < $diasRealesPeriodo) {
                        $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                        $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;
                    }

                }
                $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];

                if ($arSoportePagoPeriodoActualizar->getDescansoFestivoFijo()) {
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getFestivos() * 8;
                    $arrayResultado[$i]['descanso'] += $arSoportePagoPeriodoActualizar->getFestivos();
                }
                if ($arrayResultado[$i]['dias'] > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $arrayResultado[$i]['dias'] = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }
                //Quite esta validacion porque en estelar cuando tenia induccion le pagaba mas dias
                //$diasTransporte = $arrayResultado[$i]['dias'] + $arrayResultado[$i]['induccion'];
                $diasTransporte = $arrayResultado[$i]['dias'];
                if ($diasTransporte > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $diasTransporte = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }
                //Adiciones mes de febrero
                $arrayResultado[$i]['horasAdicionalesFebrero'] = 0;
                if ($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFebrero() > 0) {
                    //Ajustar las horas de los turnos fijos
                    if ($arSoportePago->getTurnoFijo()) {
                        if ($arSoportePago->getNovedad() == 0) {
                            $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getDiasAdicionalesFebrero() * 8;
                            $diasTransporte += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFebrero();
                        }
                    } else {//Ajustar los dias de transporte
                        if ($arSoportePago->getSoportePagoPeriodoRel()->getDiasPeriodo() == $diasTransporte) {
                            $diasTransporte += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFebrero();
                            $arrayResultado[$i]['horasAdicionalesFebrero'] += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFebrero() * 8;
                        }
                    }


                }
                if ($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo() > 0) {
                    $diasTransporte += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo();
                    $arrayResultado[$i]['dias'] += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo();
                }
                if ($diasTransporte == $arSoportePagoPeriodo->getDiasPeriodo()) {
                    $diasTransporte -= ($arrayResultado[$i]['incapacidad'] + $arrayResultado[$i]['ausentismo'] + $arrayResultado[$i]['vacacion'] + $arrayResultado[$i]['ingreso'] + $arrayResultado[$i]['retiro'] + $arrayResultado[$i]['licenciaNoRemunerada'] + $arrayResultado[$i]['licencia'] + $arrayResultado[$i]['induccion']);
                    if ($diasTransporte < 0) {
                        $diasTransporte = 0;
                    }
                }

                $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
                $arSoportePagoAct->setDias($arrayResultado[$i]['dias']);
                $arSoportePagoAct->setDiasTransporte($diasTransporte);
                $arSoportePagoAct->setDiasTransporteReal($diasTransporte);
                $arSoportePagoAct->setDescanso($arrayResultado[$i]['descanso']);
                $arSoportePagoAct->setNovedad($arrayResultado[$i]['novedad']);
                $arSoportePagoAct->setIncapacidad($arrayResultado[$i]['incapacidad']);
                $arSoportePagoAct->setIncapacidadNoLegalizada($arrayResultado[$i]['incapacidadNoLegalizada']);
                $arSoportePagoAct->setLicencia($arrayResultado[$i]['licencia']);
                $arSoportePagoAct->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
                $arSoportePagoAct->setVacacion($arrayResultado[$i]['vacacion']);
                $arSoportePagoAct->setInduccion($arrayResultado[$i]['induccion']);
                $arSoportePagoAct->setIngreso($arrayResultado[$i]['ingreso']);
                $arSoportePagoAct->setRetiro($arrayResultado[$i]['retiro']);
                $arSoportePagoAct->setAusentismo($arrayResultado[$i]['ausentismo']);
                $arSoportePagoAct->setHorasPago($intHorasPago);
                $arSoportePagoAct->setHoras($intHoras);
                $arSoportePagoAct->setHorasAdicionales($arrayResultado[$i]['horasAdicionalesFebrero']);
                $arSoportePagoAct->setHorasDescanso($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasNovedad($arrayResultado[$i]['horasNovedad']);
                $arSoportePagoAct->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
                $arSoportePagoAct->setHorasRecargoNocturno($arrayResultado[$i]['horasRecargoNocturno']);
                $arSoportePagoAct->setHorasRecargoFestivoDiurno($arrayResultado[$i]['horasRecargoFestivoDiurno']);
                $arSoportePagoAct->setHorasRecargoFestivoNocturno($arrayResultado[$i]['horasRecargoFestivoNocturno']);
                $arSoportePagoAct->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
                $arSoportePagoAct->setHorasRecargoNocturnoReales($arrayResultado[$i]['horasRecargoNocturno']);
                $arSoportePagoAct->setHorasRecargoFestivoDiurnoReales($arrayResultado[$i]['horasRecargoFestivoDiurno']);
                $arSoportePagoAct->setHorasRecargoFestivoNocturnoReales($arrayResultado[$i]['horasRecargoFestivoNocturno']);
                $em->persist($arSoportePagoAct);
            }
            */
        }
        //$arSoportePagoPeriodoActualizar->setRecursos(count($arSoportesPago));
        //$em->persist($arSoportePagoPeriodoActualizar);
        //$em->flush();
    }
}
