<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuContratoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuContratoTipo::class);
    }
}