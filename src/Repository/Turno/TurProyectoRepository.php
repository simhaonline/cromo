<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurArea;
use App\Entity\Turno\TurProyecto;
use App\Entity\Turno\TurZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurProyectoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurProyecto::class);
    }


}