<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarMovimientoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarMovimientoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarMovimientoTipo::class);
    }


}