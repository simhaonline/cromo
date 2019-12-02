<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvSolicitudDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvSolicitudDetalleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvSolicitudDetalle::class);
    }

    public function lista($codigoRegistro)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('sd')
            ->from(InvSolicitudDetalle::class, 'sd')
            ->where("sd.codigoSolicitudFk = {$codigoRegistro}")
            ->orderBy('sd.codigoSolicitudDetallePk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @param $arRegistro
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arRegistro, $arrDetallesSeleccionados)
    {
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(InvSolicitudDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSolicitudDetalle::class, 'sd');
        $queryBuilder
            ->select('sd.codigoSolicitudDetallePk')
            ->addSelect('sd.codigoItemFk')
            ->addSelect('i.nombre')
            ->addSelect('i.cantidadExistencia')
            ->addSelect('sd.cantidad')
            ->addSelect('sd.cantidadPendiente')
            ->join('sd.itemRel', 'i')
            ->join('sd.solicitudRel', 's')
            ->where('s.estadoAprobado = true')
            ->andWhere('s.estadoAnulado = false')
            ->andWhere('sd.cantidadPendiente > 0');
        if ($session->get('filtroInvBuscarItemNombre') != '') {
            $queryBuilder->andWhere("i.nombre LIKE '%{$session->get('filtroInvBuscarItemNombre')}%'");
        }
        if ($session->get('filtroInvBucarItemCodigo') != '') {
            $queryBuilder->andWhere("sd.codigoItemFk = {$session->get('filtroInvBucarItemCodigo')}");
        }
        if ($session->get('filtroInvSolicitudCodigo') != '') {
            $queryBuilder->andWhere("sd.codigoSolicitudFk = {$session->get('filtroInvSolicitudCodigo')} ");
        }
        $queryBuilder->orderBy('sd.codigoSolicitudDetallePk', 'ASC');
        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendientes()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvSolicitudDetalle::class, 'sd')
            ->select('sd.codigoSolicitudDetallePk')
            ->addSelect('s.numero')
            ->addSelect('s.fecha')
            ->addSelect('sd.codigoSolicitudFk')
            ->addSelect('sd.cantidadPendiente')
            ->addSelect('sd.cantidad')
            ->addSelect('sd.codigoItemFk')
            ->addSelect('i.nombre')
            ->addSelect('st.nombre as tipo')
            ->leftJoin('sd.solicitudRel', 's')
            ->leftJoin('s.solicitudTipoRel', 'st')
            ->leftJoin('sd.itemRel', 'i')
            ->where('s.estadoAprobado = 1')
            ->andWhere('sd.cantidadPendiente > 0');
        if ($session->get('filtroInvCodigoSolicitudTipo') != null) {
            $queryBuilder->andWhere("s.codigoSolicitudTipoFk = '{$session->get('filtroInvCodigoSolicitudTipo')}'");
        }
        return $queryBuilder;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerarCantidadAfectada()
    {
        $em = $this->getEntityManager();
        $arDetalles = $this->listaRegenerarCantidadAfectada();
        foreach ($arDetalles as $arDetalle) {
            $cantidad = $arDetalle['cantidad'];
            $cantidadAfectada = $em->getRepository(InvOrdenDetalle::class)->cantidadAfectaSolicitud($arDetalle['codigoSolicitudDetallePk']);
            $cantidadPendiente = $cantidad - $cantidadAfectada;
            if ($cantidadAfectada != $arDetalle['cantidadAfectada'] || $cantidadPendiente != $arDetalle['cantidadPendiente']) {
                $arDetalleAct = $em->getRepository(InvSolicitudDetalle::class)->find($arDetalle['codigoSolicitudDetallePk']);
                $arDetalleAct->setCantidadAfectada($cantidadAfectada);
                $arDetalleAct->setCantidadPendiente($cantidadPendiente);
                $em->persist($arDetalleAct);
            }
        }
        $em->flush();
    }

    private function listaRegenerarCantidadAfectada()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvSolicitudDetalle::class, 'ocd')
            ->select('ocd.codigoSolicitudDetallePk')
            ->addSelect('ocd.cantidad')
            ->addSelect('ocd.cantidadAfectada')
            ->addSelect('ocd.cantidadPendiente');
        return $queryBuilder->getQuery()->getResult();
    }
}