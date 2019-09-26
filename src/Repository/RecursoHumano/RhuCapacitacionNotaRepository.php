<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuCapacitacionNota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCapacitacionNotaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCapacitacionNota::class);
    }
}