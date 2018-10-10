<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuTiempo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuTiempoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuTiempo::class);
    }
}