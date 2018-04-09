<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRutaRecogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class TteRutaRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRutaRecogida::class);
    }

}