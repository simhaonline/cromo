<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvDocumentoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvDocumentoTipo::class);
    }
}