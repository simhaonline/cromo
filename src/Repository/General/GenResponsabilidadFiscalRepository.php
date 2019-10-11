<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenRegimen;
use App\Entity\General\GenResponsabilidadFiscal;
use App\Entity\General\GenTipoPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenResponsabilidadFiscalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenResponsabilidadFiscal::class);
    }

}