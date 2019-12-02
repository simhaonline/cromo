<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenReporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenReporteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenReporte::class);
    }


}