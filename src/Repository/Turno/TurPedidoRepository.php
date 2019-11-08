<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPedido::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TurPedido::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurPedidoDetalle::class)->findBy(['codigoPedidoFk' => $arRegistro->getCodigoPedidoPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $codigoPedido
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoPedido)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select("COUNT(pd.codigoPedidoDetallePk)")
            ->where("pd.codigoPedidoFk = {$codigoPedido} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $arPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arPedido)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arConfiguracionTurno = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $subtotalGeneral = 0;
        $baseAuiGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $acuSubDetalle = 0;
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            if ($arPedidoDetalle->getCompuesto() == 0) {
                $intDiasFacturar = 0;
                if ($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2 || $arPedidoDetalle->getLiquidarDiasReales() == 1) {
                    $intDias = $arPedidoDetalle->getDiaHasta() - $arPedidoDetalle->getDiaDesde();
                    $intDias += 1;
                    if ($arPedidoDetalle->getDiaHasta() == 0 || $arPedidoDetalle->getDiaDesde() == 0) {
                        $intDias = 0;
                    }
                    $intDiasFacturar = $intDias;
                } else {
                    $intDias = date("d", (mktime(0, 0, 0, $arPedido->getFechaProgramacion()->format('m') + 1, 1, $arPedido->getFechaProgramacion()->format('Y')) - 1));
                    $intDiasFacturar = 30;
                }

                $intHorasRealesDiurnas = 0;
                $intHorasRealesNocturnas = 0;
                $intHorasDiurnasLiquidacion = 0;
                $intHorasNocturnasLiquidacion = 0;
                $intDiasOrdinarios = 0;
                $intDiasSabados = 0;
                $intDiasDominicales = 0;
                $intDiasFestivos = 0;
                if ($arPedidoDetalle->getDiaDesde() == 0) {
                    $diasDesde = '01';
                } else {
                    $diasDesde = $arPedidoDetalle->getDiaDesde();
                }
                $strFechaDesde = $arPedido->getFechaProgramacion()->format('Y-m') . "-" . $diasDesde;
                $strFechaHasta = $arPedido->getFechaProgramacion()->format('Y-m') . "-" . $arPedidoDetalle->getDiaHasta();
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($strFechaDesde, $strFechaHasta);
                $fecha = $strFechaDesde;
                for ($i = 0; $i < $intDias; $i++) {
                    $nuevafecha = strtotime('+' . $i . ' day', strtotime($fecha));
                    $nuevafecha = date('Y-m-j', $nuevafecha);
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if ($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                        if ($arPedidoDetalle->getFestivo() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                        }
                    } else {
                        if ($diaSemana == 1) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getLunes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 2) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getMartes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 3) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getMiercoles() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 4) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getJueves() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 5) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getViernes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 6) {
                            $intDiasSabados += 1;
                            if ($arPedidoDetalle->getSabado() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 7) {
                            $intDiasDominicales += 1;
                            if ($arPedidoDetalle->getDomingo() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();
                            }
                        }
                    }
                }
                if ($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                    if ($arPedidoDetalle->getLiquidarDiasReales() == 0) {
                        $intDiasOrdinarios = 0;
                        $intDiasSabados = 0;
                        $intDiasDominicales = 0;
                        $intDiasFestivos = 0;
                        if ($arPedidoDetalle->getLunes() == 1) {
                            $intDiasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getMartes() == 1) {
                            $intDiasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getMiercoles() == 1) {
                            $intDiasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getJueves() == 1) {
                            $intDiasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getViernes() == 1) {
                            $intDiasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getSabado() == 1) {
                            $intDiasSabados = 4;
                        }
                        if ($arPedidoDetalle->getDomingo() == 1) {
                            $intDiasDominicales = 4;
                        }
                        if ($arPedidoDetalle->getFestivo() == 1) {
                            $intDiasFestivos = 2;
                        }
                        $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                        $intHorasDiurnasLiquidacion = $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                        $intHorasNocturnasLiquidacion = $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;
                    } else {
                        $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                        $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
                    }
                } else {
                    $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                    $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
                }
                $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas) * $arPedidoDetalle->getCantidad();
                $douCostoCalculado = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getConceptoServicioRel()->getVrCosto();
                $douCostoCalculado = $douCostoCalculado;
                $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());
                $floValorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedido->getSectorRel()->getPorcentaje();
                $porcentajeModalidad = 0;
                if ($arPedido->getSectorRel()->getCodigoSectorPk() == 2 && intval($arPedido->getEstrato()) >= 4) {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                } else {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje();
                }
                // validacion para liquidar todos los servicios residensiales
                if ($arConfiguracionTurno->getLiquidarServicioResidencialTarifaIgual() && $arPedido->getCodigoSectorFk() == 2) {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                }
                $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $porcentajeModalidad / 100);
                $floVrHoraDiurna = ((($floValorBaseServicioMes * 55.97) / 100) / 30) / 15;
                $floVrHoraNocturna = ((($floValorBaseServicioMes * 44.03) / 100) / 30) / 9;
                if ($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                    $precio = ($intHorasDiurnasLiquidacion * $floVrHoraDiurna) + ($intHorasNocturnasLiquidacion * $floVrHoraNocturna);
                } else {
                    $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);
                }

//                $precio = round($precio);
                $floVrMinimoServicio = $precio;

                $floVrServicio = 0;
                $subTotalDetalle = 0;
                if ($arPedidoDetalleActualizar->getVrPrecioAjustado() != 0) {
                    $floVrServicio = $arPedidoDetalleActualizar->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad();
                    $precio = $arPedidoDetalleActualizar->getVrPrecioAjustado();
                } else {
                    $floVrServicio = $floVrMinimoServicio * $arPedidoDetalle->getCantidad();
                }
                if ($arPedidoDetalle->getNoFacturar()) {
                    $floVrServicio = 0;
                    $precio = 0;
                }
                //Si se liquida por precio de lista
                if ($arConfiguracionTurno->getAsignarPrecioLista() == 1) {
                    $intCodigoLista = $arPedido->getClienteRel()->getCodigoListaPrecioFk();
                    $intConceptoServicio = $arPedidoDetalleActualizar->getConceptoServicioRel()->getCodigoConceptoServicioPk();
                    $arListaPrecioDetalle = new \Brasa\TurnoBundle\Entity\TurListaPrecioDetalle();
                    $arListaPrecioDetalle = $em->getRepository('BrasaTurnoBundle:TurListaPrecioDetalle')->findOneBy(array('codigoListaPrecioFk' => $intCodigoLista, 'codigoConceptoServicioFk' => $intConceptoServicio));
                    if ($arListaPrecioDetalle) {
                        $arPedidoDetalleActualizar->setVrPrecioAjustado($arListaPrecioDetalle->getVrPrecio());
                        $precio = $arPedidoDetalleActualizar->getVrPrecioAjustado();
                    }
                    if ($arPedidoDetalleActualizar->getVrPrecioAjustado() != 0) {
                        $floVrServicio = $arPedidoDetalleActualizar->getVrPrecioAjustado();
                    }
                }

                $subTotalDetalle = $floVrServicio;
                $subtotalGeneral += $subTotalDetalle;
                $baseAiuDetalle = $subTotalDetalle * ($arPedidoDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * $arPedidoDetalle->getPorcentajeIva() / 100;
                $ivaGeneral += $ivaDetalle;
                $totalDetalle = $subTotalDetalle + $ivaDetalle;
                if ($arPedidoDetalle->getSumarBaseIva()) {
                    $totalDetalle += $baseAiuDetalle;
                }
                $vrTotalDetalleAfectado = $arPedidoDetalle->getVrTotalDetalleAfectado();
                $vrTotalDetalleDevolucion = $arPedidoDetalle->getVrTotalDetalleDevolucion();
                if ($arConfiguracion->getRedondearValorFactura()) {
                    $subTotalDetalle = round($subTotalDetalle);
                    $vrTotalDetalleAfectado = round($vrTotalDetalleAfectado);
                    $vrTotalDetalleDevolucion = round($vrTotalDetalleDevolucion);
                }
                $baseAuiGeneral += $baseAiuDetalle;
//                $totalDetalle = round($totalDetalle);
                $totalGeneral += $totalDetalle;

                // validacion para factura distribuida
                if ($arPedidoDetalle->getFacturaDistribuida()) {
                    $subTotalDetalle = 0;
                    $baseAiuDetalle = 0;
                    $ivaDetalle = 0;
                    $totalDetalle = 0;
                    $precio = 0;
                    $floVrServicio = 0;
                    $serviciosDetallesConceptoDistribuido = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConceptoDistribuido')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));
                    foreach ($serviciosDetallesConceptoDistribuido as $servicioDetalleConceptoDistribuido) {
                        $subtotal = $servicioDetalleConceptoDistribuido->getVrPrecio() * $servicioDetalleConceptoDistribuido->getCantidad();
                        $subTotalDetalle += $subtotal;
                        $baseAiu = $subtotal * ($servicioDetalleConceptoDistribuido->getPorBaseAiu() / 100);
                        $baseAiuDetalle += $baseAiu;
                        $iva = $baseAiu * ($servicioDetalleConceptoDistribuido->getPorIva() / 100);
                        $ivaDetalle += $iva;
                        $totalDetalle += $subtotal + $iva;
                        $precio = $subTotalDetalle;
                    }
                    $acuSubDetalle += $subTotalDetalle;
                    $baseAuiGeneral = $baseAiuDetalle;
                    $ivaGeneral = $ivaDetalle;
                    $subtotalGeneral = $acuSubDetalle;
                    $floVrServicio = $subTotalDetalle;
                    $totalGeneral = $acuSubDetalle;
                }

                $arPedidoDetalleActualizar->setVrSubtotal($subTotalDetalle);
                $arPedidoDetalleActualizar->setVrBaseAiu($baseAiuDetalle);
                $arPedidoDetalleActualizar->setVrIva($ivaDetalle);
                $arPedidoDetalleActualizar->setVrTotalDetalle($totalDetalle);
                $vrTotalDetalleAdicion = $arPedidoDetalleActualizar->getVrTotalDetalleAdicion();
                $pendienteFacturar = round($subTotalDetalle - $vrTotalDetalleAfectado - $vrTotalDetalleDevolucion + $vrTotalDetalleAdicion, 4);
                $arPedidoDetalleActualizar->setVrTotalDetallePendiente($pendienteFacturar);
                $arPedidoDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
                $arPedidoDetalleActualizar->setVrPrecio($precio);
                $arPedidoDetalleActualizar->setVrCosto($douCostoCalculado);

                $intHorasRealesDiurnas = $intHorasRealesDiurnas * $arPedidoDetalle->getCantidad();
                $intHorasRealesNocturnas = $intHorasRealesNocturnas * $arPedidoDetalle->getCantidad();
                $arPedidoDetalleActualizar->setHoras($douHoras);
                $arPedidoDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
                $arPedidoDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
                $arPedidoDetalleActualizar->setDias($intDias);

                $em->persist($arPedidoDetalleActualizar);
                $douTotalHoras += $douHoras;
                $douTotalHorasDiurnas += $intHorasRealesDiurnas;
                $douTotalHorasNocturnas += $intHorasRealesNocturnas;
                $douTotalMinimoServicio += $floVrMinimoServicio;
                $douTotalCostoCalculado += $douCostoCalculado;
                $douTotalServicio += $floVrServicio;
                $intCantidad++;
            } else {
                $douTotalHoras += $arPedidoDetalle->getHoras();
                $douTotalHorasDiurnas += $arPedidoDetalle->getHorasDiurnas();
                $douTotalHorasNocturnas += $arPedidoDetalle->getHorasNocturnas();
                $douTotalMinimoServicio += $arPedidoDetalle->getVrPrecioMinimo();
                $subtotalGeneral += $arPedidoDetalle->getVrSubtotal();
                $douTotalServicio += $arPedidoDetalle->getVrSubtotal();
                $baseAuiGeneral += $arPedidoDetalle->getVrBaseAiu();
                $ivaGeneral += $arPedidoDetalle->getVrIva();
                $totalGeneral += $arPedidoDetalle->getVrTotalDetalle();
            }
        }

        //Otros conceptos
        $floSubTotalConceptos = 0;
        $floTotalConceptos = 0;
        $floIvaTotalConceptos = 0;
        $arPedidoDetalleConceptos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach ($arPedidoDetalleConceptos as $arPedidoDetalleConcepto) {
            $arPedidoDetalleConceptoAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($arPedidoDetalleConcepto->getCodigoPedidoDetalleConceptoPk());
            $subtotal = $arPedidoDetalleConcepto->getCantidad() * $arPedidoDetalleConcepto->getPrecio();
            if ($arPedidoDetalleConceptoAct->getNoFacturar()) {
                $subtotal = 0;
            }
            $subtotalAIU = $subtotal * $arPedidoDetalleConcepto->getConceptoServicioRel()->getPorBaseIva() / 100;
            $iva = ($subtotalAIU * $arPedidoDetalleConcepto->getPorIva()) / 100;
            $total = $subtotal + $iva;
            $arPedidoDetalleConceptoAct->setSubtotal($subtotal);
            $arPedidoDetalleConceptoAct->setIva($iva);
            $arPedidoDetalleConceptoAct->setBaseIva($subtotalAIU);
            $arPedidoDetalleConceptoAct->setTotal($total);
            $em->persist($arPedidoDetalleConceptoAct);
            $floSubTotalConceptos += $subtotal;
            $floTotalConceptos += $total;
            $floIvaTotalConceptos += $iva;
            $baseAuiGeneral += $subtotalAIU;
        }


        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);

        $arPedido->setVrTotalServicio($douTotalServicio);
        $arPedido->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arPedido->setVrTotalOtros($floSubTotalConceptos);
        $arPedido->setVrTotalCosto($douTotalCostoCalculado);
        $subtotal = $subtotalGeneral + $floSubTotalConceptos;
        $total = $totalGeneral + $floTotalConceptos;
        $iva = $ivaGeneral + $floIvaTotalConceptos;

        $arPedido->setVrSubtotal($subtotal);
        $arPedido->setVrBaseAiu($baseAuiGeneral);
        $arPedido->setVrIva($iva);
        $arPedido->setVrTotal(round($total));

        $em->persist($arPedido);
        $em->flush();
        return true;
    }

    /**
     * @param $arPedido TurPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arPedido)
    {
        $em = $this->getEntityManager();
        if (!$arPedido->getEstadoAutorizado()) {
                $registros = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
                ->select('COUNT(pd.codigoPedidoDetallePk) AS registros')
                ->where('pd.codigoPedidoFk = ' . $arPedido->getCodigoPedidoPk())
                ->getQuery()->getSingleResult();
            if ($registros['registros'] > 0) {
                $arPedido->setEstadoAutorizado(1);
                $em->persist($arPedido);
                $arPedidoDetalles = $em->getRepository( TurPedidoDetalle::class)->findBy(['codigoPedidoFk'=>$arPedido->getCodigoPedidoPk()]);
                /**@var $arPedidoDetalle TurPedidoDetalle**/
                $diasFestivos = 0;
                $diasOrdinarios = 0;
                $diasSabados = 0;
                $horasRealesDiurnas =0;
                $horasRealesNocturnas =0;
                $diasDominicales =0;
                foreach ($arPedidoDetalles as $arPedidoDetalle)
                {
                    $arConcepto = $arPedidoDetalle->getConceptoRel();

                    if ($arPedidoDetalle->getPeriodo() == "M") {
                        if ($arPedidoDetalle->getLunes()) {
                            $diasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getMartes()) {
                            $diasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getMiercoles()) {
                            $diasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getJueves()) {
                            $diasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getViernes()) {
                            $diasOrdinarios += 4;
                        }
                        if ($arPedidoDetalle->getSabado()) {
                            $diasSabados = 4;
                        }
                        if ($arPedidoDetalle->getDomingo()) {
                            $diasDominicales = 4;
                        }
                        if ($arPedidoDetalle->getFestivo()) {
                            $diasFestivos = 2;
                        }
                        $totalDias = $diasOrdinarios + $diasSabados + $diasDominicales + $diasFestivos;
                        $horasRealesDiurnas = $arConcepto->getHorasDiurnas() * $totalDias;
                        $horasRealesNocturnas = $arConcepto->getHorasNocturnas() * $totalDias;
                    } else {
                        $arFestivos = $em->getRepository(TurFestivo::class)->fecha($arPedidoDetalle->getFechaDesde()->format('Y-m-d'), $arPedidoDetalle->getFechaHasta()->format('Y-m-d'));
                        $fecha = $arPedidoDetalle->getFechaDesde()->format('Y-m-j');
                        for ($i = 0; $i < $dias; $i++) {
                            $nuevafecha = strtotime('+' . $i . ' day', strtotime($fecha));
                            $nuevafecha = date('Y-m-j', $nuevafecha);
                            $dateNuevaFecha = date_create($nuevafecha);
                            $diaSemana = $dateNuevaFecha->format('N');
                            if ($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                                $diasFestivos += 1;
                            } else {
                                if ($diaSemana == 1) {
                                    $diasOrdinarios += 1;
                                    if ($arPedidoDetalle->getLunes() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 2) {
                                    $diasOrdinarios += 1;
                                    if ($arPedidoDetalle->getMartes() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 3) {
                                    $diasOrdinarios += 1;
                                    if ($arPedidoDetalle->getMiercoles() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 4) {
                                    $diasOrdinarios += 1;
                                    if ($arPedidoDetalle->getJueves() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 5) {
                                    $diasOrdinarios += 1;
                                    if ($arPedidoDetalle->getViernes() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 6) {
                                    $diasSabados += 1;
                                    if ($arPedidoDetalle->getSabado() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                                if ($diaSemana == 7) {
                                    $diasDominicales += 1;
                                    if ($arPedidoDetalle->getDomingo() == 1) {
                                        $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                        $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                    }
                                }
                            }
                        }
                    }

                    $valorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedido->getSectorRel()->getPorcentaje();
                    if ($arPedido->getCodigoSectorFk() == "D" && $arPedido->getEstrato() >= 4) {
                        //Cambiar porcentaje para residencial mayor a estrato 4
                        //$porcentajeModalidad = $arContratoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                        $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentaje();
                    } else {
                        $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentaje();
                    }


                    $horasRealesDiurnas =  $horasRealesDiurnas * $arPedidoDetalle->getCantidad();
                    $horasRealesNocturnas = $horasRealesNocturnas * $arPedidoDetalle->getCantidad();
                    $valorBaseServicioMes = $valorBaseServicio + ($valorBaseServicio * $porcentajeModalidad / 100);

                    $valorHoraDiurna = ((($valorBaseServicioMes * 55.97) / 100) / 30) / 15;
                    $valorHoraNocturna = ((($valorBaseServicioMes * 44.03) / 100) / 30) / 9;

                    $precio = ($horasRealesDiurnas * $valorHoraDiurna) + ($horasRealesNocturnas * $valorHoraNocturna);
                    $valorMinimoServicio = $precio;

                    if ($arPedidoDetalle->getVrPrecioAjustado() != 0) {
                        $valorServicio = $arPedidoDetalle->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad();
                        $precio = $arPedidoDetalle->getVrPrecioAjustado();
                    } else {
                        $valorServicio = $valorMinimoServicio;
                    }
                    $subTotalDetalle = $valorServicio;

                    $vrTotalDetallePendiente = $subTotalDetalle-$arPedidoDetalle->getVrAbono();
                    $arPedidoDetalle->setVrTotalDetallePendiente($vrTotalDetallePendiente);
                    $em->persist($arPedidoDetalle);
                }
                $em->flush();
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arPedido TurPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arPedido)
    {
        $em = $this->getEntityManager();
        if ($arPedido->getEstadoAutorizado()) {
            $arPedido->setEstadoAutorizado(0);
            $em->persist($arPedido);
            $em->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arPedido TurPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arPedido){
        $em = $this->getEntityManager();
        if($arPedido->getEstadoAutorizado() == 1 ) {
            if($arPedido->getEstadoAprobado() == 0){
                $arPedido->setEstadoAprobado(1);
                $em->persist($arPedido);
                $em->flush();
            }else{
                Mensajes::error('El pedido ya esta aprobada');
            }

        } else {
            Mensajes::error('El pedido ya esta desautorizada');
        }
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedido::class, 'p')
            ->select('p.codigoPedidoPk')
            ->addSelect('p.numero')
            ->addSelect('p.fecha')
            ->addSelect('p.horas')
            ->addSelect('p.horasDiurnas')
            ->addSelect('p.horasNocturnas')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado')
            ->addSelect('p.vrTotal')
            ->addSelect('p.usuario')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('s.nombre as sectorNombre')
        ->leftJoin('p.pedidoTipoRel', 'pt')
        ->leftJoin('p.clienteRel', 'c')
        ->leftJoin('p.sectorRel', 's');

        if($session->get('filtroTurInformeComercialPedidoClienteCodigo') != ''){
            $queryBuilder->andWhere("p.codigoClienteFk  = '{$session->get('filtroTurInformeComercialPedidoClienteCodigo')}'");
        }
        if ($session->get('filtroTurInformeComercialPedidoClienteCodigoFechaDesde') != null) {
            $queryBuilder->andWhere("p.fecha >= '{$session->get('filtroTurInformeComercialPedidoClienteCodigoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeComercialPedidoClienteCodigoFechaHasta') != null) {
            $queryBuilder->andWhere("p.fecha <= '{$session->get('filtroTurInformeComercialPedidoClienteCodigoFechaHasta')} 23:59:59'");
        }

        if($session->get('filtroTurPedidoProgramacionCodigoCliente') != ''){
            $queryBuilder->andWhere("p.codigoClienteFk  = '{$session->get('filtroTurPedidoProgramacionCodigoCliente')}'");
        }
        if($session->get('filtroTurPedidoProgramacionNumero') != ''){
            $queryBuilder->andWhere("p.numero  = '{$session->get('filtroTurPedidoProgramacionNumero')}'");
        }
        if($session->get('filtroTurPedidoProgramacionCodigoPedido') != ''){
            $queryBuilder->andWhere("p.codigoPedidoPk  = '{$session->get('filtroTurPedidoProgramacionCodigoPedido')}'");
        }
        if($session->get('filtroTurPedidoProgramacionCodigoPedidoTipo') != ''){
            $queryBuilder->andWhere("p.codigoPedidoTipoFk  = '{$session->get('filtroTurPedidoProgramacionCodigoPedidoTipo')}'");
        }
        if ($session->get('filtroTurPedidoProgramacionFechaDesde') != null) {
            $queryBuilder->andWhere("p.fecha >= '{$session->get('filtroTurPedidoProgramacionFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurPedidoProgramacionFechaHasta') != null) {
            $queryBuilder->andWhere("p.fecha <= '{$session->get('filtroTurPedidoProgramacionFechaHasta')} 23:59:59'");
        }
        if($session->get('filtroTurPedidoProgramacionEstadoAutorizado') != ''){
            $queryBuilder->andWhere("p.estadoAutorizado  = '{$session->get('filtroTurPedidoProgramacionEstadoAutorizado')}'");
        }
        if($session->get('filtroTurPedidoProgramacionEstadoAprobado') != ''){
            $queryBuilder->andWhere("p.estadoAprobado  = '{$session->get('filtroTurPedidoProgramacionEstadoAprobado')}'");
        }
        if($session->get('filtroTurPedidoProgramacionEstadoAnulado') != ''){
            $queryBuilder->andWhere("p.estadoAnulado  = '{$session->get('filtroTurPedidoProgramacionEstadoAnulado')}'");
        }


        return $queryBuilder;
    }

    public function listaPedido($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoClienteFk = null;
        $numero = null;
        $codigoPedidoPk = null;
        $codigoPedidoTipoFk = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros){
            $codigoClienteFk =$filtros['codigoClienteFk']?? null;
            $numero =$filtros['numero']?? null;
            $codigoPedidoPk =$filtros['codigoPedidoPk']?? null;
            $codigoPedidoTipoFk =$filtros['codigoPedidoTipoFk']?? null;
            $fechaDesde = $filtros['$fechaDesde']??null;
            $fechaHasta = $filtros['$fechaHasta']??null;
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedido::class, 'p')
            ->select('p.codigoPedidoPk')
            ->addSelect('p.numero')
            ->addSelect('p.fecha')
            ->addSelect('p.horas')
            ->addSelect('p.horasDiurnas')
            ->addSelect('p.horasNocturnas')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado')
            ->addSelect('p.vrTotal')
            ->addSelect('p.usuario')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('s.nombre as sectorNombre')
            ->leftJoin('p.pedidoTipoRel', 'pt')
            ->leftJoin('p.clienteRel', 'c')
            ->leftJoin('p.sectorRel', 's');

        if($codigoClienteFk){
            $queryBuilder->andWhere("p.codigoClienteFk  = '{$codigoClienteFk}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("p.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("p.fecha <= '{$fechaHasta} 23:59:59'");
        }

        if($numero){
            $queryBuilder->andWhere("p.numero  = '{$numero}'");
        }
        if($codigoPedidoPk){
            $queryBuilder->andWhere("p.codigoPedidoPk  = '{$codigoPedidoPk}'");
        }
        if($codigoPedidoTipoFk){
            $queryBuilder->andWhere("p.codigoPedidoTipoFk  = '{$codigoPedidoTipoFk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("pestadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAnulado = 1");
                break;
        }
        $queryBuilder->orderBy('p.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('p.numero', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;

    }

    public function listaSinAprobar(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedido::class, 'p')
            ->select('p')
           ->where('p.estadoAprobado = 0');
        if($session->get('filtroTurInformeComercialPedidoSinAprobarClienteCodigo') != ''){
            $queryBuilder->andWhere("p.codigoClienteFk  = '{$session->get('filtroTurInformeComercialPedidoSinAprobarClienteCodigo')}'");
        }
        if ($session->get('filtroTurInformeComercialPedidoClienteSinAprobarFechaDesde') != null) {
            $queryBuilder->andWhere("p.fecha >= '{$session->get('filtroTurInformeComercialPedidoClienteCodigoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeComercialPedidoClienteCodigoFechaHasta') != null) {
            $queryBuilder->andWhere("p.fecha <= '{$session->get('filtroTurInformeComercialPedidoClienteSinAprobarFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }
}
