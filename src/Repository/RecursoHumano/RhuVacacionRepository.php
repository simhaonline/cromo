<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta()
    {
        return 'recursohumano_movimiento_nomina_vacacion_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacion::class);
    }

    /**
     * @param $arVacacion RhuVacacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arVacacion)
    {
        $em = $this->getEntityManager();
        if (!$arVacacion->getEstadoAutorizado()) {
            $arVacacion->setEstadoAutorizado(1);
            $em->persist($arVacacion);
            $em->flush();

        } else {
            Mensajes::error('El despacho ya esta autorizado');
        }
    }

    /**
     * @param $arVacacion RhuVacacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arVacacion)
    {
        if ($arVacacion->getEstadoAutorizado()  && !$arVacacion->getEstadoAprobado()) {
            $arVacacion->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arVacacion);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arVacacion RhuVacacion
     * @return string
     */
    public function aprobar($arVacacion)
    {
        $em = $this->getEntityManager();
        if($arVacacion->getEstadoAutorizado() && !$arVacacion->getEstadoAprobado()) {
            $arVacacion->setEstadoAprobado(1);
            $em->persist($arVacacion);


            $arContrato = $em->getRepository(RhuContrato::class)->find($arVacacion->getCodigoContratoFk());
            $numero = $em->getRepository(RhuConsecutivo::class)->consecutivo(4);
            $respuesta = $em->getRepository(RhuVacacion::class)->pagar($arVacacion->getCodigoVacacionPk(), $numero);
            /*if ($respuesta == '') {
                $arVacacion->setNumero($numero);
                $arVacacion->setFecha(new \DateTime('now'));
                $arVacacion->setFechaContabilidad($arVacacion->getFechaDesdeDisfrute());
                $arVacacion->setEstadoPagoGenerado(1);
                $em->persist($arVacacion);
                $arContrato->setFechaUltimoPagoVacaciones($arVacacion->getFechaHastaPeriodo());
                $em->persist($arContrato);
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->generarNovedadTurnos($codigoVacacion, $arUsuario->getUserName());
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            } else {
                $objMensaje->Mensaje("error", $respuesta);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }*/


            $em->flush();
        } else {
            Mensajes::error("El documento debe estar autorizado y no puede estar previamente aprobado");
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'e');
        $queryBuilder
            ->select('e.codigoCreditoPk');
        return $queryBuilder;
    }

    public function diasProgramacion($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select('v.codigoVacacionPk')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->andWhere("(((v.fechaDesdeDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$fechaDesde' AND '$fechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$fechaDesde' AND v.fechaDesdeDisfrute <= '$fechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$fechaHasta' AND v.fechaDesdeDisfrute <= '$fechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.diasDisfrutados > 0 AND v.estadoAnulado = 0");
        if ($codigoContrato) {
            $query->andWhere("v.codigoContratoFk = " . $codigoContrato);
        }
        $arVacaciones = $query->getQuery()->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde = date_create($fechaDesde);
            $dateFechaHasta = date_create($fechaHasta);
            if ($arVacacion['fechaDesdeDisfrute'] < $dateFechaDesde) {
                $dateFechaDesde = $dateFechaDesde;
            } else {
                $dateFechaDesde = $arVacacion['fechaDesdeDisfrute'];
            }

            if ($arVacacion['fechaHastaDisfrute'] > $dateFechaHasta) {
                $dateFechaHasta = $dateFechaHasta;
            } else {
                $dateFechaHasta = $arVacacion['fechaHastaDisfrute'];
            }
            if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += 0;
            //$vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;

    }

    public function validarVacacion($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = TRUE;

        $qb = $this->getEntityManager()->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->select("count(v.codigoVacacionPk) AS vacaciones")
            ->where("v.fechaDesdeDisfrute <= '{$strFechaHasta}' AND  v.fechaHastaDisfrute >= '{$strFechaHasta}'")
            ->orWhere("v.fechaDesdeDisfrute <= '{$strFechaDesde}' AND  v.fechaHastaDisfrute >='{$strFechaDesde}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("v.fechaDesdeDisfrute >= '{$strFechaDesde}' AND  v.fechaHastaDisfrute <='{$strFechaHasta}' AND v.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("v.codigoEmpleadoFk = '{$codigoEmpleado}' AND v.estadoAnulado = 0 ");
        $r = $qb->getQuery();
        $arrVacaciones = $r->getResult();
        if ($arrVacaciones[0]['vacaciones'] > 0) {
            $boolValidar = FALSE;
        }

        return $boolValidar;
    }

    public function liquidar($codigoVacacion)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        /** @var  $arVacacion RhuVacacion */
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        $arContrato = $arVacacion->getContratoRel();

        // Calcular fecha desde periodo y hasta periodo
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        if ($fechaDesdePeriodo == null) {
            $fechaDesdePeriodo = $arContrato->getFechaDesde();
        }
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
        $fechaHastaPeriodo = $em->getRepository(RhuLiquidacion::class)->diasPrestacionesHasta($intDias + 1, $fechaDesdePeriodo);
        $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
        $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
        // Calcular fecha desde periodo y hasta periodo

        //Fecha ultimo anio
        $fechaHastaUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
        $fechaDesdeUltimoAnio = date_create($arVacacion->getFechaDesdeDisfrute()->format('Y-m-d'));
        date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
        if ($fechaDesdeUltimoAnio < $arVacacion->getFechaDesdePeriodo()) {
            $fechaDesdeUltimoAnio = $arVacacion->getFechaDesdePeriodo();
        }

        //360 dias para que de 12 meses
        $objFunciones = new FuncionesController();
        $diasPeriodo = $objFunciones->diasPrestaciones($fechaDesdePeriodo, $arVacacion->getFechaHastaPeriodo());

//        Se comenta esta linea debido a que nuestros pagos no almacenan los dias de ausentismo
        $diasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($fechaDesdePeriodo, $arVacacion->getFechaHastaPeriodo(), $arVacacion->getContratoRel()->getCodigoContratoPk());
        if ($arVacacion->getDiasAusentismoPropuesto() > 0) {
            $diasAusentismo = $arVacacion->getDiasAusentismoPropuesto();
        }

        //Para que la fecha hasta quede del 01/01/2016 al 01/01/2017 va dar 361 dias pero le restamos 1 para
        //que queden solo 360 porque en soga no se puede liquidar mas de un periodo.

        if ($diasPeriodo > 360) {
            $diasPeriodo = 360;
        }
        $mesesPeriodo = $diasPeriodo / 30;
        $arVacacion->setDiasPeriodo($diasPeriodo);
        $arVacacion->setMesesPeriodo($mesesPeriodo);
        $intDias = $arVacacion->getDias();
        $floSalario = $arVacacion->getContratoRel()->getVrSalario();

        //Analizar cambios de salario
        $fecha = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime('-90 day', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");
        $floSalarioPromedio = 0;
        $floSalarioPromedioPagado = 0;
        $fechaDesdeRecargos = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime('-365 day', strtotime($fechaDesdeRecargos));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $fechaDesde = date_create($nuevafecha);

        $fechaDesdeRecargos = $nuevafecha;
        $fechaHastaRecargos = $arVacacion->getFecha()->format('Y-m-d');
//        $interval = date_diff($fechaDesdePeriodo, $fechaHastaPeriodo);

        //Recargos nocturnos
        $recargosNocturnos = 0;
        if ($arConfiguracion->getVacacionesRecargoNocturnoUltimoAnio()) {
            $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnos($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());
        } else {
            $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnos($fechaDesdePeriodo->format('Y-m-d'), $fechaHastaPeriodo->format('Y-m-d'), $arContrato->getCodigoContratoPk());
        }

        //Recargos nocturnos sobre el porcentaje de vacaciones que tiene el concepto
        if ($arConfiguracion->getVacacionesLiquidarRecargoNocturnoPorcentajeConcepto()) {
            if ($arConfiguracion->getVacacionesRecargoNocturnoUltimoAnio()) {
                //Fecha ultimo anio
                $fechaHastaUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
                $fechaDesdeUltimoAnio = date_create($arContrato->getFechaUltimoPago()->format('Y-m-d'));
                date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
                if ($fechaDesdeUltimoAnio < $arVacacion->getFechaDesdePeriodo()) {
                    $fechaDesdeUltimoAnio = $arVacacion->getFechaDesdePeriodo();
                }

                $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosIbp($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());

            } else {
                $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosIbp($fechaDesdePeriodo->format('Y-m-d'), $fechaHastaPeriodo->format('Y-m-d'), $arContrato->getCodigoContratoPk());
            }
        }

        $recargosNocturnos = round($recargosNocturnos);

        $arVacacion->setVrRecargoNocturno($recargosNocturnos);
        $arVacacion->setVrRecargoNocturnoInicial($arContrato->getIbpRecargoNocturnoInicial());
        $recargosNocturnosTotal = $recargosNocturnos + $arContrato->getIbpRecargoNocturnoInicial();
        $promedioRecargosNocturnos = $recargosNocturnosTotal / $mesesPeriodo;
        $promedioRecargosNocturnos = round($promedioRecargosNocturnos);
        $arVacacion->setVrPromedioRecargoNocturno($promedioRecargosNocturnos);

        if ($arContrato->getCodigoSalarioTipoFk() == 'FIJ') {
            $floSalarioPromedio = $arContrato->getVrSalario();
        } else {
            $floSalarioPromedio = $arContrato->getVrSalario() + $promedioRecargosNocturnos;
        }
        if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
            $floSalarioPromedio = $arVacacion->getVrSalarioPromedioPropuesto();
        }
        if ($arVacacion->getVrSalarioPromedioPropuestoPagado() > 0) {
            $floSalarioPromedioPagado = $arVacacion->getVrSalarioPromedioPropuestoPagado();
            $floSalarioPromedio = $floSalarioPromedioPagado;
        } else {
            $floSalarioPromedioPagado = $floSalarioPromedio;
        }
        $diasDisfrutadosReales = $arVacacion->getDiasDisfrutadosReales();
        $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $diasDisfrutadosReales;
        $floTotalVacacionBrutoPagados = 0;

        //Validar si son pagadas todas
        if ($arVacacion->getDiasDisfrutadosReales() > 1) {
            $floTotalVacacionBrutoPagados = $floSalarioPromedioPagado / 30 * $arVacacion->getDiasPagados();
        } else {
            if ($arVacacion->getVrSalarioPromedioPropuestoPagado() > 0) {
                $floTotalVacacionBrutoPagados = $arVacacion->getVrSalarioPromedioPropuestoPagado() / 30 * $arVacacion->getDiasPagados();
            } else {
                $floTotalVacacionBrutoPagados = $arContrato->getVrSalario() / 30 * $arVacacion->getDiasPagados();
            }
        }

        if ($arConfiguracion->getLiquidarVacacionesPromedioUltimoAnio()) {
            //Empresas segurcol que liquida con promedio ibp
            if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
                $diasPeriodoSuplementario = $objFunciones->diasPrestaciones($fechaDesdeUltimoAnio, $fechaHastaUltimoAnio);
                if ($diasPeriodoSuplementario > 360) {
                    $diasPeriodoSuplementario = 360;
                }
                $diasPeriodoSuplementarioPagadas = $diasPeriodoSuplementario;
//                $diasPeriodoSuplementarioPagadas -= $diasAusentismo;
                $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementarioVacaciones(
                    $fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arVacacion->getContratoRel()->getCodigoContratoPk(), 1);
                $suplementarioPromedio = 0;
                if ($diasPeriodoSuplementario > 0) {
                    $suplementarioPromedio = ($suplementario / $diasPeriodoSuplementario) * 30;
                }
                $floSalarioPromedio = $arContrato->getVrSalario() + $suplementarioPromedio;
                if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
                    $floSalarioPromedio = $arVacacion->getVrSalarioPromedioPropuesto();
                }
                $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $diasDisfrutadosReales; // aca se calcula el valor de las disfrutadas

                if ($arVacacion->getDiasPagados() > 0) {
                    // se comentan las linea de suplementario por solicitud del cliente
                    // el correo fue enviado el dia miercoles 16 de enero , ASUNTO: solicitud de vacaciones , enviado por Yaneth del correo auxiliarnomina@segurcol.com

//                    $suplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibpSuplementario(
//                        $fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arVacacion->getContratoRel()->getCodigoContratoPk());
                    $suplementarioPromedio = 0;
//                    if ($diasPeriodoSuplementarioPagadas > 0) {
//                        $suplementarioPromedio = ($suplementario / $diasPeriodoSuplementarioPagadas) * 30;
//                    }
                    $floSalarioPromedioPagado = $arContrato->getVrSalario() + $suplementarioPromedio;
                    if ($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
                        $floSalarioPromedioPagado = $arVacacion->getVrSalarioPromedioPropuesto();
                    }
                    $floTotalVacacionBrutoPagados = ($floSalarioPromedioPagado / 30) * $arVacacion->getDiasPagados();
                } else {
                    $floTotalVacacionBrutoPagados = 0;
                }
            }
        }
        $floTotalVacacionBruto = $floTotalVacacionBrutoDisfrute + $floTotalVacacionBrutoPagados;

        if ($arContrato->getSalarioIntegral() == 0) {
            $basePrestaciones = $floTotalVacacionBrutoDisfrute;
        } else {
            $basePrestaciones = ($floTotalVacacionBrutoDisfrute * 70) / 100;
        }
        $baseSeguridadSocial = 0;
        if ($arConfiguracion->getVacacionesBaseDescuentoLeyIbcMesAnterior()) {
            $arrIbc = $em->getRepository(RhuAporte::class)->ibcMesAnterior($arVacacion->getFechaDesdeDisfrute()->format('Y'), $arVacacion->getFechaDesdeDisfrute()->format('m'), $arVacacion->getCodigoEmpleadoFk());
            if ($arrIbc['respuesta'] == true) {
                $vrDiaMesAnterior = $arrIbc['ibc'] / $arrIbc['dias'];
                $baseSeguridadSocial = $vrDiaMesAnterior * $arVacacion->getDiasDisfrutadosReales();
            }
        } else {
            $baseSeguridadSocial = $floTotalVacacionBrutoDisfrute;
            if ($arContrato->getSalarioIntegral() == 1) {
                $baseSeguridadSocial = ($floTotalVacacionBrutoDisfrute * 70) / 100;
            }
        }


        $porcentajeSalud = $arContrato->getSaludRel()->getPorcentajeEmpleado();
        $douSalud = ($baseSeguridadSocial * $porcentajeSalud) / 100;
        if ($arVacacion->getVrSaludPropuesto() > 0) {
            $douSalud = $arVacacion->getVrSaludPropuesto();
        }
        $douSalud = round($douSalud);
        $arVacacion->setVrSalud($douSalud);
        $porcentajePension = $arContrato->getPensionRel()->getPorcentajeEmpleado();

        $douPension = ($baseSeguridadSocial * $porcentajePension) / 100;
        if ($arVacacion->getVrPensionPropuesto() > 0) {
            $douPension = $arVacacion->getVrPensionPropuesto();
        }
        $douPension = round($douPension);
        $arVacacion->setVrPension($douPension);

        //Calcular el valor de fondo de solidaridad pensional.
        $duoFondo = 0;
        if ($basePrestaciones >= ($arConfiguracion->getVrSalarioMinimo() * 4)) {
            $douPorcentajeFondo = $em->getRepository(RhuAporteDetalle::class)->porcentajeFondo($arConfiguracion->getVrSalario(), $basePrestaciones);
            $duoFondo = ($baseSeguridadSocial * $douPorcentajeFondo) / 100;
        }
        $duoFondo = round($duoFondo);
        $arVacacion->setVrFondoSolidaridad($duoFondo);

        $floDeducciones = 0;
        $floBonificaciones = 0;
        //Liquidar los embargos si el empleado tiene embargos
        $this->liquidarEmbargos($arVacacion);
        $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->FindBy(array('codigoVacacionFk' => $codigoVacacion));
        foreach ($arVacacionAdicionales as $arVacacionAdicional) {
            if ($arVacacionAdicional->getVrDeduccion() > 0) {
                $floDeducciones += $arVacacionAdicional->getVrDeduccion();
            }
            if ($arVacacionAdicional->getVrBonificacion() > 0) {
                $floBonificaciones += $arVacacionAdicional->getVrBonificacion();
            }
        }
        $floBonificaciones = round($floBonificaciones);
        $floDeducciones = round($floDeducciones);
        $floSalario = round($floSalario);
        $floSalarioPromedio = round($floSalarioPromedio);
        $floTotalVacacionBruto = round($floTotalVacacionBruto);
        $promedioIbc = $floTotalVacacionBruto / $arVacacion->getDias();
        $promedioIbc = round($promedioIbc);
        $arVacacion->setVrIbcPromedio($promedioIbc);
        $arVacacion->setVrBonificacion($floBonificaciones);
        $arVacacion->setVrDeduccion($floDeducciones);

        if ($arVacacion->getVrDisfrutePropuesto() > 0) {
            $floTotalVacacionBruto = $arVacacion->getVrDisfrutePropuesto();
            $floTotalVacacionBrutoDisfrute = $arVacacion->getVrDisfrutePropuesto();
            $floTotalVacacionBrutoPagados = 0;
            $arVacacion->setVrSalud($arVacacion->getVrDisfrutePropuesto() * 0.04);
            $arVacacion->setVrPension($arVacacion->getVrDisfrutePropuesto() * 0.04);
            $floSalarioPromedio = $floSalario;
        }
        $arVacacion->setVrBruto($floTotalVacacionBruto);
        $arVacacion->setVrDisfrute($floTotalVacacionBrutoDisfrute);
        $arVacacion->setVrDinero($floTotalVacacionBrutoPagados);
        $floTotalVacacion = ($floTotalVacacionBruto + $floBonificaciones) - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud() - $arVacacion->getVrFondoSolidaridad();
        $floTotalVacacion = round($floTotalVacacion);
        $arVacacion->setVrTotal($floTotalVacacion);
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $arVacacion->setDiasAusentismo($diasAusentismo);
        $arVacacion->setEstadoLiquidado(true);
        $em->persist($arVacacion);
        $em->flush();
        return true;
    }

    public function liquidarEmbargos($arVacacion)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $douVrSalarioMinimo = $arConfiguracion->getVrSalarioMinimo();
        $em->getRepository(RhuVacacionAdicional::class)->eliminarEmbargosVacacion($arVacacion->getCodigoVacacionPk());
        /** @var  $arEmbargo RhuEmbargo */
        $arEmbargos = $em->getRepository(RhuEmbargo::class)->listaEmbargo($arVacacion->getEmpleadoRel()->getCodigoEmpleadoPk(), 0, 1, 0, 0);
        foreach ($arEmbargos as $arEmbargo) {
            $vrDeduccionEmbargo = 0;
            $detalle = "";
            $vrPago = $arVacacion->getVrVacacionBruto();
            //Calcular la deduccion del embargo
            if ($arEmbargo->getValorFijo()) {
                $vrDeduccionEmbargo = $arEmbargo->getValor();
                $detalle = "Valor fijo embargo";
            }
            if ($arEmbargo->getPorcentajeDevengado() || $arEmbargo->getPorcentajeDevengadoPrestacional()) {
                $vrDeduccionEmbargo = ($vrPago * $arEmbargo->getPorcentaje()) / 100;
                $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                $detalle = $arEmbargo->getPorcentaje() . "% devengado";
            }
            $boolPorcentajeDescuentoLey = $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLeyTransporte();
            if ($boolPorcentajeDescuentoLey) {
                $vrDeduccionEmbargo = (($vrPago - $arVacacion->getVrSalud() - $arVacacion->getVrPension()) * $arEmbargo->getPorcentaje()) / 100;
                $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley)";
            }
            if ($arEmbargo->getPorcentajeExcedaSalarioMinimo()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDiasVacaciones();
                $baseDescuento = $vrPago - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = ($baseDescuento * $arEmbargo->getPorcentaje()) / 100;
                    $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                }
                $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
            }
            if ($arEmbargo->getPorcentajeSalarioMinimo()) {
                if ($douVrSalarioMinimo > 0) {
                    $vrDeduccionEmbargo = ($douVrSalarioMinimo * $arEmbargo->getPorcentaje()) / 100;
                    $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
            }
            if ($arEmbargo->getPartesExcedaSalarioMinimo()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDiasVacaciones();
                $baseDescuento = $vrPago - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = $baseDescuento / $arEmbargo->getPartes();
                }
                $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
            }
            if ($arEmbargo->getPartesExcedaSalarioMinimoMenosDescuentoLey()) {
                $salarioMinimoDevengado = ($douVrSalarioMinimo / 30) * $arVacacion->getDiasVacaciones();
                $baseDescuento = ($vrPago - $arVacacion->getVrSalud() - $arVacacion->getVrPension()) - $salarioMinimoDevengado;
                if ($baseDescuento > 0) {
                    $vrDeduccionEmbargo = $baseDescuento / $arEmbargo->getPartes();
                }
                $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
            }
            if ($arEmbargo->getValidarMontoMaximo()) {
                $saldo = $arEmbargo->getVrMontoMaximo() - $arEmbargo->getDescuento();
                if ($saldo > 0) {
                    if ($saldo < $vrDeduccionEmbargo) {
                        $vrDeduccionEmbargo = round($saldo);
                    }
                } else {
                    $vrDeduccionEmbargo = 0;
                }
            }
            //Aqui se registra la deduccion del embargo en la liquidacion adicionales
            if ($vrDeduccionEmbargo > 0) {
                $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                $arVacacionAdicional->setPagoConceptoRel($arEmbargo->getEmbargoTipoRel()->getPagoConceptoRel());
                $arVacacionAdicional->setEmbargoRel($arEmbargo);
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setVrDeduccion($vrDeduccionEmbargo);
                $arVacacionAdicional->setDetalle($detalle);
                $em->persist($arVacacionAdicional);
            }
        }
    }

    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $queryBuilder = $em->createQueryBuilder()->from(RhuVacacion::class, 'v')
            ->addSelect('v.codigoVacacionPk')
            ->addSelect('v.diasDisfrutadosReales')
            ->addSelect('v.fechaDesdeDisfrute')
            ->addSelect('v.fechaHastaDisfrute')
            ->addSelect('v.vrIbcPromedio')
            ->where("v.estadoAnulado = 0 AND (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) ");

        /*$dql = "SELECT vacacion FROM BrasaRecursoHumanoBundle:RhuVacacion vacacion "
            . "WHERE vacacion.estadoAnulado = 0 AND (((vacacion.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (vacacion.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (vacacion.fechaDesdeDisfrute >= '$strFechaDesde' AND vacacion.fechaDesdeDisfrute <= '$strFechaHasta') "
            . "OR (vacacion.fechaHastaDisfrute >= '$strFechaHasta' AND vacacion.fechaDesdeDisfrute <= '$strFechaDesde')) ";*/
        if ($codigoEmpleado != "") {
            $queryBuilder->andWhere("v.codigoEmpleadoFk = {$codigoEmpleado}");
        }
        $arVacaciones = $queryBuilder->getQuery()->getResult();
        return $arVacaciones;
    }

    public function pagar($codigoVacacion, $numero)
    {
        $em = $this->getEntityManager();

        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arVacacion = $em->getRepository(RhuVacacion::class)->find($codigoVacacion);
        /** @var $arContrato RhuContrato */
        $arContrato = $arVacacion->getContratoRel();
        $validar = "";
        // Calcular los creditos
        $arrCreditos = $em->getRepository(RhuVacacionAdicional::class)->resumenCredito($codigoVacacion);
        foreach ($arrCreditos as $arrCredito) {
            /** @var $arCredito RhuCredito */
            $arCredito = $em->getRepository(RhuCredito::class)->find($arrCredito['codigoCreditoFk']);
            if ($arCredito->getVrSaldo() < $arrCredito['total']) {
                $validar = "El credito " . $arrCredito['codigoCreditoFk'] . " tiene un saldo de " . $arCredito->getSaldo() . " y la deduccion de " . $arrCredito['total'] . " lo supera";
            }
        }
        if ($validar == "") {
            $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->findBy(array('codigoVacacionFk' => $codigoVacacion));
            foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                if ($arVacacionAdicional->getCodigoCreditoFk() != null) {
                    $arCredito = $em->getRepository(RhuCredito::class)->find($arVacacionAdicional->getCodigoCreditoFk());
                    $arCredito->setVrSaldo($arCredito->getVrSaldo() - $arVacacionAdicional->getVrDeduccion());
                    $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                    $arCredito->setTotalPagos($arCredito->getTotalPagos() + $arVacacionAdicional->getVrDeduccion());

                    $arPagoCredito = new RhuCreditoPago();
                    $arPagoCredito->setCreditoRel($arCredito);
                    $arPagoCredito->setfechaPago(new \ DateTime("now"));
                    $arCreditoTipoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipoPago')->find(3);
                    $arPagoCredito->setCreditoTipoPagoRel($arCreditoTipoPago);
                    $arPagoCredito->setVrCuota($arVacacionAdicional->getVrDeduccion());
                    $arPagoCredito->setSaldo($arCredito->getVrSaldo());
                    $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                    $em->persist($arPagoCredito);
                    if ($arCredito->getSaldo() <= 0) {
                        $arCredito->setEstadoPagado(1);
                    }
                    $em->persist($arCredito);
                }
            }
        }

        //Generar el pago en RhuPago
        if ($arVacacion->getEstadoAutorizado() == 1 && $arVacacion->getEstadoPagoGenerado() == 0) {
            $arPagoTipo = $em->getRepository(RhuPagoTipo::class)->find('VAC');
            $arPago = new RhuPago();
            $arPago->setPagoTipoRel($arPagoTipo);
            $arPago->setVacacionRel($arVacacion);
            $arPago->setCentroCostoRel($arVacacion->getContratoRel()->getCentroCostoRel());
            $arPago->setEmpleadoRel($arVacacion->getEmpleadoRel());
            $arPago->setVrSalarioEmpleado($arVacacion->getContratoRel()->getVrSalario());
            $arPago->setVrSalarioPeriodo($arVacacion->getVrSalarioActual());
            $arPago->setVrSalario($arVacacion->getContratoRel()->getVrSalario());
            $arPago->setFechaDesde($arVacacion->getFechaDesdeDisfrute());
            $arPago->setFechaHasta($arVacacion->getFechaDesdeDisfrute());
            $arPago->setFechaDesdePago($arVacacion->getFechaDesdeDisfrute());
            $arPago->setFechaHastaPago($arVacacion->getFechaDesdeDisfrute());
            $arPago->setContratoRel($arVacacion->getContratoRel());
            $arPago->setEntidadPensionRel($arVacacion->getContratoRel()->getEntidadPensionRel());
            $arPago->setEntidadSaludRel($arVacacion->getContratoRel()->getEntidadSaludRel());
            $arPago->setDiasPeriodo($arVacacion->getDiasPagados());
            $arPago->setCodigoUsuario($arVacacion->getCodigoUsuario());
            $arPago->setComentarios($arVacacion->getComentarios());
            $arPago->setEstadoPagado(1);
            $arPago->setNumero($numero);
            $em->persist($arPago);

            //Estos van en el detalle del pago
            /** @var $arConfiguracion RhuConfiguracion */
            $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);

            //Total de pago de vacacion por concepto de vacacion , se consulta el parametro en vacacionParametrizacion
            if ($arVacacion->getDiasPagados() > 0) {
                if ($arConfiguracion->getCodigoConceptoVacacionFk()) {
                    $arPagoConceptoVacacion = $em->getRepository(RhuConcepto::class)->find($arConfiguracion->getCodigoConceptoVacacionFk());
                    $arPagoDetalleVacacion = new RhuPagoDetalle();
                    $vrvacacionDinero = $arVacacion->getVrVacacionDinero();
                    $arPagoDetalleVacacion->setPagoRel($arPago);
                    $arPagoDetalleVacacion->setPagoConceptoRel($arPagoConceptoVacacion);
                    $arPagoDetalleVacacion->setDetalle('');
                    $arPagoDetalleVacacion->setVrPago($vrvacacionDinero);
                    $arPagoDetalleVacacion->setOperacion($arPagoConceptoVacacion->getOperacion());
                    $arPagoDetalleVacacion->setNumeroDias($arVacacion->getDiasPagados());
                    $arPagoDetalleVacacion->setVrPagoOperado($vrvacacionDinero * $arPagoConceptoVacacion->getOperacion());
                    $em->persist($arPagoDetalleVacacion);
                } else {
                    $validar = "El parametro de vacacion pagas no esta configurado correctamente";
                }
            }

            if ($arVacacion->getDiasDisfrutados() > 0) {
                if ($arConfiguracion->getCodigoConceptoVacacionDisfruteFk()) {
                    $arPagoConceptoVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arConfiguracion->getCodigoConceptoVacacionDisfruteFk());
                    $arPagoDetalleVacacion = new RhuPagoDetalle();
                    $vrVacacionDisfrute = round($arVacacion->getVrVacacionDisfrute());
                    $arPagoDetalleVacacion->setPagoRel($arPago);
                    $arPagoDetalleVacacion->setPagoConceptoRel($arPagoConceptoVacacion);
                    $arPagoDetalleVacacion->setDetalle('');
                    $arPagoDetalleVacacion->setVrPago($vrVacacionDisfrute);
                    $arPagoDetalleVacacion->setOperacion($arPagoConceptoVacacion->getOperacion());
                    $arPagoDetalleVacacion->setNumeroDias($arVacacion->getDiasDisfrutados());
                    $arPagoDetalleVacacion->setVrPagoOperado($vrVacacionDisfrute * $arPagoConceptoVacacion->getOperacion());
                    $em->persist($arPagoDetalleVacacion);
                } else {
                    $validar = "El parametro de vacacion disfrute no esta configurado correctamente";
                }
            }

            $codigoPension = $arContrato->getPensionRel()->getCodigoConceptoFk();
            if ($codigoPension) {
                //Total de pago de vacacion por concepto de pension, se consulta el parametro en vacacionParametrizacion
                $arPagoConceptoPension = $em->getRepository(RhuConcepto::class)->find($codigoPension);
                $arPagoDetallePension = new RhuPagoDetalle();
                $arPagoDetallePension->setPagoRel($arPago);
                $arPagoDetallePension->setPagoConceptoRel($arPagoConceptoPension);
                $arPagoDetallePension->setDetalle('');
                $arPagoDetallePension->setVrPago($arVacacion->getVrPension());
                $arPagoDetallePension->setPorcentajeAplicado($arPagoConceptoPension->getPorPorcentaje());
                $arPagoDetallePension->setOperacion($arPagoConceptoPension->getOperacion());
                $arPagoDetallePension->setVrPagoOperado($arVacacion->getVrPension() * $arPagoConceptoPension->getOperacion());
                $em->persist($arPagoDetallePension);
            } else {
                $validar = "El parametro de vacacion no esta configurado correctamente";
            }

            $codigoSalud = $arContrato->getSaludRel()->getCodigoConceptoFk();
            if ($codigoSalud) {
                $arPagoConceptoSalud = $em->getRepository(RhuConcepto::class)->find($codigoSalud);
                $arPagoDetalleSalud = new RhuPagoDetalle();
                $arPagoDetalleSalud->setPagoRel($arPago);
                $arPagoDetalleSalud->setPagoConceptoRel($arPagoConceptoSalud);
                $arPagoDetalleSalud->setDetalle('');
                $arPagoDetalleSalud->setVrPago($arVacacion->getVrSalud());
                $arPagoDetalleSalud->setPorcentajeAplicado($arPagoConceptoSalud->getPorPorcentaje());
                $arPagoDetalleSalud->setOperacion($arPagoConceptoSalud->getOperacion());
                $arPagoDetalleSalud->setVrPagoOperado($arVacacion->getVrSalud() * $arPagoConceptoSalud->getOperacion());
                $em->persist($arPagoDetalleSalud);
            } else {
                $validar = "El parametro de vacacion no esta configurado correctamente";
            }

            //Total de pago de vacacion por concepto de fondo de solidaridad pensional, se consulta el parametro en vacacionParametrizacion

            if ($arVacacion->getVrFondoSolidaridad() > 0) {
                $codigoSolidaridad = $arConfiguracion->getCodigoConceptoFondoSolidaridadPensionFk();
                if ($codigoSolidaridad) {
                    $arPagoConceptoFondo = $em->getRepository(RhuConcepto::class)->find($codigoSolidaridad);
                    $arPagoDetalleFondoSolidaridad = new RhuPagoDetalle();
                    $arPagoDetalleFondoSolidaridad->setPagoRel($arPago);
                    $arPagoDetalleFondoSolidaridad->setPagoConceptoRel($arPagoConceptoFondo);
                    $arPagoDetalleFondoSolidaridad->setDetalle('');
                    $arPagoDetalleFondoSolidaridad->setVrPago($arVacacion->getVrFondoSolidaridad());
                    $arPagoDetalleFondoSolidaridad->setPorcentajeAplicado($arPagoConceptoFondo->getPorPorcentaje());
                    $arPagoDetalleFondoSolidaridad->setOperacion($arPagoConceptoFondo->getOperacion());
                    $arPagoDetalleFondoSolidaridad->setVrPagoOperado($arVacacion->getVrFondoSolidaridad() * $arPagoConceptoFondo->getOperacion());
                    $em->persist($arPagoDetalleFondoSolidaridad);
                } else {
                    $validar = "El parametro de vacacion no esta configurado correctamente";
                }
            }

            //Se recorren los adicionales de las vacaciones
            $arVacacionAdicionales = $em->getRepository(RhuVacacionAdicional::class)->findBy(array('codigoVacacionFk' => $arVacacion->getCodigoVacacionPk()));
            foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                $vrPagoAdicional = 0;
                if ($arVacacionAdicional->getVrBonificacion() > 0) {
                    $vrPagoAdicional = $arVacacionAdicional->getVrBonificacion();
                } else {
                    $vrPagoAdicional = $arVacacionAdicional->getVrDeduccion();
                }
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setPagoConceptoRel($arVacacionAdicional->getPagoConceptoRel());
                $arPagoDetalle->setDetalle('');
                $arPagoDetalle->setVrPago($vrPagoAdicional);
                $arPagoDetalle->setOperacion($arVacacionAdicional->getPagoConceptoRel()->getOperacion());
                $arPagoDetalle->setVrPagoOperado($vrPagoAdicional * $arVacacionAdicional->getPagoConceptoRel()->getOperacion());
                if ($arVacacionAdicional->getPagoConceptoRel()->getGeneraIngresoBaseCotizacion()) {
                    $arPagoDetalle->setVrIngresoBaseCotizacion($vrPagoAdicional);
                }
                if ($arVacacionAdicional->getPagoConceptoRel()->getGeneraIngresoBasePrestacion()) {
                    $arPagoDetalle->setVrIngresoBasePrestacion($vrPagoAdicional);
                }
                $em->persist($arPagoDetalle);
                //Validar si algun adicional corresponde a un embargo para generar el pago en RhuEmbargoPago.
                if ($arVacacionAdicional->getCodigoEmbargoFk() != "") {
                    $arEmbargo = $arVacacionAdicional->getEmbargoRel();
                    //Crear embargo pago, se guarda el pago en al tabla rhu_embargo_pago
                    $arEmbargoPago = new RhuEmbargoPago();
                    $arEmbargoPago->setEmbargoRel($arEmbargo);
                    $arEmbargoPago->setPagoRel($arPago);
                    $arEmbargoPago->setFechaPago(new \ DateTime('now'));
                    $arEmbargoPago->setVrCuota($arVacacionAdicional->getVrDeduccion());
                    $arEmbargo->setDescuento($arEmbargo->getDescuento() + $arVacacionAdicional->getVrDeduccion());
                    $em->persist($arEmbargoPago);
                    $em->persist($arEmbargo);
                }
            }
            if ($validar == "") {
                $em->flush();
                if ($arPago->getCodigoPagoPk() > 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($arPago->getCodigoPagoPk(), $arConfiguracion);
                }
            }

        } else {
            $validar = "La vacacion debe estar autorizada y no puede estar pagada";
        }

        return $validar;
    }

}