<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenRegimen;
use App\Entity\General\GenTipoPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenRegimenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenRegimen::class);
    }

}