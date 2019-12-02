<?php

namespace App\Repository\Turno;

use App\Entity\RecursoHumano\RhuEmpleado;
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
        $dql = "SELECT pd.codigoEmpleadoFk "
            . "FROM App\Entity\Turno\TurProgramacion pd "
            . "WHERE pd.anio =  " . $arCierre->getAnio() . " AND pd.mes =  " . $arCierre->getMes() . " AND pd.codigoEmpleadoFk <> ''"
            . "GROUP BY pd.codigoEmpleadoFk";
        $query = $em->createQuery($dql);
        $arEmpleados = $query->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            //$arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arRecursoPeriodo['codigoRecursoFk']);
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
            foreach ($arrayResultados as $detalle) {

                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($detalle['codigoPedidoDetalleFk']);

                $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                $participacionRecurso = 0;
                //Para el caso en que el empleado esta en novedad todo el periodo
                if ($peso == 0 && $numeroServicios >= 1 && $pesoTotal == 1) {
                    $peso = 1 / $numeroServicios;
                }
                if ($peso > 0) {
                    $participacionRecurso = $peso / $pesoTotal;
                }

                $participacion = 0;
                if ($detalle['pDS'] > 0) {
                    $participacion = $detalle['pDS'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pD'] > 0) {
                    $participacion = $detalle['pD'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pN'] > 0) {
                    $participacion = $detalle['pN'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pFD'] > 0) {
                    $participacion = $detalle['pFD'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pFN'] > 0) {
                    $participacion = $detalle['pFN'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pEOD'] > 0) {
                    $participacion = $detalle['pEOD'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pEON'] > 0) {
                    $participacion = $detalle['pEON'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pEFD'] > 0) {
                    $participacion = $detalle['pEFD'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pEFN'] > 0) {
                    $participacion = $detalle['pEFN'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pRN'] > 0) {
                    $participacion = $detalle['pRN'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pRFD'] > 0) {
                    $participacion = $detalle['pRFD'] / $peso;
                }

                $participacion = 0;
                if ($detalle['pRFN'] > 0) {
                    $participacion = $detalle['pRFN'] / $peso;
                }
                $participacionRecurso = $participacionRecurso * 100;
                /*if ($participacionMayor < $participacionRecurso) {
                    $participacionMayor = $participacionRecurso;
                    $arEmpleado->setCentroCostoContabilidadRel($arPedidoDetalle->getPuestoRel()->getCentroCostoContabilidadRel());
                    $arEmpleado->setSucursalRel($arPedidoDetalle->getPuestoRel()->getSucursalRel());
                    $arEmpleado->setAreaRel($arPedidoDetalle->getPuestoRel()->getAreaRel());
                    $arEmpleado->setProyectoRel($arPedidoDetalle->getPuestoRel()->getProyectoRel());
                    $arEmpleado->setCodigoClienteTurnoFk($arPedidoDetalle->getPedidoRel()->getCodigoClienteFk());
                    $arEmpleado->setCodigoZonaPuestoFk($arPedidoDetalle->getPuestoRel()->getCodigoZonaFk());
                }*/
                if ($participacionRecurso > 0) {
                    $arDistribucionEmpleado = new TurDistribucionEmpleado();
                    $arDistribucionEmpleado->setAnio($arCierre->getAnio());
                    $arDistribucionEmpleado->setMes($arCierre->getMes());
                    $arDistribucionEmpleado->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arEmpleado['codigoEmpleadoFk']));
                    $arDistribucionEmpleado->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoRel());
                    $arDistribucionEmpleado->setParticipacion($participacionRecurso);
                }
                $em->persist($arDistribucionEmpleado);
            }
        }
        $em->flush();
    }


}
