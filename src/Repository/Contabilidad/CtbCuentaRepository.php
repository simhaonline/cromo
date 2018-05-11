<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CtbCuentaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbCuenta::class);
    }

}