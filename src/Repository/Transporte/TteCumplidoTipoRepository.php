<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCumplidoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteCumplidoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteCumplidoTipo::class);
    }

}