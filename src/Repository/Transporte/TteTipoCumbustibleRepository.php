<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteTipoCombustible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteTipoCumbustibleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteTipoCombustible::class);
    }



}