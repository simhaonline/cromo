<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidenteCuerpoAfectado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteCuerpoAfectadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidenteCuerpoAfectado::class);
    }
}