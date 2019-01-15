<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteRedespachoMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoMotivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRedespachoMotivo::class);
    }

}

