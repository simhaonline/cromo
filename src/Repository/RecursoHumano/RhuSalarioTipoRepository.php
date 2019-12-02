<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSalarioTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSalarioTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSalarioTipo::class);
    }
}