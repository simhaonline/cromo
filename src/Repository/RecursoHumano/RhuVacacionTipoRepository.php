<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuVacacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacionTipo::class);
    }
    
}