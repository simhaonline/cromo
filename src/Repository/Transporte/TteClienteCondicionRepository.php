<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteClienteCondicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteClienteCondicionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteClienteCondicion::class);
    }
}