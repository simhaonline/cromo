<?php

namespace App\Repository\Turno;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurDistribucionEmpleado;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurDistribucionEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurDistribucionEmpleado::class);
    }

    public function generar($arCierre)
    {
        $em = $this->getEntityManager();
        $strDesde = $arCierre->getAnio() . "/" . $arCierre->getMes() . "/01";
        $strUltimoDia = date("d", (mktime(0, 0, 0, $arCierre->getMes() + 1, 1, $arCierre->getAnio()) - 1));
        $strHasta = $arCierre->getAnio() . "/" . $arCierre->getMes() . "/" . $strUltimoDia;
        $queryBuilder = $em->createQueryBuilder()->from(TurProgramacion::class, 'p')
            ->select('p.codigoEmpleadoFk')
            ->addSelect('e.codigoContratoFk')
            ->addSelect('e.codigoContratoUltimoFk')
            ->leftJoin('p.empleadoRel', 'e')
            ->where('p.anio = ' . $arCierre->getAnio())
            ->andWhere('p.mes = ' . $arCierre->getMes())
            ->andWhere("p.codigoEmpleadoFk <> ''")
            ->groupBy('p.codigoEmpleadoFk');
        $arEmpleados = $queryBuilder->getQuery()->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            $dql = "SELECT dd.codigoPedidoDetalleFk, "
                . "SUM(dd.horasDescanso) as horasDescanso, "
                . "SUM(dd.horasDiurnas) as horasDiurnas, "
                . "SUM(dd.horasNocturnas) as horasNocturnas, "
                . "SUM(dd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                . "SUM(dd.horasFestivasNocturnas) as horasFestivasNocturnas, "
                . "SUM(dd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(dd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(dd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(dd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                . "SUM(dd.horasRecargoNocturno) as horasRecargoNocturno, "
                . "SUM(dd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                . "SUM(dd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno, "
                . "SUM(dd.horasDescanso)*100 as pDS, "
                . "SUM(dd.horasDiurnas)*100 as pD, "
                . "SUM(dd.horasNocturnas)*135 as pN, "
                . "SUM(dd.horasFestivasDiurnas)*175 as pFD, "
                . "SUM(dd.horasFestivasNocturnas)*210 as pFN, "
                . "SUM(dd.horasExtrasOrdinariasDiurnas)*125 as pEOD, "
                . "SUM(dd.horasExtrasOrdinariasNocturnas)*175 as pEON, "
                . "SUM(dd.horasExtrasFestivasDiurnas)*200 as pEFD, "
                . "SUM(dd.horasExtrasFestivasNocturnas)*250 as pEFN, "
                . "SUM(dd.horasRecargoNocturno)*35 as pRN, "
                . "SUM(dd.horasRecargoFestivoDiurno)*75 as pRFD, "
                . "SUM(dd.horasRecargoFestivoNocturno)*110 as pRFN "
                . "FROM App\Entity\Turno\TurDistribucion dd "
                . "WHERE dd.anio =  " . $arCierre->getAnio() . " AND dd.mes =  " . $arCierre->getMes() . " AND dd.codigoEmpleadoFk = " . $arEmpleado['codigoEmpleadoFk'] . " "
                . "GROUP BY dd.codigoPedidoDetalleFk";
            $query = $em->createQuery($dql);
            $arrayResultados = $query->getResult();
            $numeroServicios = count($arrayResultados);
            $pesoTotal = 0;
            foreach ($arrayResultados as $detalle) {
                $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                $pesoTotal += $peso;
            }
            if ($pesoTotal == 0 && $numeroServicios >= 1) {
                $pesoTotal = 1;
            }
            $participacionMayor = 0;
            $diferencia = 0;
            $registro = 0;
            foreach ($arrayResultados as $detalle) {
                $registro++;
                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($detalle['codigoPedidoDetalleFk']);
                $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                $participacionEmpleado = 0;
                //Para el caso en que el empleado esta en novedad todo el periodo
                if ($peso == 0 && $numeroServicios >= 1 && $pesoTotal == 1) {
                    $peso = 1 / $numeroServicios;
                }
                if ($peso > 0) {
                    $participacionEmpleado = $peso / $pesoTotal;
                }

                $participacionAbsoluta = $participacionEmpleado * 100;
                $participacion = round($participacionEmpleado * 100);
                $diferencia += $participacionAbsoluta - $participacion;
                if($numeroServicios == $registro && $diferencia > 0) {
                    $participacion += $diferencia;
                }

                if ($participacionMayor < $participacion) {
                    $participacionMayor = $participacion;
                    if($arEmpleado['codigoContratoFk']) {
                        $codigoContrato = $arEmpleado['codigoContratoFk'];
                    } else {
                        $codigoContrato = $arEmpleado['codigoContratoUltimoFk'];
                    }
                    /** @var $arContrato RhuContrato */
                    $arContrato = $em->getRepository(RhuContrato::class)->find($codigoContrato);
                    if($arContrato->getCodigoCostoTipoFk() == 'OPE') {
                        $arContrato->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoRel());
                        $em->persist($arContrato);
                    }
                }

                if ($participacion > 0) {
                    $arDistribucionEmpleado = new TurDistribucionEmpleado();
                    $arDistribucionEmpleado->setCierreRel($arCierre);
                    $arDistribucionEmpleado->setAnio($arCierre->getAnio());
                    $arDistribucionEmpleado->setMes($arCierre->getMes());
                    $arDistribucionEmpleado->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arEmpleado['codigoEmpleadoFk']));
                    $arDistribucionEmpleado->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoRel());
                    $arDistribucionEmpleado->setParticipacion($participacion);
                }
                $em->persist($arDistribucionEmpleado);
            }
        }
        $em->flush();
    }

    public function lista($codigoCierre)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurDistribucionEmpleado::class, 'de')
            ->select('de.codigoDistribucionEmpleadoPk')
            ->addSelect('de.anio')
            ->addSelect('de.mes')
            ->addSelect('de.codigoEmpleadoFk')
            ->addSelect('de.codigoCentroCostoFk')
            ->addSelect('de.participacion')
            ->addSelect('cc.nombre as centroCostoNombre')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->leftJoin('de.empleadoRel', 'e')
            ->leftJoin('de.centroCostoRel', 'cc')
            ->where('de.codigoCierreFk = ' . $codigoCierre);
        return $queryBuilder->getQuery()->getResult();
    }

}
