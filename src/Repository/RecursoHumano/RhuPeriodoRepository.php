<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPeriodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuPeriodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuPeriodo::class);
    }
}