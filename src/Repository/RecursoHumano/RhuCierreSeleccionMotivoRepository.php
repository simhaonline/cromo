<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCierreSeleccionMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCierreSeleccionMotivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCierreSeleccionMotivo::class);
    }

}