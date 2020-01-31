<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuCapacitacionTema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCapacitacionTemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCapacitacionTema::class);
    }
}
