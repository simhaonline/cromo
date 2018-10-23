<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConcepto::class);
    }
}