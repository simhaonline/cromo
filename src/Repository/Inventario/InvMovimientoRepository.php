<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvMovimientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimiento::class);
    }
}