<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuExamenClaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuExamenClase::class);
    }

}