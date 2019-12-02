<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaDebitoConcepto;
use App\Entity\Cartera\CarNotaDebitoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarNotaDebitoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarNotaDebitoDetalle::class);
    }

}