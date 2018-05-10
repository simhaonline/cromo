<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteTipoCarroceria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteTipoCarroceriaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteTipoCarroceria::class);
    }



}