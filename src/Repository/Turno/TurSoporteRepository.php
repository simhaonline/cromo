<?php

namespace App\Repository\Turno;

use App\Entity\General\GenInconsistencia;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuDistribucion;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionRespaldo;
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


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoSoportePk = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        if ($filtros){
            $codigoSoportePk = $filtros['codigoSoportePk']??null;
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporte::class, 's')
            ->select('s.codigoSoportePk')
            ->addSelect('s.fechaDesde')
            ->addSelect('s.fechaHasta')
            ->addSelect('s.usuario')
            ->addSelect('s.estadoAutorizado')
            ->addSelect('s.estadoAprobado')
            ->addSelect('s.estadoAnulado')
            ->addSelect('s.cargadoNomina')
            ->addSelect('g.nombre as grupo')
            ->leftJoin('s.grupoRel', 'g');

        if ($codigoSoportePk) {
            $queryBuilder->andWhere("s.codigoSoportePk = '{$codigoSoportePk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAnulado = 1");
                break;
        }
        $queryBuilder->orderBy('s.codigoSoportePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
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
            ->leftJoin('s.grupoRel', 'g')
            ->where('s.cargadoNomina = 0')
            ->orderBy('s.fechaDesde', 'DESC');
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
        $respuesta = '';
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arRegistro = $this->getEntityManager()->getRepository(TurSoporte::class)->find($arrSeleccionado);
            if ($arRegistro) {
                if ($arRegistro->getEstadoAprobado() == 0) {
                    if ($arRegistro->getEstadoAutorizado() == 0) {
                        if ( count($this->getEntityManager()->getRepository(TurSoporteHora::class)->findBy(['codigoSoporteFk'=>$arrSeleccionado])) <= 0 ){
                            $this->getEntityManager()->remove($arRegistro);
                        }else{
                            $respuesta = 'No se puede eliminar, el registro tiene detalles';
                        }
                    }else{
                        $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                    }
                }else{
                    $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                }
            }
            if ($respuesta != '') {
                Mensajes::error($respuesta);
            } else {
                $this->getEntityManager()->flush();
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
        $arrConfiguracionRecursoHumano = $em->getRepository(RhuConfiguracion::class)->autorizarProgramacion();
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

                        if($arSoporte->getGrupoRel()->getCodigoDistribucionFk()) {
                            $arSoporteContrato->setDistribucionRel($em->getReference(RhuDistribucion::class, $arSoporte->getGrupoRel()->getCodigoDistribucionFk()));
                        }
                        if($arContrato['codigoDistribucionFk']) {
                            $arSoporteContrato->setDistribucionRel($em->getReference(RhuDistribucion::class, $arContrato['codigoDistribucionFk']));
                        }
                        $arSoporteContrato->setVrSalario($arContrato['vrSalario']);
                        $arSoporteContrato->setVrDevengadoPactado($arContrato['vrDevengadoPactado']);
                        if($arContrato['auxilioTransporte']) {
                            $arSoporteContrato->setAuxilioTransporte($arContrato['auxilioTransporte']);
                            $arSoporteContrato->setVrAuxilioTransporte($arrConfiguracionRecursoHumano['vrAuxilioTransporte']);
                        }
                        //$arSoportePago->setAuxilioTransporte($arContrato->getAuxilioTransporte());

                        $arSoporteContrato->setAnio($arSoporte->getFechaDesde()->format('Y'));
                        $arSoporteContrato->setMes($arSoporte->getFechaDesde()->format('m'));

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
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getEntityManager();

        $arrFestivos = $em->getRepository(TurFestivo::class)->fecha($arSoporte->getFechaDesde()->format('Y-m-') . '01', $arSoporte->getFechaHasta()->format('Y-m-t'));
        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->listaHoras($arSoporte->getCodigoSoportePk(), null);
        foreach ($arSoportesContratos as $arSoportesContrato) {
            $arrHoras = $em->getRepository(TurSoporteContrato::class)->generarHoras($arSoporte, $arSoportesContrato, $arrFestivos);
        }
        $arSoporte->setEstadoAutorizado(1);
        $em->flush();

        $em->getRepository(TurSoporte::class)->resumen($arSoporte);

        foreach ($arSoportesContratos as $arSoporteContrato) {
            $em->getRepository(TurProgramacionRespaldo::class)->generar($arSoporte, $arSoporteContrato);
        }
        $em->flush();





        //Distribuir
        foreach ($arSoportesContratos as $arSoportesContrato) {
            if($arSoportesContrato['codigoDistribucionFk']) {
                $em->getRepository(TurSoporteContrato::class)->distribucionSoporte($arSoporte);
            }
        }
        $em->flush();


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
            $q = $em->createQuery('delete from App\Entity\Turno\TurProgramacionRespaldo pr where pr.codigoSoporteFk = ' . $arSoporte->getCodigoSoportePk());
            $numeroRegistros = $q->execute();
            $em->flush();
            $em->getRepository(TurSoporte::class)->resumen($arSoporte);
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    public function aprobar($arSoporte)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getEntityManager();
        if ($arSoporte->getEstadoAutorizado() == 1 && $arSoporte->getEstadoAprobado() == 0) {
            $validar = $em->getRepository(TurSoporte::class)->validarAprobado($arSoporte);
            if($validar) {
                $arSoporte->setEstadoAprobado(1);
                $em->persist($arSoporte);
                $em->flush();
            } else {
                Mensajes::error("No se puede aprobar el documento porque tiene inconsistencias");
            }
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
            /** @var $arSoporteContrato TurSoporteContrato */
            $dql = "SELECT "
                . "SUM(sh.descanso) as descanso, "
                . "SUM(sh.novedad) as novedad, "
                . "SUM(sh.incapacidad) as incapacidad, "
                . "SUM(sh.incapacidadNoLegalizada) as incapacidadNoLegalizada, "
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
                $diasTransporte = $arrayResultado[$i]['dias'] + $arrayResultado[$i]['induccion'];
                if ($diasTransporte > $arSoporte->getDias()) {
                    $diasTransporte = $arSoporte->getDias();
                }

                if ($diasTransporte == $arSoporte->getDias()) {
                    $diasTransporte -= ($arrayResultado[$i]['incapacidad'] + $arrayResultado[$i]['ausentismo'] + $arrayResultado[$i]['vacacion'] + $arrayResultado[$i]['ingreso'] + $arrayResultado[$i]['retiro'] + $arrayResultado[$i]['licenciaNoRemunerada'] + $arrayResultado[$i]['licencia'] + $arrayResultado[$i]['induccion']);
                    if ($diasTransporte < 0) {
                        $diasTransporte = 0;
                    }
                }

                $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
                $arSoporteContrato->setDias($arrayResultado[$i]['dias']?? 0);
                $arSoporteContrato->setDiasTransporte($diasTransporte);
                //$arSoporteContrato->setDiasTransporteReal($diasTransporte);
                $arSoporteContrato->setDescanso($arrayResultado[$i]['descanso']?? 0);
                $arSoporteContrato->setNovedad($arrayResultado[$i]['novedad']?? 0);
                $arSoporteContrato->setIncapacidad($arrayResultado[$i]['incapacidad']?? 0);
                $arSoporteContrato->setIncapacidadNoLegalizada($arrayResultado[$i]['incapacidadNoLegalizada']);
                $arSoporteContrato->setLicencia($arrayResultado[$i]['licencia'] ?? 0);
                $arSoporteContrato->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']??0);
                $arSoporteContrato->setVacacion($arrayResultado[$i]['vacacion']?? 0);
                $arSoporteContrato->setInduccion($arrayResultado[$i]['induccion']?? 0);
                $arSoporteContrato->setIngreso($arrayResultado[$i]['ingreso']?? 0);
                $arSoporteContrato->setRetiro($arrayResultado[$i]['retiro']?? 0);
                $arSoporteContrato->setAusentismo($arrayResultado[$i]['ausentismo']?? 0);

                //$arSoporteContrato->setHorasPago($intHorasPago);
                $arSoporteContrato->setHoras($intHoras);
                //$arSoporteContrato->setHorasAdicionales($arrayResultado[$i]['horasAdicionalesFebrero']);
                $arSoporteContrato->setHorasNovedad($arrayResultado[$i]['horasNovedad']?? 0);
                $arSoporteContrato->setHorasDescanso($arrayResultado[$i]['horasDescanso']?? 0);
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
                $em->getRepository(TurSoporteContrato::class)->valorizar($arSoporteContrato);
                $em->persist($arSoporteContrato);
            }

        }
        //$arSoportePagoPeriodoActualizar->setRecursos(count($arSoportesPago));
        //$em->persist($arSoportePagoPeriodoActualizar);
        $em->flush();
    }

    /**
     * @param $arSoporte TurSoporte
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function validarAprobado($arSoporte) {
        $em = $this->getEntityManager();
        $em->getRepository(GenInconsistencia::class)->limpiar('TurSoporte', $arSoporte->getCodigoSoportePk());

        $arrInconsistencias = array();

        $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->findBy(array('codigoSoporteFk' => $arSoporte->getCodigoSoportePk()));
        foreach ($arSoportesContratos as $arSoporteContrato) {
            if ($arSoporteContrato->getCodigoContratoFk()) {
                $arrVacaciones = $em->getRepository(RhuVacacion::class)->diasValidarTurnos($arSoporteContrato->getCodigoEmpleadoFk(), $arSoporteContrato->getCodigoContratoFk(), $arSoporte->getFechaDesde(), $arSoporte->getFechaHasta());

                $arrLicencias = $em->getRepository(RhuLicencia::class)->licenciasPerido($arSoporte->getFechaDesde(), $arSoporte->getFechaHasta(), $arSoporteContrato->getCodigoEmpleadoFk());
                $intDiasIncapacidadSoportePagoPeriodo = $em->getRepository(TurSoporte::class)->diasIncapacidad($arSoporte->getCodigoSoportePk(), $arSoporteContrato->getCodigoEmpleadoFk());
                $intDiasIncapacidad = $em->getRepository(RhuIncapacidad::class)->diasIncapacidadPeriodo31($arSoporte->getFechaDesde(), $arSoporte->getFechaHasta(), $arSoporteContrato->getCodigoEmpleadoFk());
                $intDiasVacaciones = $arrVacaciones['dias'];

                if($intDiasVacaciones > 0){
                    $intDiasVacaciones = $arrVacaciones['dias']-$arrLicencias['1']['licencia'];
                }

                if ($arrVacaciones['dias'] != $arSoporteContrato->getVacacion()) {
                    $arrInconsistencias[] = [
                        'descripcion' => "Vacaciones de " . $arSoporteContrato->getVacacion() . " dias en turnos y de " . $arrVacaciones['dias'] . " en recurso humano",
                        'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                        'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                        'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                    ];
                }

                if ($arrLicencias) {
                    foreach ($arrLicencias as $arrLicencia) {
                        // ausentismo o licencia no remunerada
                        if (array_key_exists('licenciaNoRemunerada', $arrLicencia)) {
                            if ($arrLicencia['licenciaNoRemunerada'] != ($arSoporteContrato->getAusentismo() + $arSoporteContrato->getLicenciaNoRemunerada())) {
                                $arrInconsistencias[] = [
                                    'descripcion' => "Licencia no remunerada o ausentismo de " . ($arSoporteContrato->getAusentismo() + $arSoporteContrato->getLicenciaNoRemunerada()) . " dias en turnos y de " . $arrLicencia['licenciaNoRemunerada'] . " en recurso humano",
                                    'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                                    'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                                    'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                                ];
                            }
                        }

                        // licencias
                        if (array_key_exists('licencia', $arrLicencia)) {
                            if ($arrLicencia['licencia'] != ($arSoporteContrato->getLicencia())) {
                                $arrInconsistencias[] = [
                                    'descripcion' => "Licencia remunerada de " . $arSoporteContrato->getLicencia() . " dias en turnos y de " . $arrLicencia['licencia'] . " en recurso humano",
                                    'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                                    'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                                    'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                                ];
                            }
                        }
                    }
                }

                if ($intDiasIncapacidad != $intDiasIncapacidadSoportePagoPeriodo) {
                    $arrInconsistencias[] = [
                        'descripcion' => "Incapacidades de " . $intDiasIncapacidadSoportePagoPeriodo . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano",
                        'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                        'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                        'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                    ];
                }

            }
        }


        $fechaHastaSoportePagoPeriodo = $arSoporte->getFechaHasta()->format('Y-m-d');
        if ($arSoporte->getFechaHasta()->format('d') == 31) {
            $fechaHastaSoportePagoPeriodo = $arSoporte->getFechaHasta()->format("Y-m") . "-30";
        }
        $qb = $em->createQueryBuilder()->from(RhuContrato::class, "c")
            ->select("c.codigoContratoPk, c.codigoEmpleadoFk, e.numeroIdentificacion, e.nombreCorto, c.fechaHasta, c.estadoTerminado")
            ->join("c.empleadoRel", "e")
            ->where("c.codigoGrupoFk = '{$arSoporte->getCodigoGrupoFk()}'")
            ->andWhere("c.fechaUltimoPago < '{$fechaHastaSoportePagoPeriodo}'")
            ->andWhere("c.fechaDesde <= '{$arSoporte->getFechaHasta()->format('Y-m-d')}'")
            ->andWhere("c.fechaHasta >= '{$arSoporte->getFechaDesde()->format('Y-m-d')}' OR c.indefinido = 1");
        if ($arSoporte->getContratoTerminado() == 1) {
            $qb->andWhere("c.estadoTerminado = 1");
        }
        $arContratos = $qb->getQuery()->getResult();

        foreach ($arContratos as $arContrato) {
            $arSoporteContrato = $em->getRepository(TurSoporteContrato::class)->findOneBy(array('codigoSoporteFk' => $arSoporte->getCodigoSoportePk(), 'codigoContratoFk' => $arContrato['codigoContratoPk']));
            if (!$arSoporteContrato) {
                $arrInconsistencias[] = [
                    'codigoReferencia' => $arContrato['codigoEmpleadoFk'],
                    'referencia' => $arContrato['numeroIdentificacion'],
                    'nombreReferencia' => $arContrato['nombreCorto'],
                    'descripcion' => "El empleado tiene un contrato para este periodo y no registra turnos en programacion, verifique la ultima fecha de pago del contrato"
                ];
            }

            // valida contratos terminados en recurso humano y sin turnos de retiro
            if ($arContrato['estadoTerminado']) {
                if ($arSoporteContrato) {
                    if ($arSoporteContrato->getRetiro() == 0 && $arContrato['fechaHasta']->format('Y-m-d') < $arSoporte->getFechaHasta()->format('Y-m-d')) {
                        $qb = $em->createQueryBuilder()->from(RhuContrato::class, "c")->select("c")
                            ->join("c.empleadoRel", "e")
                            ->andWhere("e.codigoEmpleadoPk = '{$arContrato['codigoEmpleadoFk']}'")
                            ->andWhere("c.fechaDesde > '{$arContrato['fechaHasta']->format('Y-m-d')}'");
                        $arContratosReingreso = $qb->getQuery()->getResult();
                        if ($arContratosReingreso) {
                            $diff = $arContrato['fechaHasta']->diff($arContratosReingreso[0]->getFechaDesde());
                            $arDiasRetiro = $em->getRepository(TurProgramacion::class)->validarDiasRetiro($arContrato['fechaHasta'], $arContratosReingreso[0]->getFechaDesde(), $arContrato['codigoEmpleadoFk']);
                            $intervaloDias = intval($diff->days) - 1;
                            if ($arDiasRetiro != $intervaloDias) {
                                $arrInconsistencias[] = [
                                    'referencia' => $arContrato['numeroIdentificacion'],
                                    'codigoReferencia' => $arContrato['codigoEmpleadoFk'],
                                    'nombreReferencia' => $arContrato['nombreCorto'],
                                    'descripcion' => "El empleado tiene '{$intervaloDias}' dias de retiro en recurso humano y no tiene turnos de retiro"

                                ];
                            }
                        } else {
                            $arrInconsistencias[] = [
                                'descripcion' => "El empleado se encuentra retirado en recurso humano y no tiene turnos de retiro",
                                'referencia' => $arContrato['numeroIdentificacion'],
                                'nombreReferencia' => $arContrato['nombreCorto'],
                                'codigoReferencia' => $arContrato['codigoEmpleadoFk']
                            ];
                        }
                    }
                }
            }

        }

        //Recursos sin turno asignado o con turnos dobles
        foreach ($arSoportesContratos as $arSoporteContrato) {
            $arrValidacionTurnos = $em->getRepository(TurProgramacion::class)->validacionTurnos($arSoporteContrato->getCodigoEmpleadoFk(), $arSoporteContrato->getAnio(), $arSoporteContrato->getMes(), $arSoporte->getFechaDesde()->format('j'), $arSoporte->getFechaHasta()->format('j'));
            if($arrValidacionTurnos['faltantes']) {
                $arrInconsistencias[] = [
                    'descripcion' => "El empleado no tiene turnos asignados dias: " . $arrValidacionTurnos['faltantes'],
                    'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                    'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                    'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                ];
            }
            if($arrValidacionTurnos['dobles']) {
                $arrInconsistencias[] = [
                    'descripcion' => "El empleado tiene turnos duplicados dias: " . $arrValidacionTurnos['dobles'],
                    'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                    'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                    'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                ];
            }
        }

        //Turnos retirado y sin retirar de recurso humano
        foreach ($arSoportesContratos as $arSoporteContrato) {
            if ($arSoporteContrato->getRetiro() > 0) {
                $arContrato = $arSoporteContrato->getContratoRel();
                if ($arContrato) {
                    if ($arContrato->getEstadoTerminado() == 0) {
                        $arrInconsistencias[] = [
                            'descripcion' => "El contrato del empleado esta activo y tiene turnos de retiro (" . $arSoporteContrato->getRetiro() . ") en el soporte de pago",
                            'referencia' => $arSoporteContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                            'nombreReferencia' => $arSoporteContrato->getEmpleadoRel()->getNombreCorto(),
                            'codigoReferencia' => $arSoporteContrato->getCodigoEmpleadoFk()
                        ];
                    }
                }
            }
        }

        if ($arrInconsistencias) {
            //$arSoportePagoPeriodo->setInconsistencias(1);
            foreach ($arrInconsistencias as $arrInconsistencia) {
                $arInconsistencia = new GenInconsistencia();
                $arInconsistencia->setCodigoModeloFk('TurSoporte');
                $arInconsistencia->setCodigoModelo($arSoporte->getCodigoSoportePk());
                $arInconsistencia->setCodigoReferencia($arrInconsistencia['codigoReferencia']);
                $arInconsistencia->setNombreReferencia($arrInconsistencia['nombreReferencia']);
                $arInconsistencia->setReferencia($arrInconsistencia['referencia']);
                $arInconsistencia->setDescripcion($arrInconsistencia['descripcion']);
                $em->persist($arInconsistencia);
            }
            $em->flush();
            return false;
        } else {
            return true;
        }
    }

    public function diasIncapacidad($codigoSoporte, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $diasIncapacidad = 0;
        $dql = "SELECT SUM(sp.incapacidad) as diasIncapacidad "
            . "FROM App\Entity\Turno\TurSoporteContrato sp "
            . "WHERE sp.codigoSoporteFk =  " . $codigoSoporte . " AND sp.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        if ($arrayResultado) {
            $diasIncapacidad = $arrayResultado[0]['diasIncapacidad'];
        }


        return $diasIncapacidad;
    }

    /**
     * @param $arSoporte TurSoporte
     */
    public function semanas($arSoporte) {
        $arrSemanas = array();
        $arrDomingos = array();
        $dateFechaDesde = $arSoporte->getFechaDesde();
        $dateFechaHasta = $arSoporte->getFechaHasta();
        $intDiaInicial = $dateFechaDesde->format('j');
        $intDiaFinal = $dateFechaHasta->format('j');
        $diaInicialSemana = $intDiaInicial;
        $diasCorte = 0;
        for ($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
            $diasCorte++;
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $dateFecha->format('N');
            if ($diaSemana == 7 || ($diasCorte > 0 && $i == $intDiaFinal)) {
                $diasCorte = 0;
                $dias = ($i - $diaInicialSemana) + 1;
                $arrSemanas[] = array('diaInicial' => $diaInicialSemana,
                    'diaFinal' => $i,
                    'fechaInicial' => $dateFechaDesde->format('Y/m/') . $diaInicialSemana,
                    'fechaFinal' => $dateFechaDesde->format('Y/m/') . $i,
                    'dias' => $dias,
                    'descanso' => '0');
                $diaInicialSemana = $i + 1;
                $arrDomingos[] = array('domingo' => $dateFecha);
            }
        }
        return $arrSemanas;
    }

}
