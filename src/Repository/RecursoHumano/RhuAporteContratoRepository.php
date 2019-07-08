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

    public function lista()
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
            ->leftJoin('ac.empleadoRel', 'e');
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
            ->leftJoin('ac.contratoRel', 'c')
            ->leftJoin('c.pensionRel', 'pen')
            ->leftJoin('c.clasificacionRiesgoRel', 'arl')
            ->where('ac.codigoAporteFk=' . $codigoAporte);
        $arAporteContratos = $queryBuilder->getQuery()->getResult();
        return $arAporteContratos;
    }

    public function cargar($arAporte) {
        $em = $this->getEntityManager();
        $arContratos = $em->getRepository(RhuContrato::class)->contratosPeriodoAporte($arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
        foreach ($arContratos as $arContrato) {
            $arContratoProceso = $em->getRepository(RhuContrato::class)->find($arContrato['codigoContratoPk']);
            $arAporteContrato = new RhuAporteContrato();
            $arAporteContrato->setAporteRel($arAporte);
            $arAporteContrato->setContratoRel($arContratoProceso);
            $arAporteContrato->setEmpleadoRel($arContratoProceso->getEmpleadoRel());
            $arAporteContrato->setSucursalRel($arAporte->getSucursalRel());
            $em->persist($arAporteContrato);
        }
        $em->flush();
    }


}