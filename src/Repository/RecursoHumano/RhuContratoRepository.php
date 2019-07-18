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
        $session = new Session();
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
            ->andWhere('c.codigoContratoPk <> 0')
        ->orderBy('c.codigoContratoPk', 'ASC');
        if ($session->get('filtroRhuNombreEmpleado') != '') {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuNombreEmpleado')}%' ");
        }
        if ($session->get('filtroRhuNumeroIdentificacionEmpleado') != '') {
            $queryBuilder->andWhere("e.numeroIdentificacion = {$session->get('filtroRhuNumeroIdentificacionEmpleado')} ");
        }
        if ($session->get('filtroRhuCodigoContrato') != '') {
            $queryBuilder->andWhere("c.codigoContratoPk = {$session->get('filtroRhuCodigoContrato')} ");
        }
        if ($session->get('filtroRhuGrupo')) {
            $queryBuilder->andWhere("c.codigoGrupoFk = '" . $session->get('filtroRhuGrupo') . "'");
        }
        switch ($session->get('filtroRhuContratoEstadoTerminado')) {
            case '0':
                $queryBuilder->andWhere("c.estadoTerminado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoTerminado = 1");
                break;
        }
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


    public function contratosPeriodoAporte($fechaDesde = "", $fechaHasta = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->leftJoin('c.empleadoRel', 'e')
            ->where("(c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' ");
        $arContratos = $queryBuilder->getQuery()->getResult();
        return $arContratos;
    }

    /* Ojo esta depronto se puede borrar */
    public function contratosPeriodoTemporal($fechaDesde = "", $fechaHasta = "")
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.codigoTipoCotizanteFk')
            ->addSelect('c.codigoSubtipoCotizanteFk')
            ->addSelect('c.indefinido')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.vrSalario')
            ->addSelect('c.salarioIntegral')
            ->addSelect('e.codigoIdentificacionFk as empleadoCodigoIdentificacionFk')
            ->addSelect('e.nombre1 as empleadoNombre1')
            ->addSelect('e.nombre2 as empleadoNombre2')
            ->addSelect('e.apellido1 as empleadoApellido1')
            ->addSelect('e.apellido1 as empleadoApellido2')
            ->addSelect('cl.codigoDane as ciudadLaboraCodigoDane')
            ->addSelect('cld.codigoDane as ciudadLaboraDepartamentoCodigoDane')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('c.ciudadLaboraRel', 'cl')
            ->leftJoin('cl.departamentoRel', 'cld')
            ->where("(c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' ");
        $arContratos = $queryBuilder->getQuery()->getResult();
        return $arContratos;
    }

    public function informeContrato()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c');
             return $queryBuilder;
    }
}