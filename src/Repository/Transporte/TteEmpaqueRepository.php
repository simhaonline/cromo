<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteEmpaque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteEmpaqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteEmpaque::class);
    }



}