<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvOrdenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrden::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvOrdenCompra', 'ioc');
        $qb
            ->select('ioc.codigoOrdenCompraPk as ID')
            ->join('ioc.terceroRel', 't')
            ->addSelect('t.nombreCorto AS TERCERO')
            ->addSelect('ioc.numero as NUMERO')
            ->addSelect('ioc.fecha as FECHA')
            ->addSelect('ioc.soporte as SOPORTE')
            ->addSelect('ioc.estadoAutorizado as AUTORIZADO')
            ->addSelect('ioc.estadoAprobado as APROBADO')
            ->addSelect('ioc.estadoAnulado as ANULADO');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @return mixed
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvOrden::class, 'o')
            ->select('o.codigoOrdenPk')
            ->addSelect('o.numero')
            ->addSelect('ot.nombre as nombreTipo')
            ->addSelect('o.fecha')
            ->addSelect('o.estadoAutorizado')
            ->addSelect('o.estadoAprobado')
            ->addSelect('o.estadoAnulado')
            ->join('o.ordenTipoRel', 'ot')
            ->where('o.codigoOrdenPk <> 0');
        if ($session->get('filtroInvOrdenNumero') != '') {
            $queryBuilder->andWhere("o.numero = {$session->get('filtroInvOrdenNumero')}");
        }
        switch ($session->get('filtroInvOrdenEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("o.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("o.estadoAprobado = 1");
                break;
        }
        if ($session->get('filtroInvOrdenCodigoOrdenTipo')) {
            $queryBuilder->andWhere("o.codigoOrdenTipoFk = '{$session->get('filtroInvOrdenCodigoOrdenTipo')}'");
        }
        return $queryBuilder;
    }

    /**
     * @param $codigoOrden
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoOrden)
    {
        $em = $this->getEntityManager();
        $arOrden = $em->getRepository(InvOrden::class)->find($codigoOrden);
        $arOrdenDetalles = $em->getRepository(InvOrdenDetalle::class)->findBy(['codigoOrdenFk' => $codigoOrden]);
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        foreach ($arOrdenDetalles as $arOrdenDetalle) {
            $arOrdenDetalleAct = $em->getRepository(InvOrdenDetalle::class)->find($arOrdenDetalle->getCodigoOrdenDetallePk());
            $subtotal = $arOrdenDetalle->getCantidad() * $arOrdenDetalle->getVrPrecio();
            $porcentajeIva = $arOrdenDetalle->getPorcentajeIva();
            $iva = $subtotal * $porcentajeIva / 100;
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $total = $subtotal + $iva;
            $totalGeneral += $total;
            $arOrdenDetalleAct->setVrSubtotal($subtotal);
            $arOrdenDetalleAct->setVrIva($iva);
            $arOrdenDetalleAct->setVrTotal($total);
            $em->persist($arOrdenDetalleAct);
        }
        $arOrden->setVrSubtotal($subtotalGeneral);
        $arOrden->setVrIva($ivaGeneral);
        $arOrden->setVrTotal($totalGeneral);
        $em->persist($arOrden);
        $em->flush();
    }    
    
    /**
     * @param $arOrden InvOrden
     */
    public function aprobar($arOrden)
    {
        $em = $this->getEntityManager();
        $arOrdenTipo = $em->getRepository(InvOrdenTipo::class)->find($arOrden->getCodigoOrdenTipoFk());
        if (!$arOrden->getEstadoAprobado()) {
            $arOrdenTipo->setConsecutivo($arOrdenTipo->getConsecutivo() + 1);
            $em->persist($arOrdenTipo);
            $arOrden->setEstadoAprobado(1);
            $arOrden->setNumero($arOrdenTipo->getConsecutivo());
            $em->persist($arOrden);

            $arOrdenDetalles = $em->getRepository(InvOrdenDetalle::class)->findBy(['codigoOrdenFk' => $arOrden->getCodigoOrdenPk()]);
            foreach ($arOrdenDetalles as $arOrdenDetalle) {
                $arItem = $em->getRepository(InvItem::class)->find($arOrdenDetalle->getCodigoItemFk());
                $arItem->setCantidadOrden($arItem->getCantidadOrden() + $arOrdenDetalle->getCantidad());
                $em->persist($arItem);
            }
            $em->flush();
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     * @return array
     */
    public function anular($arOrdenCompra)
    {
        $respuesta = [];
        if ($arOrdenCompra->getEstadoAprobado() == 1) {
            $arOrdenCompra->setEstadoAnulado(1);
            $this->_em->persist($arOrdenCompra);

            $arOrdenCompraDetalles = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $arOrdenCompra->getCodigoOrdenCompraPk()]);
            foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
                $respuesta = $this->validarDetalleEnuso($arOrdenCompraDetalle->getCodigoOrdenCompraDetallePk());
                $arItem = $this->_em->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arOrdenCompraDetalle->getCodigoItemFk()]);
                if ($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk()) {
                    $arSolicitudDetalle = $this->_em->getRepository('App:Inventario\InvSolicitudDetalle')->find($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk());
                    $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() + $arOrdenCompraDetalle->getCantidad());
                    $this->_em->persist($arSolicitudDetalle);
                    $arItem->setCantidadSolicitud($arItem->getCantidadSolicitud() + $arOrdenCompraDetalle->getCantidad());
                }
                $arItem->setCantidadOrdenCompra($arItem->getCantidadOrdenCompra() - $arOrdenCompraDetalle->getCantidad());
                $this->_em->persist($arItem);
            }
            if(count($respuesta) == 0){
                $this->_em->flush();
            }
            return $respuesta;
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     */
    public function desautorizar($arOrdenCompra)
    {
        if ($arOrdenCompra->getEstadoAutorizado() == 1 && $arOrdenCompra->getEstadoAprobado() == 0) {
            $arOrdenCompra->setEstadoAutorizado(0);
            $this->_em->persist($arOrdenCompra);
            $this->_em->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arOrden InvOrden
     */
    public function autorizar($arOrden)
    {
        $em = $this->getEntityManager();
        if (count($em->getRepository(InvOrdenDetalle::class)->findBy(['codigoOrdenFk' => $arOrden->getCodigoOrdenPk()])) > 0) {
            $arOrden->setEstadoAutorizado(1);
            $em->persist($arOrden);
            $em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    private function validarDetalleEnuso($codigoOrdenCompraDetalle)
    {
        $respuesta = [];
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvMovimientoDetalle', 'imd')
            ->select('imd.codigoMovimientoDetallePk')
            ->addSelect('imd.codigoMovimientoFk')
            ->join('imd.movimientoRel', 'm')
            ->where("imd.codigoOrdenCompraDetalleFk = {$codigoOrdenCompraDetalle}")
            ->andWhere('m.estadoAnulado = 0');
        $query = $this->_em->createQuery($qb->getDQL());
        $resultado = $query->execute();
        if(count($resultado) > 0){
            foreach ($resultado as $result) {
                $respuesta[] = 'No se puede anular, el detalle con ID '.$codigoOrdenCompraDetalle. ' esta siendo utilizado en el movimiento con ID '.$result['codigoMovimientoFk'];
            }
        }
        return $respuesta;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arOrden InvOrden
         */
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvOrden::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvOrdenDetalle::class)->findBy(['codigoOrdenFk' => $arRegistro->getCodigoOrdenPk()])) <= 0) {
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
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }
}