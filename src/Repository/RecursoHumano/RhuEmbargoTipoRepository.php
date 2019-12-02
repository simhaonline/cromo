<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEmbargoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEmbargoTipo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuEmbargoTipo::class,'et')
            ->select('et.codigoEmbargoTipoPk AS ID')
            ->addSelect('et.nombre')
            ->addSelect('et.codigoConceptoFk')
            ->where('et.codigoEmbargoTipoPk IS NOT NULL');
    }
}