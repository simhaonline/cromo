<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuCapacitacionMetodologia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCapacitacionMetodologiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCapacitacionMetodologia::class);
    }
}