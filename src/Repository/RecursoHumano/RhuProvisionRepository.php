<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuProvision;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuProvisionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuProvision::class);
    }

    public function lista()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuProvision::class, 'p')
            ->select('p.codigoProvisionPk')
            ->addSelect('p.anio')
            ->addSelect('p.mes')
            ->addSelect('p.vrSalud')
            ->addSelect('p.vrPension')
            ->addSelect('p.vrCaja')
            ->addSelect('p.vrRiesgos')
            ->addSelect('p.vrCesantias')
            ->addSelect('p.vrInteresesCesantias')
            ->addSelect('p.vrVacaciones')
            ->addSelect('p.vrPrimas')
            ->addSelect('p.vrIngresoBaseCotizacion')
            ->addSelect('p.vrIngresoBasePrestacion')
            ->addSelect('p.fechaDesde')
            ->addSelect('p.fechaHasta')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado');
        return $queryBuilder;
    }

    public function autorizar($provisionPeriodo)
    {

        $em = $this->getEntityManager();
        $arProvisionPeriodo = $em->getRepository(RhuProvision::class)->find($provisionPeriodo);
        if (!$arProvisionPeriodo->getEstadoAutorizado()) {
            $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
            $salarioMinimo = $arConfiguracion->getVrSalarioMinimo();
            $porcentajeCaja = $arConfiguracion->getAportesPorcentajeCaja();
            $porcentajeCesantias = $arConfiguracion->getPrestacionesPorcentajeCesantias();
            $porcentajeInteresesCesantias = $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias();
            $porcentajeVacaciones = $arConfiguracion->getPrestacionesPorcentajeVacaciones();
            $porcentajePrimas = $arConfiguracion->getPrestacionesPorcentajePrimas();
            $porcentajeIndemnizacion = $arConfiguracion->getPrestacionesPorcentajeIndemnizacion();
            $anio = $arProvisionPeriodo->getAnio();

            $ingresoBasePrestacionTotal = 0;
            $ingresoBaseCotizacionTotal = 0;
            $cesantiasTotal = 0;
            $interesesCesantiasTotal = 0;
            $primasTotal = 0;
            $vacacionesTotal = 0;
            $indemnizacionTotal = 0;
            $pensionTotal = 0;
            $saludTotal = 0;
            $riesgosTotal = 0;
            $cajaTotal = 0;
            $senaTotal = 0;
            $icbfTotal = 0;

            //Verificar los contratos activos a la fecha para la provision
            $dql = "SELECT c.codigoContratoPk, c.codigoEmpleadoFk, c.fechaDesde, c.fechaHasta, c.estadoTerminado, c.fechaUltimoPagoCesantias FROM App\Entity\RecursoHumano\RhuContrato c "
                . "WHERE c.fechaDesde <= '" . $arProvisionPeriodo->getFechaHasta()->format('Y/m/d') . "' "
                . " AND (c.fechaHasta >= '" . $arProvisionPeriodo->getFechaDesde()->format('Y/m/d') . "' "
                . " OR c.indefinido = 1)";
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();

            //Adicionar los que aunque no tenian contrato tuvieron pago
            $dql = "SELECT p.codigoEmpleadoFk, p.codigoContratoFk AS codigoContratoPk, c.fechaDesde, c.fechaHasta, c.estadoTerminado, c.fechaUltimoPagoCesantias FROM App\Entity\RecursoHumano\RhuPago p "
                . "JOIN p.contratoRel c "
                . "WHERE p.estadoAprobado = 1 AND p.fechaDesde >= '" . $arProvisionPeriodo->getFechaDesde()->format('Y/m/d') . "' AND p.fechaDesde <= '" . $arProvisionPeriodo->getFechaHasta()->format('Y/m/d') . "'"
                . "GROUP BY p.codigoEmpleadoFk, p.codigoContratoFk, c.fechaDesde, c.fechaHasta, c.estadoTerminado, c.fechaUltimoPagoCesantias";
            $query = $em->createQuery($dql);
            $arPagos = $query->getResult();
            foreach ($arPagos as $arPago) {
                if (!in_array($arPago, $arContratos)) {
                    $arContratos[] = $arPago;
                }
            }

            foreach ($arContratos as $arContrato) {

                $ingresoBasePrestacion = 0;
                $ingresoBaseCotizacion = 0;
                $ingresoBaseIndemnizacion = 0;
                $ingresoBaseVacacion = 0;
                $pensionEmpleado = 0;
                $saludEmpleado = 0;
                $saludEmpleadoOperado = 0;
                $pensionEmpleadoOperado = 0;
                $dql = "SELECT pd.vrPago, pd.vrPagoOperado,  pd.vrIngresoBasePrestacion, pd.vrIngresoBaseCotizacion, pc.provisionIndemnizacion, pc.provisionVacacion, pc.conceptoPension, pc.conceptoSalud, pc.conceptoFondoSolidaridadPensional, pd.operacion, p.codigoPagoTipoFk FROM App\Entity\RecursoHumano\RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.conceptoRel pc "
                    . "WHERE p.codigoEmpleadoFk = " . $arContrato['codigoEmpleadoFk'] . " AND p.codigoContratoFk = " . $arContrato['codigoContratoPk'] . " AND p.estadoAprovado = 1 AND p.fechaDesde >= '" . $arProvisionPeriodo->getFechaDesde()->format('Y/m/d') . "' AND p.fechaDesde <= '" . $arProvisionPeriodo->getFechaHasta()->format('Y/m/d') . "'";
                $query = $em->createQuery($dql);
                $arPagosDetalles = $query->getResult();
                foreach ($arPagosDetalles as $arPagoDetalle) {
                    $ingresoBasePrestacion += ($arPagoDetalle['vrIngresoBasePrestacion']);//*$arPagoDetalle['operacion']); se comenta mientras se verifica con el jefe la logica
                    $ingresoBaseCotizacion += $arPagoDetalle['vrIngresoBaseCotizacion'];
                    if ($arPagoDetalle['provisionIndemnizacion'] == 1) {
                        $ingresoBaseIndemnizacion += $arPagoDetalle['vrIngresoBasePrestacion'];
                    }
                    if ($arPagoDetalle['provisionVacacion'] == 1) {
                        $ingresoBaseVacacion += $arPagoDetalle['vrIngresoBasePrestacion'];
                    }
                    // se añade validacion dado que no se puede sumar para la provision el valor de pension en la liquidacion -- solicitud de Ruben de grupo record
                    if ($arPagoDetalle['conceptoPension'] == 1 && $arPagoDetalle['vrPagoOperado'] < 0 || $arPagoDetalle['conceptoFondoSolidaridadPensional'] == 1) {
                        $pensionEmpleadoOperado += $arPagoDetalle['vrPagoOperado'];
                        $pensionEmpleado += $arPagoDetalle['vrPago'];
                    }
                    if ($arPagoDetalle['conceptoSalud'] == 1 && $arPagoDetalle['vrPagoOperado'] < 0) {
                        $saludEmpleadoOperado += $arPagoDetalle['vrPagoOperado'];
                        $saludEmpleado += $arPagoDetalle['vrPago'];
                    }
                }
                $arrVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->deduccionesAportes($arContrato['codigoContratoPk'], $arProvisionPeriodo->getFechaDesde(), $arProvisionPeriodo->getFechaHasta());
                //Se retira esta condicion porque ya los descuentos estan en pago
                //$pensionEmpleado += $arrVacacion['vrPension'];
                //$saludEmpleado += $arrVacacion['vrSalud'];
                $arEmpleadoAct = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato['codigoEmpleadoFk']);
                $arContratoAct = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContratoAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arContrato['codigoContratoPk']);
                $porcentajeRiesgos = $arContratoAct->getClasificacionRiesgoRel()->getPorcentaje();
                $porcentajePension = $arContratoAct->getTipoPensionRel()->getPorcentajeEmpleador();
                $porcentajeSalud = $arContratoAct->getTipoSaludRel()->getPorcentajeEmpleador();
                //Prestaciones
                if ($arContratoAct->getSalarioIntegral() == 0) {
                    $cesantias = ($ingresoBasePrestacion * $porcentajeCesantias) / 100; // Porcentaje 8.33
                    $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100; // Porcentaje 1 sobre las cesantias
                    $primas = ($ingresoBasePrestacion * $porcentajePrimas) / 100; // 8.33
                    // se liquida los intereses sobre el aculumado de cesantias
                    if ($arConfiguracion->getLiquidarPromedioCesantiasAcumulado()) {
                        $fechaInicioContrato = $arContrato['fechaDesde'];
                        $fechaFinContrato = $arContrato['fechaHasta'];
                        $estadoActivo = $arContrato['estadoActivo'];
                        $fechaUltimoPagoCesantias = $arContrato['fechaUltimoPagoCesantias'];
                        $fechaHasta = $fechaFinContrato < $arProvisionPeriodo->getFechaHasta() && $estadoActivo == false ? $fechaFinContrato : $arProvisionPeriodo->getFechaHasta();
                        if ($fechaHasta->format('d') == '31') {
                            $fechaHasta = new \DateTime(($fechaHasta->format('Y-m') . '-30'));
                        }

                        $fechaDesde = $fechaInicioContrato > $fechaUltimoPagoCesantias ? $fechaInicioContrato : $fechaUltimoPagoCesantias;

                        $acumuladoCesantias = 0;
                        $acumuladoIntereses = 0;
                        if ($fechaInicioContrato < $fechaUltimoPagoCesantias) {
                            $intDiasIntereses = $objFunciones->diasPrestaciones($fechaDesde, $fechaHasta);
                        } else {
                            $intDiasIntereses = $objFunciones->diasPrestaciones($fechaFinContrato, $fechaHasta);
                        }
//                                if ($fechaDesde->format('m') != 1) {
                        $acumuladoCesantias += $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->acumuladoCesantias($fechaDesde, $fechaHasta, $arContrato['codigoContratoPk']);
                        $acumuladoCesantias += round($cesantias);
                        $acumuladoIntereses = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->acumuladoIntereses($fechaDesde, $fechaHasta, $arContrato['codigoContratoPk']);

                        $interesesCesantias = round(($acumuladoCesantias * $intDiasIntereses * 0.12 / 360) - $acumuladoIntereses);
//
                    }
                } else {
                    $cesantias = 0;
                    $interesesCesantias = 0;
                    $primas = 0;
                }
                $vacaciones = ($ingresoBaseVacacion * $porcentajeVacaciones) / 100; // 4.17
                $indemnizacion = ($ingresoBaseIndemnizacion * $porcentajeIndemnizacion) / 100; // 4.17

                //Aportes
                $arrAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->cotizacionMes($arProvisionPeriodo->getAnio(), $arProvisionPeriodo->getMes(), $arContrato['codigoContratoPk']);
                $salud = 0;
                $saludMayorDescuento = 0;
                if ($arrAportes['cotizacionSalud'] > $saludEmpleado || ($arrAportes['cotizacionSalud'] > $saludEmpleadoOperado && $saludEmpleadoOperado >= 0)) {
                    //Esto se da cuando se descuenta salud en un periodo con vacaciones partidas queda el descuento recargado
                    //en un solo mes
                    if ($arConfiguracion->getProvisionAnticipoSalud()) {
                        $anticipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvisionAnticipo')->anticipo($arProvisionPeriodo->getAnio(), $arProvisionPeriodo->getMes(), $arContrato['codigoEmpleadoFk'], $arContrato['codigoContratoPk']);
                        $saludEmpleado += $anticipo;
                    }

                    if (($arrAportes['cotizacionSalud'] > $saludEmpleadoOperado && $saludEmpleadoOperado >= 0)) {
                        $salud = $arrAportes['cotizacionSalud'] - $saludEmpleadoOperado;
                    }
                    if ($arrAportes['cotizacionSalud'] > $saludEmpleado) {
                        $salud = $arrAportes['cotizacionSalud'] - $saludEmpleado;
                    }

                } else {
                    $saludMayorDescuento = $saludEmpleado - $arrAportes['cotizacionSalud'];
                    if ($arConfiguracion->getProvisionAnticipoSalud()) {
                        $periodo = $this->periodoSiguiente($arProvisionPeriodo->getAnio(), $arProvisionPeriodo->getMes());
                        $valor = $saludEmpleado - $arrAportes['cotizacionSalud'];
                        if ($valor > 1000) {
                            $arProvisionAnticipo = new RhuProvisionAnticipo();
                            $arProvisionAnticipo->setCodigoProvisionPeriodoFk($codigoProvisionPeriodo);
                            $arProvisionAnticipo->setEmpleadoRel($arEmpleadoAct);
                            $arProvisionAnticipo->setContratoRel($arContratoAct);
                            $arProvisionAnticipo->setMes($periodo['mes']);
                            $arProvisionAnticipo->setAnio($periodo['anio']);
                            $arProvisionAnticipo->setValor($valor);
                            $em->persist($arProvisionAnticipo);
                        }
                    }
                }
                $pension = 0;
                $pensionMayorDescuento = 0;
                if ($arrAportes['cotizacionPension'] > $pensionEmpleado || ($arrAportes['cotizacionPension'] > $pensionEmpleadoOperado && $pensionEmpleadoOperado >= 0)) {
                    $pension = $arrAportes['cotizacionPension'] - $pensionEmpleado;
                    if ($arrAportes['cotizacionPension'] > $pensionEmpleadoOperado && $pensionEmpleadoOperado >= 0) {
                        $pension = $arrAportes['cotizacionPension'] - $pensionEmpleadoOperado;
                    }
                } else {
                    $pensionMayorDescuento = $pensionEmpleado - $arrAportes['cotizacionPension'];
                }

                $caja = $arrAportes['cotizacionCaja'];
                $riesgos = $arrAportes['cotizacionRiesgos'];
                $sena = $arrAportes['cotizacionSena'];
                $icbf = $arrAportes['cotizacionIcbf'];

                $salarioAporte = 0;
                if ($arContratoAct->getSalarioIntegral() == 1) {
                    $salarioAporte = ($ingresoBaseCotizacion * 70) / 100;
                } else {
                    $salarioAporte = $ingresoBaseCotizacion;
                }

                //12 aprendiz, 19 practicante y 23 estudiante
                if ($arContratoAct->getCodigoTipoCotizanteFk() == '19' || $arContratoAct->getCodigoTipoCotizanteFk() == '12' || $arContratoAct->getCodigoTipoCotizanteFk() == '23') {
                    $cesantias = 0;
                    $interesesCesantias = 0;
                    $primas = 0;
                    $vacaciones = 0;
                }

                $ingresoBasePrestacion = round($ingresoBasePrestacion);
                $ingresoBaseCotizacion = round($ingresoBaseCotizacion);
                $cesantias = round($cesantias);
                $interesesCesantias = round($interesesCesantias);
                $primas = round($primas);
                $vacaciones = round($vacaciones);
                $indemnizacion = round($indemnizacion);

                $ingresoBasePrestacionTotal += $ingresoBasePrestacion;
                $ingresoBaseCotizacionTotal += $ingresoBaseCotizacion;
                $cesantiasTotal += $cesantias;
                $interesesCesantiasTotal += $interesesCesantias;
                $primasTotal += $primas;
                $vacacionesTotal += $vacaciones;
                $indemnizacionTotal += $indemnizacion;
                $pensionTotal += $pension;
                $saludTotal += $salud;
                $riesgosTotal += $riesgos;
                $cajaTotal += $caja;
                $senaTotal += $sena;
                $icbfTotal += $icbf;

                $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                $arProvision->setEmpleadoRel($arEmpleadoAct);
                $arProvision->setContratoRel($arContratoAct);
                $arProvision->setAnio($arProvisionPeriodo->getAnio());
                $arProvision->setMes($arProvisionPeriodo->getMes());
                $arProvision->setFechaDesde($arProvisionPeriodo->getFechaDesde());
                $arProvision->setFechaHasta($arProvisionPeriodo->getFechaHasta());
                $arProvision->setProvisionPeriodoRel($arProvisionPeriodo);
                $arProvision->setVrSalario($arContratoAct->getVrSalarioPago());
                $arProvision->setVrIngresoBasePrestacion($ingresoBasePrestacion);
                $arProvision->setVrIngresoBaseCotizacion($ingresoBaseCotizacion);
                $arProvision->setVrCesantias($cesantias);
                $arProvision->setVrInteresesCesantias($interesesCesantias);
                $arProvision->setVrPrimas($primas);
                $arProvision->setVrVacaciones($vacaciones);
                $arProvision->setVrIndemnizacion($indemnizacion);
                $arProvision->setVrPension($pension);
                $arProvision->setVrSalud($salud);
                $arProvision->setVrRiesgos($riesgos);
                $arProvision->setVrCaja($caja);
                $arProvision->setVrSena($sena);
                $arProvision->setVrIcbf($icbf);
                $arProvision->setVrSaludMayorDescuento($saludMayorDescuento);
                $arProvision->setVrSaludDescuento($saludEmpleado);
                $arProvision->setVrSaludAporte($arrAportes['cotizacionSalud']);
                $arProvision->setVrPensionMayorDescuento($pensionMayorDescuento);
                $arProvision->setVrPensionDescuento($pensionEmpleado);
                $arProvision->setVrPensionAporte($arrAportes['cotizacionPension']);
                $em->persist($arProvision);
            }

        } else {
            Mensajes::error("El registro ya se encuentra autorizado");
        }
    }
}