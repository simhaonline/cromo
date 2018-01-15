<?php

namespace App\Repository;

use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class GuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guia::class);
    }
}