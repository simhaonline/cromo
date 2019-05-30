<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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

}
