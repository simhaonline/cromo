<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuSeleccionEntrevistaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionEntrevistaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccionEntrevistaTipo::class);
    }
}