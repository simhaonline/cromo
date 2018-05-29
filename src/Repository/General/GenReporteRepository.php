<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenReporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenReporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenReporte::class);
    }


}