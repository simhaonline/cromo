<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEntidadExamenDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEntidadExamenDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEntidadExamenDetalle::class);
    }

}