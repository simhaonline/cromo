<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinCentroCostoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinCentroCosto::class);
    }

}