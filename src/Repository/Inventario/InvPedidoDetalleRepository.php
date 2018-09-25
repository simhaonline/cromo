<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPedidoDetalle::class,'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.codigoPedidoFk')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('pd.cantidadPendiente')
            ->addSelect('i.nombre')
            ->addSelect('p.numero')
            ->addSelect('pt.nombre as pedidoTipo')
            ->leftJoin('pd.itemRel','i')
            ->leftJoin('pd.pedidoRel','p')
            ->leftJoin('p.pedidoTipoRel','pt')
            ->where('p.estadoAprobado = 1')
            ->andWhere('pd.cantidadPendiente > 0');
        return $queryBuilder->getQuery();
    }


}