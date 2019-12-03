<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarMovimientoClase;
use App\Entity\Cartera\CarMovimientoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarMovimientoClaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarMovimientoClase::class);
    }


}