<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaCreditoConcepto;
use App\Entity\Cartera\CarNotaCreditoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarNotaCreditoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarNotaCreditoDetalle::class);
    }

}