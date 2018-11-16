<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEgresoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEgresoTipoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEgresoTipo::class);
    }

}