<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuDesempeno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuDesempenoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuDesempeno::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoIncidente = null;
        $codigoEmpleado = null;
        $incidenteTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoIncidente = $filtros['codigoIncidente'] ?? null;
            $incidenteTipo = $filtros['incidenteTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuDesempeno::class, 'd')
            ->select('d.codigoDesempenoPk')
            ->addSelect('d.codigoEmpleadoFk')
            ->addSelect('d.codigoCargoFk')
            ->addSelect('d.estadoAnulado')
            ->addSelect('d.fecha')
            ->addSelect('d.estadoAprobado')
            ->addSelect('d.estadoAutorizado');
        if ($codigoIncidente) {
            $queryBuilder->andWhere("d.codigoDesempenoPk = '{$codigoIncidente}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("d.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($incidenteTipo) {
            $queryBuilder->andWhere("d.codigoIncidenteTipoFk = '{$incidenteTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("d.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("d.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("d.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("d.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("d.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('d.codigoDesempenoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }
}