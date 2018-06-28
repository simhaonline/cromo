<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvPedidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPedido::class);
    }

    public function lista(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvPedido::class,'p')
            ->join('p.terceroRel', 't')
            ->select('p.codigoPedidoPk')
            ->addSelect('p.numero')
            ->addSelect('p.fecha')
            ->addSelect('p.vrSubtotal')
            ->addSelect('p.vrIva')
            ->addSelect('p.vrNeto')
            ->addSelect('p.vrTotal')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where('p.codigoPedidoPk <> 0')
            ->orderBy('p.codigoPedidoPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();

    }

    public function liquidar($codigoPedido): bool
    {
        $arPedidoDetalles = $this->getEntityManager()->getRepository(InvPedidoDetalle::class)->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            $arPedidoDetalleAct = $this->getEntityManager()->getRepository(InvPedidoDetalle::class)->find($arPedidoDetalle->getCodigoPedidoDetallePk());
            $subtotal = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getVrPrecio();
            $arPedidoDetalleAct->setVrSubtotal($subtotal);
            $this->getEntityManager()->persist($arPedidoDetalleAct);
        }
        $this->getEntityManager()->flush();
        return true;
    }

}