<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoTipo;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        //$arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        //$arConfiguracionTurno = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
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
        $arPedidosDetalle = $em->getRepository(TurPedidoDetalle::class)->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk()));
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            if ($arPedidoDetalle->getCompuesto() == 0) {
                if ($arPedidoDetalle->getPeriodo() == "D" || $arPedidoDetalle->getDiasReales() == 1) {
                    $intDias = $arPedidoDetalle->getDiaHasta() - $arPedidoDetalle->getDiaDesde();
                    $intDias += 1;
                    if ($arPedidoDetalle->getDiaHasta() == 0 || $arPedidoDetalle->getDiaDesde() == 0) {
                        $intDias = 0;
                    }
                } else {
                    $intDias = date("d", (mktime(0, 0, 0, $arPedido->getFecha()->format('m') + 1, 1, $arPedido->getFecha()->format('Y')) - 1));
                }

                $intHorasRealesDiurnas = 0;
                $intHorasRealesNocturnas = 0;
                $intDiasOrdinarios = 0;
                $intDiasSabados = 0;
                $intDiasDominicales = 0;
                $intDiasFestivos = 0;
                if ($arPedidoDetalle->getDiaDesde() == 0) {
                    $diasDesde = '01';
                } else {
                    $diasDesde = $arPedidoDetalle->getDiaDesde();
                }
                $strFechaDesde = $arPedido->getFecha()->format('Y-m') . "-" . $diasDesde;
                $strFechaHasta = $arPedido->getFecha()->format('Y-m') . "-" . $arPedidoDetalle->getDiaHasta();
                $arFestivos = $em->getRepository(TurFestivo::class)->festivos($strFechaDesde, $strFechaHasta);
                $fecha = $strFechaDesde;
                for ($i = 0; $i < $intDias; $i++) {
                    $nuevafecha = strtotime('+' . $i . ' day', strtotime($fecha));
                    $nuevafecha = date('Y-m-j', $nuevafecha);
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if ($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                        if ($arPedidoDetalle->getFestivo() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                        }
                    } else {
                        if ($diaSemana == 1) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getLunes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 2) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getMartes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 3) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getMiercoles() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 4) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getJueves() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 5) {
                            $intDiasOrdinarios += 1;
                            if ($arPedidoDetalle->getViernes() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 6) {
                            $intDiasSabados += 1;
                            if ($arPedidoDetalle->getSabado() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                        if ($diaSemana == 7) {
                            $intDiasDominicales += 1;
                            if ($arPedidoDetalle->getDomingo() == 1) {
                                $intHorasRealesDiurnas += $arPedidoDetalle->getConceptoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas += $arPedidoDetalle->getConceptoRel()->getHorasNocturnas();
                            }
                        }
                    }
                }
                if ($arPedidoDetalle->getPeriodo() == 'M') {
                    if ($arPedidoDetalle->getDiasReales() == 0) {
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
                        $intHorasDiurnasLiquidacion = $arPedidoDetalle->getConceptoRel()->getHorasDiurnas() * $intTotalDias;
                        $intHorasNocturnasLiquidacion = $arPedidoDetalle->getConceptoRel()->getHorasNocturnas() * $intTotalDias;
                    } else {
                        $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                        $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
                    }
                } else {
                    $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                    $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
                }
                $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas) * $arPedidoDetalle->getCantidad();
                $arPedidoDetalleActualizar = $em->getRepository(TurPedidoDetalle::class)->find($arPedidoDetalle->getCodigoPedidoDetallePk());
                $floValorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedido->getSectorRel()->getPorcentaje();
                $porcentajeModalidad = 0;
                if ($arPedido->getSectorRel()->getCodigoSectorPk() == "R" && intval($arPedido->getEstrato()) >= 4) {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentajeEspecial();
                } else {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentaje();
                }

                $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $porcentajeModalidad / 100);
                $floVrHoraDiurna = ((($floValorBaseServicioMes * 55.97) / 100) / 30) / 15;
                $floVrHoraNocturna = ((($floValorBaseServicioMes * 44.03) / 100) / 30) / 9;
                if ($arPedidoDetalle->getPeriodo() == "M") {
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

                $subTotalDetalle = $floVrServicio;
                $subtotalGeneral += $subTotalDetalle;
                $baseAiuDetalle = $subTotalDetalle * ($arPedidoDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * $arPedidoDetalle->getPorcentajeIva() / 100;
                $ivaGeneral += $ivaDetalle;
                $totalDetalle = $subTotalDetalle + $ivaDetalle;

                $vrTotalDetalleAfectado = $arPedidoDetalle->getVrAfectado();
                $vrTotalDetalleDevolucion = $arPedidoDetalle->getVrDevolucion();
                $baseAuiGeneral += $baseAiuDetalle;
                $totalGeneral += $totalDetalle;

                $arPedidoDetalleActualizar->setVrSubtotal($subTotalDetalle);
                $arPedidoDetalleActualizar->setVrBaseIva($baseAiuDetalle);
                $arPedidoDetalleActualizar->setVrIva($ivaDetalle);
                $arPedidoDetalleActualizar->setVrTotal($totalDetalle);
                $vrTotalDetalleAdicion = $arPedidoDetalleActualizar->getVrAdicion();
                $pendienteFacturar = round($subTotalDetalle - $vrTotalDetalleAfectado - $vrTotalDetalleDevolucion + $vrTotalDetalleAdicion, 4);
                $arPedidoDetalleActualizar->setVrPendiente($pendienteFacturar);
                $arPedidoDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
                $arPedidoDetalleActualizar->setVrPrecio($precio);

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
                $douTotalServicio += $floVrServicio;
                $intCantidad++;
            } else {
                $douTotalHoras += $arPedidoDetalle->getHoras();
                $douTotalHorasDiurnas += $arPedidoDetalle->getHorasDiurnas();
                $douTotalHorasNocturnas += $arPedidoDetalle->getHorasNocturnas();
                $douTotalMinimoServicio += $arPedidoDetalle->getVrPrecioMinimo();
                $subtotalGeneral += $arPedidoDetalle->getVrSubtotal();
                $douTotalServicio += $arPedidoDetalle->getVrSubtotal();
                $baseAuiGeneral += $arPedidoDetalle->getVrBaseIva();
                $ivaGeneral += $arPedidoDetalle->getVrIva();
                $totalGeneral += $arPedidoDetalle->getVrTotal();
            }
        }

        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedido->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $subtotal = $subtotalGeneral + 0;
        $total = $totalGeneral + 0;
        $iva = $ivaGeneral + 0;

        $arPedido->setVrSubtotal($subtotal);
        $arPedido->setVrBaseIva($baseAuiGeneral);
        $arPedido->setVrIva($iva);
        $arPedido->setVrTotal(round($total));

        $em->persist($arPedido);
        $em->flush();
        return true;
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
                if($arPedido->getNumero() == 0) {
                    $arPedidoTipo = $em->getRepository(TurPedidoTipo::class)->find($arPedido->getCodigoPedidoTipoFk());
                    $arPedido->setNumero($arPedidoTipo->getConsecutivo());
                    $arPedidoTipo->setConsecutivo($arPedidoTipo->getConsecutivo() + 1);
                    $em->persist($arPedidoTipo);
                }
                $arPedido->setEstadoAutorizado(1);
                $em->persist($arPedido);
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
        $queryBuilder->orderBy('p.fecha', 'DESC');
        $queryBuilder->addOrderBy('p.numero', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

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
