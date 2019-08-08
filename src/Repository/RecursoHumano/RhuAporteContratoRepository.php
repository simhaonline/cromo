<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPagoDetalle;
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
            ->addSelect('ac.codigoContratoFk')
            ->addSelect('ac.vrSalario')
            ->addSelect('ac.dias')
            ->addSelect('ac.ibc')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->leftJoin('ac.empleadoRel', 'e')
        ->where('ac.codigoAporteFk = ' . $codigoAporte);
        return $queryBuilder;
    }

    public function listaGenerarSoporte($codigoAporte)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteContrato::class, 'ac')
            ->select('ac.codigoAporteContratoPk')
            ->addSelect('ac.codigoContratoFk')
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

    public function cargar($arAporte) {
        $em = $this->getEntityManager();
        if(!$arAporte->getEstadoAutorizado()) {
            $arContratos = $em->getRepository(RhuContrato::class)->contratosPeriodoAporte($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
            foreach ($arContratos as $arContrato) {
                if($this->validarContratoCargar($arAporte->getCodigoAportePk(), $arContrato['codigoContratoPk'])) {
                    $arContratoProceso = $em->getRepository(RhuContrato::class)->find($arContrato['codigoContratoPk']);
                    $arAporteContrato = new RhuAporteContrato();
                    $arAporteContrato->setAporteRel($arAporte);
                    $arAporteContrato->setContratoRel($arContratoProceso);
                    $arAporteContrato->setEmpleadoRel($arContratoProceso->getEmpleadoRel());
                    $arAporteContrato->setSucursalRel($arAporte->getSucursalRel());
                    $em->persist($arAporteContrato);
                }
            }
            $em->flush();
            $this->cantidadEmpleados($arAporte);
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