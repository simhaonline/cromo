<?php

namespace App\Repository\Turno;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use App\Entity\Turno\TurTurno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporteContrato::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteContrato::class, 'sc');
        $queryBuilder
            ->select('sc.codigoSoporteContratoPk')
            ->addSelect('sc.codigoEmpleadoFk')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('sc.dias')
            ->addSelect('sc.diasTransporte')
            ->addSelect('sc.novedad')
            ->addSelect('sc.induccion')
            ->addSelect('sc.retiro')
            ->addSelect('sc.incapacidad')
            ->addSelect('sc.licencia')
            ->addSelect('sc.ausentismo')
            ->addSelect('sc.vacacion')
            ->addSelect('sc.horas')
            ->addSelect('sc.horasDescanso')
            ->addSelect('sc.horasDiurnas')
            ->addSelect('sc.horasNocturnas')
            ->addSelect('sc.horasFestivasDiurnas')
            ->addSelect('sc.horasFestivasNocturnas')
            ->addSelect('sc.horasExtrasOrdinariasDiurnas')
            ->addSelect('sc.horasExtrasOrdinariasNocturnas')
            ->addSelect('sc.horasExtrasFestivasDiurnas')
            ->addSelect('sc.horasExtrasFestivasNocturnas')
            ->addSelect('sc.horasRecargoNocturno')
            ->addSelect('sc.horasRecargoFestivoDiurno')
            ->addSelect('sc.horasRecargoFestivoNocturno')
            ->addSelect('sc.horasRecargo')
            ->leftJoin('sc.contratoRel', 'c')
            ->leftJoin('sc.empleadoRel', 'e')
            ->where('sc.codigoSoporteFk = ' . $id);

        return $queryBuilder;
    }

    public function listaHoras($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteContrato::class, 'sc')
            ->select('sc.codigoSoporteContratoPk')
            ->addSelect('sc.fechaDesde')
            ->addSelect('sc.fechaHasta')
            ->addSelect('sc.anio')
            ->addSelect('sc.mes')
            ->addSelect('sc.codigoEmpleadoFk')
            ->where('sc.codigoSoporteFk = ' . $id);
        $arSoporteContratos = $queryBuilder->getQuery()->getResult();

        return $arSoporteContratos;
    }

    public function cargarNomina($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteContrato::class, 'sc')
            ->select('sc.codigoSoporteContratoPk')
            ->addSelect('sc.fechaDesde')
            ->addSelect('sc.fechaHasta')
            ->addSelect('sc.anio')
            ->addSelect('sc.mes')
            ->addSelect('sc.codigoEmpleadoFk')
            ->addSelect('sc.codigoContratoFk')
            ->addSelect('c.vrSalario')
            ->addSelect('c.fechaDesde as contratoFechaDesde')
            ->addSelect('c.fechaHasta as contratoFechaHasta')
            ->addSelect('sc.dias')
            ->addSelect('sc.diasTransporte')
            ->addSelect('sc.horasDescanso')
            ->addSelect('sc.horasDiurnas')
            ->addSelect('sc.horasNocturnas')
            ->addSelect('sc.horasFestivasDiurnas')
            ->addSelect('sc.horasFestivasNocturnas')
            ->addSelect('sc.horasExtrasOrdinariasDiurnas')
            ->addSelect('sc.horasExtrasOrdinariasNocturnas')
            ->addSelect('sc.horasExtrasFestivasDiurnas')
            ->addSelect('sc.horasExtrasFestivasNocturnas')
            ->addSelect('sc.horasRecargo')
            ->addSelect('sc.horasRecargoNocturno')
            ->addSelect('sc.horasRecargoFestivoDiurno')
            ->addSelect('sc.horasRecargoFestivoNocturno')
            ->addSelect('sc.novedad')
            ->leftJoin('sc.contratoRel', 'c')
            ->where('sc.codigoSoporteFk = ' . $id);
        $arSoporteContratos = $queryBuilder->getQuery()->getResult();

        return $arSoporteContratos;
    }

    /**
     * @param $arSoporteContrato TurSoporteContrato
     */
    public function generarHoras($arSoporte, $arSoporteContrato, $arFestivos) {
        $em = $this->getEntityManager();
        $intDiaInicial = $arSoporteContrato['fechaDesde']->format('j');
        $intDiaFinal = $arSoporteContrato['fechaHasta']->format('j');
        $dateFechaDesde = $arSoporteContrato['fechaDesde'];
        $dateFechaHasta = $arSoporteContrato['fechaHasta'];
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->periodoDias($dateFechaDesde->format('Y'), $dateFechaDesde->format('m'), $arSoporteContrato['codigoEmpleadoFk']);
        for ($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $nuevafecha = strtotime('+1 day', strtotime($strFecha));
            $dateFecha2 = date('Y/m/j', $nuevafecha);
            $dateFecha2 = date_create($dateFecha2);
            $boolFestivo = FuncionesController::verificarFestivoArray($arFestivos, $dateFecha);
            $boolFestivo2 = FuncionesController::verificarFestivoArray($arFestivos, $dateFecha2);
            $laborado = false;
            $arrTurnos = array();

            foreach ($arProgramaciones as $arProgramacion) {
                if ($arProgramacion['dia' . $i] != "") {
                    /** @var $arTurno TurTurno */
                    $arTurno = $em->getRepository(TurTurno::class)->find($arProgramacion['dia' . $i]);
                    if ($arTurno) {
                        $complementario = $arTurno->getComplementario();
                        if(!$complementario && $arProgramacion['complementario']) {
                            $complementario = true;
                        }
                        $arrTurnos[] = [
                            'turno' => $arTurno->getCodigoTurnoPk(),
                            'horaDesde' => $arTurno->getHoraDesde(),
                            'horaHasta' => $arTurno->getHoraHasta(),
                            'novedad' => $arTurno->getNovedad(),
                            'descanso' => $arTurno->getDescanso(),
                            'completo' => $arTurno->getCompleto(),
                            'vacacion' => $arTurno->getVacacion(),
                            'licencia' => $arTurno->getLicencia(),
                            'licenciaNoRemunerada' => $arTurno->getLicenciaNoRemunerada(),
                            'ausentismo' => $arTurno->getAusentismo(),
                            'induccion' => $arTurno->getInduccion(),
                            'incapacidad' => $arTurno->getIncapacidad(),
                            'ingreso' => $arTurno->getIngreso(),
                            'codigoProgramacionPk' => $arProgramacion['codigoProgramacionPk'],
                            'complementario' => $complementario,
                        ];
                        if (!$arTurno->getNovedad() && !$arTurno->getDescanso()) {
                            $laborado = true;
                        }
                    }
                }
            }
            $horasIniciales = 0;

            foreach ($arrTurnos as $arrTurno) {
                /*if ($codigoProgramacionDetalle) {
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);
                }*/

                /*$strTurnoFijoNomina = $arSoportePagoPeriodo->getCentroCostoRel()->getCodigoTurnoFijoNominaFk();
                $strTurnoFijoDescanso = $arSoportePagoPeriodo->getCentroCostoRel()->getCodigoTurnoFijoDescansoFk();

                if ($arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
                    $strTurnoFijoNomina = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk();
                }
                if ($arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk()) {
                    $strTurnoFijoDescanso = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk();
                }*/



                if ($arrTurno['descanso'] && $arrTurno['novedad']) {
                    /*if ($strTurnoFijoNomina) {
                        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoNomina);
                    }
                    if ($dateFecha->format('d') == 31) {
                        if ($arSoportePago->getRecursoRel()->getCodigoTurnoFijo31Fk()) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arSoportePago->getRecursoRel()->getCodigoTurnoFijo31Fk());
                        }
                    }*/
                }
                if ($arrTurno['descanso']) {
                    /*if ($strTurnoFijoDescanso) {
                        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoDescanso);
                    }*/
                }

                $intDias = 0;
                $intMinutoInicio = (($arrTurno['horaDesde']->format('i') * 100) / 60) / 100;
                $intHoraInicio = $arrTurno['horaDesde']->format('G');
                $intHoraInicio += $intMinutoInicio;
                $intMinutoFinal = (($arrTurno['horaHasta']->format('i') * 100) / 60) / 100;
                $intHoraFinal = $arrTurno['horaHasta']->format('G');
                $intHoraFinal += $intMinutoFinal;
                $diaSemana = $dateFecha->format('N');
                $diaSemana2 = $dateFecha2->format('N');
                //Debe ser sin novedades ya que no cuentan como dias
                if ($arrTurno['novedad'] == false && $arrTurno['complementario'] == false) {
                    $intDias += 1;
                }
                if ($diaSemana == 7) {
                    $boolFestivo = 1;
                }
                if ($diaSemana2 == 7) {
                    $boolFestivo2 = 1;
                }
                $arrHoras1 = null;
                if ($arrTurno['complementario']) {
                    $boolComplementario = true;
                }

                if (($intHoraInicio) <= $intHoraFinal && $arrTurno['completo'] == 0) {
                    $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $horasIniciales, $dateFecha, $arrTurno);
                    $horasTotales = $arrHoras['horas'] + $arrHoras1['horas'];
                } else {
                    $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, $horasIniciales, $dateFecha, $arrTurno);
                    $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $dateFecha, $arrTurno);
                    $horasTotales = $arrHoras1['horas'];
                }
                /*if ($arrTurno->getDescanso() == 1) {
                    if ($arSoportePago->getDescansoOrdinario()) {
                        $arrHoras['horasDescanso'] = 8;
                    }
                }*/
                /*if ($arrTurno->getNovedad() == 0 && $arSoportePago->getTurnoFijo()) {
                    $arrHoras = $this->horasOrdinarias();
                    $arrHoras1 = $this->horasAnuladas();
                }*/

                $arSoporteHora = new TurSoporteHora();
                $arSoporteHora->setSoporteRel($arSoporte);
                $arSoporteHora->setSoporteContratoRel($em->getReference(TurSoporteContrato::class, $arSoporteContrato['codigoSoporteContratoPk']));
                $arSoporteHora->setAnio($arSoporteContrato['anio']);
                $arSoporteHora->setMes($arSoporteContrato['mes']);
                $arSoporteHora->setFecha($dateFecha);
                $arSoporteHora->setFechaReal($dateFecha);
                $arSoporteHora->setTurnoRel($em->getReference(TurTurno::class, $arrTurno['turno']));
                /*if ($validarDescansoLaborado) {
                    if ($laborado == false) {
                        $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
                    }
                } else {
                    $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
                }*/

                //Se agrega esta validacion para los dias de descanso que estan como adicionales no cuenten para auxilio transporte
                /*if ($laborado && $arProgramacionDetalle->getAdicional()) {
                    $intDias = 0;
                }*/
                $arSoporteHora->setNovedad($arrTurno['novedad']);
                $arSoporteHora->setIncapacidad($arrTurno['incapacidad']);
                //$arSoporteHora->setIncapacidadNoLegalizada($arrTurno['incapacidadNoLegalizada']);
                $arSoporteHora->setLicencia($arrTurno['licencia']);
                $arSoporteHora->setLicenciaNoRemunerada($arrTurno['licenciaNoRemunerada']);
                $arSoporteHora->setVacacion($arrTurno['vacacion']);
                $arSoporteHora->setAusentismo($arrTurno['ausentismo']);
                $arSoporteHora->setIngreso($arrTurno['ingreso']);
                $arSoporteHora->setInduccion($arrTurno['induccion']);
                $arSoporteHora->setComplementario($arrTurno['complementario']);
                /*if ($dateFecha->format('d') == 31) {
                    $arSoporteHora->setDia31(1);
                }*/

                $arSoporteHora->setRetiro($arTurno->getRetiro());
                $arSoporteHora->setDias($intDias);
                //$arSoporteHora->setHoras($arTurno->getHorasNomina());
                $arSoporteHora->setHorasDiurnas($arrHoras['horasDiurnas']);
                $arSoporteHora->setHorasNocturnas($arrHoras['horasNocturnas']);
                $arSoporteHora->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
                $arSoporteHora->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);

                $arSoporteHora->setHorasRecargoNocturno($arrHoras['horasRecargoNocturno']);
                $arSoporteHora->setHorasRecargoFestivoDiurno($arrHoras['horasRecargoFestivoDiurno']);
                $arSoporteHora->setHorasRecargoFestivoNocturno($arrHoras['horasRecargoFestivoNocturno']);

                $arSoporteHora->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
                $arSoporteHora->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
                $arSoporteHora->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
                $arSoporteHora->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
                $arSoporteHora->setHorasDescanso($arrHoras['horasDescanso']);
                $arSoporteHora->setHorasNovedad($arrHoras['horasNovedad']);

                /*if ($strTurnoFijoNomina) {
                    $arSoporteHora->setHorasDiurnas($arrHoras['horasDiurnas'] + $arrHoras['horasFestivasDiurnas']);
                    $arSoporteHora->setHorasFestivasDiurnas(0);
                    if ($dateFecha->format('d') == 31 && $arSoportePagoPeriodo->getPagarDia31() == false) {
                        $arSoporteHora->setHorasDiurnas(0);
                        $arSoporteHora->setHorasFestivasDiurnas(0);
                    }
                }*/
                if ($arrTurno['codigoProgramacionPk']) {
                    $arSoporteHora->setProgramacionRel($em->getReference(TurProgramacion::class, $arrTurno['codigoProgramacionPk']));
                    //$arSoporteHora->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
                    //$arSoporteHora->setPuestoRel($arProgramacionDetalle->getPuestoRel());
                    //$arSoporteHora->setAdicional($arProgramacionDetalle->getAdicional());
                    //$arSoporteHora->setClienteRel($arProgramacionDetalle->getProgramacionRel()->getClienteRel());
                }
                $arSoporteHora->setFestivo($boolFestivo);

                $em->persist($arSoporteHora);

                if ($arrHoras1) {
                    $arSoporteHora = new TurSoporteHora();
                    $arSoporteHora->setSoporteRel($arSoporte);
                    $arSoporteHora->setSoporteContratoRel($em->getReference(TurSoporteContrato::class, $arSoporteContrato['codigoSoporteContratoPk']));
                    $arSoporteHora->setAnio($arSoporteContrato['anio']);
                    $arSoporteHora->setMes($arSoporteContrato['mes']);
                    $arSoporteHora->setFecha($dateFecha);
                    $arSoporteHora->setFechaReal($dateFecha);
                    $arSoporteHora->setTurnoRel($em->getReference(TurTurno::class, $arrTurno['turno']));
                    $arSoporteHora->setDescanso($arrTurno['descanso']);
                    $arSoporteHora->setNovedad(0);
                    /*if ($dateFecha->format('d') == 31) {
                        $arSoporteHora->setDia31(1);
                    }*/

                    $arSoporteHora->setHorasDiurnas($arrHoras1['horasDiurnas']);
                    $arSoporteHora->setHorasNocturnas($arrHoras1['horasNocturnas']);
                    $arSoporteHora->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
                    $arSoporteHora->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);
                    $arSoporteHora->setHorasRecargoNocturno($arrHoras1['horasRecargoNocturno']);
                    $arSoporteHora->setHorasRecargoFestivoDiurno($arrHoras1['horasRecargoFestivoDiurno']);
                    $arSoporteHora->setHorasRecargoFestivoNocturno($arrHoras1['horasRecargoFestivoNocturno']);
                    $arSoporteHora->setDias(0);
                    $arSoporteHora->setHoras(0);

                    $arSoporteHora->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
                    $arSoporteHora->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
                    $arSoporteHora->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
                    $arSoporteHora->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
                    $arSoporteHora->setHorasDescanso($arrHoras1['horasDescanso']);
                    $arSoporteHora->setHorasNovedad($arrHoras1['horasNovedad']);
                    if ($arrTurno['codigoProgramacionPk']) {
                        $arSoporteHora->setProgramacionRel($em->getReference(TurProgramacion::class, $arrTurno['codigoProgramacionPk']));
                        //$arSoporteHora->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
                        //$arSoporteHora->setPuestoRel($arProgramacionDetalle->getPuestoRel());
                        //$arSoporteHora->setAdicional($arProgramacionDetalle->getAdicional());
                        //$arSoporteHora->setClienteRel($arProgramacionDetalle->getProgramacionRel()->getClienteRel());
                    }
                    $arSoporteHora->setFestivo($boolFestivo2);
                    $em->persist($arSoporteHora);
                }
            }
        }
    }

    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $dateFecha, $arrTurno)
    {
        if ($arrTurno['novedad'] == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if ($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if ($intHorasJornada >= 0.1) {
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
                if ($intHorasJornada > 0.1) {
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
                if ($intHorasJornada > 0.1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
//
//            // validacion para turnos complementarios
            /*if ($intHoras > 0 && $arrTurno['complementario'] || $arrTurnoAnt['complementario'] && $arrTurnoAnt['turno'] <> $strTurno) {

                $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 21, 24);
                $intTotalHoras = $intHorasNocturnas + $intHoras + $intHorasNocturnasNoche + $intHorasDiurnas;

                $intHorasNocturnasNoche = $intHorasNocturnas - $this->calcularTiempo($intHoraInicio, $intHoraFinal, 21, 24);
                if ($intHorasNocturnasNoche < 0) {
                    $intHorasNocturnasNoche = 0;
                }
                if ($intTotalHoras > 8) {
                    $intHorasJornada = 8 - $intHoras;
                    if ($intHorasJornada >= 0.1) {
                        $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                        if ($intHorasNocturnasReales < 0) {
                            $intHorasNocturnasReales = 0;
                        }
                        $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                        $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                        $intHorasNocturnasNoche = 0;
                    } else {
                        $intHorasExtrasNocturnas = $intHorasNocturnas;
                        $intHorasNocturnas = 0;
                        $intHorasNocturnasNoche = 0;
                    }
                }

                $intHorasExtrasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
                $intTotalHoras = $intHorasExtrasNocturnasNoche + $intHoras;
                if ($intTotalHoras > 8) {
                    $intHorasJornada = 8 - $intHoras;
                    if ($intHorasJornada >= 0.1) {
                        $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                        $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                        $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                        $intHorasNocturnasNoche = 0;
                    } else {
                        //  $intHorasExtrasNocturnasNoche = $intHorasExtrasNocturnas;
                        $intHorasExtrasNocturnas = $intHorasNocturnas;
                        $intHorasNocturnas = 0;
                    }
                }
                $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 21);
                $intHorasExtrasDiurnas = 0;
                $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
                if ($intTotalHoras > 8) {
                    $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);
                    if ($intHorasJornada > 0.1) {
                        $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                        $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                        $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                        //        $intHorasNocturnas = 0;
                    } else {
                        $intHorasExtrasDiurnas = $intHorasDiurnas;
                        $intHorasDiurnas = 0;
                    }
                }
            }*/

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
            if ($intHoras > 0) {
                $intTotalHoras = $intTotalHoras + $intHoras;
            }
            if ($arrTurno['descanso'] == 1) {
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

}

