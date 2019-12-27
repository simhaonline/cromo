<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPagoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuPagoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuPagoTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoConcepto = null;
        $nombreConcepto = null;

        if ($filtros) {
            $codigoConcepto = $filtros['codigoPagoTipo'] ?? null;
            $nombreConcepto = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPagoTipo::class, 'pt')
            ->select('pt.codigoPagoTipoPk')
            ->addSelect('pt.nombre')
            ->addSelect('cpt.nombre AS cuentaPagar')
            ->addSelect('pt.codigoComprobanteFk')
            ->addSelect('pt.generaTesoreria')
            ->addSelect('pt.codigoCuentaFk')
            ->leftJoin('pt.cuentaPagarTipoRel' , 'cpt');
        if ($codigoConcepto) {
            $queryBuilder->andWhere("c.codigoConceptoPk = '{$codigoConcepto}'");
        }
        if ($nombreConcepto) {
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombreConcepto}%' ");
        }
        $queryBuilder->addOrderBy('pt.codigoPagoTipoPk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuPagoTipo::class,'pt')
            ->select('pt.codigoPagoTipoPk AS ID')
            ->addSelect('pt.nombre')
            ->where('pt.codigoPagoTipoPk IS NOT NULL');
    }
}