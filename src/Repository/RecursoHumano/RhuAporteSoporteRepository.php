<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPagoDetalle;
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

    public function generar($arAporte) {
        $em = $this->getEntityManager();
        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->listaGenerarSoporte($arAporte->getCodigoAportePk());
        foreach ($arAporteContratos as $arAporteContrato) {
            $arAporteContratoActualizar = $em->getRepository(RhuAporteContrato::class)->find($arAporteContrato['codigoAporteContratoPk']);

            //$arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());
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
            $salarioMinimo = 0;
            /*$arrLicenciasPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->listaLicenciasMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk());
            $arrLicencias = $this->setDiasLicenciaMes($arrLicenciasPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());

            foreach ($arrLicencias as $arrLicencia) {
                $diasLicenciaTotal += $arrLicencia['dias'];
            }
            $arrIncapacidades = NULL;
            if ($arConfiguracionNomina->getSsTomarIncapacidadActual()) {
                $arrIncapacidadesPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk());
                $arrIncapacidades = $this->setDiasIncapacidadMes($arrIncapacidadesPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());
            } else {
                $arrIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->incapacidad($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());
            }
            foreach ($arrIncapacidades as $arrIncapacidad) {
                $diasIncapacidadTotal += $arrIncapacidad['dias'];
            }
            $arrVacaciones = NULL;
            if ($arConfiguracionNomina->getSsTomarVacacionActual()) {
                $arrVacacionesPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->listaVacacionesMes($arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk());
                $arrVacaciones = $this->setDiasVacacionMes($arrVacacionesPeriodo, $arAporte->getFechaDesde(), $arAporte->getFechaHasta(), $arContrato->getVrSalarioPago());
            } else {
                $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->vacacion($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());
            }
            foreach ($arrVacaciones as $arrVacacion) {
                $diasVacacionesTotal += $arrVacacion['dias'];
            }
            $arPeriodoEmpleadoActualizar->setDiasLicencia($diasLicenciaTotal);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidad($diasIncapacidadTotal);
            $arPeriodoEmpleadoActualizar->setDiasVacaciones($diasVacacionesTotal);
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
                $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($arrVacacion['codigoVacacionFk']);
                $ibcVacacion = 0;
                //validacion para verificar si la vacacion cambia de mes  (febrero)
                if ($arPeriodoDetalle->getSsoPeriodoRel()->getMes() == '2' && $arPeriodoDetalle->getSsoPeriodoRel()->getMes() < $arVacacion->getFechaHastaDisfrute()->format('n') && $arConfiguracionNomina->getSsTomarVacacionActual()) {
                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arPeriodoDetalle->getSsoPeriodoRel()->getMes() + 1, 1, $arPeriodoDetalle->getSsoPeriodoRel()->getAnio()) - 1));
                    if ($intUltimoDia == 29) {
                        $arrVacacion['dias'] -= 1;
                    } else {
                        $arrVacacion['dias'] -= 2;
                    }
                }
                $diasVacaciones += $arrVacacion['dias'];
                $arrIbc = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->ibcMesAnterior($arVacacion->getFechaDesdeDisfrute()->format('Y'), $arVacacion->getFechaDesdeDisfrute()->format('m'), $arContrato->getCodigoEmpleadoFk());
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
                        if ($intUltimoDia == 31 && $arPeriodoDetalle->getSsoPeriodoRel()->getMes() == $arVacacion->getFechaDesdeDisfrute()->format("m")) {
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
                    $ibcDia = $arVacacion->getVrVacacionBruto() / $arVacacion->getDiasDisfrutadosReales();
                    $ibcCaja = $ibcDia * $arrVacacion['dias'];

//                    $mes = $arPeriodoDetalle->getSsoPeriodoRel()->getMes();
//                    $anio = $arPeriodoDetalle->getSsoPeriodoRel()->getAnio();
//                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $mes - 1, 1, $anio) - 1));
                    if (intval($arVacacion->getFechaDesdeDisfrute()->format("m")) < intval($arVacacion->getFechaHastaDisfrute()->format("m"))) {
                        $intUltimoDia = cal_days_in_month(CAL_GREGORIAN, intval($arVacacion->getFechaDesdeDisfrute()->format("m")), intval($arVacacion->getFechaDesdeDisfrute()->format("m")));
                        if ($intUltimoDia == 31 && $arPeriodoDetalle->getSsoPeriodoRel()->getMes() == $arVacacion->getFechaDesdeDisfrute()->format("m")) {
                            $ibcCaja = $ibcDia * ($arrVacacion['dias'] + 1);
                        }
                    }

                }

                if ($diasVacaciones > 0) {
                    $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                    $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                    $arPeriodoEmpleadoDetalle->setDias($arrVacacion['dias']);
                    $arPeriodoEmpleadoDetalle->setHoras(intval($arrVacacion['horas']));
                    $arPeriodoEmpleadoDetalle->setVrSalario($salario);
                    $arPeriodoEmpleadoDetalle->setIbc($ibcVacacion);
                    $arPeriodoEmpleadoDetalle->setIbcCajaVacaciones($ibcCaja);
                    //$arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacaciones);
                    $arPeriodoEmpleadoDetalle->setVacaciones(1);
                    $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                    $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);
                    if ($arContrato->getSalarioIntegral()) {
                        $arPeriodoEmpleadoDetalle->setTarifaSalud(12.5);
                    } else {
                        $arPeriodoEmpleadoDetalle->setTarifaSalud(4);
                    }

                    $arPeriodoEmpleadoDetalle->setTarifaCaja(4);
                    $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrVacacion['fechaDesdeNovedad']));
                    $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrVacacion['fechaHastaNovedad']));
                    $diaSalarioVacacion = $ibcVacaciones / $arrVacacion['dias'];
                    if ($diaSalarioVacacion != $diaSalario) {
                        $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                        $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                        $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);
                        if ($strNovedadRetiro == 'X') {
                            $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
                            if ($arLiquidacion) {
                                $ibcVacaciones = $arLiquidacion->getVrVacaciones();
                                $arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacaciones);
                            }
                        }
                        if ($strNovedadIngreso == "X") {
                            $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                        }
                        $novedadesIngresoRetiro = TRUE;
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                        //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                        $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                        if ($arrTrasladoSaludEmpleado) {
                            $arPeriodoEmpleadoDetalle->setTrasladoAOtraEps(true);
                            $arPeriodoEmpleadoDetalle->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                            $novedadTrasladoEntidad = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                        //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                        $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                        if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                            $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraEps(true);
                            $novedadTrasladoDesdeOtraEntidad = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                        //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                        $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                        if ($arrTrasladoPensionEmpleado) {
                            $arPeriodoEmpleadoDetalle->setTrasladoAOtraPension(true);
                            $arPeriodoEmpleadoDetalle->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                            $novedadTrasladoPension = true;
                        }
                    }

                    if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                        //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                        $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                        if ($arrTrasladoDesdeOtraPensionEmpleado) {
                            $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraPension(true);
                            $novedadTrasladoDesdeOtraPension = true;
                        }
                    }
                    $em->persist($arPeriodoEmpleadoDetalle);
                }
            }

            //Incapacidades
            $diasIncapacidad = 0;
            $diasIncapacidadLaboral = 0;
            foreach ($arrIncapacidades as $arrIncapacidad) {
                $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad;
                $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arrIncapacidad['codigoIncapacidadFk']);
                $incapacidadFechaHasta = date_create($arrIncapacidad['fechaHastaNovedad']);
                // validacion por si el empleado estuvo incapacidado todo el mes de febrero
                if ($arPeriodoDetalle->getSsoPeriodoRel()->getMes() == '2' && ($arrIncapacidad['dias'] == 28 || $arrIncapacidad['dias'] == 29)) {
                    $arrIncapacidad['dias'] = 30;
                }
                //validacion para verificar si la incapacida cambia de mes  (febrero)
                if ($arPeriodoDetalle->getSsoPeriodoRel()->getMes() == '2' && $arPeriodoDetalle->getSsoPeriodoRel()->getMes() < $arIncapacidad->getFechaHasta()->format('n') && $arConfiguracionNomina->getSsTomarIncapacidadActual()) {
                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arPeriodoDetalle->getSsoPeriodoRel()->getMes() + 1, 1, $arPeriodoDetalle->getSsoPeriodoRel()->getAnio()) - 1));
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
                if ($arIncapacidad->getIncapacidadTipoRel()->getTipo() == 1) {
                    if (($diasIncapacidadLaboral + $diasIncapacidad) + $diasIncapacidadIndividualReportar > 30) {
                        $diasIncapacidadIndividualReportar--;
                    }
                    $diasIncapacidad += $diasIncapacidadIndividualReportar;
                    $arPeriodoEmpleadoDetalle->setIncapacidadGeneral(1);
                } else {
                    if (($diasIncapacidadLaboral + $diasIncapacidad) + $diasIncapacidadIndividualReportar > 30) {
                        $diasIncapacidadIndividualReportar--;
                    }
                    $diasIncapacidadLaboral += $diasIncapacidadIndividualReportar;
                    $arPeriodoEmpleadoDetalle->setIncapacidadLaboral(1);
                }

                $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                $arPeriodoEmpleadoDetalle->setDias($diasIncapacidadIndividualReportar);
                $arPeriodoEmpleadoDetalle->setHoras(intval($arrIncapacidad['horas']));
                $arPeriodoEmpleadoDetalle->setVrSalario($salario);
                $ibcIncapacidad = ceil($arrIncapacidad['ibc']);
                if ($arrIncapacidad['dias'] > 0) {
                    $ibcMinimo = ($salarioMinimo / 30) * $arrIncapacidad['dias'];
                    if ($ibcIncapacidad < $ibcMinimo) {
                        $ibcIncapacidad = ceil($ibcMinimo);
                    }
                }
                $arPeriodoEmpleadoDetalle->setIbc($ibcIncapacidad);
                $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);
                $arPeriodoEmpleadoDetalle->setTarifaSalud(4);

                $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrIncapacidad['fechaDesdeNovedad']));
                $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrIncapacidad['fechaHastaNovedad']));
                if ($diasIncapacidadIndividual > 0) {
                    $diaSalarioLicencia = $ibcIncapacidad / $diasIncapacidadIndividual;
                    if ($diaSalarioLicencia != $diaSalario) {
                        $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
                    }
                }
                if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                    $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);
                    if ($strNovedadRetiro == 'X') {
                        $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1, 'estadoAnulado' => 0));
                        if ($arLiquidacion) {
                            $ibcVacacionesLiquidacion = $arLiquidacion->getVrVacaciones();
                            $arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacacionesLiquidacion);
                            $arPeriodoEmpleadoDetalle->setTarifaCaja(4);
                        }
                    }
                    if ($strNovedadIngreso == "X") {
                        $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                    }
                    $novedadesIngresoRetiro = TRUE;
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                    //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                    $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoSaludEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoAOtraEps(true);
                        $arPeriodoEmpleadoDetalle->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                        $novedadTrasladoEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                    $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraEps(true);
                        $novedadTrasladoDesdeOtraEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                    //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                    $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoPensionEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoAOtraPension(true);
                        $arPeriodoEmpleadoDetalle->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                        $novedadTrasladoPension = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                    $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraPensionEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraPension(true);
                        $novedadTrasladoDesdeOtraPension = true;
                    }
                }

                $em->persist($arPeriodoEmpleadoDetalle);
            }

            //Licencias
            $diasLicencia = 0;
            $diasLicenciaMaternidad = 0;
            foreach ($arrLicencias as $arrLicencia) {
                $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($arrLicencia['codigoLicenciaFk']);
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
                    $arPeriodoEmpleadoDetalle->setLicenciaMaternidad(1);
                    $ibcLicencia = $arrLicencia['ibc'];
                    if ($arrLicencia['dias'] > 0) {
                        $ibcMinimo = ($salarioMinimo / 30) * $arrLicencia['dias'];
                        if ($ibcLicencia < $ibcMinimo) {
                            $ibcLicencia = ceil($ibcMinimo);
                        }
                    }
                    $diaSalarioLicencia = $ibcLicencia / $diasLicenciaIndividualReportar;
                    if ($diaSalarioLicencia != $diaSalario) {
                        $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
                    }
                    $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                    $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);
                    $arPeriodoEmpleadoDetalle->setTarifaSalud(4);
                    if ($arConfiguracionNomina->getAportarCajaLicenciaMaternidadPaternidad()) {
                        $arPeriodoEmpleadoDetalle->setTarifaCaja(4);
                    }

                } else {
                    $ibcLicencia = $diaSalario * $diasLicenciaIndividualReportar;
                    // validacion para liquidar las licencia remuneradas con ibc anterior
                    if ($arConfiguracionNomina->getLiquidarLicenciasIbcMesAnteriorSSo()) {
                        $diasIbcMesAnterior = $arLicencia->getDiasIbcMesAnterior();
                        $vrIbcMesAnterior = $arLicencia->getVrIbcMesAnterior();
                        if ($diasIbcMesAnterior > 0 && $vrIbcMesAnterior > 0) {
                            $diaSalarioIbcAnterior = $arLicencia->getVrIbcMesAnterior() / $arLicencia->getDiasIbcMesAnterior();
                            $ibcLicencia = $diaSalarioIbcAnterior * $diasLicenciaIndividualReportar;
                        }
                        if ($ibcLicencia < (($arConfiguracionNomina->getVrSalario() / 30) * $diasLicenciaIndividualReportar)) {
                            $ibcLicencia = $diaSalario * $diasLicenciaIndividualReportar;
                        }
                    }
                    // fin validacion
                    $diasLicencia += $diasLicenciaIndividualReportar;
                    $arPeriodoEmpleadoDetalle->setLicencia(1);
                    if ($arConfiguracionNomina->getTarifaPensionCompletaLicencia()) {
                        $arPeriodoEmpleadoDetalle->setTarifaPension(16);
                    } else {
                        $arPeriodoEmpleadoDetalle->setTarifaPension(12);
                    }
                    if ($arLicencia->getLicenciaTipoRel()->getRemunerada()) {
                        $arPeriodoEmpleadoDetalle->setLicenciaRemunerada(1);
                        $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                        $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);
                        $arPeriodoEmpleadoDetalle->setTarifaSalud(4);
                        $arPeriodoEmpleadoDetalle->setTarifaCaja(4);
                    } else {
                        $arPeriodoEmpleadoDetalle->setTarifaSalud(0);
                    }

                }

                $arPeriodoEmpleadoDetalle->setIbc($ibcLicencia);
                $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                $arPeriodoEmpleadoDetalle->setDias($diasLicenciaIndividualReportar);

                $arPeriodoEmpleadoDetalle->setHoras(intval($arrLicencia['horas']));
                $arPeriodoEmpleadoDetalle->setVrSalario($salario);
                $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrLicencia['fechaDesdeNovedad']));
                $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrLicencia['fechaHastaNovedad']));
                if ($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                    $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);
                    if ($strNovedadRetiro == 'X') {
                        $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1, 'estadoAnulado' => 0));
                        if ($arLiquidacion) {
                            $ibcVacacionesLiquidacion = $arLiquidacion->getVrVacaciones();
                            $arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacacionesLiquidacion);
                            $arPeriodoEmpleadoDetalle->setTarifaCaja(4);
                        }
                    }
                    if ($strNovedadIngreso == "X") {
                        $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                    }
                    $novedadesIngresoRetiro = TRUE;
                }
                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoEntidad == false) {
                    //Validación si el empleado contiene un traslado de salud en el periodo a reportar para marcar la X trasladoSaludAOtraEps.
                    $arrTrasladoSaludEmpleado = $this->trasladoSaludEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoSaludEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoAOtraEps(true);
                        $arPeriodoEmpleadoDetalle->setCodigoEntidadSaludTraslada($arrTrasladoSaludEmpleado['codigoEntidadNueva']);
                        $novedadTrasladoEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraEntidad == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra entidad.
                    $arrTrasladoDesdeOtraEntidadEmpleado = $this->trasladoDesdeOtraEntidadEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraEntidadEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraEps(true);
                        $novedadTrasladoDesdeOtraEntidad = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoPension == false) {
                    //Validación si el empleado contiene un traslado de pension en el periodo a reportar para marcar la X trasladoAOtraPension.
                    $arrTrasladoPensionEmpleado = $this->trasladoPensionEmpleado($arContrato->getCodigoContratoPk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                    if ($arrTrasladoPensionEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoAOtraPension(true);
                        $arPeriodoEmpleadoDetalle->setCodigoEntidadPensionTraslada($arrTrasladoPensionEmpleado['codigoPensionNueva']);
                        $novedadTrasladoPension = true;
                    }
                }

                if ($diasOrdinariosTotal <= 0 && $novedadTrasladoDesdeOtraPension == false) {
                    //Se valida si el traslado fue aprobado para reportar traslado desde otra pension.
                    $arrTrasladoDesdeOtraPensionEmpleado = $this->trasladoDesdeOtraPensionEmpleado($arContrato->getCodigoContratoPk(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago());
                    if ($arrTrasladoDesdeOtraPensionEmpleado) {
                        $arPeriodoEmpleadoDetalle->setTrasladoDesdeOtraPension(true);
                        $novedadTrasladoDesdeOtraPension = true;
                    }
                }
                $em->persist($arPeriodoEmpleadoDetalle);
            }*/


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
            //$arAporteContratoActualizar->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            //$arAporteContratoActualizar->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel() ? $arContrato->getEntidadPensionRel()->getCodigoInterface() : null);
            //$arAporteContratoActualizar->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel() ? $arContrato->getEntidadCajaRel()->getCodigoInterface() : null);
            $arAporteContratoActualizar->setVrSalario($salario);
            $arAporteContratoActualizar->setDias($diasCotizar);
            $arAporteContratoActualizar->setIbc($ibc);
            //$arAporteContratoActualizar->setIbcFondoSolidaridad($ibcFS);


            $em->persist($arAporteContratoActualizar);
            //$arPeriodoDetalle->setEstadoActualizado(1);
            //$em->persist($arPeriodoDetalle);
        }
        $em->flush();
    }


}