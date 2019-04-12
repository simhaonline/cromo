<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuContrato::class);
    }

    public function lista(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.numero')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('ct.nombre AS tipo')
            ->addSelect('gp.nombre AS nombreGrupo')
            ->addSelect('t.nombre AS tiempo')
            ->addSelect('c.vrSalario')
            ->addSelect('cr.nombre')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.estadoTerminado')
            ->leftJoin('c.contratoTipoRel', 'ct')
            ->leftJoin('c.clasificacionRiesgoRel', 'cr')
            ->leftJoin('c.tiempoRel', 't')
            ->leftJoin('c.grupoRel', 'gp')
            ->leftJoin('c.cargoRel', 'cg')
            ->leftJoin('c.empleadoRel', 'e')
            ->andWhere('c.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function contratosEmpleado($codigoEmpleado)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.numero')
            ->addSelect('c.codigoGrupoFk')
            ->addSelect('c.codigoCostoTipoFk')
            ->addSelect('c.codigoClasificacionRiesgoFk')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.vrSalario')
            ->addSelect('c.auxilioTransporte')
            ->addSelect('cr.nombre')
            ->addSelect('cg.nombre as nombreCargo')
            ->addSelect('gp.nombre as nombreGrupo')
            ->addSelect('c.estadoTerminado')
            ->leftJoin('c.clasificacionRiesgoRel', 'cr')
            ->leftJoin('c.grupoRel', 'gp')
            ->leftJoin('c.cargoRel', 'cg')
            ->where('c.codigoEmpleadoFk = ' . $codigoEmpleado)
            ->andWhere('c.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function generarPago($codigoContrato)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.vrSalario')
            ->where('c.codigoContratoPk = ' . $codigoContrato);
        return $queryBuilder->getQuery()->execute();
    }

    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 're')
            ->select('re.codigoContratoPk')
            ->addSelect('re.fechaDesde')
            ->addSelect('re.numero')
            ->addSelect('re.codigoGrupoFk')
            ->addSelect('re.codigoCargoFk')
            ->addSelect('re.codigoCostoTipoFk')
            ->addSelect('re.codigoClasificacionRiesgoFk')
            ->addSelect('re.fechaHasta')
            ->addSelect('re.vrSalario')
            ->addSelect('cr.nombre')
            ->addSelect('cg.nombre as nombreCargo')
            ->addSelect('gp.nombre as nombreGrupo')
            ->addSelect('re.estadoTerminado')
            ->leftJoin('re.clasificacionRiesgoRel', 'cr')
            ->leftJoin('re.grupoRel', 'gp')
            ->leftJoin('re.cargoRel', 'cg')
            ->where('re.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }
}