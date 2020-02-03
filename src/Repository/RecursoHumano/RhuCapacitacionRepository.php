<?php


namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCapacitacion;
use App\Entity\RecursoHumano\RhuCapacitacionDetalle;
use App\Utilidades\Mensajes;
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
            ->addSelect('cte.nombre AS tema')
            ->addSelect('cm.nombre AS metodologia')
            ->addSelect('z.nombre AS zona')
            ->addSelect('c.lugar')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('c.numeroPersonasCapacitar')
            ->addSelect('c.numeroPersonasAsistieron')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->leftJoin('c.capacitacionTemaRel', 'cte')
            ->leftJoin('c.capacitacionTipoRel', 'ct')
            ->leftJoin('c.capacitacionMetadologiaRel', 'cm')
            ->leftJoin('c.zonaRel', 'z')
            ->leftJoin('c.puestoRel', 'p');
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

    public function contarDetalles($codigoCapacitacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCapacitacionDetalle::class, 'cd')
            ->select("COUNT(cd.codigoCapacitacionDetallePk)")
            ->where("cd.codigoCapacitacionFk = {$codigoCapacitacion} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $arCapacitacion RhuCapacitacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCapacitacion)
    {
        $em = $this->getEntityManager();
        if ($arCapacitacion->getEstadoAutorizado() == 0) {

            $arCapacitacion->setEstadoAutorizado(1);
            $em->persist($arCapacitacion);
            $em->flush();

        } else {
            Mensajes::error('El registro de capacitacion ya se encuentra autorizado');
        }
    }

    /**
     * @param $arCapacitacion RhuCapacitacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCapacitacion)
    {
        $em = $this->getEntityManager();
        if ($arCapacitacion->getEstadoAutorizado() == 1) {

            $arCapacitacion->setEstadoAutorizado(0);
            $em->persist($arCapacitacion);
            $em->flush();

        } else {
            Mensajes::error('El registro de la capacitacion ya se encuentra autorizada o aprobada');
        }
    }

    /**
     * @param $arCapaciacion RhuCapacitacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCapaciacion)
    {
        $arCapaciacion->setEstadoAprobado(1);
        $this->getEntityManager()->persist($arCapaciacion);
        $this->getEntityManager()->flush();
    }

}