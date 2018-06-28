<?php

namespace App\Repository\Inventario;



use App\Entity\Inventario\InvPrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvPrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPrecio::class);
    }

    public function lista(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvPrecio::class,'p')
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

}