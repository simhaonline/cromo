<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEntidadExamenDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEntidadExamenDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEntidadExamenDetalle::class);
    }

}