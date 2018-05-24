<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvOrdenCompraTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvOrdenCompraTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompraTipo::class);
    }

}