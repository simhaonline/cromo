<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuNovedadTipo::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuNovedadTipo::class,'nt')
            ->leftJoin('nt.conceptoRel','c')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre')
            ->addSelect('nt.subTipo')
            ->addSelect('c.nombre AS CONCEPTO')
            ->where('nt.codigoNovedadTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }

}