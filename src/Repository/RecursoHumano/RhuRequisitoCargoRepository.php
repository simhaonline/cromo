<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisitoCargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuRequisitoCargoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRequisitoCargo::class);
    }


    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoCargo::class, 'rhrc')
            ->select('rhrc.codigoRequisitoCargoPk')
            ->addSelect('rc.nombre AS nombreRequisito')
            ->addSelect('c.nombre AS nombreCargo')
            ->addSelect('rc.general')
            ->leftJoin('rhrc.requisitoConceptoRel','rc')
            ->leftJoin('rhrc.cargoRel','c')
            ->orderBy('rhrc.codigoRequisitoCargoPk', 'ASC');

        return $queryBuilder;
    }
}