<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenIdentificacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenIdentificacionTipo::class);
    }
}