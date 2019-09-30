<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuVacacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacionTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoVacacionTipo = null;
        $nombreVacacionTipo = null;

        if ($filtros) {
            $codigoVacacionTipo = $filtros['codigoVacacionTipo'] ?? null;
            $nombreVacacionTipo = $filtros['nombreVacacionTipo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuVacacionTipo::class, 'vt')
            ->select('vt.codigoVacacionTipoPk')
            ->addSelect('vt.nombre')
            ->addselect('cd.nombre AS conceptoDinero')
            ->addselect('cdf.nombre AS conceptoDisfrutada')
            ->addSelect('vt.consecutivo')
            ->leftJoin('vt.conceptoDineroRel', 'cd')
            ->leftJoin('vt.conceptoDisfrutadaRel', 'cdf');
        if ($codigoVacacionTipo) {
            $queryBuilder->andWhere("vt.codigoVacacionTipoPk = '{$codigoVacacionTipo}'");
        }
        if ($nombreVacacionTipo) {
            $queryBuilder->andWhere("vt.nombre LIKE '%{$nombreVacacionTipo}%' ");
        }
        $queryBuilder->addOrderBy('vt.codigoVacacionTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

}