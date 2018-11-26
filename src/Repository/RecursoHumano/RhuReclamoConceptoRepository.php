<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuReclamoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuReclamoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuReclamoConcepto::class);
    }
}