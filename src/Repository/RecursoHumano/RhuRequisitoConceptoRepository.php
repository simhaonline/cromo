<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuRequisitoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuRequisitoConcepto::class);
    }

    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoConcepto::class, 'rhrc')
            ->select('rhrc.codigoRequisitoConceptoPk')
            ->addSelect('rhrc.nombre')
            ->addSelect('rhrc.general')
            ->orderBy('rhrc.codigoRequisitoConceptoPk', 'ASC');

        return $queryBuilder;
    }
}