<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedadTipo::class);
    }

}