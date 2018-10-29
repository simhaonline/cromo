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

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RHuEmpleado', 'e')
            ->select('e.codigoEmpleadoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function contratosEmpleado($codigoEmpleado)
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
            ->where('re.codigoEmpleadoFk = ' . $codigoEmpleado)
            ->andWhere('re.codigoContratoPk <> 0');
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

    /**
     * @param $arProgramacion RhuProgramacion
     * @throws \Doctrine\ORM\ORMException
     */
    public function cargarContratos($arProgramacion)
    {
        $arContratos = $this->_em->getRepository(RhuContrato::class)->findBy(['codigoGrupoFk' => $arProgramacion->getCodigoGrupoFk(), 'estadoTerminado' => false]);
        foreach ($arContratos as $arContrato) {
            if (!$this->_em->getRepository(RhuProgramacionDetalle::class)->findBy(['fechaDesde' => $arProgramacion->getFechaDesde(), 'fechaHasta' => $arProgramacion->getFechaHasta(), 'codigoContratoFk' => $arContrato->getCodigoContratoPk()])) {
                $arProgramacionDetalle = new RhuProgramacionDetalle();
                $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                $arProgramacionDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionDetalle->setContratoRel($arContrato);
                $arProgramacionDetalle->setFechaDesde($arProgramacion->getFechaDesde());
                $arProgramacionDetalle->setFechaHasta($arProgramacion->getFechaHasta());
                $this->_em->persist($arProgramacionDetalle);
            }
        }
        $this->_em->flush();
    }
}