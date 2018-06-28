<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedidoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
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
                  pd.vrSubtotal,
                  pd.vrNeto,
                  i.nombre as itemNombre,
                  m.nombre as itemMarcaNombre                         
        FROM App\Entity\Inventario\InvPedidoDetalle pd
        LEFT JOIN pd.itemRel i
        LEFT JOIN i.marcaRel m
        WHERE pd.codigoPedidoFk = :codigoPedido'
        )->setParameter('codigoPedido', $codigoPedido);

        return $query->execute();
    }
    public function eliminar($arPedido, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arPedido->getEstadoAutorizado() == 0) {
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
                    MensajesController::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            MensajesController::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

}