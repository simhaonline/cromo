<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoDetalleCompuesto;
use App\Entity\Turno\TurPedidoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurContrato::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoContratoPk = null;
        $codigoClienteFk = null;
        $estadoAutorizado = null;
        $estadoCerrado = null;

        if ($filtros) {
            $codigoContratoPk = $filtros['codigoContratoPk'] ?? null;
            $codigoClienteFk = $filtros['codigoClienteFk'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoCerrado = $filtros['estadoCerrado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.horas')
            ->addSelect('c.horasDiurnas')
            ->addSelect('c.horasNocturnas')
            ->addSelect('c.vrTotal')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoCerrado')
            ->addSelect('ct.nombre as contratoTipo')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto ')
            ->addSelect('s.nombre as  sector')
            ->leftJoin('c.contratoTipoRel', 'ct')
            ->leftJoin('c.clienteRel', 'cl')
            ->leftJoin('c.sectorRel', 's');

        if ($codigoContratoPk) {
            $queryBuilder->andWhere("c.codigoContratoPk = '{$codigoContratoPk}'");
        }

        if ($codigoClienteFk) {
            $queryBuilder->andWhere("c.codigoClienteFk = '{$codigoClienteFk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }
        switch ($estadoCerrado) {
            case '0':
                $queryBuilder->andWhere("c.estadoCerrado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoCerrado = 1");
                break;
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
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
            ->where("cd.codigoContratoFk = {$codigoContrato} ")
            ->andWhere("cd.estadoCerrado = 0");
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
            if ($arContratoDetalle->getCompuesto() == 0) {
                if ($arContratoDetalle->getEstadoCerrado() == 0) {
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

                    $horasRealesDiurnas = $horasRealesDiurnas * $arContratoDetalle->getCantidad();
                    $horasRealesNocturnas = $horasRealesNocturnas * $arContratoDetalle->getCantidad();
                    $horas = $horasRealesDiurnas + $horasRealesNocturnas;
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
                        $valorServicio = $valorMinimoServicio;
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
            } else {
                if ($arContratoDetalle->getEstadoCerrado() == 0) {
                    $totalHoras += $arContratoDetalle->getHoras();
                    $totalHorasDiurnas += $arContratoDetalle->getHorasDiurnas();
                    $totalHorasNocturnas += $arContratoDetalle->getHorasNocturnas();
                    $totalMinimoServicio += $arContratoDetalle->getVrPrecioMinimo();
                    $subtotalGeneral += $arContratoDetalle->getVrSubtotal();
                    $totalServicio += $arContratoDetalle->getVrSubtotal();
                    $baseAuiGeneral += $arContratoDetalle->getVrBaseAiu();
                    $ivaGeneral += $arContratoDetalle->getVrIva();
                    $totalGeneral += $arContratoDetalle->getVrTotalDetalle();
                }
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

    public function listaGenerarPedido($fecha)
    {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.vrTotal')
            ->addSelect('c.horas')
            ->addSelect('c.horasDiurnas')
            ->addSelect('c.horasNocturnas')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoCerrado')
            ->addSelect('c.fechaGeneracion')
            ->addSelect('cli.numeroIdentificacion as clienteNumeroIdentificacion')
            ->addSelect('cli.nombreCorto as clienteNombreCorto')
            ->addSelect('sec.nombre as sectorNombre')
            ->addSelect('ct.nombre as contratoTipoNombre')
            ->leftJoin('c.contratoTipoRel', 'ct')
            ->leftJoin('c.clienteRel', 'cli')
            ->leftJoin('c.sectorRel', 'sec')
            ->where("c.fechaGeneracion < '{$fecha}'")
            ->where('c.estadoCerrado = 0');
        $arContratos = $queryBuilder->getQuery()->getResult();
        return $arContratos;

    }

    /**
     * @param $arrSeleccionados
     * @param $fecha
     * @param $usuario
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generarPedido($arrSeleccionados, $fecha, $usuario)
    {
        $em = $this->getEntityManager();
        $fechaDesde = date_create($fecha);
        $anio = $fechaDesde->format('Y');
        $mes = $fechaDesde->format('m');
        $ultimoDiaMes = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $fechaHasta = date_create($fechaDesde->format('Y') . "/" . $fechaDesde->format('m') . "/" . $ultimoDiaMes);
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                /** @var $arContrato TurContrato */
                $arContrato = $em->getRepository(TurContrato::class)->find($codigo);
                if ($arContrato) {
                    /** @var $arPedidoTipo TurPedidoTipo */
                    $arPedidoTipo = $em->getRepository(TurPedidoTipo::class)->find('CON');
                    $arPedido = new TurPedido();
                    $arPedido->setClienteRel($arContrato->getClienteRel());
                    $arPedido->setPedidoTipoRel($arPedidoTipo);
                    $arPedido->setSectorRel($arContrato->getSectorRel());
                    $arPedido->setFecha($fechaDesde);
                    $arPedido->setEstrato(intval($arContrato->getEstrato()));
                    $arPedido->setUsuario($usuario);
                    $arPedido->setVrSalarioBase($arContrato->getVrSalarioBase());
                    $arPedido->setEstadoAutorizado(1);
                    $numero = $arPedidoTipo->getConsecutivo();
                    $arPedido->setNumero($numero);
                    $arPedidoTipo->setConsecutivo($numero + 1);
                    $em->persist($arPedidoTipo);
                    $em->persist($arPedido);

                    $arContrato->setFechaGeneracion($fechaDesde);
                    $em->persist($arContrato);

                    $arContratoDetalles = $em->getRepository(TurContratoDetalle::class)->findBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoCerrado' => 0));
                    foreach ($arContratoDetalles as $arContratoDetalle) {
                        $arPedidoDetalle = new TurPedidoDetalle();
                        $arPedidoDetalle->setPedidoRel($arPedido);
                        $arPedidoDetalle->setContratoDetalleRel($arContratoDetalle);
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
                        $arPedidoDetalle->setItemRel($arContratoDetalle->getItemRel());
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
                                if ($arContratoDetalle->getCompuesto() == 1) {
                                    $arContratoDetallesCompuestos = $em->getRepository(TurContratoDetalleCompuesto::class)->findBy(array('codigoContratoDetalleFk' => $arContratoDetalle->getCodigoCOntratoDetallePk()));
                                    foreach ($arContratoDetallesCompuestos as $arContratoDetalleCompuesto) {
                                        $arPedidoDetalleCompuesto = new TurPedidoDetalleCompuesto();
                                        $arPedidoDetalleCompuesto->setPedidoDetalleRel($arPedidoDetalle);
                                        $arPedidoDetalleCompuesto->setModalidadRel($arContratoDetalleCompuesto->getModalidadRel());
                                        $arPedidoDetalleCompuesto->setPeriodo($arContratoDetalleCompuesto->getPeriodo());
                                        $arPedidoDetalleCompuesto->setConceptoRel($arContratoDetalleCompuesto->getConceptoRel());
                                        $arPedidoDetalleCompuesto->setDias($arContratoDetalleCompuesto->getDias());
                                        $arPedidoDetalleCompuesto->setLunes($arContratoDetalleCompuesto->getLunes());
                                        $arPedidoDetalleCompuesto->setMartes($arContratoDetalleCompuesto->getMartes());
                                        $arPedidoDetalleCompuesto->setMiercoles($arContratoDetalleCompuesto->getMiercoles());
                                        $arPedidoDetalleCompuesto->setJueves($arContratoDetalleCompuesto->getJueves());
                                        $arPedidoDetalleCompuesto->setViernes($arContratoDetalleCompuesto->getViernes());
                                        $arPedidoDetalleCompuesto->setSabado($arContratoDetalleCompuesto->getSabado());
                                        $arPedidoDetalleCompuesto->setDomingo($arContratoDetalleCompuesto->getDomingo());
                                        $arPedidoDetalleCompuesto->setFestivo($arContratoDetalleCompuesto->getFestivo());
                                        $arPedidoDetalleCompuesto->setCantidad($arContratoDetalleCompuesto->getCantidad());
                                        $arPedidoDetalleCompuesto->setVrPrecioAjustado($arContratoDetalleCompuesto->getVrPrecioAjustado());
                                        $arPedidoDetalleCompuesto->setPorcentajeIva($arContratoDetalleCompuesto->getPorcentajeIva());
                                        $strAnioMes = $arPedido->getFecha()->format('Y/m/');
                                        $dateFechaDesde = date_create($strAnioMes . "1");
                                        $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFechaDesde->format('m') + 1, 1, $dateFechaDesde->format('Y')) - 1));
                                        $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
                                        $intDiaInicial = 0;
                                        $intDiaFinal = 0;
                                        if ($dateFechaDesde < $arContratoDetalle->getFechaHasta()) {
                                            $dateFechaProceso = $dateFechaDesde;
                                            if ($arContratoDetalle->getFechaDesde() <= $dateFechaHasta) {
                                                if ($arContratoDetalle->getFechaDesde() > $dateFechaProceso) {
                                                    $dateFechaProceso = $arContratoDetalle->getFechaDesde();
                                                    if ($dateFechaProceso <= $arContratoDetalle->getFechaHasta()) {
                                                        $intDiaInicial = $dateFechaProceso->format('j');
                                                    }
                                                } else {
                                                    $intDiaInicial = $dateFechaProceso->format('j');
                                                }
                                            }
                                            $dateFechaProceso = $dateFechaHasta;
                                            if ($dateFechaHasta >= $arContratoDetalle->getFechaDesde()) {
                                                if ($arContratoDetalle->getFechaHasta() < $dateFechaProceso) {
                                                    $dateFechaProceso = $arContratoDetalle->getFechaHasta();
                                                    if ($dateFechaProceso >= $arContratoDetalle->getFechaHasta()) {
                                                        $intDiaFinal = $dateFechaProceso->format('j');
                                                    }
                                                } else {
                                                    $intDiaFinal = $dateFechaProceso->format('j');
                                                }
                                            }
                                        }
                                        $arPedidoDetalleCompuesto->setDiaDesde($intDiaInicial);
                                        $arPedidoDetalleCompuesto->setDiaHasta($intDiaFinal);
                                        $em->persist($arPedidoDetalleCompuesto);
                                    }
                                }

                            }

                        }
                    }

                    $em->flush();
                    $arPedidoDetalles = $em->getRepository(TurPedidoDetalle::class)->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk(), 'compuesto' => 1));
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $em->getRepository(TurPedidoDetalle::class)->liquidar($arPedidoDetalle->getCodigoPedidoDetallePk());
                    }
                    $em->getRepository(TurPedido::class)->liquidar($arPedido);
                }
            }
        }
    }

    public function listaPrototipo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk');
        return $queryBuilder;
    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('cl.nombreCorto')
            ->addSelect('ct.nombre')
            ->addSelect('c.fechaGeneracion')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->addSelect('c.estadoCerrado')
            ->addSelect('c.cantidad')
            ->addSelect('c.estrato')
            ->addSelect('c.horas')
            ->addSelect('c.horasDiurnas')
            ->addSelect('c.horasNocturnas')
            ->addSelect('c.vrTotalCosto')
            ->addSelect('c.vrTotalContrato')
            ->addSelect('c.vrTotalPrecioAjustado')
            ->addSelect('c.vrTotalPrecioMinimo')
            ->addSelect('c.vrSubtotal')
            ->addSelect('c.vrIva')
            ->addSelect('c.vrBaseAiu')
            ->addSelect('c.vrSalarioBase')
            ->addSelect('c.vrTotal')
            ->addSelect('c.vrTotalServicio')
            ->addSelect('c.usuario')
            ->leftJoin('c.clienteRel', 'cl')
            ->leftJoin('c.contratoTipoRel', 'ct');

        if ($session->get('filtroTurInformeContratoCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClienteFk  = '{$session->get('filtroTurInformeContratoCodigoCliente')}'");
        }
        if ($session->get('filtroTurInformeContratoFechaDesde') != null) {
            $queryBuilder->andWhere("c.fechaGeneracion >= '{$session->get('filtroTurInformeContratoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeContratoFechaHasta') != null) {
            $queryBuilder->andWhere("c.fechaGeneracion <= '{$session->get('filtroTurInformeContratoFechaHasta')} 23:59:59'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function festivo($arFestivos, $dateFecha)
    {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if ($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }
}
