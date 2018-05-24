<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuGrupoPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuGrupoPagoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuGrupoPago::class);
    }

}