<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSeleccionPruebaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionPruebaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccionPruebaTipo::class);
    }

}