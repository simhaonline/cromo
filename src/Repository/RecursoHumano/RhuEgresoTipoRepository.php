<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEgresoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEgresoTipoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEgresoTipo::class);
    }

}