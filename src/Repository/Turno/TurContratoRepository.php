<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContrato::class);
    }

    public function autorizar($arContrato)
    {
        $em = $this->getEntityManager();
        if (!$arContrato->getEstadoAutorizado()) {
            $registros = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'c')
                ->select('COUNT(c.codigoContratoDetallePk) AS registros')
                ->where('c.codigoContratoFk = ' . $arContrato->getCodigoContratoPk())
                ->getQuery()->getSingleResult();
            if ($registros['registros'] > 0) {
                $arContrato->setEstadoAutorizado(1);
                $em->persist($arContrato);
                $em->flush();
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    public function desautorizar($arContrato)
    {
        $em = $this->getEntityManager();
        if ($arContrato->getEstadoAutorizado()) {
            $arContrato->setEstadoAutorizado(0);
            $em->persist($arContrato);
            $em->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TurContrato::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurContratoDetalle::class)->findBy(['codigoContratoFk' => $arRegistro->getCodigoContratoPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $codigoContrato
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoContrato)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd')
            ->select("COUNT(cd.codigoContratoDetallePk)")
            ->where("cd.codigoContratoFk = {$codigoContrato} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $arContratos TurContrato
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @var $arContrato TurContrato
     */
    public function liquidar($arContrato)
    {
        $em = $this->getEntityManager();
        $intCantidad = 0;
        $precio = 0;
        $totalHoras = 0;
        $totalHorasDiurnas = 0;
        $totalHorasNocturnas = 0;
        $totalServicio = 0;
        $totalMinimoServicio = 0;
        $totalCostoCalculado = 0;
        $subtotalGeneral = 0;
        $baseAuiGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arContratoDetalles = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->findBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk()));
        /** @var $arContratoDetalle TurContratoDetalle */
        foreach ($arContratoDetalles as $arContratoDetalle) {
            if ($arContratoDetalle->getEstadoTerminado() == 0) {
                /** @var $arConcepto TurConcepto */
                $arConcepto = $arContratoDetalle->getConceptoRel();
                if ($arContratoDetalle->getPeriodo() == "D") {
                    $dias = $arContratoDetalle->getFechaDesde()->diff($arContratoDetalle->getFechaHasta());
                    $dias = $dias->format('%a');
                    $dias += 1;
                    if ($arContratoDetalle->getFechaHasta()->format('d') == '31') {
                        $dias = $dias - 1;
                    }
                    if ($arContratoDetalle->getDia31() == 1) {
                        if ($arContratoDetalle->getFechaHasta()->format('d') == '31') {
                            $dias = $dias + 1;
                        }
                    }
                } else {
                    $dias = 30;
                }

                $horasRealesDiurnas = 0;
                $horasRealesNocturnas = 0;
                $diasOrdinarios = 0;
                $diasSabados = 0;
                $diasDominicales = 0;
                $diasFestivos = 0;
                if ($arContratoDetalle->getPeriodo() == "M") {
                    if ($arContratoDetalle->getLunes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arContratoDetalle->getMartes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arContratoDetalle->getMiercoles()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arContratoDetalle->getJueves()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arContratoDetalle->getViernes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arContratoDetalle->getSabado()) {
                        $diasSabados = 4;
                    }
                    if ($arContratoDetalle->getDomingo()) {
                        $diasDominicales = 4;
                    }
                    if ($arContratoDetalle->getFestivo()) {
                        $diasFestivos = 2;
                    }
                    $totalDias = $diasOrdinarios + $diasSabados + $diasDominicales + $diasFestivos;
                    $horasRealesDiurnas = $arConcepto->getHorasDiurnas() * $totalDias;
                    $horasRealesNocturnas = $arConcepto->getHorasNocturnas() * $totalDias;
                } else {
                    $arFestivos = $em->getRepository(TurFestivo::class)->fecha($arContratoDetalle->getFechaDesde()->format('Y-m-d'), $arContratoDetalle->getFechaHasta()->format('Y-m-d'));
                    $fecha = $arContratoDetalle->getFechaDesde()->format('Y-m-j');
                    for ($i = 0; $i < $dias; $i++) {
                        $nuevafecha = strtotime('+' . $i . ' day', strtotime($fecha));
                        $nuevafecha = date('Y-m-j', $nuevafecha);
                        $dateNuevaFecha = date_create($nuevafecha);
                        $diaSemana = $dateNuevaFecha->format('N');
                        if ($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                            $diasFestivos += 1;
                        } else {
                            if ($diaSemana == 1) {
                                $diasOrdinarios += 1;
                                if ($arContratoDetalle->getLunes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 2) {
                                $diasOrdinarios += 1;
                                if ($arContratoDetalle->getMartes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 3) {
                                $diasOrdinarios += 1;
                                if ($arContratoDetalle->getMiercoles() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 4) {
                                $diasOrdinarios += 1;
                                if ($arContratoDetalle->getJueves() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 5) {
                                $diasOrdinarios += 1;
                                if ($arContratoDetalle->getViernes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 6) {
                                $diasSabados += 1;
                                if ($arContratoDetalle->getSabado() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 7) {
                                $diasDominicales += 1;
                                if ($arContratoDetalle->getDomingo() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                        }
                    }
                }


                $horas = ($horasRealesDiurnas + $horasRealesNocturnas) * $arContratoDetalle->getCantidad();
                $valorBaseServicio = $arContratoDetalle->getVrSalarioBase() * $arContrato->getSectorRel()->getPorcentaje();
                if ($arContrato->getCodigoSectorFk() == "D" && $arContrato->getEstrato() >= 4) {
                    //Cambiar porcentaje para residencial mayor a estrato 4
                    //$porcentajeModalidad = $arContratoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                    $porcentajeModalidad = $arContratoDetalle->getModalidadRel()->getPorcentaje();
                } else {
                    $porcentajeModalidad = $arContratoDetalle->getModalidadRel()->getPorcentaje();
                }


                $valorBaseServicioMes = $valorBaseServicio + ($valorBaseServicio * $porcentajeModalidad / 100);
                $valorHoraDiurna = ((($valorBaseServicioMes * 55.97) / 100) / 30) / 15;
                $valorHoraNocturna = ((($valorBaseServicioMes * 44.03) / 100) / 30) / 9;

                $precio = ($horasRealesDiurnas * $valorHoraDiurna) + ($horasRealesNocturnas * $valorHoraNocturna);
                $valorMinimoServicio = $precio;


                if ($arContratoDetalle->getVrPrecioAjustado() != 0) {
                    $valorServicio = $arContratoDetalle->getVrPrecioAjustado() * $arContratoDetalle->getCantidad();
                    $precio = $arContratoDetalle->getVrPrecioAjustado();
                } else {
                    $valorServicio = $valorMinimoServicio * $arContratoDetalle->getCantidad();
                }


                $subTotalDetalle = $valorServicio;
                $baseAiuDetalle = $subTotalDetalle * ($arContratoDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * ($arContratoDetalle->getPorcentajeIva() / 100);
                $totalDetalle = $subTotalDetalle + $ivaDetalle;

                $arContratoDetalle->setVrSubtotal($subTotalDetalle);
                $arContratoDetalle->setVrBaseAiu($baseAiuDetalle);
                $arContratoDetalle->setVrIva($ivaDetalle);
                $arContratoDetalle->setVrTotalDetalle($totalDetalle);
                $arContratoDetalle->setVrPrecioMinimo($valorMinimoServicio);
                $arContratoDetalle->setVrPrecio($precio);
                $arContratoDetalle->setHoras($horas);
                $arContratoDetalle->setHorasDiurnas($horasRealesDiurnas);
                $arContratoDetalle->setHorasNocturnas($horasRealesNocturnas);
                $arContratoDetalle->setDias($dias);
                $em->persist($arContratoDetalle);

                $subtotalGeneral += $subTotalDetalle;
                $baseAuiGeneral += $baseAiuDetalle;
                $ivaGeneral += $ivaDetalle;
                $totalGeneral += $totalDetalle;

                $totalHoras += $horas;
                $totalHorasDiurnas += $horasRealesDiurnas;
                $totalHorasNocturnas += $horasRealesNocturnas;
                $totalMinimoServicio += $valorMinimoServicio;
                $totalServicio += $valorServicio;
                $intCantidad++;
            }
        }

        $arContrato->setHoras($totalHoras);
        $arContrato->setHorasDiurnas($totalHorasDiurnas);
        $arContrato->setHorasNocturnas($totalHorasNocturnas);
        $arContrato->setVrTotalServicio($totalServicio);
        $arContrato->setVrTotalPrecioMinimo($totalMinimoServicio);
        $arContrato->setVrTotalCosto($totalCostoCalculado);
        $arContrato->setVrSubtotal($subtotalGeneral);
        $arContrato->setVrBaseAiu($baseAuiGeneral);
        $arContrato->setVrIva($ivaGeneral);
        $arContrato->setVrTotal($totalGeneral);
        $em->persist($arContrato);
        $em->flush();
    }

    public function listaGenerarPedido($fecha){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->where("c.fechaGeneracion < '{$fecha}'");
        $arContratos = $queryBuilder->getQuery()->getResult();
        return $arContratos;

    }

    public function generarPedido($arrSeleccionados, $fecha, $usuario) {
        $em = $this->getEntityManager();
        $fechaDesde = date_create($fecha);
        $anio = $fechaDesde->format('Y');
        $mes = $fechaDesde->format('m');
        $ultimoDiaMes = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $fechaHasta = date_create($fechaDesde->format('Y') . "/" . $fechaDesde->format('m') . "/" . $ultimoDiaMes);
        if($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                /** @var $arContrato TurContrato */
                $arContrato = $em->getRepository(TurContrato::class)->find($codigo);
                if($arContrato) {
                    $arPedidoTipo = $em->getRepository(TurPedidoTipo::class)->find('CON');
                    $arPedido = new TurPedido();
                    $arPedido->setClienteRel($arContrato->getClienteRel());
                    $arPedido->setPedidoTipoRel($arPedidoTipo);
                    $arPedido->setSectorRel($arContrato->getSectorRel());
                    $arPedido->setFecha($fechaDesde);
                    $arPedido->setEstrato(intval($arContrato->getEstrato()));
                    $arPedido->setUsuario($usuario);
                    $arPedido->setVrSalarioBase($arContrato->getVrSalarioBase());
                    $em->persist($arPedido);

                    $arContratoDetalles = $em->getRepository(TurContratoDetalle::class)->findBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoTerminado' => 0));
                    foreach ($arContratoDetalles as $arContratoDetalle) {
                        $arPedidoDetalle = new TurPedidoDetalle();
                        $arPedidoDetalle->setPedidoRel($arPedido);

                        $diaInicial = 0;
                        $diaFinal = 0;
                        $fechaProceso = $fechaDesde;
                        if ($arContratoDetalle->getFechaDesde() <= $fechaHasta) {
                            if ($arContratoDetalle->getFechaDesde() > $fechaProceso) {
                                $fechaProceso = $arContratoDetalle->getFechaDesde();
                                if ($fechaProceso <= $arContratoDetalle->getFechaHasta()) {
                                    $diaInicial = $fechaProceso->format('j');
                                }
                            } else {
                                $diaInicial = $fechaProceso->format('j');
                            }
                        }
                        $fechaProceso = $fechaHasta;
                        if ($fechaHasta >= $arContratoDetalle->getFechaDesde()) {
                            if ($arContratoDetalle->getFechaHasta() < $fechaProceso) {
                                $fechaProceso = $arContratoDetalle->getFechaHasta();
                                if ($fechaProceso >= $arContratoDetalle->getFechaHasta()) {
                                    $diaFinal = $fechaProceso->format('j');
                                }
                            } else {
                                $diaFinal = $fechaProceso->format('j');
                            }
                        }
                        $arPedidoDetalle->setDiaDesde($diaInicial);
                        $arPedidoDetalle->setDiaHasta($diaFinal);

                        if ($diaInicial != 1 || $diaFinal != $ultimoDiaMes) {
                            $arPedidoDetalle->setPeriodo("D");
                        } else {
                            $arPedidoDetalle->setPeriodo("M");
                        }

                        $arPedidoDetalle->setCantidad($arContratoDetalle->getCantidad());
                        $arPedidoDetalle->setConceptoRel($arContratoDetalle->getConceptoRel());
                        $arPedidoDetalle->setVrSalarioBase($arContratoDetalle->getVrSalarioBase());
                        $arPedidoDetalle->setModalidadRel($arContratoDetalle->getModalidadRel());
                        $arPedidoDetalle->setPuestoRel($arContratoDetalle->getPuestoRel());
                        $arPedidoDetalle->setLunes($arContratoDetalle->getLunes());
                        $arPedidoDetalle->setMartes($arContratoDetalle->getMartes());
                        $arPedidoDetalle->setMiercoles($arContratoDetalle->getMiercoles());
                        $arPedidoDetalle->setJueves($arContratoDetalle->getJueves());
                        $arPedidoDetalle->setViernes($arContratoDetalle->getViernes());
                        $arPedidoDetalle->setSabado($arContratoDetalle->getSabado());
                        $arPedidoDetalle->setDomingo($arContratoDetalle->getDomingo());
                        $arPedidoDetalle->setFestivo($arContratoDetalle->getFestivo());
                        $arPedidoDetalle->setVrPrecioAjustado($arContratoDetalle->getVrPrecioAjustado());
                        $arPedidoDetalle->setPorcentajeIva($arContratoDetalle->getPorcentajeIva());
                        $arPedidoDetalle->setPorcentajeBaseIva($arContratoDetalle->getPorcentajeBaseIva());
                        $arPedidoDetalle->setAnio($anio);
                        $arPedidoDetalle->setMes($mes);
                        $arPedidoDetalle->setCompuesto($arContratoDetalle->getCompuesto());

                        if ($arContratoDetalle->getFechaHasta() >= $fechaDesde) {
                            if ($diaInicial != 0 && $diaFinal != 0) {
                                $em->persist($arPedidoDetalle);
                                /*if ($arServicioDetalle->getCompuesto() == 1) {
                                    $arServicioDetallesCompuestos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
                                    $arServicioDetallesCompuestos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk()));
                                    foreach ($arServicioDetallesCompuestos as $arServicioDetalleCompuesto) {
                                        $arPedidoDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();
                                        $arPedidoDetalleCompuesto->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                        $arPedidoDetalleCompuesto->setModalidadServicioRel($arServicioDetalleCompuesto->getModalidadServicioRel());
                                        $arPedidoDetalleCompuesto->setPeriodoRel($arServicioDetalleCompuesto->getPeriodoRel());
                                        $arPedidoDetalleCompuesto->setConceptoServicioRel($arServicioDetalleCompuesto->getConceptoServicioRel());
                                        $arPedidoDetalleCompuesto->setDias($arServicioDetalleCompuesto->getDias());
                                        $arPedidoDetalleCompuesto->setLunes($arServicioDetalleCompuesto->getLunes());
                                        $arPedidoDetalleCompuesto->setMartes($arServicioDetalleCompuesto->getMartes());
                                        $arPedidoDetalleCompuesto->setMiercoles($arServicioDetalleCompuesto->getMiercoles());
                                        $arPedidoDetalleCompuesto->setJueves($arServicioDetalleCompuesto->getJueves());
                                        $arPedidoDetalleCompuesto->setViernes($arServicioDetalleCompuesto->getViernes());
                                        $arPedidoDetalleCompuesto->setSabado($arServicioDetalleCompuesto->getSabado());
                                        $arPedidoDetalleCompuesto->setDomingo($arServicioDetalleCompuesto->getDomingo());
                                        $arPedidoDetalleCompuesto->setFestivo($arServicioDetalleCompuesto->getFestivo());
                                        $arPedidoDetalleCompuesto->setCantidad($arServicioDetalleCompuesto->getCantidad());
                                        $arPedidoDetalleCompuesto->setVrPrecioAjustado($arServicioDetalleCompuesto->getVrPrecioAjustado());
                                        $arPedidoDetalleCompuesto->setPorcentajeIva($arServicioDetalleCompuesto->getConceptoServicioRel()->getPorIva());
                                        $arPedidoDetalleCompuesto->setLiquidarDiasReales($arServicioDetalleCompuesto->getLiquidarDiasReales());
                                        $arPedidoDetalleCompuesto->setNoFacturar($arServicioDetalleCompuesto->getNoFacturar());
                                        $strAnioMes = $arPedidoNuevo->getFechaProgramacion()->format('Y/m/');
                                        $dateFechaDesde = date_create($strAnioMes . "1");
                                        $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFechaDesde->format('m') + 1, 1, $dateFechaDesde->format('Y')) - 1));
                                        $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
                                        $intDiaInicial = 0;
                                        $intDiaFinal = 0;
                                        if ($dateFechaDesde < $arServicioDetalle->getFechaHasta()) {
                                            $dateFechaProceso = $dateFechaDesde;
                                            if ($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                                                if ($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                                    $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                                    if ($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                                        $intDiaInicial = $dateFechaProceso->format('j');
                                                    }
                                                } else {
                                                    $intDiaInicial = $dateFechaProceso->format('j');
                                                }
                                            }
                                            $dateFechaProceso = $dateFechaHasta;
                                            if ($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                                                if ($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                                    $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                                    if ($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                                        $intDiaFinal = $dateFechaProceso->format('j');
                                                    }
                                                } else {
                                                    $intDiaFinal = $dateFechaProceso->format('j');
                                                }
                                            }
                                        }
                                        $arPedidoDetalleCompuesto->setDiaDesde($intDiaInicial);
                                        $arPedidoDetalleCompuesto->setDiaHasta($intDiaFinal);

                                        $strUltimoDiaMes = date("j", (mktime(0, 0, 0, $dateFechaDesde->format('m') + 1, 1, $dateFechaDesde->format('Y')) - 1));
                                        $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                                        if ($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                                        } else {
                                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                                        }
                                        $arPedidoDetalleCompuesto->setPeriodoRel($arPeriodo);
                                        $em->persist($arPedidoDetalleCompuesto);
                                    }
                                }*/

                            }

                        }
                    }

                    $em->flush();
                    $em->getRepository(TurPedido::class)->liquidar($arPedido);
                }
            }
        }
    }

}
