<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvDocumento::class);
    }
}