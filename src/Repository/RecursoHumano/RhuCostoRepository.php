<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCostoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCosto::class);
    }

    /**
     * @param $arCierre RhuCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generar($arCierre)
    {
        $em = $this->getEntityManager();
        $strDesde = $arCierre->getAnio() . "-" . $arCierre->getMes() . "-01";
        $strUltimoDia = date("d", (mktime(0, 0, 0, $arCierre->getMes() + 1, 1, $arCierre->getAnio()) - 1));
        $strHasta = $arCierre->getAnio() . "-" . $arCierre->getMes() . "-" . $strUltimoDia;
        $qb = $em->createQueryBuilder()->from(RhuPago::class, "p")
            ->select("p.codigoEmpleadoFk")
            ->addSelect("COUNT(p.codigoPagoPk) AS numeroPagos")
            ->addSelect("SUM(p.vrDevengado) as vrDevengado")
            ->addSelect("SUM(p.vrCesantia) as vrCesantia")
            ->addSelect("SUM(p.vrInteres) as vrInteres")
            ->addSelect("SUM(p.vrPrima) as vrPrima")
            ->addSelect("SUM(p.vrVacacion) as vrVacacion")
            ->where("p.codigoPagoTipoFk = 'NOM'")
            ->andWhere("p.fechaDesdeContrato >= '{$strDesde}'")
            ->andWhere("p.fechaDesdeContrato <= '{$strHasta}'")
            ->andWhere("p.estadoAnulado = 0")
            ->groupBy("p.codigoEmpleadoFk")
            ->orderBy("p.codigoEmpleadoFk");
        $arrPagos = $qb->getQuery()->getResult();
        foreach ($arrPagos as $arrPago) {
            $devengado = $arrPago['vrDevengado'];
            $arCosto = new RhuCosto();
            $arCosto->setCierreRel($arCierre);
            $arCosto->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arrPago['codigoEmpleadoFk']));
            $arCosto->setVrNomina($devengado);
            $arCosto->setAnio($arCierre->getAnio());
            $arCosto->setMes($arCierre->getMes());
            $aporte = 0;
            $qb = $em->createQueryBuilder()->from(RhuAporteDetalle::class, "ad")
                ->select("SUM(ad.totalCotizacion) as totalCotizacion")
                ->where("ad.codigoEmpleadoFk = " . $arrPago['codigoEmpleadoFk'])
                ->andWhere("ad.anio = {$arCierre->getAnio()}")
                ->andWhere("ad.mes = {$arCierre->getMes()}");
            $arAporteDetalle = $qb->getQuery()->getSingleResult();
            if($arAporteDetalle['totalCotizacion']) {
                $aporte = $arAporteDetalle['totalCotizacion'];
            }
            $prestaciones = $arrPago['vrCesantia'] + $arrPago['vrInteres'] + $arrPago['vrPrima'] + $arrPago['vrVacacion'];
            $arCosto->setVrAporte($aporte);
            $arCosto->setVrProvision($prestaciones);
            $total = $devengado + $aporte + $prestaciones;
            $arCosto->setVrTotal($total);

            $em->persist($arCosto);
        }
        $em->flush();
    }
}