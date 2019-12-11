<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurPedidoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurPedidoTipo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoTipo::class, 'pt')
            ->select('pt.codigoPedidoTipoPk')
            ->addSelect('pt.nombre');
        if ($session->get('filtroTurnoPedidoTipoNombre') != '') {
            $queryBuilder->andWhere("pt.nombre LIKE '%{$session->get('filtroTurnoPedidoTipoNombre')}%' ");
        }
        return $queryBuilder->getQuery()->getResult();
    }

}
