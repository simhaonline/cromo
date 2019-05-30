<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuExamenTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuExamenTipo::class);
    }

}