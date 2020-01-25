<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurOperacion;
use App\Entity\Turno\TurOperacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurOperacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurOperacionTipo::class);
    }

}