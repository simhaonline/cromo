<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarAnticipoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarAnticipoConcepto::class);
    }

}