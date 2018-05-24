<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvConfiguracion::class);
    }
}