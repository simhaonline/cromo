<?php

namespace App\Repository\Turno;

use App\Entity\General\GenInconsistencia;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporte::class);
    }

    public function cargarSoporte()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporte::class, 's');
        $queryBuilder
            ->select('s.codigoSoportePk')
            ->addSelect('s.fechaDesde')
            ->addSelect('s.fechaHasta')
            ->addSelect('g.nombre as grupoNombre')
            ->addSelect('s.estadoAutorizado')
            ->addSelect('s.estadoAprobado')
            ->leftJoin('s.grupoRel', 'g');
        $arSoportes = $queryBuilder->getQuery()->getResult();
        return $arSoportes;
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

        $arrFestivos = $em->getRepository(TurFestivo::class)->fecha($arSoporte->getFechaDesde()->format('Y-m-') . '01', $arSoporte->getFechaHasta()->format('Y-m-t'));
        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->listaHoras($arSoporte->getCodigoSoportePk(), null);
        foreach ($arSoportesContratos as $arSoportesContrato) {
            $em->getRepository(TurSoporteContrato::class)->generarHoras($arSoporte, $arSoportesContrato, $arrFestivos);
        }
        $arSoporte->setEstadoAutorizado(1);
        $em->flush();

        $em->getRepository(TurSoporte::class)->resumen($arSoporte);

        //$em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->analizarInconsistencias($codigoSoportePagoPeriodo);
        //$em->flush();
        //$em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);
        /*if (!$arSoportePagoPeriodo->getHorasRecargoAgrupadas()) {
            $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->desagregarHoras(null, $codigoSoportePagoPeriodo);
        }*/
    }

    public function desAutorizar($arSoporte)
    {
        $em = $this->getEntityManager();
        if ($arSoporte->getEstadoAutorizado() == 1 && $arSoporte->getEstadoAprobado() == 0) {
            $arSoporte->setEstadoAutorizado(0);
            $em->persist($arSoporte);
            $q = $em->createQuery('delete from App\Entity\Turno\TurSoporteHora sh where sh.codigoSoporteFk = ' . $arSoporte->getCodigoSoportePk());
            $numeroRegistros = $q->execute();
            $em->flush();
            $em->getRepository(TurSoporte::class)->resumen($arSoporte);
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    public function aprobar($arSoporte)
    {
        $em = $this->getEntityManager();
        if ($arSoporte->getEstadoAutorizado() == 1 && $arSoporte->getEstadoAprobado() == 0) {
            $respuesta = $em->getRepository(TurSoporte::class)->validarAprobado($arSoporte);
            $arSoporte->setEstadoAprobado(1);
            $em->persist($arSoporte);
            $em->flush();
        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    public function resumen($arSoporte) {
        $em = $this->getEntityManager();
        $intDias = $arSoporte->getFechaDesde()->diff($arSoporte->getFechaHasta());
        $diasRealesPeriodo = $intDias->format('%a') + 1;

        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->findBy(array('codigoSoporteFk' => $arSoporte->getCodigoSoportePk()));
        foreach ($arSoportesContratos as $arSoporteContrato) {
            $dql = "SELECT "
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
                . "FROM App\Entity\Turno\TurSoporteHora sh "
                . "WHERE sh.codigoSoporteContratoFk =  " . $arSoporteContrato->getCodigoSoporteContratoPk() . " ";
            $query = $em->createQuery($dql);

            $arrayResultado = $query->getResult();
            for ($i = 0; $i < count($arrayResultado); $i++) {
                /*if ($arSoportePagoPeriodo->getDiasAdicionales() > 0 && $arSoportePago->getContratoMultiple() == 0) {
                    $novedades = $arrayResultado[$i]['incapacidad'] + $arrayResultado[$i]['incapacidadNoLegalizada'] + $arrayResultado[$i]['licencia'] + $arrayResultado[$i]['licenciaNoRemunerada'] + $arrayResultado[$i]['ausentismo'];
                    if ($arrayResultado[$i]['retiro'] <= 0 && $novedades < $diasRealesPeriodo) {
                        $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                        $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;
                    }

                }*/
                $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];

                /*if ($arSoportePagoPeriodoActualizar->getDescansoFestivoFijo()) {
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getFestivos() * 8;
                    $arrayResultado[$i]['descanso'] += $arSoportePagoPeriodoActualizar->getFestivos();
                }*/
                /*if ($arrayResultado[$i]['dias'] > $arSoporte->getDiasPeriodo()) {
                    $arrayResultado[$i]['dias'] = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }*/
                //Quite esta validacion porque en estelar cuando tenia induccion le pagaba mas dias
                //$diasTransporte = $arrayResultado[$i]['dias'] + $arrayResultado[$i]['induccion'];
                $diasTransporte = $arrayResultado[$i]['dias'];
                /*if ($diasTransporte > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $diasTransporte = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }*/
                //Adiciones mes de febrero
                $arrayResultado[$i]['horasAdicionalesFebrero'] = 0;
                /*if ($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFebrero() > 0) {
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


                }*/
                /*if ($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo() > 0) {
                    $diasTransporte += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo();
                    $arrayResultado[$i]['dias'] += $arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionalesFijo();
                }*/
                if ($diasTransporte == $arSoporte->getDias()) {
                    $diasTransporte -= ($arrayResultado[$i]['incapacidad'] + $arrayResultado[$i]['ausentismo'] + $arrayResultado[$i]['vacacion'] + $arrayResultado[$i]['ingreso'] + $arrayResultado[$i]['retiro'] + $arrayResultado[$i]['licenciaNoRemunerada'] + $arrayResultado[$i]['licencia'] + $arrayResultado[$i]['induccion']);
                    if ($diasTransporte < 0) {
                        $diasTransporte = 0;
                    }
                }

                $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
                $arSoporteContrato->setDias($arrayResultado[$i]['dias']?? 0);
                //$arSoporteContrato->setDiasTransporte($diasTransporte);
                //$arSoporteContrato->setDiasTransporteReal($diasTransporte);
                $arSoporteContrato->setDescanso($arrayResultado[$i]['descanso']?? 0);
                $arSoporteContrato->setNovedad($arrayResultado[$i]['novedad']?? 0);
                $arSoporteContrato->setIncapacidad($arrayResultado[$i]['incapacidad']?? 0);
                //$arSoporteContrato->setIncapacidadNoLegalizada($arrayResultado[$i]['incapacidadNoLegalizada']);
                $arSoporteContrato->setLicencia($arrayResultado[$i]['licencia'] ?? 0);
                //$arSoporteContrato->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
                $arSoporteContrato->setVacacion($arrayResultado[$i]['vacacion']?? 0);
                $arSoporteContrato->setInduccion($arrayResultado[$i]['induccion']?? 0);
                $arSoporteContrato->setIngreso($arrayResultado[$i]['ingreso']?? 0);
                $arSoporteContrato->setRetiro($arrayResultado[$i]['retiro']?? 0);
                $arSoporteContrato->setAusentismo($arrayResultado[$i]['ausentismo']?? 0);
                //$arSoporteContrato->setHorasPago($intHorasPago);
                $arSoporteContrato->setHoras($intHoras);
                //$arSoporteContrato->setHorasAdicionales($arrayResultado[$i]['horasAdicionalesFebrero']);
                $arSoporteContrato->setHorasDescanso($arrayResultado[$i]['horasDescanso']?? 0);
                $arSoporteContrato->setHorasNovedad($arrayResultado[$i]['horasNovedad']?? 0);
                $arSoporteContrato->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']?? 0);
                $arSoporteContrato->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']?? 0);
                $arSoporteContrato->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']?? 0);
                $arSoporteContrato->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']?? 0);
                $arSoporteContrato->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']?? 0);
                $arSoporteContrato->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']?? 0);
                $arSoporteContrato->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']?? 0);
                $arSoporteContrato->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']?? 0);
                $arSoporteContrato->setHorasRecargoNocturno($arrayResultado[$i]['horasRecargoNocturno']?? 0);
                $arSoporteContrato->setHorasRecargoFestivoDiurno($arrayResultado[$i]['horasRecargoFestivoDiurno']?? 0);
                $arSoporteContrato->setHorasRecargoFestivoNocturno($arrayResultado[$i]['horasRecargoFestivoNocturno']?? 0);
                $arSoporteContrato->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']?? 0);
                $arSoporteContrato->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']?? 0);
                $arSoporteContrato->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']?? 0);
                $arSoporteContrato->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']?? 0);
                $arSoporteContrato->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']?? 0);
                $arSoporteContrato->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']?? 0);
                $arSoporteContrato->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']?? 0);
                $arSoporteContrato->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']?? 0);
                $arSoporteContrato->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']?? 0);
                $arSoporteContrato->setHorasRecargoNocturnoReales($arrayResultado[$i]['horasRecargoNocturno']?? 0);
                $arSoporteContrato->setHorasRecargoFestivoDiurnoReales($arrayResultado[$i]['horasRecargoFestivoDiurno']?? 0);
                $arSoporteContrato->setHorasRecargoFestivoNocturnoReales($arrayResultado[$i]['horasRecargoFestivoNocturno']?? 0);
                $em->persist($arSoporteContrato);
            }

        }
        //$arSoportePagoPeriodoActualizar->setRecursos(count($arSoportesPago));
        //$em->persist($arSoportePagoPeriodoActualizar);
        $em->flush();
    }

    public function validarAprobado($arSoporte) {
        $em = $this->getEntityManager();
        $em->getRepository(GenInconsistencia::class)->limpiar('TurSoporte', $arSoporte->getCodigoSoportePk());

        $arrInconsistencias = array();
        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->findBy(array('codigoSoporteFk' => $arSoporte->getCodigoSoportePk()));
        foreach ($arSoportesContratos as $arSoporteContrato) {
                if ($arSoporteContrato->getCodigoContratoFk()) {
                    $arrVacaciones = $em->getRepository(RhuVacacion::class)->diasValidarTurnos($arSoporteContrato->getCodigoEmpleadoFk(), $arSoporteContrato->getCodigoContratoFk(), $arSoporte->getFechaDesde(), $arSoporte->getFechaHasta());

                    /*$arrLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->licenciasPerido($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());
                    $intDiasIncapacidadSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->diasIncapacidad($codigoSoportePagoPeriodo, $arSoportePago->getRecursoRel()->getCodigoRecursoPk());
                    $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo31($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());
                    $intDiasVacaciones = $arrVacaciones['dias'];

                    if($intDiasVacaciones > 0){
                        $intDiasVacaciones = $arrVacaciones['dias']-$arrLicencias['1']['licencia'];
                    }*/

                    if ($arrVacaciones['dias'] != $arSoporteContrato->getVacacion()) {
                        $arrInconsistencias[] = [
                            'inconsistencia' => "Vacaciones de " . $arSoporteContrato->getVacacion() . " dias en turnos y de " . $arrVacaciones['dias'] . " en recurso humano",
                            'empleado' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                            'numeroIdentificacion' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                            'codigo' => $arSoporteContrato->getCodigoEmpleadoFk()
                        ];
                    }

                    /*if ($arrLicencias) {
                        foreach ($arrLicencias as $arrLicencia) {
                            // ausentismo o licencia no remunerada
                            if (array_key_exists('licenciaNoRemunerada', $arrLicencia)) {
                                if ($arrLicencia['licenciaNoRemunerada'] != ($arSoportePago->getAusentismo() + $arSoportePago->getLicenciaNoRemunerada())) {
                                    $arrInconsistencias[] = [
                                        'inconsistencia' => "Licencia no remunerada o ausentismo de " . ($arSoportePago->getAusentismo() + $arSoportePago->getLicenciaNoRemunerada()) . " dias en turnos y de " . $arrLicencia['licenciaNoRemunerada'] . " en recurso humano",
                                        'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                                        'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                                        'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                                        'codigo' => $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk()
                                    ];
                                }
                            }
                            // licencias
                            if (array_key_exists('licencia', $arrLicencia)) {
                                if ($arrLicencia['licencia'] != ($arSoportePago->getLicencia())) {
                                    $arrInconsistencias[] = [
                                        'inconsistencia' => "Licencia remunerada de " . $arSoportePago->getLicencia() . " dias en turnos y de " . $arrLicencia['licencia'] . " en recurso humano",
                                        'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                                        'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                                        'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                                        'codigo' => $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk()
                                    ];
                                }
                            }
                        }
                    }*/

                    /*if ($intDiasIncapacidad != $intDiasIncapacidadSoportePagoPeriodo) {
                        $arrInconsistencias[] = [
                            'inconsistencia' => "Incapacidades de " . $intDiasIncapacidadSoportePagoPeriodo . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano",
                            'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                            'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                            'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                            'codigo' => $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk()
                        ];
                    }*/
                }

        }
        /*$contratosTerminados = $arSoportePagoPeriodo->getContratosTerminados();
        global $kernel;//Esto es para poder usar la variable global del kernel
        $arUsuario = $kernel->getContainer()->get("security.token_storage")->getToken()->getUser();//Obtener el usuario logeado con la variable global kernel
        $fechaHastaSoportePagoPeriodo = $arSoportePagoPeriodo->getFechaHasta()->format('Y-m-d');
        if ($arSoportePagoPeriodo->getFechaHasta()->format('d') == 31) {
            $fechaHastaSoportePagoPeriodo = $arSoportePagoPeriodo->getFechaHasta()->format("Y-m") . "-30";
        }
        $qb = $em->createQueryBuilder()->from("BrasaRecursoHumanoBundle:RhuContrato", "c")->select("c.codigoEmpleadoFk, e.numeroIdentificacion, e.nombreCorto, e.codigoSegmentoFk, c.estadoActivo,c.fechaHasta")
            ->join("c.empleadoRel", "e")
            ->where("c.codigoCentroCostoFk = {$arSoportePagoPeriodo->getCodigoCentroCostoFk()}")
            ->andWhere("c.fechaUltimoPago < '{$fechaHastaSoportePagoPeriodo}'")
            ->andWhere("c.fechaDesde <= '{$arSoportePagoPeriodo->getFechaHasta()->format('Y-m-d')}'")
            ->andWhere("c.fechaHasta >= '{$arSoportePagoPeriodo->getFechaDesde()->format('Y-m-d')}' OR c.indefinido = 1");
        if ($contratosTerminados) {
            $qb->andWhere("c.estadoActivo = 0");
        }
        if ($arUsuario->getUtilizaSegmento() && ($arSoportePagoPeriodo->getCodigoSegmentoFk() != "" || $arSoportePagoPeriodo->getCodigoSegmentoFk() != null)) {
            $qb->andWhere("e.codigoSegmentoFk = {$arSoportePagoPeriodo->getCodigoSegmentoFk()}");
        }

        $arContratos = $qb->getQuery()->getResult();
        foreach ($arContratos as $arContrato) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('codigoEmpleadoFk' => $arContrato['codigoEmpleadoFk']));
            $arSoportePago = null;
            if ($arRecurso) {
                $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findOneBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo, 'codigoRecursoFk' => $arRecurso->getCodigoRecursoPk()));
                if (!$arSoportePago) {
                    $arrInconsistencias[] = [
                        'inconsistencia' => "El recurso esta en el centro de costo con contrato activo y no registra turnos en programacion, verifique la ultima fecha de pago del contrato",
                        'recurso' => $arRecurso->getNombreCorto(),
                        'segmento' => $arRecurso->getCodigoSegmentoFk(),
                        'numeroIdentificacion' => $arRecurso->getNumeroIdentificacion(),
                        'codigo' => $arRecurso->getCodigoEmpleadoFk()
                    ];
                }
            } else {
                $arrInconsistencias[] = [
                    'inconsistencia' => "El empleado esta en un centro de costos pagado por turnos y no esta en programado en turnos",
                    'recurso' => $arContrato['nombreCorto'],
                    'segmento' => $arContrato['codigoSegmentoFk'],
                    'numeroIdentificacion' => $arContrato['numeroIdentificacion'],
                    'codigo' => $arContrato['codigoEmpleadoFk']
                ];
            }
            // valida contratos terminados en recurso humano y sin turnos de retiro
            if ($arContrato['estadoActivo'] == 0) {
                if ($arSoportePago) {
                    if ($arSoportePago->getRetiro() == 0 && $arContrato['fechaHasta']->format('Y-m-d') < $arSoportePagoPeriodo->getFechaHasta()->format('Y-m-d')) {
                        $qb = $em->createQueryBuilder()->from("BrasaRecursoHumanoBundle:RhuContrato", "c")->select("c")
                            ->join("c.empleadoRel", "e")
                            ->andWhere("e.codigoEmpleadoPk = '{$arContrato['codigoEmpleadoFk']}'")
                            ->andWhere("c.fechaDesde > '{$arContrato['fechaHasta']->format('Y-m-d')}'");
                        $arContratosReingreso = $qb->getQuery()->getResult();
                        if (count($arContratosReingreso) > 0) {
                            $diff = $arContrato['fechaHasta']->diff($arContratosReingreso[0]->getFechaDesde());
                            $arDiasRetiro = $em->getRepository("BrasaTurnoBundle:TurProgramacionDetalle")->validarDiasRetiro($arContrato['fechaHasta'], $arContratosReingreso[0]->getFechaDesde(), $arContrato['codigoEmpleadoFk']);
                            $intervaloDias = intval($diff->days) - 1;
                            if ($arDiasRetiro != $intervaloDias) {
                                $arrInconsistencias[] = [
                                    'inconsistencia' => "El empleado tiene '{$intervaloDias}' dias de retiro en recurso humano y no tiene turnos de retiro",
                                    'recurso' => $arContrato['nombreCorto'],
                                    'segmento' => $arContrato['codigoSegmentoFk'],
                                    'numeroIdentificacion' => $arContrato['numeroIdentificacion'],
                                    'codigo' => $arContrato['codigoEmpleadoFk']
                                ];
                            }
                        } else {
                            $arrInconsistencias[] = [
                                'inconsistencia' => "El empleado se encuentra retirado en recurso humano y no tiene turnos de retiro",
                                'recurso' => $arContrato['nombreCorto'],
                                'segmento' => $arContrato['codigoSegmentoFk'],
                                'numeroIdentificacion' => $arContrato['numeroIdentificacion'],
                                'codigo' => $arContrato['codigoEmpleadoFk']
                            ];
                        }


                    }
                }
            }

        }

        //Recursos sin turno asignado
        foreach ($arSoportesPagoProcesar as $arSoportePago) {
            $desde = $arSoportePago->getFechaDesde()->format('j');
            $hasta = $arSoportePago->getFechaHasta()->format('j');
            $dql = "SELECT spp.codigoSoportePagoProgramacionPk, spp.dia1, spp.dia2, spp.dia3, spp.dia4, spp.dia5, spp.dia6, spp.dia7, spp.dia8, spp.dia9, spp.dia10, "
                . "spp.dia11, spp.dia12, spp.dia13, spp.dia14, spp.dia15, spp.dia16, spp.dia17, spp.dia18, spp.dia19, spp.dia20, "
                . "spp.dia21, spp.dia22, spp.dia23, spp.dia24, spp.dia25, spp.dia26, spp.dia27, spp.dia28, spp.dia29, spp.dia30, spp.dia31 "
                . "FROM BrasaTurnoBundle:TurSoportePagoProgramacion spp "
                . "WHERE spp.codigoSoportePagoFk =" . $arSoportePago->getCodigoSoportePagoPk();
            $query = $em->createQuery($dql);
            $arResultado = $query->getResult();
            if ($arResultado) {
                for ($i = $desde; $i <= $hasta; $i++) {
                    if ($arResultado[0]['dia' . $i] == "") {
                        $arrInconsistencias[] = [
                            'inconsistencia' => "El empleado no tiene turno asignado el dia" . $i,
                            'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                            'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                            'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                            'codigo' => $arSoportePago->getCodigoRecursoFk()
                        ];
                    }
                }
            } else {
                $arrInconsistencias[] = [
                    'inconsistencia' => "No se encontro soporte pago programacion",
                    'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                    'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                    'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                    'codigo' => $arSoportePago->getCodigoRecursoFk()
                ];
            }
        }

        //Turnos retirado y sin retirar de recurso humano
        foreach ($arSoportesPagoProcesar as $arSoportePago) {
            if ($arSoportePago->getRetiro() > 0) {
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arSoportePago->getCodigoContratoFk());
                if ($arContrato) {
                    if ($arContrato->getEstadoActivo() == 1) {
                        $arrInconsistencias[] = [
                            'inconsistencia' => "El contrato del empleado esta activo y tiene turnos de retiro (" . $arSoportePago->getRetiro() . ") en el soporte de pago",
                            'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(),
                            'segmento' => $arSoportePago->getRecursoRel()->getCodigoSegmentoFk(),
                            'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(),
                            'codigo' => $arSoportePago->getRecursoRel()->getCodigoRecursoPk()
                        ];
                    }
                }
            }
        }

        //Turnos dobles
        $arrInconsistencias = $this->recursosTurnoDoble($arrInconsistencias, $arSoportePagoPeriodo->getAnio(), $arSoportePagoPeriodo->getMes(), $arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arSoportePagoPeriodo->getCodigoCentroCostoFk(), $arSoportePagoPeriodo->getCodigoSegmentoFk());

        if (count($arrInconsistencias) > 0) {
            $arSoportePagoPeriodo->setInconsistencias(1);
            foreach ($arrInconsistencias as $arrInconsistencia) {
                $arSoportePagoInconsistencia = new \Brasa\TurnoBundle\Entity\TurSoportePagoInconsistencia;
                $arSoportePagoInconsistencia->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
                $arSoportePagoInconsistencia->setRecurso($arrInconsistencia['recurso']);
                $arSoportePagoInconsistencia->setCodigoSegmentoFk($arrInconsistencia['segmento']);
                $arSoportePagoInconsistencia->setNumeroIdentificacion($arrInconsistencia['numeroIdentificacion']);
                $arSoportePagoInconsistencia->setCodigoRecurso($arrInconsistencia['codigo']);
                $arSoportePagoInconsistencia->setDetalle($arrInconsistencia['inconsistencia']);
                $em->persist($arSoportePagoInconsistencia);
            }
        }


        $em->persist($arSoportePagoPeriodo);
        $em->flush();*/

        return true;
    }
}
