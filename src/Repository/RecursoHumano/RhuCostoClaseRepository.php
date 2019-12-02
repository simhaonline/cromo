<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCostoClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCostoClaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCostoClase::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuCostoClase::class,'rcc')
            ->select('rcc.codigoCostoClasePk as ID')
            ->addSelect('rcc.nombre')
            ->addSelect('rcc.operativo')
            ->where('rcc.codigoCostoClasePk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}