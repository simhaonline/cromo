<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;

use App\Entity\RecursoHumano\RhuContratoClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuContratoClaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuContratoClase::class);
    }
}