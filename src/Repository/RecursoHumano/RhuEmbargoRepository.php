<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEmbargoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmbargo::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmbargo::class, 'e');
        $queryBuilder
            ->select('e.codigoEmbargoPk');
        return $queryBuilder;
    }

}