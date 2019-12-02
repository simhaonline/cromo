<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCreditoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCreditoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCreditoTipo::class);
    }

    /**
     * @return mixed
     */
    public function parametrosExcel(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuCreditoTipo::class,'ct')
            ->select('ct.codigoCreditoTipoPk AS ID')
            ->addSelect('ct.nombre')
            ->addSelect('ct.cupoMaximo')
            ->leftJoin('ct.conceptoRel','c')
            ->addSelect('c.nombre AS CONCEPTO')
            ->where('ct.codigoCreditoTipoPk IS NOT NULL')->getQuery()->execute();
    }

}