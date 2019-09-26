<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteRepository  extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidente::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAccidente = null;
        $codigoEmpleado = null;
        $incidenteTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoAccidente = $filtros['codigoAccidente'] ?? null;
            $incidenteTipo = $filtros['incidenteTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAccidente::class, 'a')
            ->select('a.codigoAccidentePk')
            ->addSelect('a.codigoEmpleadoFk')
            ->addSelect('a.codigoEmpleadoFk')
            ->addSelect('a.fechaAccidente')
            ->addSelect('a.codigoTipoAccidenteFk')
            ->addSelect('a.diagnostico')
            ->addSelect('a.estadoAnulado')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado');

        if ($codigoAccidente) {
            $queryBuilder->andWhere("a.codigoAccidentePk = '{$codigoAccidente}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("a.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($incidenteTipo) {
            $queryBuilder->andWhere("a.codigoIncidenteTipoFk = '{$incidenteTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("a.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("a.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('a.codigoAccidentePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

}