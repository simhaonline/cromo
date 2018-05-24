<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuClasificacionRiesgoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuClasificacionRiesgo::class);
    }

}