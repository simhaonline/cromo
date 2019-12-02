<?php

namespace App\Repository\Turno;


use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\Turno\TurCierre;
use App\Entity\Turno\TurCostoEmpleado;
use App\Entity\Turno\TurCostoEmpleadoServicio;
use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurCostoEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCostoEmpleado::class);
    }

    /**
     * @param $arCierre TurCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generar($arCierre)
    {
        $em = $this->getEntityManager();
        $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arCierre->getMes() + 1, 1, $arCierre->getAnio()) - 1));
        $strFechaDesde = $arCierre->getAnio() . "/" . $arCierre->getMes() . "/01";
        $strFechaHasta = $arCierre->getAnio() . "/" . $arCierre->getMes() . "/" . $strUltimoDiaMes;
        //Costos del mes
        $arCostos = $em->getRepository(RhuCosto::class)->findBy(array('anio' => $arCierre->getAnio(), 'mes' => $arCierre->getMes()));
        if ($arCostos) {
            foreach ($arCostos as $arCostoRecursoHumano) {
                $costoRecurso = $arCostoRecursoHumano->getVrTotal();
                $nomina = $arCostoRecursoHumano->getVrNomina();
                $aporte = $arCostoRecursoHumano->getVrAporte();
                $provision = $arCostoRecursoHumano->getVrProvision();
                $arCostoEmpleado = new TurCostoEmpleado();
                $arCostoEmpleado->setCierreRel($arCierre);
                $arCostoEmpleado->setEmpleadoRel($arCostoRecursoHumano->getEmpleadoRel());
                $arCostoEmpleado->setAnio($arCierre->getAnio());
                $arCostoEmpleado->setMes($arCierre->getMes());
                $arCostoEmpleado->setVrNomina($nomina);
                $arCostoEmpleado->setVrProvision($provision);
                $arCostoEmpleado->setVrAporte($aporte);
                $arCostoEmpleado->setVrTotal($costoRecurso);

                $arCentroCostoParticipacion = NULL;
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
                        . "WHERE dd.anio =  " . $arCierre->getAnio() . " AND dd.mes =  " . $arCierre->getMes() . " AND dd.codigoEmpleadoFk = " . $arCostoRecursoHumano->getCodigoEmpleadoFk() . " "
                        . "GROUP BY dd.codigoPedidoDetalleFk";

                    $query = $em->createQuery($dql);
                    $arrayResultados = $query->getResult();
                    $pesoTotal = 0;
                    foreach ($arrayResultados as $detalle) {
                        $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                        $pesoTotal += $peso;
                    }
                    $participacionMayor = 0;
                    foreach ($arrayResultados as $detalle) {

                        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($detalle['codigoPedidoDetalleFk']);
                        $arCostoEmpleadoServicio = new TurCostoEmpleadoServicio();
                        $arCostoEmpleadoServicio->setAnio($arCierre->getAnio());
                        $arCostoEmpleadoServicio->setMes($arCierre->getMes());
                        $arCostoEmpleadoServicio->setCierreRel($arCierre);
                        $arCostoEmpleadoServicio->setEmpleadoRel($arCostoRecursoHumano->getEmpleadoRel());
                        $arCostoEmpleadoServicio->setPedidoDetalleRel($arPedidoDetalle);
                        $arCostoEmpleadoServicio->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arCostoEmpleadoServicio->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                        $arCostoEmpleadoServicio->setHorasDescanso($detalle['horasDescanso']);
                        $arCostoEmpleadoServicio->setHorasDiurnas($detalle['horasDiurnas']);
                        $arCostoEmpleadoServicio->setHorasNocturnas($detalle['horasNocturnas']);
                        $arCostoEmpleadoServicio->setHorasFestivasDiurnas($detalle['horasFestivasDiurnas']);
                        $arCostoEmpleadoServicio->setHorasFestivasNocturnas($detalle['horasFestivasNocturnas']);
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasDiurnas($detalle['horasExtrasOrdinariasDiurnas']);
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasNocturnas($detalle['horasExtrasOrdinariasNocturnas']);
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasDiurnas($detalle['horasExtrasFestivasDiurnas']);
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasNocturnas($detalle['horasExtrasFestivasNocturnas']);
                        $arCostoEmpleadoServicio->setHorasRecargoNocturno($detalle['horasRecargoNocturno']);
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoDiurno($detalle['horasRecargoFestivoDiurno']);
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoNocturno($detalle['horasRecargoFestivoNocturno']);

                        $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                        $participacionRecurso = 0;
                        if ($peso > 0) {
                            $participacionRecurso = $peso / $pesoTotal;
                        }
                        $costoDetalle = $participacionRecurso * $costoRecurso;
                        $costoDetalleNomina = $participacionRecurso * $nomina;
                        $costoDetalleSeguridadSocial = $participacionRecurso * $aporte;
                        $costoDetallePrestaciones = $participacionRecurso * $provision;
                        $participacion = 0;

                        if ($detalle['pDS'] > 0) {
                            $participacion = $detalle['pDS'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasDescansoCosto($costo);

                        $participacion = 0;
                        if ($detalle['pD'] > 0) {
                            $participacion = $detalle['pD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasDiurnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pN'] > 0) {
                            $participacion = $detalle['pN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasNocturnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pFD'] > 0) {
                            $participacion = $detalle['pFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasFestivasDiurnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pFN'] > 0) {
                            $participacion = $detalle['pFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasFestivasNocturnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pEOD'] > 0) {
                            $participacion = $detalle['pEOD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasDiurnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pEON'] > 0) {
                            $participacion = $detalle['pEON'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasNocturnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pEFD'] > 0) {
                            $participacion = $detalle['pEFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasDiurnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pEFN'] > 0) {
                            $participacion = $detalle['pEFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasNocturnasCosto($costo);

                        $participacion = 0;
                        if ($detalle['pRN'] > 0) {
                            $participacion = $detalle['pRN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoNocturnoCosto($costo);

                        $participacion = 0;
                        if ($detalle['pRFD'] > 0) {
                            $participacion = $detalle['pRFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoDiurnoCosto($costo);

                        $participacion = 0;
                        if ($detalle['pRFN'] > 0) {
                            $participacion = $detalle['pRFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoNocturnoCosto($costo);
                        $participacionRecurso = $participacionRecurso * 100;
                        $arCostoEmpleadoServicio->setParticipacion($participacionRecurso);
                        $arCostoEmpleadoServicio->setPeso($peso);
                        $arCostoEmpleadoServicio->setVrCosto($costoDetalle);
                        $arCostoEmpleadoServicio->setVrNomina($costoDetalleNomina);
                        $arCostoEmpleadoServicio->setVrAporte($costoDetalleSeguridadSocial);
                        $arCostoEmpleadoServicio->setVrProvision($costoDetallePrestaciones);
                        $arCostoEmpleadoServicio->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoRel());
                        $em->persist($arCostoEmpleadoServicio);
                        if ($participacionMayor < $participacionRecurso) {
                            $participacionMayor = $participacionRecurso;
                            $arCentroCostoParticipacion = $arPedidoDetalle->getPuestoRel()->getCentroCostoRel();
                        }
                    }


                /*if ($arCostoRecursoHumano->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo() == 0 || $arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoFijo() == 1) {
                    $arCostoEmpleado->setCentroCostoRel($arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoRel());
                } else {
                    if ($arCentroCostoParticipacion) {
                        $arCostoEmpleado->setCentroCostoRel($arCentroCostoParticipacion);
                    }
                }*/
                $em->persist($arCostoEmpleado);
            }
            $em->flush();

            //Costos de los servicios del mes
            /*
            //Crear los centros de costo y puestos donde trabajo el recurso
            foreach ($arCostos as $arCostoRecursoHumano) {
                if ($arCostoRecursoHumano->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo() == 0 || $arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoFijo() == 1) {
                    $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                    $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                    $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arCostoRecursoHumano->getCodigoEmpleadoFk());
                    $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arCostoRecursoHumano->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk());
                    $arEmpleadoCentroCosto->setCodigoPuestoFk($arCostoRecursoHumano->getEmpleadoRel()->getCodigoPuestoFk());
                    $arEmpleadoCentroCosto->setParticipacion(100);
                    $em->persist($arEmpleadoCentroCosto);
                } else {
                    $arCostoDetalles = new \Brasa\TurnoBundle\Entity\TurCostoDetalle();
                    $arCostoDetalles = $em->getRepository('BrasaTurnoBundle:TurCostoDetalle')->findBy(array('codigoEmpleadoFk' => $arCostoRecursoHumano->getCodigoEmpleadoFk(), 'anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));
                    foreach ($arCostoDetalles as $arCostoDetalle) {
                        $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                        $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                        $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                        $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arCostoRecursoHumano->getCodigoEmpleadoFk());
                        $arEmpleadoCentroCosto->setParticipacion($arCostoDetalle->getParticipacion());
                        if ($arCostoDetalle->getCentroCostoRel()) {
                            $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arCostoDetalle->getCentroCostoRel()->getCodigoCentroCostoPk());
                        }
                        if ($arCostoDetalle->getPuestoRel()) {
                            $arEmpleadoCentroCosto->setCodigoPuestoFk($arCostoDetalle->getPuestoRel()->getCodigoPuestoPk());
                        }

                        $em->persist($arEmpleadoCentroCosto);
                    }
                }
            }
            $em->flush();*/

        }

    }


}
