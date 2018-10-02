<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;

class InvPedidoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPedidoDetalle::class);
    }

    public function pedido($codigoPedido): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT pd.codigoPedidoDetallePk,
                  pd.codigoPedidoFk,
                  pd.cantidad,
                  pd.cantidadPendiente,
                  pd.vrPrecio,
                  pd.porcentajeIva,
                  pd.vrIva,
                  pd.vrSubtotal,
                  pd.vrNeto,
                  pd.vrTotal,
                  i.nombre as itemNombre,
                  m.nombre as itemMarcaNombre                         
        FROM App\Entity\Inventario\InvPedidoDetalle pd
        LEFT JOIN pd.itemRel i
        LEFT JOIN i.marcaRel m
        WHERE pd.codigoPedidoFk = :codigoPedido'
        )->setParameter('codigoPedido', $codigoPedido);

        return $query->execute();
    }

    /**
     * @param $arPedido InvPedido
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arPedido, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arPedido->getEstadoAutorizado()) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function pendientes(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPedidoDetalle::class,'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.codigoPedidoFk')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('pd.cantidadPendiente')
            ->addSelect('pd.cantidad')
            ->addSelect('i.nombre')
            ->addSelect('p.numero')
            ->addSelect('p.fecha as fechaPedido')
            ->addSelect('pt.nombre as pedidoTipo')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->leftJoin('pd.itemRel','i')
            ->leftJoin('pd.pedidoRel','p')
            ->leftJoin('p.pedidoTipoRel','pt')
            ->leftJoin('p.terceroRel', 't')
            ->where('p.estadoAprobado = 1')
            ->andWhere('p.estadoAnulado = 0')
            ->andWhere('pd.cantidadPendiente > 0');
        if($session->get('filtroInvPedidoTipo')){
            $queryBuilder->andWhere("p.codigoPedidoTipoFk = '{$session->get('filtroInvPedidoTipo')}'");
        }
        return $queryBuilder->getQuery();
    }

    public function listarDetallesPendientes()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.cantidadPendiente')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('p.numero')
            ->leftJoin('pd.itemRel', 'i')
            ->leftJoin('pd.pedidoRel', 'p')
            ->where('p.estadoAprobado = 1')
            ->andWhere('p.estadoAnulado = 0')
            ->andWhere('pd.cantidadPendiente > 0')
            ->orderBy('pd.codigoPedidoDetallePk', 'DESC');
        if($session->get('filtroInvPedidoNumero')){
            $queryBuilder->andWhere("p.numero = '{$session->get('filtroInvPedidoNumero')}'");
        }
        $query = $this->_em->createQuery($queryBuilder->getDQL());
        return $query->execute();
    }

    public function listaRegenerarCantidadAfectada()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.cantidadAfectada')
            ->addSelect('pd.cantidadPendiente');
        $arrPedidosDetalles = $queryBuilder->getQuery()->getResult();
        return $arrPedidosDetalles;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerarCantidadAfectada() {
        $em = $this->getEntityManager();
        $arPedidosDetalles = $em->getRepository(InvPedidoDetalle::class)->listaRegenerarCantidadAfectada();
        foreach ($arPedidosDetalles as $arPedidoDetalle) {
            $cantidad = $arPedidoDetalle['cantidad'];
            $cantidadAfectada = $em->getRepository(InvMovimientoDetalle::class)->cantidadAfectaPedido($arPedidoDetalle['codigoPedidoDetallePk']);
            $cantidadPendiente = $cantidad - $cantidadAfectada;
            if($cantidadAfectada != $arPedidoDetalle['cantidadAfectada'] || $cantidadPendiente != $arPedidoDetalle['cantidadPendiente']) {
                $arPedidoDetalleAct = $em->getRepository(InvPedidoDetalle::class)->find($arPedidoDetalle['codigoPedidoDetallePk']);
                $arPedidoDetalleAct->setCantidadAfectada($cantidadAfectada);
                $arPedidoDetalleAct->setCantidadPendiente($cantidadPendiente);
                $em->persist($arPedidoDetalleAct);
            }
        }
        $em->flush();
    }

}