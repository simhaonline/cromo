<?php

namespace App\Repository\General;

use App\Entity\General\GenProceso;
use App\Entity\General\GenProcesoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenProcesoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenProcesoTipo::class);
    }

}