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
        $em = $this->getEntityManager();
        $arContratos = $em->createQueryBuilder()->from(RhuContrato::class,'c')
            ->select("c")
            ->where("c.codigoGrupoFk = {$arProgramacion->getCodigoGrupoFk()}")
            ->andWhere("c.fechaUltimoPago < '{$arProgramacion->getFechaHastaPeriodo()->format('Y-m-d')}'")
            ->andWhere("c.fechaDesde <= '{$arProgramacion->getFechaHastaPeriodo()->format('Y-m-d')}'")
            ->andWhere("(c.fechaHasta >= '{$arProgramacion->getFechaDesde()->format('Y-m-d')}')")
            ->orWhere("c.indefinido = 1")->getQuery()->execute();
        /** @var $arContrato RhuContrato */
        foreach ($arContratos as $arContrato) {
            if (!$em->getRepository(RhuProgramacionDetalle::class)->findBy(['fechaDesde' => $arProgramacion->getFechaDesde(), 'fechaHasta' => $arProgramacion->getFechaHasta(), 'codigoContratoFk' => $arContrato->getCodigoContratoPk()])) {
                $arProgramacionDetalle = new RhuProgramacionDetalle();
                $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                $arProgramacionDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionDetalle->setContratoRel($arContrato);
                $arProgramacionDetalle->setVrSalario($arContrato->getVrSalario());
                $arProgramacionDetalle->setIndefinido($arContrato->getIndefinido());
                $arProgramacionDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
                $arProgramacionDetalle->setFechaDesde($arProgramacion->getFechaDesde());
                $arProgramacionDetalle->setFechaHasta($arProgramacion->getFechaHasta());
                $arProgramacionDetalle->setSalarioBasico(1);
                if ($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'APR' || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 'PRA') {
                    $arProgramacionDetalle->setDescuentoPension(0);
                    $arProgramacionDetalle->setDescuentoSalud(0);
                    $arProgramacionDetalle->setPagoAuxilioTransporte(0);
                }
                $em->persist($arProgramacionDetalle);
                if ($arContrato->getCodigoPensionFk() == 'PEN') {
                    $arProgramacionDetalle->setDescuentoPension(0);
                }
                $dateFechaDesde = "";
                $dateFechaHasta = "";
                $intDiasDevolver = 0;
                $fechaFinalizaContrato = $arContrato->getFechaHasta();
                if ($arContrato->getIndefinido() == 1) {
                    $fecha = date_create(date('Y-m-d'));
                    date_modify($fecha, '+100000 day');
                    $fechaFinalizaContrato = $fecha;
                }
                if ($arContrato->getFechaDesde() < $arProgramacion->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacion->getFechaDesde();
                } else {
                    if ($arContrato->getFechaDesde() > $arProgramacion->getFechaHasta() == true) {
                        if ($arContrato->getFechaDesde() == $arProgramacion->getFechaHastaPeriodo()) {
                            $dateFechaDesde = $arProgramacion->getFechaHastaPeriodo();
                            $intDiasDevolver = 1;
                        } else {
                            $intDiasDevolver = 0;
                        }
                    } else {
                        $dateFechaDesde = $arContrato->getFechaDesde();
                    }
                }

                if ($fechaFinalizaContrato > $arProgramacion->getFechaHasta() == true) {
                    $dateFechaHasta = $arProgramacion->getFechaHasta();
                } else {
                    if ($fechaFinalizaContrato < $arProgramacion->getFechaDesde() == true) {
                        $intDiasDevolver = 0;
                    } else {
                        $dateFechaHasta = $fechaFinalizaContrato;
                    }
                }

                if ($dateFechaDesde != "" && $dateFechaHasta != "") {
                    $intDias = $dateFechaDesde->diff($dateFechaHasta);
                    $intDias = $intDias->format('%a');
                    $intDiasDevolver = $intDias + 1;
                    $intDiasPeriodoReales = $intDias + 1;
                    //Mes de febrero para periodos NO continuos
                    $intDiasInhabilesFebrero = 0;
                    if ($arProgramacion->getGrupoRel()->getPeriodoPagoRel()->getContinuo() == 0) {
                        if ($dateFechaHasta->format('md') == '0228' || $dateFechaHasta->format('md') == '0229') {
                            //Verificar si el año es bisiesto

                            if (date('L', mktime(1, 1, 1, 1, 1, $dateFechaHasta->format('Y'))) == 1) {
                                $intDiasInhabilesFebrero = 1;
                            } else {
                                $intDiasInhabilesFebrero = 2;
                            }
                        }

                        if ($dateFechaDesde->format('d') == "31") {
                            $intDiasDevolver = 1;
                        } else {
                            $intDiasDevolver += $intDiasInhabilesFebrero;
                        }
                    } else {
                        $intDiasDevolver += $intDiasInhabilesFebrero;
                    }
                }

            }
        }
        $em->flush();
        $em->getRepository(RhuProgramacion::class)->setCantidadRegistros($arProgramacion);
    }
}