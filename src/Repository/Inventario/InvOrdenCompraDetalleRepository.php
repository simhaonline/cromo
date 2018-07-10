<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrdenCompra;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvOrdenCompraDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompraDetalle::class);
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

    public function listarDetallesPendientes($nombreItem = '', $codigoItem = '', $codigoOrdenCompra = '')
    {
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvOrdenCompraDetalle', 'iocd');
        $qb
            ->select('iocd.codigoOrdenCompraDetallePk')
            ->join('iocd.itemRel', 'it')
            ->join('iocd.ordenCompraRel', 'oc')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('iocd.cantidad')
            ->addSelect('iocd.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->where('oc.estadoAprobado = true')
            ->where('oc.estadoAnulado = false')
            ->andWhere('iocd.cantidadPendiente > 0');
        if ($nombreItem != '') {
            $qb->andWhere("it.nombre LIKE '%{$nombreItem}%'");
        }
        if ($codigoItem != '') {
            $qb->andWhere("iocd.codigoItemFk = {$codigoItem}");
        }
        if ($codigoOrdenCompra != '') {
            $qb->andWhere("iocd.codigoOrdenCompraFk = {$codigoOrdenCompra} ");
        }
        $qb->orderBy('iocd.codigoOrdenCompraDetallePk', 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
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
}