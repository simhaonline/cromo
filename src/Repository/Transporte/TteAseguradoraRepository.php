<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAseguradora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteAseguradoraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteAseguradora::class);
    }



}