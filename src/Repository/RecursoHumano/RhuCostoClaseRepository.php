<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCostoClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCostoClaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCostoClase::class);
    }
}