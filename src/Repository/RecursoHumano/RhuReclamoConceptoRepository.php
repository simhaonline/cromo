<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuReclamoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuReclamoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuReclamoConcepto::class);
    }
}