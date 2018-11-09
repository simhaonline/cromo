<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvImportacionCostoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionCosto::class);
    }
}
