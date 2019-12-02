<?php

namespace App\Repository\Turno;


use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\Turno\TurCierre;
use App\Entity\Turno\TurCostoEmpleado;
use App\Entity\Turno\TurCostoEmpleadoServicio;
use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurCostoEmpleadoServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCostoEmpleadoServicio::class);
    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCostoEmpleadoServicio::class, 'ces')
            ->select('ces.codigoCostoEmpleadoServicioPk')
            ->addSelect('ces.anio')
            ->addSelect('ces.mes')
            ->addSelect('ces.codigoEmpleadoFk')
            ->addSelect('ces.codigoCentroCostoFk')
            ->addSelect('ces.vrProvision')
            ->addSelect('ces.vrAporte')
            ->addSelect('ces.vrNomina')
            ->addSelect('ces.vrCosto')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->leftJoin('ces.empleadoRel', 'e');

        if ($session->get('filtroTurCostoEmpleadoServicioAnio') != null) {
            $queryBuilder->andWhere("ces.anio = '{$session->get('filtroTurCostoEmpleadoServicioAnio')}'");
        }
        if ($session->get('filtroTurCostoEmpleadoServicioMes') != null) {
            $queryBuilder->andWhere("ces.mes = '{$session->get('filtroTurCostoEmpleadoServicioMes')}'");
        }
        if ($session->get('filtroTurCostoEmpleadoServicioCodigoEmpleado') != null) {
            $queryBuilder->andWhere("ces.codigoEmpleadoFk = '{$session->get('filtroTurCostoEmpleadoServicioCodigoEmpleado')}'");
        }
        return $queryBuilder->setMaxResults(5000)->getQuery();
    }

}
