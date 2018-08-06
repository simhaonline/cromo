<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteLinea;
use App\Entity\Transporte\TteMarca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteLineaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteLinea::class);
    }

}