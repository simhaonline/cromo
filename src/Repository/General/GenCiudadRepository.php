<?php

namespace App\Repository\General;

use App\Entity\General\GenCiudad;
use App\Entity\General\GenFormaPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenCiudadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenCiudad::class);
    }




}