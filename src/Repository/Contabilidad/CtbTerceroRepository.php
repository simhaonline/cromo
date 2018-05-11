<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CtbTerceroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbTercero::class);
    }

}