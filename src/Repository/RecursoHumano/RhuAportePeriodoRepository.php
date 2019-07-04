<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAportePeriodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAportePeriodoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAportePeriodo::class);
    }

}