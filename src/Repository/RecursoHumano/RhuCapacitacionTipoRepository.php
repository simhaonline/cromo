<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuCapacitacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCapacitacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCapacitacionTipo::class);
    }
}
