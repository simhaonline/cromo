<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurGrupo;
use App\Entity\Turno\TurProgramacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurGrupoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurGrupo::class);
    }
    
    
}