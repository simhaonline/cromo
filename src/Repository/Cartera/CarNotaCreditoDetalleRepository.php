<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaCreditoConcepto;
use App\Entity\Cartera\CarNotaCreditoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarNotaCreditoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarNotaCreditoDetalle::class);
    }

}