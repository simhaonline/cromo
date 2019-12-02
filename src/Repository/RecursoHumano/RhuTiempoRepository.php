<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuTiempo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuTiempoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuTiempo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('t.codigoTiempoPk AS ID')
            ->addSelect('t.nombre')
            ->addSelect('t.abreviatura')
            ->addSelect('t.factor')
            ->addSelect('t.factorHorasDia')
            ->addSelect('t.orden')
            ->from(RhuTiempo::class,'t')
            ->where('t.codigoTiempoPk IS NOT NULL')->getQuery()->execute();
    }
}