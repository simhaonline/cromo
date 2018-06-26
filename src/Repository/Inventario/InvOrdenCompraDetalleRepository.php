<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
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
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvOrdenCompra', 'ioc');
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
                    $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->find($codigoOrdenCompraDetalle);
                    if ($arOrdenCompraDetalle) {
                        if ($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk() != '') {
                            $arSolicitudDetalle = $this->_em->getRepository('App:Inventario\InvSolicitudDetalle')->find($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk());
                            if ($arSolicitudDetalle) {
                                $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() + $arOrdenCompraDetalle->getCantidadSolicitada());
                                $this->_em->persist($arSolicitudDetalle);
                            }
                        }
                        $this->_em->remove($arOrdenCompraDetalle);
                    }
                }
                try {
                    $this->_em->flush();
                } catch (\Exception $e) {
                    MensajesController::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            MensajesController::error('No se puede eliminar, el registro se encuentra autorizado');
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
            ->addSelect('iocd.cantidadSolicitada')
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
}