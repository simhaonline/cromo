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
        /**
         * @var $arPedido TurPedido
         */
        $em = $this->getEntityManager();
        $intCantidad = 0;
        $precio = 0;
        $totalHoras = 0;
        $totalHorasDiurnas = 0;
        $totalHorasNocturnas = 0;
        $totalServicio = 0;
        $totalMinimoServicio = 0;
        $totalCostoCalculado = 0;
        $subtotalGeneral = 0;
        $baseAuiGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arPedidoDetalles = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk()));
        /** @var $arContratoDetalle TurContratoDetalle */
        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            if ($arPedidoDetalle->getEstadoTerminado() == 0) {
                /** @var $arConcepto TurConcepto */
                $arConcepto = $arPedidoDetalle->getConceptoRel();
                if ($arPedidoDetalle->getPeriodo() == "D") {
                    $dias = $arPedidoDetalle->getFechaDesde()->diff($arPedidoDetalle->getFechaHasta());
                    $dias = $dias->format('%a');
                    $dias += 1;
                    if ($arPedidoDetalle->getFechaHasta()->format('d') == '31') {
                        $dias = $dias - 1;
                    }
                    if ($arPedidoDetalle->getDia31() == 1) {
                        if ($arPedidoDetalle->getFechaHasta()->format('d') == '31') {
                            $dias = $dias + 1;
                        }
                    }
                } else {
                    $dias = 30;
                }

                $horasRealesDiurnas = 0;
                $horasRealesNocturnas = 0;
                $diasOrdinarios = 0;
                $diasSabados = 0;
                $diasDominicales = 0;
                $diasFestivos = 0;
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

                $horasRealesDiurnas =  $horasRealesDiurnas * $arPedidoDetalle->getCantidad();
                $horasRealesNocturnas = $horasRealesNocturnas * $arPedidoDetalle->getCantidad();
                $horas = $horasRealesDiurnas + $horasRealesNocturnas;
                $valorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedido->getSectorRel()->getPorcentaje();
                if ($arPedido->getCodigoSectorFk() == "D" && $arPedido->getEstrato() >= 4) {
                    //Cambiar porcentaje para residencial mayor a estrato 4
                    //$porcentajeModalidad = $arContratoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentaje();
                } else {
                    $porcentajeModalidad = $arPedidoDetalle->getModalidadRel()->getPorcentaje();
                }


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
                $baseAiuDetalle = $subTotalDetalle * ($arPedidoDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * ($arPedidoDetalle->getPorcentajeIva() / 100);
                $totalDetalle = $subTotalDetalle + $ivaDetalle;

                $arPedidoDetalle->setVrSubtotal($subTotalDetalle);
                $arPedidoDetalle->setVrBaseAiu($baseAiuDetalle);
                $arPedidoDetalle->setVrIva($ivaDetalle);
                $arPedidoDetalle->setVrTotalDetalle($totalDetalle);
                $arPedidoDetalle->setVrPrecioMinimo($valorMinimoServicio);
                $arPedidoDetalle->setVrPrecio($precio);
                $arPedidoDetalle->setHoras($horas);
                $arPedidoDetalle->setHorasDiurnas($horasRealesDiurnas);
                $arPedidoDetalle->setHorasNocturnas($horasRealesNocturnas);
                $arPedidoDetalle->setDias($dias);
                $em->persist($arPedidoDetalle);

                $subtotalGeneral += $subTotalDetalle;
                $baseAuiGeneral += $baseAiuDetalle;
                $ivaGeneral += $ivaDetalle;
                $totalGeneral += $totalDetalle;

                $totalHoras += $horas;
                $totalHorasDiurnas += $horasRealesDiurnas;
                $totalHorasNocturnas += $horasRealesNocturnas;
                $totalMinimoServicio += $valorMinimoServicio;
                $totalServicio += $valorServicio;
                $intCantidad++;
            }
        }

        $arPedido->setHoras($totalHoras);
        $arPedido->setHorasDiurnas($totalHorasDiurnas);
        $arPedido->setHorasNocturnas($totalHorasNocturnas);
        $arPedido->setVrTotalServicio($totalServicio);
        $arPedido->setVrTotalPrecioMinimo($totalMinimoServicio);
        $arPedido->setVrTotalCosto($totalCostoCalculado);
        $arPedido->setVrSubtotal($subtotalGeneral);
        $arPedido->setVrBaseAiu($baseAuiGeneral);
        $arPedido->setVrIva($ivaGeneral);
        $arPedido->setVrTotal($totalGeneral);
        $em->persist($arPedido);
        $em->flush();
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

        if($session->get('filtroTurPedidoCodigoCliente') != ''){
            $queryBuilder->andWhere("p.codigoClienteFk  = '{$session->get('filtroTurPedidoCodigoCliente')}'");
        }
        if($session->get('filtroTurPedidoNumero') != ''){
            $queryBuilder->andWhere("p.numero  = '{$session->get('filtroTurPedidoNumero')}'");
        }
        if($session->get('filtroTurPedidoCodigoPedido') != ''){
            $queryBuilder->andWhere("p.codigoPedidoPk  = '{$session->get('filtroTurPedidoCodigoPedido')}'");
        }
        if($session->get('filtroTurPedidoCodigoPedidoTipo') != ''){
            $queryBuilder->andWhere("p.codigoPedidoTipoFk  = '{$session->get('filtroTurPedidoCodigoPedidoTipo')}'");
        }
        if ($session->get('filtroTurPedidoFechaDesde') != null) {
            $queryBuilder->andWhere("p.fecha >= '{$session->get('filtroTurPedidoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurPedidoFechaHasta') != null) {
            $queryBuilder->andWhere("p.fecha <= '{$session->get('filtroTurPedidoFechaHasta')} 23:59:59'");
        }
        if($session->get('filtroTurPedidoEstadoAutorizado') != ''){
            $queryBuilder->andWhere("p.estadoAutorizado  = '{$session->get('filtroTurPedidoEstadoAutorizado')}'");
        }
        if($session->get('filtroTurPedidoEstadoAprobado') != ''){
            $queryBuilder->andWhere("p.estadoAprobado  = '{$session->get('filtroTurPedidoEstadoAprobado')}'");
        }
        if($session->get('filtroTurPedidoEstadoAnulado') != ''){
            $queryBuilder->andWhere("p.estadoAnulado  = '{$session->get('filtroTurPedidoEstadoAnulado')}'");
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
