<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurCotizacionDetalle;
use App\Entity\Turno\TurCotizacionTipo;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurCotizacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCotizacion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoCotizacionPk = null;
        $numero = null;
        $codigoCotizacionTipoFk = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        if ($filtros){
            $codigoCotizacionPk = $filtros['codigoCotizacionPk']??null;
            $numero = $filtros['numero']??null;
            $codigoCotizacionTipoFk = $filtros['codigoCotizacionTipoFk']??null;
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCotizacion::class, 'c')
            ->select('c.codigoCotizacionPk')
            ->addSelect('c.numero')
            ->addSelect('ct.nombre as cotizacionTipo')
            ->addSelect('c.fecha')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('s.nombre as sector')
            ->addSelect('c.horas')
            ->addSelect('c.horasDiurnas')
            ->addSelect('c.horasNocturnas')
            ->addSelect('c.vrTotal')
            ->addSelect('c.usuario')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->leftJoin('c.cotizacionTipoRel', 'ct')
            ->leftJoin('c.clienteRel', 'cl')
            ->leftJoin('c.sectorRel', 's');

        if ($codigoCotizacionPk) {
            $queryBuilder->andWhere("c.codigoCotizacionPk = '{$codigoCotizacionPk}'");
        }

        if ($numero) {
            $queryBuilder->andWhere("c.numero = '{$numero}'");
        }

        if ($codigoCotizacionTipoFk) {
            $queryBuilder->andWhere("ct.codigoCotizacionTipoPk = '{$codigoCotizacionTipoFk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAnulado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
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
                $arRegistro = $this->getEntityManager()->getRepository(TurCotizacion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurCotizacionDetalle::class)->findBy(['codigoCotizacionFk' => $arRegistro->getCodigoCotizacionPk()])) <= 0) {
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
     * @param $codigoCotizacion TurCotizacion
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoCotizacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCotizacionDetalle::class, 'cd')
            ->select("COUNT(cd.codigoCotizacionDetallePk)")
            ->where("cd.codigoCotizacionFk = {$codigoCotizacion} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $arCotizacion TurCotizacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arCotizacion)
    {
        /**
         * @var $arCotizacion TurCotizacion
         */
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
        $arCotizacionDetalles = $this->getEntityManager()->getRepository(TurCotizacionDetalle::class)->findBy(array('codigoCotizacionFk' => $arCotizacion->getCodigoCotizacionPk()));
        foreach ($arCotizacionDetalles as $arCotizacionDetalle) {
            if ($arCotizacionDetalle->getEstadoTerminado() == 0) {
                /** @var $arConcepto TurConcepto */
                $arConcepto = $arCotizacionDetalle->getConceptoRel();
                if ($arCotizacionDetalle->getPeriodo() == "D") {
                    $dias = $arCotizacionDetalle->getFechaDesde()->diff($arCotizacionDetalle->getFechaHasta());
                    $dias = $dias->format('%a');
                    $dias += 1;
                    if ($arCotizacionDetalle->getFechaHasta()->format('d') == '31') {
                        $dias = $dias - 1;
                    }
                    if ($arCotizacionDetalle->getDia31() == 1) {
                        if ($arCotizacionDetalle->getFechaHasta()->format('d') == '31') {
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
                if ($arCotizacionDetalle->getPeriodo() == "M") {
                    if ($arCotizacionDetalle->getLunes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCotizacionDetalle->getMartes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCotizacionDetalle->getMiercoles()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCotizacionDetalle->getJueves()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCotizacionDetalle->getViernes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCotizacionDetalle->getSabado()) {
                        $diasSabados = 4;
                    }
                    if ($arCotizacionDetalle->getDomingo()) {
                        $diasDominicales = 4;
                    }
                    if ($arCotizacionDetalle->getFestivo()) {
                        $diasFestivos = 2;
                    }
                    $totalDias = $diasOrdinarios + $diasSabados + $diasDominicales + $diasFestivos;
                    $horasRealesDiurnas = $arConcepto->getHorasDiurnas() * $totalDias;
                    $horasRealesNocturnas = $arConcepto->getHorasNocturnas() * $totalDias;
                } else {
                    $arFestivos = $em->getRepository(TurFestivo::class)->fecha($arCotizacionDetalle->getFechaDesde()->format('Y-m-d'), $arCotizacionDetalle->getFechaHasta()->format('Y-m-d'));
                    $fecha = $arCotizacionDetalle->getFechaDesde()->format('Y-m-j');
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
                                if ($arCotizacionDetalle->getLunes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 2) {
                                $diasOrdinarios += 1;
                                if ($arCotizacionDetalle->getMartes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 3) {
                                $diasOrdinarios += 1;
                                if ($arCotizacionDetalle->getMiercoles() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 4) {
                                $diasOrdinarios += 1;
                                if ($arCotizacionDetalle->getJueves() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 5) {
                                $diasOrdinarios += 1;
                                if ($arCotizacionDetalle->getViernes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 6) {
                                $diasSabados += 1;
                                if ($arCotizacionDetalle->getSabado() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 7) {
                                $diasDominicales += 1;
                                if ($arCotizacionDetalle->getDomingo() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                        }
                    }
                }


                $horas = ($horasRealesDiurnas + $horasRealesNocturnas) * $arCotizacionDetalle->getCantidad();
                $valorBaseServicio = $arCotizacionDetalle->getVrSalarioBase() * $arCotizacion->getSectorRel()->getPorcentaje();
                if ($arCotizacion->getCodigoSectorFk() == "D" && $arCotizacion->getEstrato() >= 4) {
                    //Cambiar porcentaje para residencial mayor a estrato 4
                    //$porcentajeModalidad = $arContratoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                    $porcentajeModalidad = $arCotizacionDetalle->getModalidadRel()->getPorcentaje();
                } else {
                    $porcentajeModalidad = $arCotizacionDetalle->getModalidadRel()->getPorcentaje();
                }


                $valorBaseServicioMes = $valorBaseServicio + ($valorBaseServicio * $porcentajeModalidad / 100);
                $valorHoraDiurna = ((($valorBaseServicioMes * 55.97) / 100) / 30) / 15;
                $valorHoraNocturna = ((($valorBaseServicioMes * 44.03) / 100) / 30) / 9;

                $precio = ($horasRealesDiurnas * $valorHoraDiurna) + ($horasRealesNocturnas * $valorHoraNocturna);
                $valorMinimoServicio = $precio;


                if ($arCotizacionDetalle->getVrPrecioAjustado() != 0) {
                    $valorServicio = $arCotizacionDetalle->getVrPrecioAjustado() * $arCotizacionDetalle->getCantidad();
                    $precio = $arCotizacionDetalle->getVrPrecioAjustado();
                } else {
                    $valorServicio = $valorMinimoServicio * $arCotizacionDetalle->getCantidad();
                }


                $subTotalDetalle = $valorServicio;
                $baseAiuDetalle = $subTotalDetalle * ($arCotizacionDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * ($arCotizacionDetalle->getPorcentajeIva() / 100);
                $totalDetalle = $subTotalDetalle + $ivaDetalle;

                $arCotizacionDetalle->setVrSubtotal($subTotalDetalle);
                $arCotizacionDetalle->setVrBaseAiu($baseAiuDetalle);
                $arCotizacionDetalle->setVrIva($ivaDetalle);
                $arCotizacionDetalle->setVrTotalDetalle($totalDetalle);
                $arCotizacionDetalle->setVrPrecioMinimo($valorMinimoServicio);
                $arCotizacionDetalle->setVrPrecio($precio);
                $arCotizacionDetalle->setHoras($horas);
                $arCotizacionDetalle->setHorasDiurnas($horasRealesDiurnas);
                $arCotizacionDetalle->setHorasNocturnas($horasRealesNocturnas);
                $arCotizacionDetalle->setDias($dias);
                $em->persist($arCotizacionDetalle);

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

        $arCotizacion->setHoras($totalHoras);
        $arCotizacion->setHorasDiurnas($totalHorasDiurnas);
        $arCotizacion->setHorasNocturnas($totalHorasNocturnas);
        $arCotizacion->setVrTotalServicio($totalServicio);
        $arCotizacion->setVrTotalPrecioMinimo($totalMinimoServicio);
        $arCotizacion->setVrTotalCosto($totalCostoCalculado);
        $arCotizacion->setVrSubtotal($subtotalGeneral);
        $arCotizacion->setVrBaseAiu($baseAuiGeneral);
        $arCotizacion->setVrIva($ivaGeneral);
        $arCotizacion->setVrTotal($totalGeneral);
        $em->persist($arCotizacion);
        $em->flush();
    }

    /**
     * @param $arCotizacion TurCotizacion
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCotizacion)
    {
        $em = $this->getEntityManager();
        if (!$arCotizacion->getEstadoAutorizado()) {
                $registros = $this->getEntityManager()->createQueryBuilder()->from(TurCotizacionDetalle::class, 'cd')
                ->select('COUNT(cd.codigoCotizacionDetallePk) AS registros')
                ->where('cd.codigoCotizacionFk = ' . $arCotizacion->getCodigoCotizacionPk())
                ->getQuery()->getSingleResult();
            if ($registros['registros'] > 0) {
                $arCotizacion->setEstadoAutorizado(1);
                $em->persist($arCotizacion);
                $em->flush();
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arCotizacion TurCotizacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCotizacion)
    {
        $em = $this->getEntityManager();
        if ($arCotizacion->getEstadoAutorizado()) {
            $arCotizacion->setEstadoAutorizado(0);
            $em->persist($arCotizacion);
            $em->flush();

        } else {
            Mensajes::error('La cotizacion no esta autorizado');
        }
    }

    /**
     * @param $arCotizacion TurCotizacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCotizacion){
        $em = $this->getEntityManager();
        if($arCotizacion->getEstadoAutorizado() == 1 ) {
            if($arCotizacion->getEstadoAprobado() == 0){
                $arCotizacion->setEstadoAprobado(1);
                $em->persist($arCotizacion);
                $em->flush();
            }else{
                Mensajes::error('La cotizacion ya esta aprobada');
            }

        } else {
            Mensajes::error('La cotizacion ya esta desautorizada');
        }
    }
}
