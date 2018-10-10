<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCentroTrabajo;
use App\Entity\RecursoHumano\RhuPension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCentroTrabajoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCentroTrabajo::class);
    }
}