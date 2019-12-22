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
                    $numeroServicios = count($arrayResultados);
                    $pesoTotal = 0;
                    foreach ($arrayResultados as $detalle) {
                        $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                        $pesoTotal += $peso;
                    }
                    $participacionMayor = 0;
                    $diferencia = 0;
                    $registro = 0;
                    foreach ($arrayResultados as $detalle) {
                        $registro++;
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
                        $participacionEmpleado = 0;
                        if ($peso > 0) {
                            $participacionEmpleado = $peso / $pesoTotal;
                        }

                        $participacionAbsoluta = $participacionEmpleado * 100;
                        $participacion = round($participacionEmpleado * 100);
                        $diferencia += $participacionAbsoluta - $participacion;
                        if($numeroServicios == $registro && $diferencia > 0) {
                            $participacion += $diferencia;
                        }

                        $costoDetalle = ($participacion * $costoRecurso) / 100;
                        $costoDetalleNomina = ($participacion * $nomina) / 100;
                        $costoDetalleSeguridadSocial = ($participacion * $aporte) / 100;
                        $costoDetallePrestaciones = ($participacion * $provision) / 100;

                        $participacionDetalle = 0;
                        if ($detalle['pDS'] > 0) {
                            $participacionDetalle = $detalle['pDS'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasDescansoCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pD'] > 0) {
                            $participacionDetalle = $detalle['pD'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasDiurnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pN'] > 0) {
                            $participacionDetalle = $detalle['pN'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasNocturnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pFD'] > 0) {
                            $participacionDetalle = $detalle['pFD'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasFestivasDiurnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pFN'] > 0) {
                            $participacionDetalle = $detalle['pFN'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasFestivasNocturnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pEOD'] > 0) {
                            $participacionDetalle = $detalle['pEOD'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasDiurnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pEON'] > 0) {
                            $participacionDetalle = $detalle['pEON'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasOrdinariasNocturnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pEFD'] > 0) {
                            $participacionDetalle = $detalle['pEFD'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasDiurnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pEFN'] > 0) {
                            $participacionDetalle = $detalle['pEFN'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasExtrasFestivasNocturnasCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pRN'] > 0) {
                            $participacionDetalle = $detalle['pRN'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoNocturnoCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pRFD'] > 0) {
                            $participacionDetalle = $detalle['pRFD'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoDiurnoCosto($costo);

                        $participacionDetalle = 0;
                        if ($detalle['pRFN'] > 0) {
                            $participacionDetalle = $detalle['pRFN'] / $peso;
                        }
                        $costo = $participacionDetalle * $costoDetalle;
                        $arCostoEmpleadoServicio->setHorasRecargoFestivoNocturnoCosto($costo);

                        //$participacionRecurso = $participacionRecurso * 100;
                        $arCostoEmpleadoServicio->setParticipacion($participacion);
                        $arCostoEmpleadoServicio->setPeso($peso);
                        $arCostoEmpleadoServicio->setVrCosto($costoDetalle);
                        $arCostoEmpleadoServicio->setVrNomina($costoDetalleNomina);
                        $arCostoEmpleadoServicio->setVrAporte($costoDetalleSeguridadSocial);
                        $arCostoEmpleadoServicio->setVrProvision($costoDetallePrestaciones);
                        $arCostoEmpleadoServicio->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoRel());
                        $em->persist($arCostoEmpleadoServicio);
                        if ($participacionMayor < $participacion) {
                            $participacionMayor = $participacion;
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

    public function lista($codigoCierre)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurCostoEmpleado::class, 'ce')
            ->select('ce.codigoCostoEmpleadoPk')
            ->addSelect('ce.anio')
            ->addSelect('ce.mes')
            ->addSelect('ce.codigoEmpleadoFk')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('ce.vrNomina')
            ->addSelect('ce.vrProvision')
            ->addSelect('ce.vrAporte')
            ->addSelect('ce.vrTotal')
            ->leftJoin('ce.empleadoRel', 'e')
            ->where('ce.codigoCierreFk = ' . $codigoCierre);
        return $queryBuilder->getQuery()->getResult();
    }

}
