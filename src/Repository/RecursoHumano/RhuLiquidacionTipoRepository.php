<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuLiquidacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLiquidacionTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoLiquidacionTipo = null;
        $nombreLiquidacionTipo = null;

        if ($filtros) {
            $codigoLiquidacionTipo = $filtros['codigoLiquidacionTipo'] ?? null;
            $nombreLiquidacionTipo = $filtros['nombreLiquidacionTipo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLiquidacionTipo::class, 'vt')
            ->select('vt.codigoLiquidacionTipoPk')
            ->addSelect('vt.nombre')
            ->addselect('cd.nombre AS conceptoDinero')
            ->addselect('cdf.nombre AS conceptoDisfrutada')
            ->addSelect('vt.consecutivo')
            ->leftJoin('vt.conceptoDineroRel', 'cd')
            ->leftJoin('vt.conceptoDisfrutadaRel', 'cdf');
        if ($codigoLiquidacionTipo) {
            $queryBuilder->andWhere("vt.codigoLiquidacionTipoPk = '{$codigoLiquidacionTipo}'");
        }
        if ($nombreLiquidacionTipo) {
            $queryBuilder->andWhere("vt.nombre LIKE '%{$nombreLiquidacionTipo}%' ");
        }
        $queryBuilder->addOrderBy('vt.codigoLiquidacionTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

}