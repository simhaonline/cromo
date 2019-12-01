<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAnticipoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAnticipoConcepto::class);
    }

}