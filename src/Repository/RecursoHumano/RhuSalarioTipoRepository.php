<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSalarioTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSalarioTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSalarioTipo::class);
    }
}