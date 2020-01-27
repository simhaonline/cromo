<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSeleccionReferenciaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionReferenciaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccionReferenciaTipo::class);
    }

}