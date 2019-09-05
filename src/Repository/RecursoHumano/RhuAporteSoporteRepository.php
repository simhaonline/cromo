<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuTrasladoPension;
use App\Entity\RecursoHumano\RhuTrasladoSalud;
use App\Entity\RecursoHumano\RhuVacacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteSoporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporteSoporte::class);
    }

    public function listaSoporte($codigoSoporteContrato)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteSoporte::class, 'asop')
            ->select('asop.codigoAporteSoportePk')
            ->where('asop.codigoAporteContratoFk=' . $codigoSoporteContrato);
        $arAporteSoportes = $queryBuilder->getQuery()->getResult();
        return $arAporteSoportes;
    }

    public function listaGenerarDetalle($codigoSoporteContrato)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteSoporte::class, 'asop')
            ->select('asop.codigoAporteSoportePk')
            ->where('asop.codigoAporteContratoFk =' . $codigoSoporteContrato);
        $arAporteSoportes = $queryBuilder->getQuery()->getResult();
        return $arAporteSoportes;
    }

    /**
     * @param $arAporte RhuAporte
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generar($arAporte) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = $em->getRepository(RhuConfiguracion::class)->generarAporte();
        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->listaGenerarSoporte($arAporte->getCodigoAportePk());
        $salarioMinimo = $arConfiguracionNomina['vrSalarioMinimo'];
        foreach ($arAporteContratos as $arAporteContrato) {
            /** @var $arAporteContratoActualizar RhuAporteContrato */
            $arAporteContratoActualizar = $em->getRepository(RhuAporteContrato::class)->find($arAporteContrato['codigoAporteContratoPk']);
            $arContrato = $em->getRepository(RhuContrato::class)->find($arAporteContrato['codigoContratoFk']);
            $novedadIngreso = " ";
            $novedadRetiro = " ";
            $diasCotizar = 0;
            if ($arAporteContrato['indefinido'] == 1) {
                $dateFechaHasta = $arAporte->getFechaHasta();
            } else {
                if ($arAporteContrato['fechaHasta'] > $arAporte->getFechaHasta()) {
                    $dateFechaHasta = $arAporte->getFechaHasta();
                } else {
                    $dateFechaHasta = $arAporteContrato['fechaHasta'];
                }
            }

            if ($arAporteContrato['fechaDesde'] < $arAporte->getFechaDesde() == true) {
                $dateFechaDesde = $arAporte->getFechaDesde();
            } else {
                $dateFechaDesde = $arAporteContrato['fechaDesde'];
            }

            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $dias = $dateFechaDesde->diff($dateFechaHasta);
                $dias = $dias->format('%a');
                $diasCotizar = $dias + 1;
                if ($diasCotizar == 31) {
                    $diasCotizar = $diasCotizar - 1;
                } else {
                    if ($arAporte->getFechaHasta()->format('d') == 28) {
                        if ($arAporteContrato['fechaHasta'] >= $arAporte->getFechaHasta() || $arAporteContrato['indefinido'] == 1) {
                            $diasCotizar = $diasCotizar + 2;
                        }
                    }
                    if ($arAporte->getFechaHasta()->format('d') == 29) {
                        if ($arAporteContrato['fechaHasta'] >= $arAporte->getFechaHasta() || $arAporteContrato['indefinido'] == 1) {
                            $diasCotizar = $diasCotizar + 1;
                        }
                    }
                    if ($arAporte->getFechaHasta()->format('d') == 31) {
                        if ($arAporteContrato['fechaHasta'] >= $arAporte->getFechaHasta() || $arAporteContrato['indefinido'] == 1) {
                            if ($arAporteContrato['fechaDesde']->format('d') != 31) {
                                $diasCotizar = $diasCotizar - 1;
                            }
                        }
                    }
                }
            }

            if ($arAporteContrato['fechaDesde'] >= $arAporte->getFechaDesde()) {
                $novedadIngreso = "X";
            }

            if ($arAporteContrato['indefinido'] == 0 && $arAporteContrato['fechaHasta'] <= $arAporte->getFechaHasta()) {
                $novedadRetiro = "X";
            }

            $salario = $arAporteContrato['vrSalario'];
            $diaSalario = $salario / 30;
            if ($arAporteContrato['salarioIntegral']) {
                $arAporteContratoActualizar->setSalarioIntegral("X");
            }
            //Este es para generarlo varias veces y que elimine las anteriores
            //$strSql = "DELETE FROM rhu_sso_periodo_empleado_detalle WHERE codigo_periodo_empleado_fk = " . $arPeriodoEmpleadoActualizar->getCodigoPeriodoEmpleadoPk();
            //$em->getConnection()->executeQuery($strSql);

            $arrIbcOrdinario = $em->getRepository(RhuPagoDetalle::class)->ibcOrdinario($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'), $arAporteContrato['codigoContratoFk']);

            $diasLicenciaTotal = 0;
            $diasIncapacidadTotal = 0;
            $diasVacacionesTotal = 0;
            $diasLicenciaMaternidad = 0;
            $diasLicencia = 0;
            $diasIncapacidadLaboral = 0;
            $diasIncapacidad = 0;
            $diasVacaciones = 0;
            $ibcVacaciones = 0;


            $arrLicenciasPeriodo = $em->getRepository(RhuLicencia::class)->listaLicenciasMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arAporteContrato['codigoEmpleadoFk']);
            $arrLicencias = $this->setDiasLicenciaMes($arrLicenciasPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());
            foreach ($arrLicencias as $arrLicencia) {
                $diasLicenciaTotal += $arrLicencia['dias'];
            }

            $arrIncapacidades = NULL;
            $arrIncapacidadesPeriodo = $em->getRepository(RhuIncapacidad::class)->listaIncapacidadMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arAporteContrato['codigoEmpleadoFk']);
            $arrIncapacidades = $this->setDiasIncapacidadMes($arrIncapacidadesPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());
            foreach ($arrIncapacidades as $arrIncapacidad) {
                $diasIncapacidadTotal += $arrIncapacidad['dias'];
            }
            $arrVacaciones = NULL;
            $arrVacacionesPeriodo = $em->getRepository(RhuVacacion::class)->listaVacacionesMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arAporteContrato['codigoEmpleadoFk']);
            $arrVacaciones = $this->setDiasVacacionMes($arrVacacionesPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());

            foreach ($arrVacaciones as $arrVacacion) {
                $diasVacacionesTotal += $arrVacacion['dias'];
            }

            $arAporteContratoActualizar->setDiasLicencia($diasLicenciaTotal);
            $arAporteContratoActualizar->setDiasIncapacidad($diasIncapacidadTotal);
            $arAporteContratoActualizar->setDiasVacaciones($diasVacacionesTotal);
            $diasOrdinariosTotal = $diasCotizar - $diasLicenciaTotal - $diasIncapacidadTotal - $diasVacacionesTotal;
            // nos indica si ya aplico las novedades de ingreso y retiro para periodos sin dias ordinarios
            $novedadesIngresoRetiro = FALSE;
            // nos indica si ya aplico la novedad de traslado de eps para periodos sin dias ordinarios
            $novedadTrasladoEntidad = false;
            // nos indica si ya aplico la novedad de traslado de pension para periodos sin dias ordinarios
            $novedadTrasladoPension = false;
            // nos indica si ya aplico la novedad de traslado de eps para periodos sin dias ordinarios
            $novedadTrasladoDesdeOtraEntidad = false;
            // nos indica si ya aplico la novedad de traslado de pension para periodos sin dias ordinarios
            $novedadTrasladoDesdeOtraPension = false;



            //Vacaciones
            $diasVacaciones = 0;
            $ibcVacaciones = 0;
            foreach ($arrVacaciones as $arrVacacion) {
                $arVacacion = $em->getRepository(RhuVacacion::class)->find($arrVacacion['codigoVacacionFk']);
                $ibcVacacion = 0;
                //validacion para verificar si la vacacion cambia de mes  (febrero)
                if ($arAporte->getMes() == '2' && $arAporte->getMes() < $arVacacion->getFechaHastaDisfrute()->format('n')) {
                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arAporte->getMes() + 1, 1, $arAporte->getAnio()) - 1));
                    if ($intUltimoDia == 29) {
                        $arrVacacion['dias'] -= 1;
                    } else {
                        $arrVacacion['dias'] -= 2;
                    }
                }
                $diasVacaciones += $arrVacacion['dias'];
                $arrIbc = $em->getRepository(RhuAporteDetalle::class)->ibcMesAnterior($arVacacion->getFechaDesdeDisfrute()->format('Y'), $arVacacion->getFechaDesdeDisfrute()->format('m'), $arContrato->getCodigoEmpleadoFk());
                if ($arrIbc['respuesta'] == false) {
                    $arrIbc['dias'] = $arrVacacion['dias'];
                    $arrIbc['ibc'] = ceil($arrVacacion['dias'] * $diaSalario);
                }
                if ($arrIbc) {
                    $ibcDia = 0;
                    if ($arrIbc['dias'] > 0) {
                        $ibcDia = $arrIbc['ibc'] / $arrIbc['dias'];
                    }

                    if (intval($arVacacion->getFechaDesdeDisfrute()->format("m")) < intval($arVacacion->getFechaHastaDisfrute()->format("m"))) {
                        $intUltimoDia = cal_days_in_month(CAL_GREGORIAN, intval($arVacacion->getFechaDesdeDisfrute()->format("m")), intval($arVacacion->getFechaDesdeDisfrute()->format("m")));
                        if ($intUltimoDia == 31 && $arAporte->getMes() == $arVacacion->getFechaDesdeDisfrute()->format("m")) {
                            $ibcVacacion = ($arrVacacion['dias'] + 1) * $ibcDia;
                        } else {
                            $ibcVacacion = $arrVacacion['dias'] * $ibcDia;
                        }
                    } else {
                        $ibcVacacion = $arrVacacion['dias'] * $ibcDia;
                    }
                    $ibcVacacion = round($ibcVacacion);
                }
                $ibcVacaciones += $ibcVacacion;

                $ibcMinimo = ($salarioMinimo / 30) * $diasVacaciones;
                if ($ibcVacacion < $ibcMinimo) {
                    $arrVacacion['ibc'] = ceil($ibcMinimo);
                }
                $ibcCaja = 0;
                if ($arVacacion->getDiasDisfrutadosReales() > 0) {
                    $ibcDia = $arVacacion->getVrBruto() / $arVacacion->getDiasDisfrutadosReales();
                    $ibcCaja = $ibcDia * $arrVacacion['dias'];
                    if (intval($arVacacion->getFechaDesdeDisfrute()->format("m")) < intval($arVacacion->getFechaHastaDisfrute()->format("m"))) {
                        $intUltimoDia = cal_days_in_month(CAL_GREGORIAN, intval($arVacacion->getFechaDesdeDisfrute()->format("m")), intval($arVacacion->getFechaDesdeDisfrute()->format("m")));
                        if ($intUltimoDia == 31 && $arAporte->getMes() == $arVacacion->getFechaDesdeDisfrute()->format("m")) {
                            $ibcCaja = $ibcDia * ($arrVacacion['dias'] + 1);
                        }
                    }

                }

                if ($diasVacaciones > 0) {
                    $arAporteSoporte = new RhuAporteSoporte();
                    $arAporteSoporte->setAporteRel($arAporte);
                    $arAporteSoporte->setAporteContratoRel($arAporteContratoActualizar);
                    $arAporteSoporte->setDias($arrVacacion['dias']);
                    $arAporteSoporte->setHoras(intval($arrVacacion['horas']));
                    $arAporteSoporte->setVrSalario($salario);
                    $arAporteSoporte->setIbc($ibcVacacion);
                    $arAporteSoporte->setIbcCajaVacaciones($ibcCaja);
                    $arAporteSoporte->setVacaciones(1);
                    $porcentaje = $arContrato->getPensionRel()->getPorcentajeEmpleador() + 4;
                    $arAporteSoporte->setTarifaPension($porcentaje);
                    if ($arContrato->getSalarioIntegral()) {
                        $arAporteSoporte->setTarifaSalud(12.5);
                    } else {
                        $arAporteSoporte->setTarifaSalud(4);
                    }

                    $arAporteSoporte->setTarifaCaja(4);
                    $arAporteSoporte->setFechaDesde(date_create($arrVacacion['fechaDesdeNovedad']));
                    $arAporteSoporte->setFechaHasta(date_create($arrVacacion['fechaHastaNovedad']));
                    $diaSalarioVacacion = $ibcVacaciones / $arrVacacion['dias'];
                    if ($diaSalarioVacacion != $diaSalario) {
                        $arAporteSoporte->setVariacionTransitoriaSalario('X');
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                        $arAporteSoporte->setIngreso($novedadIngreso);
                        $arAporteSoporte->setRetiro($novedadRetiro);
                        if ($novedadRetiro == 'X') {
                            $arAporteSoporte->setFechaRetiro($arContrato->getFechaHasta());
                            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
                            if ($arLiquidacion) {
                                $ibcVacaciones = $arLiquidacion->getVrVacaciones();
                                $arAporteSoporte->setVrVacaciones($ibcVacaciones);
                            }
                        }
                        if ($novedadIngreso == "X") {
                            $arAporteSoporte->setFechaIngreso($arContrato->getFechaDesde());
                        }
                        $novedadesIngresoRetiro = TRUE;
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                        //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                        $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                        if ($arrTrasladoSaludEmpleado) {
                            $arAporteSoporte->setTrasladoAOtraEps(true);
                            $arAporteSoporte->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                            $novedadTrasladoEntidad = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                        //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                        $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde());
                        if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                            $arAporteSoporte->setTrasladoDesdeOtraEps(true);
                            $novedadTrasladoDesdeOtraEntidad = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                        //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                        $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                        if ($arrTrasladoPensionEmpleado) {
                            $arAporteSoporte->setTrasladoAOtraPension(true);
                            $arAporteSoporte->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                            $novedadTrasladoPension = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                        //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                        $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde());
                        if ($arrTrasladoDesdeOtraPensionEmpleado) {
                            $arAporteSoporte->setTrasladoDesdeOtraPension(true);
                            $novedadTrasladoDesdeOtraPension = true;
                        }
                    }
                    $em->persist($arAporteSoporte);
                }
            }

            //Incapacidades
            $diasIncapacidad = 0;
            $diasIncapacidadLaboral = 0;
            foreach ($arrIncapacidades as $arrIncapacidad) {
                $arAporteSoporte = new RhuAporteSoporte();
                $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($arrIncapacidad['codigoIncapacidadFk']);
                $incapacidadFechaHasta = date_create($arrIncapacidad['fechaHastaNovedad']);
                // validacion por si el empleado estuvo incapacidado todo el mes de febrero
                if ($arAporte->getMes() == '2' && ($arrIncapacidad['dias'] == 28 || $arrIncapacidad['dias'] == 29)) {
                    $arrIncapacidad['dias'] = 30;
                }
                //validacion para verificar si la incapacida cambia de mes  (febrero)
                if ($arAporte->getMes() == '2' && $arAporte->getMes() < $arIncapacidad->getFechaHasta()->format('n')) {
                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arAporteContrato->getSsoPeriodoRel()->getMes() + 1, 1, $arAporte->getAnio()) - 1));
                    if ($intUltimoDia == 29) {
                        $arrIncapacidad['dias'] += 1;
                    } else {
                        $arrIncapacidad['dias'] += 2;
                    }
                }
                $diasIncapacidadIndividual = $arrIncapacidad['dias'];
                $diasIncapacidadIndividualReportar = $arrIncapacidad['dias'];

                if ($incapacidadFechaHasta->format('d') == "31") {
                    $diasIncapacidadIndividual--;
                }
                if ($arIncapacidad->getCodigoIncapacidadTipoFk() == "GEN") {
                    if (($diasIncapacidadLaboral + $diasIncapacidad) + $diasIncapacidadIndividualReportar > 30) {
                        $diasIncapacidadIndividualReportar--;
                    }
                    $diasIncapacidad += $diasIncapacidadIndividualReportar;
                    $arAporteSoporte->setIncapacidadGeneral(1);
                } else {
                    if (($diasIncapacidadLaboral + $diasIncapacidad) + $diasIncapacidadIndividualReportar > 30) {
                        $diasIncapacidadIndividualReportar--;
                    }
                    $diasIncapacidadLaboral += $diasIncapacidadIndividualReportar;
                    $arAporteSoporte->setIncapacidadLaboral(1);
                }

                $arAporteSoporte->setAporteRel($arAporte);
                $arAporteSoporte->setAporteContratoRel($arAporteContratoActualizar);
                $arAporteSoporte->setDias($diasIncapacidadIndividualReportar);
                $arAporteSoporte->setHoras(intval($arrIncapacidad['horas']));
                $arAporteSoporte->setVrSalario($salario);
                $ibcIncapacidad = ceil($arrIncapacidad['ibc']);
                if ($arrIncapacidad['dias'] > 0) {
                    $ibcMinimo = ($salarioMinimo / 30) * $arrIncapacidad['dias'];
                    if ($ibcIncapacidad < $ibcMinimo) {
                        $ibcIncapacidad = ceil($ibcMinimo);
                    }
                }
                $arAporteSoporte->setIbc($ibcIncapacidad);
                $porcentaje = $arContrato->getPensionRel()->getPorcentajeEmpleador() + 4;
                $arAporteSoporte->setTarifaPension($porcentaje);
                $arAporteSoporte->setTarifaSalud(4);

                $arAporteSoporte->setFechaDesde(date_create($arrIncapacidad['fechaDesdeNovedad']));
                $arAporteSoporte->setFechaHasta(date_create($arrIncapacidad['fechaHastaNovedad']));
                if ($diasIncapacidadIndividual > 0) {
                    $diaSalarioLicencia = $ibcIncapacidad / $diasIncapacidadIndividual;
                    if ($diaSalarioLicencia != $diaSalario) {
                        $arAporteSoporte->setVariacionTransitoriaSalario('X');
                    }
                }
                if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arAporteSoporte->setIngreso($novedadIngreso);
                    $arAporteSoporte->setRetiro($novedadRetiro);
                    if ($novedadRetiro == 'X') {
                        $arAporteSoporte->setFechaRetiro($arContrato->getFechaHasta());
                        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1, 'estadoAnulado' => 0));
                        if ($arLiquidacion) {
                            $ibcVacacionesLiquidacion = $arLiquidacion->getVrVacaciones();
                            $arAporteSoporte->setVrVacaciones($ibcVacacionesLiquidacion);
                            $arAporteSoporte->setTarifaCaja(4);
                        }
                    }
                    if ($novedadIngreso == "X") {
                        $arAporteSoporte->setFechaIngreso($arContrato->getFechaDesde());
                    }
                    $novedadesIngresoRetiro = TRUE;
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                    //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                    $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoSaludEmpleado) {
                        $arAporteSoporte->setTrasladoAOtraEps(true);
                        $arAporteSoporte->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                        $novedadTrasladoEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                    $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arAporteContrato->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                        $arAporteSoporte->setTrasladoDesdeOtraEps(true);
                        $novedadTrasladoDesdeOtraEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                    //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                    $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoPensionEmpleado) {
                        $arAporteSoporte->setTrasladoAOtraPension(true);
                        $arAporteSoporte->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                        $novedadTrasladoPension = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                    $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporteContrato->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraPensionEmpleado) {
                        $arAporteSoporte->setTrasladoDesdeOtraPension(true);
                        $novedadTrasladoDesdeOtraPension = true;
                    }
                }

                $em->persist($arAporteSoporte);
            }

            //Licencias
            $diasLicencia = 0;
            $diasLicenciaMaternidad = 0;
            foreach ($arrLicencias as $arrLicencia) {
                $arAporteSoporte = new RhuAporteSoporte();
                $arLicencia = $em->getRepository(RhuLicencia::class)->find($arrLicencia['codigoLicenciaFk']);
                $diasLicenciaIndividual = $arrLicencia['dias'];
                $diasLicenciaIndividualReportar = $arrLicencia['dias'];
                if ($arrLicencia['dias'] > 30) {
                    $diasLicenciaIndividual--;
                    $diasLicenciaIndividualReportar--;
                }
                if (($diasLicencia + $diasLicenciaMaternidad + $diasIncapacidad + $diasIncapacidadLaboral) + $diasLicenciaIndividualReportar > 30) {
                    $diasLicenciaIndividualReportar--;
                }
                if ($arLicencia->getLicenciaTipoRel()->getMaternidad() == 1 || $arLicencia->getLicenciaTipoRel()->getPaternidad() == 1) {
                    $diasLicenciaMaternidad += $diasLicenciaIndividualReportar;
                    $arAporteSoporte->setLicenciaMaternidad(1);
                    $ibcLicencia = $arrLicencia['ibc'];
                    if ($arrLicencia['dias'] > 0) {
                        $ibcMinimo = ($salarioMinimo / 30) * $arrLicencia['dias'];
                        if ($ibcLicencia < $ibcMinimo) {
                            $ibcLicencia = ceil($ibcMinimo);
                        }
                    }
                    $diaSalarioLicencia = $ibcLicencia / $diasLicenciaIndividualReportar;
                    if ($diaSalarioLicencia != $diaSalario) {
                        $arAporteSoporte->setVariacionTransitoriaSalario('X');
                    }
                    $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                    $arAporteSoporte->setTarifaPension($porcentaje);
                    $arAporteSoporte->setTarifaSalud(4);
                    /*if ($arConfiguracionNomina->getAportarCajaLicenciaMaternidadPaternidad()) {
                        $arAporteSoporte->setTarifaCaja(4);
                    }*/

                } else {
                    $ibcLicencia = $diaSalario * $diasLicenciaIndividualReportar;
                    // validacion para liquidar las licencia remuneradas con ibc anterior
                    //if ($arConfiguracionNomina->getLiquidarLicenciasIbcMesAnteriorSSo()) {
                        $diasIbcMesAnterior = $arLicencia->getDiasIbcMesAnterior();
                        $vrIbcMesAnterior = $arLicencia->getVrIbcMesAnterior();
                        if ($diasIbcMesAnterior > 0 && $vrIbcMesAnterior > 0) {
                            $diaSalarioIbcAnterior = $arLicencia->getVrIbcMesAnterior() / $arLicencia->getDiasIbcMesAnterior();
                            $ibcLicencia = $diaSalarioIbcAnterior * $diasLicenciaIndividualReportar;
                        }
                        if ($ibcLicencia < (($salarioMinimo / 30) * $diasLicenciaIndividualReportar)) {
                            $ibcLicencia = $diaSalario * $diasLicenciaIndividualReportar;
                        }
                    //}
                    // fin validacion
                    $diasLicencia += $diasLicenciaIndividualReportar;
                    $arAporteSoporte->setLicencia(1);
                    $arAporteSoporte->setTarifaPension(16);

                    if ($arLicencia->getLicenciaTipoRel()->getRemunerada()) {
                        $arAporteSoporte->setLicenciaRemunerada(1);
                        $porcentaje = $arContrato->getPensionRel()->getPorcentajeEmpleador() + 4;
                        $arAporteSoporte->setTarifaPension($porcentaje);
                        $arAporteSoporte->setTarifaSalud(4);
                        $arAporteSoporte->setTarifaCaja(4);
                    } else {
                        $arAporteSoporte->setTarifaSalud(0);
                    }

                }

                $arAporteSoporte->setIbc($ibcLicencia);
                $arAporteSoporte->setAporteRel($arAporte);
                $arAporteSoporte->setAporteContratoRel($arAporteContratoActualizar);
                $arAporteSoporte->setDias($diasLicenciaIndividualReportar);

                $arAporteSoporte->setHoras(intval($arrLicencia['horas']));
                $arAporteSoporte->setVrSalario($salario);
                $arAporteSoporte->setFechaDesde(date_create($arrLicencia['fechaDesdeNovedad']));
                $arAporteSoporte->setFechaHasta(date_create($arrLicencia['fechaHastaNovedad']));
                if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arAporteSoporte->setIngreso($novedadIngreso);
                    $arAporteSoporte->setRetiro($novedadRetiro);
                    if ($novedadRetiro == 'X') {
                        $arAporteSoporte->setFechaRetiro($arContrato->getFechaHasta());
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1, 'estadoAnulado' => 0));
                        if ($arLiquidacion) {
                            $ibcVacacionesLiquidacion = $arLiquidacion->getVrVacaciones();
                            $arAporteSoporte->setVrVacaciones($ibcVacacionesLiquidacion);
                            $arAporteSoporte->setTarifaCaja(4);
                        }
                    }
                    if ($novedadIngreso == "X") {
                        $arAporteSoporte->setFechaIngreso($arContrato->getFechaDesde());
                    }
                    $novedadesIngresoRetiro = TRUE;
                }
                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                    //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                    $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoSaludEmpleado) {
                        $arAporteSoporte->setTrasladoAOtraEps(true);
                        $arAporteSoporte->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                        $novedadTrasladoEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                    $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde());
                    if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                        $arAporteSoporte->setTrasladoDesdeOtraEps(true);
                        $novedadTrasladoDesdeOtraEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                    //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                    $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoPensionEmpleado) {
                        $arAporteSoporte->setTrasladoAOtraPension(true);
                        $arAporteSoporte->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                        $novedadTrasladoPension = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                    $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde());
                    if ($arrTrasladoDesdeOtraPensionEmpleado) {
                        $arAporteSoporte->setTrasladoDesdeOtraPension(true);
                        $novedadTrasladoDesdeOtraPension = true;
                    }
                }
                $em->persist($arAporteSoporte);
            }


            $diasOrdinarios = $diasCotizar - $diasLicenciaMaternidad - $diasLicencia - $diasIncapacidadLaboral - $diasIncapacidad - $diasVacaciones;
            $horasOrdinarias = $diasOrdinarios * 8;
            $ibc = $arrIbcOrdinario['ibc'];
            $ibc = round($ibc);
            $ibcMinimo = ($salarioMinimo / 30) * $diasOrdinarios;
            if ($ibc < $ibcMinimo) {
                $ibc = ceil($ibcMinimo);
            }
            //Para verificar si el empleado alcansa el monto de cotizacion de fondos de solidaridad
            $ibcFS = $ibc + $ibcVacaciones;

            if ($diasOrdinarios > 0) {
                $arAporteSoporte = new RhuAporteSoporte();
                $arAporteSoporte->setAporteRel($arAporte);
                $arAporteSoporte->setAporteContratoRel($arAporteContratoActualizar);
                $arAporteSoporte->setDias($diasOrdinarios);
                $arAporteSoporte->setHoras(intval($horasOrdinarias));
                $arAporteSoporte->setVrSalario($salario);
                $arAporteSoporte->setIbc($ibc);
                if ($diasVacacionesTotal <= 0 && $ibcVacaciones > 0) {
                    $arAporteSoporte->setVrVacaciones($ibcVacaciones);
                }
                $porcentaje = $arAporteContrato['pensionPorcentajeEmpleador'] + 4;
                $arAporteSoporte->setTarifaPension($porcentaje);
                $arAporteSoporte->setTarifaSalud(4);
                $arAporteSoporte->setTarifaRiesgos($arAporteContrato['clasificacionRiesgoPorcentaje']);
                $arAporteSoporte->setTarifaCaja(4);
                $arAporteSoporte->setIngreso($novedadIngreso);
                $arAporteSoporte->setRetiro($novedadRetiro);
                if ($novedadRetiro == 'X') {
                    $arAporteSoporte->setFechaRetiro($arAporteContrato['fechaHasta']);
                    /*$arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1, 'estadoAnulado' => 0));
                    if ($arLiquidacion) {
                        $ibcVacacionesLiquidacion = $arLiquidacion->getVrVacaciones();
                        $arAporteSoporte->setVrVacaciones($ibcVacacionesLiquidacion);
                    }*/
                }
                if ($novedadIngreso == "X") {
                    $arAporteSoporte->setFechaIngreso($arAporteContrato['fechaDesde']);
                }
                $diaSalarioOrdinario = 0;
                if ($diasOrdinarios > 0) {
                    $diaSalarioOrdinario = $ibc / $diasOrdinarios;
                } else {
                    //echo "Problema dias " . $arPeriodoEmpleado->getEmpleadoRel()->getNumeroIdentificacion() . " ";
                }
                if (round($diaSalarioOrdinario) != round($diaSalario)) {
                    $arAporteSoporte->setVariacionTransitoriaSalario('X');
                }
                /*
                //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                if ($arrTrasladoSaludEmpleado) {
                    $arPeriodoEmpleadoDetalle->setTrasladoAOtraEps(true);
                    $arPeriodoEmpleadoDetalle->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                    $novedadTrasladoEntidad = true;
                }

                //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                    $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraEps(true);
                    $novedadTrasladoDesdeOtraEntidad = true;
                }

                //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                if ($arrTrasladoPensionEmpleado) {
                    $arPeriodoEmpleadoDetalle->setTrasladoAOtraPension(true);
                    $arPeriodoEmpleadoDetalle->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                    $novedadTrasladoPension = true;
                }

                //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                if ($arrTrasladoDesdeOtraPensionEmpleado) {
                    $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraPension(true);
                    $novedadTrasladoDesdeOtraPension = true;
                }*/
                $em->persist($arAporteSoporte);
            }
            $arAporteContratoActualizar->setCodigoEntidadSaludPertenece($arAporteContrato['entidadSaludCodigo']);
            $arAporteContratoActualizar->setCodigoEntidadPensionPertenece($arAporteContrato['entidadPensionCodigo']);
            $arAporteContratoActualizar->setCodigoEntidadCajaPertenece($arAporteContrato['entidadCajaCodigo']);
            $arAporteContratoActualizar->setVrSalario($salario);
            $arAporteContratoActualizar->setDias($diasCotizar);
            $arAporteContratoActualizar->setIbc($ibc);
            $arAporteContratoActualizar->setIbcFondoSolidaridad($ibcFS);


            $em->persist($arAporteContratoActualizar);
            //$arPeriodoDetalle->setEstadoActualizado(1);
            //$em->persist($arPeriodoDetalle);
        }
        $em->flush();
    }

    public function setDiasLicenciaMes($arrLicencias, $fechaDesdePeriodo, $fechaHastaPeriodo, $vrSalario)
    {
        $em = $this->getEntityManager();
        $respuesta = [];

        foreach ($arrLicencias as $licencia) {// recorremos cada licencia y la evaluamos
            $dias = 0;
            $fechaInicio = $licencia['fechaDesde'];
            $fechaFinal = $licencia['fechaHasta'];
            $diaFinMes = cal_days_in_month(CAL_GREGORIAN, $fechaDesdePeriodo->format("m"), $fechaFinal->format("Y"));
            $fechaDesde = $fechaInicio;
            $fechaHasta = $fechaFinal;
            if ($fechaDesde->format('d') == 31 && $fechaDesde->format('d') == 31) {
                continue;
            }

            if ($fechaDesde <= $fechaDesdePeriodo) {
                $fechaDesde = $fechaDesdePeriodo;
            }
            if ($fechaHasta >= $fechaHastaPeriodo) {
                $fechaHasta = $fechaHastaPeriodo;
                if ($fechaHasta->format('d') == 31) {
                    $fechaHasta = date_create($fechaHasta->format('Y-m') . "-30");
                }
            }
            $diasTemporal = $fechaDesde->diff($fechaHasta);
            $dias = $diasTemporal->format('%a') + 1;
            if ($fechaHastaPeriodo->format('n') == 2 && $fechaFinal >= $fechaHastaPeriodo) {
                if ($diaFinMes == 28) {
                    $dias += 2;
                } else {
                    $dias++;
                }
            }
            if ($dias >= 31) {
                $dias = 30;
            }

            $vrHora = $vrSalario / 30 / 8;
            $ibc = $vrHora * ($dias * 8);
            $obj = array(
                'codigoLicenciaFk' => $licencia['codigoLicenciaPk'],
                'dias' => $dias,
                'horas' => $dias * 8,
                'fechaDesdeNovedad' => $fechaDesde->format('Y-m-d'),
                'fechaHastaNovedad' => $fechaHasta->format('Y-m-d'),
                'ibc' => $ibc

            );
            array_push($respuesta, $obj);

        }

        return $respuesta;
    }

    public function setDiasIncapacidadMes($arrIncapacidades, $fechaDesdePeriodo, $fechaHastaPeriodo, $vrSalario)
    {
        $em = $this->getEntityManager();
        $respuesta = [];

        foreach ($arrIncapacidades as $incapacidad) {// recorremos cada licencia y la evaluamos
            $dias = 0;
            $fechaInicio = $incapacidad['fechaDesde'];
            $fechaFinal = $incapacidad['fechaHasta'];
            $diaFinMes = cal_days_in_month(CAL_GREGORIAN, $fechaDesdePeriodo->format("m"), $fechaFinal->format("Y"));
            $fechaDesde = $fechaInicio;
            $fechaHasta = $fechaFinal;
            if ($fechaDesde->format('d') == 31 && $fechaDesde->format('d') == 31) {
                continue;
            }

            if ($fechaDesde <= $fechaDesdePeriodo) {
                $fechaDesde = $fechaDesdePeriodo;
            }
            if ($fechaHasta >= $fechaHastaPeriodo) {
                $fechaHasta = $fechaHastaPeriodo;
                if ($fechaHasta->format('d') == 31) {
                    $fechaHasta = date_create($fechaHasta->format('Y-m') . "-30");
                }
            }
            $diasTemporal = $fechaDesde->diff($fechaHasta);
            $dias = $diasTemporal->format('%a') + 1;
            if ($fechaHastaPeriodo->format('n') == 2 && $fechaFinal >= $fechaHastaPeriodo) {
                if ($diaFinMes == 28) {
                    $dias += 2;
                } else {
                    $dias++;
                }
            }
            if ($dias >= 31) {
                $dias = 30;
            }

            $vrDia = 0;
            if ($incapacidad["generaIbc"] == 1) {
                $vrDia = $incapacidad['vrIncapacidad'] / $incapacidad['cantidad'];
            }
            $ibc = $vrDia * $dias;
            $obj = array(
                'codigoIncapacidadFk' => $incapacidad['codigoIncapacidadPk'],
                'dias' => $dias,
                'horas' => $dias * 8,
                'fechaDesdeNovedad' => $fechaDesde->format('Y-m-d'),
                'fechaHastaNovedad' => $fechaHasta->format('Y-m-d'),
                'ibc' => $ibc

            );
            array_push($respuesta, $obj);

        }

        return $respuesta;
    }

    public function setDiasVacacionMes($arrVacaciones, $fechaDesdePeriodo, $fechaHastaPeriodo, $vrSalario)
    {
        $em = $this->getEntityManager();
        $respuesta = [];

        foreach ($arrVacaciones as $vacacion) {// recorremos cada licencia y la evaluamos
            $dias = 0;
            $fechaInicio = $vacacion['fechaDesdeDisfrute'];
            $fechaFinal = $vacacion['fechaHastaDisfrute'];
            $diaFinMes = cal_days_in_month(CAL_GREGORIAN, $fechaDesdePeriodo->format("m"), $fechaFinal->format("Y"));
            $fechaDesde = $fechaInicio;
            $fechaHasta = $fechaFinal;
            if ($fechaDesde->format('d') == 31 && $fechaDesde->format('d') == 31) {
                // se añade esta validacion por que no estaba tomando las vacaciones que inician el 31 y pasan de mes
                $fechaDesde = date_create($fechaDesde->format('Y-m') . "-30");
                //continue;
            }

            if ($fechaDesde <= $fechaDesdePeriodo) {
                $fechaDesde = $fechaDesdePeriodo;
            }
            if ($fechaHasta >= $fechaHastaPeriodo) {
                $fechaHasta = $fechaHastaPeriodo;
                if ($fechaHasta->format('d') == 31) {
                    $fechaHasta = date_create($fechaHasta->format('Y-m') . "-30");
                }
            }
            $diasTemporal = $fechaDesde->diff($fechaHasta);
            $dias = $diasTemporal->format('%a') + 1;
            if ($fechaHastaPeriodo->format('n') == 2 && $fechaFinal >= $fechaHastaPeriodo) {
                if ($diaFinMes == 28) {
                    $dias += 2;
                } else {
                    $dias++;
                }
            }
            if ($dias >= 31) {
                $dias = 30;
            }

            $obj = array(
                'codigoVacacionFk' => $vacacion['codigoVacacionPk'],
                'dias' => $dias,
                'horas' => $dias * 8,
                'fechaDesdeNovedad' => $fechaDesde->format('Y-m-d'),
                'fechaHastaNovedad' => $fechaHasta->format('Y-m-d')

            );
            array_push($respuesta, $obj);

        }

        return $respuesta;
    }

    public function trasladoSaludEmpleado($codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuTrasladoSalud::class, "ts")->select("ts.fecha,ts.fechaFosyga, esn.codigoInterface AS codigoEntidadNueva, esa.codigoInterface AS codigoEntidadAnterior")
            ->leftJoin("ts.entidadSaludNuevaRel", "esn")
            ->leftJoin("ts.entidadSaludAnteriorRel", "esa")
            ->where("ts.codigoContratoFk = {$codigoContrato}")
            ->andWhere("ts.fecha >= '{$fechaDesde}'")
            ->andWhere("ts.fecha <= '{$fechaHasta}'")
            ->andWhere("ts.estadoAprobado = 0 OR ts.estadoAprobado IS NULL")
            ->setMaxResults(1);
        $arrTrasladoSaludEmpleado = $query->getQuery()->getOneOrNullResult();

        return $arrTrasladoSaludEmpleado;
    }

    public function trasladoPensionEmpleado($codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuTrasladoPension::class, "tp")->select("tp.fecha,tp.fechaFosyga, epn.codigoInterface AS codigoPensionNueva, epa.codigoInterface AS codigoPensionAnterior")
            ->join("tp.entidadPensionNuevaRel", "epn")
            ->join("tp.entidadPensionAnteriorRel", "epa")
            ->where("tp.codigoContratoFk = {$codigoContrato}")
            ->andWhere("tp.fecha >= '{$fechaDesde}'")
            ->andWhere("tp.fecha <= '{$fechaHasta}'")
            ->andWhere("tp.estadoAprobado = 0 OR tp.estadoAprobado IS NULL")
            ->setMaxResults(1);
        $arrTrasladoPensionEmpleado = $query->getQuery()->getOneOrNullResult();

        return $arrTrasladoPensionEmpleado;
    }

    public function trasladoDesdeOtraEntidadEmpleado($codigoContrato, $fechaPago)
    {
        $em = $this->getEntityManager();
        $fechaDesde = $fechaPago->format('Y-m') . '-01';
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $fechaPago->format('m') + 1, 1, $fechaPago->format('Y')) - 1));
        $fechaHasta = $fechaPago->format('Y-m') . "-{$intUltimoDia}";
        $query = $em->createQueryBuilder()->from(RhuTrasladoSalud::class, "ts")->select("ts.fecha,ts.fechaFosyga, esn.codigoInterface AS codigoEntidadNueva, esa.codigoInterface AS codigoEntidadAnterior")
            ->join("ts.entidadSaludNuevaRel", "esn")
            ->join("ts.entidadSaludAnteriorRel", "esa")
            ->where("ts.codigoContratoFk = {$codigoContrato}")
            ->andWhere("ts.fechaCambioAfiliacion >= '{$fechaDesde}'")
            ->andWhere("ts.fechaCambioAfiliacion <= '{$fechaHasta}'")
            ->andWhere("ts.estadoAprobado = 1")
            ->setMaxResults(1);
        $arrTrasladoDesdeOtraEntidadEmpleado = $query->getQuery()->getOneOrNullResult();


        return $arrTrasladoDesdeOtraEntidadEmpleado;
    }

    public function trasladoDesdeOtraPensionEmpleado($codigoContrato, $fechaPago)
    {
        $em = $this->getEntityManager();
        $fechaDesde = $fechaPago->format('Y-m') . '-01';
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $fechaPago->format('m') + 1, 1, $fechaPago->format('Y')) - 1));
        $fechaHasta = $fechaPago->format('Y-m') . "-" . "{$intUltimoDia}";
        $query = $em->createQueryBuilder()->from(RhuTrasladoPension::class, "tp")->select("tp.fecha,tp.fechaFosyga, epn.codigoInterface AS codigoPensionNueva, epa.codigoInterface AS codigoPensionAnterior")
            ->join("tp.entidadPensionNuevaRel", "epn")
            ->join("tp.entidadPensionAnteriorRel", "epa")
            ->where("tp.codigoContratoFk = {$codigoContrato}")
            ->andWhere("tp.fechaCambioAfiliacion >= '{$fechaDesde}'")
            ->andWhere("tp.fechaCambioAfiliacion <= '{$fechaHasta}'")
            ->andWhere("tp.estadoAprobado = 1")
            ->setMaxResults(1);
        $arrTrasladoDesdeOtraPensionEmpleado = $query->getQuery()->getOneOrNullResult();

        return $arrTrasladoDesdeOtraPensionEmpleado;
    }

}