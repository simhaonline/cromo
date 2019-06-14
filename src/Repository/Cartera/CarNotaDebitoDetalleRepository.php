<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaDebitoConcepto;
use App\Entity\Cartera\CarNotaDebitoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarNotaDebitoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarNotaDebitoDetalle::class);
    }

}