<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbCentroCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CtbCentroCostoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbCentroCosto::class);
    }

}