<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuBanco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuBancoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuBanco::class);
    }
}