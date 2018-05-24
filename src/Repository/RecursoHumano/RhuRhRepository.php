<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRh;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuRhRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRh::class);
    }

}