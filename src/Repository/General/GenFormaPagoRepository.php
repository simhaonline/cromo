<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFormaPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenFormaPagoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenFormaPago::class);
    }




}