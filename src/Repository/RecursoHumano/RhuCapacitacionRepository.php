<?php


namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCapacitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCapacitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCapacitacion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCapacitacion = null;
        $codigoEmpleado = null;
        $incidenteTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCapacitacion = $filtros['codigoCapacitacionPk'] ?? null;
            $incidenteTipo = $filtros['incidenteTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCapacitacion::class, 'c')
            ->select('c.codigoCapacitacionPk')
            ->addSelect('c.fechaCapacitacion')
            ->addSelect('c.codigoCapacitacionTipoFk')
            ->addSelect('c.tema')
            ->addSelect('c.codigoCapacitacionMetodologiaFk')
            ->addSelect('c.codigoZonaFk')
            ->addSelect('c.lugar')
            ->addSelect('c.codigoPuestoFk')
            ->addSelect('c.numeroPersonasCapacitar')
            ->addSelect('c.numeroPersonasAsistieron')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado');

        if ($codigoCapacitacion) {
            $queryBuilder->andWhere("c.codigoCapacitacionPk = '{$codigoCapacitacion}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("c.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($incidenteTipo) {
            $queryBuilder->andWhere("c.codigoIncidenteTipoFk = '{$incidenteTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("c.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("c.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('c.codigoCapacitacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}