<?php

namespace App\Repository\General;

use App\Entity\General\GenProceso;
use App\Entity\General\GenProcesoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenProcesoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenProcesoTipo::class);
    }

}