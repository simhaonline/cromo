<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidenteAgente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteAgenteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidenteAgente::class);
    }
}