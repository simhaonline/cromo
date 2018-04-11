<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteGuiaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaTipo::class);
    }

}