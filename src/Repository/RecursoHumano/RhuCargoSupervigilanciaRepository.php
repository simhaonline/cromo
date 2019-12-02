<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargoSupervigilancia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCargoSupervigilanciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCargoSupervigilancia::class);
    }


}