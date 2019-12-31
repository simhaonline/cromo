<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurConfiguracion::class);
    }

    public function comercialNuevo(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurConfiguracion::class, 'c')
            ->select('c.vrSalarioMinimo')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function liquidarPedido(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurConfiguracion::class, 'c')
            ->select('c.redondearValorFactura')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

}
