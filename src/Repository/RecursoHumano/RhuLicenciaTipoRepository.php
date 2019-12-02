<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuLicenciaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuLicenciaTipoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuLicenciaTipo::class);
    }
}