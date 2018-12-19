<?php

namespace App\Repository\General;

use App\Entity\General\GenTareaPrioridad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenTareaPrioridadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenTareaPrioridad::class);
    }
}
