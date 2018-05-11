<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbComprobante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CtbComprobanteRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbComprobante::class);
    }

}