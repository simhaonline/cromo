<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinComprobante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinComprobanteRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinComprobante::class);
    }

}