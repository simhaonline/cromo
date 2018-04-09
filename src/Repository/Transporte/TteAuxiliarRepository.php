<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteAuxiliar::class);
    }

}