<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuReclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuReclamoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuReclamo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoReclamo = null;
        $reclamoConcepto = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoReclamo = $filtros['codigoReclamo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $reclamoConcepto = $filtros['reclamoConcepto'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuReclamo::class, 'r')
            ->select('r.codigoReclamoPk')
            ->addSelect('rc.nombre AS concepto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaCierre')
            ->addSelect('r.responsable')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->leftJoin('r.empleadoRel', 'e')
        ->leftJoin('r.reclamoConceptoRel', 'rc');
        if ($codigoReclamo) {
            $queryBuilder->andWhere("r.codigoReclamoPk = '{$codigoReclamo}'");
        }
        if ($reclamoConcepto) {
            $queryBuilder->andWhere("r.codigoReclamoConceptoFk = '{$reclamoConcepto}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("r.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("r.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("r.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('r.codigoReclamoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }
}