<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbRegistro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CtbRegistroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbRegistro::class);
    }

}