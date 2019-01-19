<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarDescuentoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarDescuentoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarDescuentoConcepto::class);
    }

}