<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAseguradora;
use App\Entity\Transporte\TteUbicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteUbicacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteUbicacion::class);
    }

}