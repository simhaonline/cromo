<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteRedespachoMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoMotivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteRedespachoMotivo::class);
    }

}

