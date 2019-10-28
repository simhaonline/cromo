<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporteContrato::class);
    }

    public function lista($codigoAporte)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->select('ac.codigoAporteContratoPk')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('ac.codigoContratoFk')
            ->addSelect('ac.vrSalario')
            ->addSelect('ac.dias')
            ->addSelect('ac.ibc')
            ->addSelect('ac.vrPensionCotizacion')
            ->addSelect('ac.vrSaludCotizacion')
            ->addSelect('ac.vrCaja')
            ->addSelect('ac.vrRiesgos')
            ->addSelect('ac.vrPensionEmpleado')
            ->addSelect('ac.vrSaludEmpleado')
            ->addSelect('ac.vrPension')
            ->addSelect('ac.vrSalud')
            ->leftJoin('ac.empleadoRel', 'e')
        ->where('ac.codigoAporteFk = ' . $codigoAporte);
        return $queryBuilder;
    }

    public function listaGenerarSoporte($codigoAporte)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->select('ac.codigoAporteContratoPk')
            ->addSelect('ac.codigoContratoFk')
            ->addSelect('ac.codigoEmpleadoFk')
            ->addSelect('c.indefinido')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.salarioIntegral')
            ->addSelect('c.vrSalario')
            ->addSelect('pen.porcentajeEmpleador as pensionPorcentajeEmpleador')
            ->addSelect('arl.porcentaje as clasificacionRiesgoPorcentaje')
            ->addSelect('es.codigoInterface as entidadSaludCodigo')
            ->addSelect('ep.codigoInterface as entidadPensionCodigo')
            ->addSelect('ec.codigoInterface as entidadCajaCodigo')
            ->leftJoin('ac.contratoRel', 'c')
            ->leftJoin('c.pensionRel', 'pen')
            ->leftJoin('c.clasificacionRiesgoRel', 'arl')
            ->leftJoin('c.entidadSaludRel', 'es')
            ->leftJoin('c.entidadPensionRel', 'ep')
            ->leftJoin('c.entidadCajaRel', 'ec')
            ->where('ac.codigoAporteFk=' . $codigoAporte);
        $arAporteContratos = $queryBuilder->getQuery()->getResult();
        return $arAporteContratos;
    }

    public function listaGenerarDetalle($codigoAporte)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->select('ac.codigoAporteContratoPk')
            ->addSelect('ac.codigoContratoFk')
            ->addSelect('ac.codigoEmpleadoFk')
            ->where('ac.codigoAporteFk=' . $codigoAporte);
        $arAporteContratos = $queryBuilder->getQuery()->getResult();
        return $arAporteContratos;
    }

    /**
     * @param $arAporte RhuAporte
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function cargar($arAporte) {
        $em = $this->getEntityManager();
        if(!$arAporte->getEstadoAutorizado()) {
            $arrConfiguracionNomina = $em->getRepository(RhuConfiguracion::class)->generarAporte();
            $arEntidadRiesgos = $em->getRepository(RhuEntidad::class)->find($arrConfiguracionNomina['codigoEntidadRiesgosProfesionalesFk']);
            if($arEntidadRiesgos) {
                $arContratos = $em->getRepository(RhuContrato::class)->contratosPeriodoAporte($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'), $arAporte->getCodigoSucursalFk());
                foreach ($arContratos as $arContrato) {
                    if($this->validarContratoCargar($arAporte->getCodigoAportePk(), $arContrato['codigoContratoPk'])) {
                        $arContratoProceso = $em->getRepository(RhuContrato::class)->find($arContrato['codigoContratoPk']);
                        $arAporteContrato = new RhuAporteContrato();
                        $arAporteContrato->setAporteRel($arAporte);
                        $arAporteContrato->setContratoRel($arContratoProceso);
                        $arAporteContrato->setEmpleadoRel($arContratoProceso->getEmpleadoRel());
                        $arAporteContrato->setSucursalRel($arAporte->getSucursalRel());
                        $arAporteContrato->setEntidadPensionRel($arContratoProceso->getEntidadPensionRel());
                        $arAporteContrato->setEntidadSaludRel($arContratoProceso->getEntidadSaludRel());
                        $arAporteContrato->setEntidadCajaRel($arContratoProceso->getEntidadCajaRel());
                        $arAporteContrato->setEntidadRiesgosRel($arEntidadRiesgos);
                        $em->persist($arAporteContrato);
                    }
                }
                $em->flush();
                $this->cantidadEmpleados($arAporte);
            } else {
                Mensajes::error('No se puede cargar los contratos porque no esta definida una entidad de riesgos profesionales');
            }
        }
    }

    private function validarContratoCargar($codigoAporte, $codigoContrato) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->addSelect('ac.codigoAporteContratoPk')
            ->where("ac.codigoAporteFk = {$codigoAporte}")
            ->andWhere("ac.codigoContratoFk = {$codigoContrato}");
        $arResultado = $queryBuilder->getQuery()->getResult();
        $retornar = $arResultado ? false : true;
        return $retornar;
    }

    private function cantidadEmpleados($arAporte) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->select("count(ac.codigoEmpleadoFk)")
            ->where("ac.codigoAporteFk = {$arAporte->getCodigoAportePk()}")
            ->groupBy('ac.codigoEmpleadoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();
        if($arrResultado) {
            $cantidadEmpleados = count($arrResultado);
            $arAporte->setCantidadEmpleados($cantidadEmpleados);
        }
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->addSelect('COUNT(ac.codigoContratoFk) as numeroContratos')
            ->where("ac.codigoAporteFk = {$arAporte->getCodigoAportePk()}")
            ->groupBy('ac.codigoContratoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();
        if($arrResultado) {
            $cantidadContratos = count($arrResultado);
            $arAporte->setCantidadContratos($cantidadContratos);
        }
        $em->persist($arAporte);
        $em->flush();
    }

}