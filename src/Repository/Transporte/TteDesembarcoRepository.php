<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDesembarco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDesembarcoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDesembarco::class);
    }

}

