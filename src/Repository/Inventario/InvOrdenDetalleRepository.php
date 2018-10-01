<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvOrdenDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenDetalle::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvOrdenCompra::class, 'ioc');
        $qb
            ->select('ioc.codigoOrdenCompraPk as ID')
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
     * @param $arOrdenCompra InvOrdenCompra
     * @param $arrDetallesSeleccionados
     */
    public function eliminar($arOrdenCompra, $arrDetallesSeleccionados)
    {
        if ($arOrdenCompra->getEstadoAutorizado() == 0) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigoOrdenCompraDetalle) {
                    $arOrdenCompraDetalle = $this->_em->getRepository(InvOrdenCompraDetalle::class)->find($codigoOrdenCompraDetalle);
                    if ($arOrdenCompraDetalle) {
                        if ($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk() != '') {
                            $arSolicitudDetalle = $this->_em->getRepository(InvSolicitudDetalle::class)->find($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk());
                            if ($arSolicitudDetalle) {
                                $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() + $arOrdenCompraDetalle->getCantidad());
                                $this->_em->persist($arSolicitudDetalle);
                            }
                        }
                        $this->_em->remove($arOrdenCompraDetalle);
                    }
                }
                try {
                    $this->_em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvOrdenCompraDetalle::class, 'iocd')
            ->select('iocd.codigoOrdenCompraDetallePk')
            ->join('iocd.itemRel', 'it')
            ->join('iocd.ordenCompraRel', 'oc')
            ->addSelect('it.codigoItemPk AS codigoItem')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('iocd.cantidad')
            ->addSelect('iocd.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->addSelect('oc.numero AS ordenCompra')
            ->where('oc.estadoAprobado = true')
            ->where('oc.estadoAnulado = false')
            ->andWhere('iocd.cantidadPendiente > 0')
        ->orderBy('iocd.codigoOrdenCompraDetallePk', 'ASC');
        if ($session->get('filtroInvMovimientoItemCodigo') != '') {
            $queryBuilder->andWhere("iocd.codigoItemFk = {$session->get('filtroInvMovimientoItemCodigo')}");
        }
        if ($session->get('filtroInvNumeroOrdenCompra') != '') {
            $queryBuilder->andWhere("oc.numero = {$session->get('filtroInvNumeroOrdenCompra')}");
        }
        $query = $this->_em->createQuery($queryBuilder->getDQL());
        return $query->execute();
    }

    /**
     * @param $codigoOrdenCompra
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($codigoOrdenCompra){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvOrdenCompraDetalle::class,'iocd')
            ->select('iocd.codigoOrdenCompraDetallePk')
            ->addSelect('i.nombre')
            ->join('iocd.itemRel','i')
            ->addSelect('iocd.cantidad')
            ->addSelect('iocd.vrPrecio')
            ->addSelect('iocd.vrSubtotal')
            ->addSelect('iocd.porcentajeDescuento')
            ->addSelect('iocd.vrDescuento')
            ->addSelect('iocd.porcentajeIva')
            ->addSelect('iocd.vrIva')
            ->addSelect('iocd.vrTotal')
            ->where("iocd.codigoOrdenCompraFk = {$codigoOrdenCompra}");
        return $queryBuilder;
    }

    public function informeOrdenCompraPendientes(){

        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvOrdenCompraDetalle::class, 'iocd')
            ->select('iocd.codigoOrdenCompraDetallePk')
            ->join('iocd.itemRel', 'it')
            ->join('iocd.ordenCompraRel', 'oc')
            ->addSelect('it.codigoItemPk AS codigoItem')
            ->addSelect('oc.numero AS ordenCompra')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('iocd.cantidad')
            ->addSelect('iocd.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->where('oc.estadoAprobado = true')
            ->where('oc.estadoAnulado = false')
            ->andWhere('iocd.cantidadPendiente > 0')
            ->orderBy('iocd.codigoOrdenCompraDetallePk', 'ASC');
        if ($session->get('filtroInvCodigoOrdenCompraTipo') != null) {
            $queryBuilder->andWhere("oc.codigoOrdenCompraTipoFk = '{$session->get('filtroInvCodigoOrdenCompraTipo')}'");
        }
        if ($session->get('filtroInvOrdenCompraNumero') != '') {
            $queryBuilder->andWhere("oc.numero = {$session->get('filtroInvOrdenCompraNumero')}");
        }

        return $queryBuilder;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerarCantidadAfectada() {
        $em = $this->getEntityManager();
        $arDetalles = $this->listaRegenerarCantidadAfectada();
        foreach ($arDetalles as $arDetalle) {
            $cantidad = $arDetalle['cantidad'];
            $cantidadAfectada = $em->getRepository(InvMovimientoDetalle::class)->cantidadAfectaOrdenCompra($arDetalle['codigoOrdenCompraDetallePk']);
            $cantidadPendiente = $cantidad - $cantidadAfectada;
            if($cantidadAfectada != $arDetalle['cantidadAfectada'] || $cantidadPendiente != $arDetalle['cantidadPendiente']) {
                $arDetalleAct = $em->getRepository(InvOrdenCompraDetalle::class)->find($arDetalle['codigoOrdenCompraDetallePk']);
                $arDetalleAct->setCantidadAfectada($cantidadAfectada);
                $arDetalleAct->setCantidadPendiente($cantidadPendiente);
                $em->persist($arDetalleAct);
            }
        }
        $em->flush();
    }

    private function listaRegenerarCantidadAfectada()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvOrdenCompraDetalle::class, 'ocd')
            ->select('ocd.codigoOrdenCompraDetallePk')
            ->addSelect('ocd.cantidad')
            ->addSelect('ocd.cantidadAfectada')
            ->addSelect('ocd.cantidadPendiente');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arCodigo
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cantidadAfecta($arCodigo)
    {
        $cantidad = 0;
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvOrdenCompraDetalle::class, 'r')
            ->select("SUM(r.cantidad)")
            ->where("r.codigoSolicitudDetalleFk = {$arCodigo} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }
}