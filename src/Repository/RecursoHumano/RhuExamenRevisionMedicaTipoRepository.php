<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenRevisionMedicaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuExamenRevisionMedicaTipoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuExamenRevisionMedicaTipo::class);
    }

}