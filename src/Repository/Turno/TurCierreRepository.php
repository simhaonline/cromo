<?php

namespace App\Repository\Turno;


use App\Entity\Crm\CrmVisita;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurCostoEmpleado;
use App\Entity\Turno\TurCostoEmpleadoServicio;
use App\Entity\Turno\TurCostoServicio;
use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurDistribucionEmpleado;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurCierre;
use App\Entity\Turno\TurCierreDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurCierreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurCierre::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros){
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCierre::class, 'c')
            ->select('c.codigoCierrePk')
            ->addSelect('c.anio')
            ->addSelect('c.mes')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->addSelect('c.usuario');

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("pestadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAnulado = 1");
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
                $arRegistro = $this->getEntityManager()->getRepository(TurCierre::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurCierreDetalle::class)->findBy(['codigoCierreFk' => $arRegistro->getCodigoCierrePk()])) <= 0) {
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
     * @param $arCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arCierre)
    {
        /**
         * @var $arCierre TurCierre
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
        $arCierreDetalles = $this->getEntityManager()->getRepository(TurCierreDetalle::class)->findBy(array('codigoCierreFk' => $arCierre->getCodigoCierrePk()));
        /** @var $arContratoDetalle TurContratoDetalle */
        foreach ($arCierreDetalles as $arCierreDetalle) {
            if ($arCierreDetalle->getEstadoTerminado() == 0) {
                /** @var $arConcepto TurConcepto */
                $arConcepto = $arCierreDetalle->getConceptoRel();
                if ($arCierreDetalle->getPeriodo() == "D") {
                    $dias = $arCierreDetalle->getFechaDesde()->diff($arCierreDetalle->getFechaHasta());
                    $dias = $dias->format('%a');
                    $dias += 1;
                    if ($arCierreDetalle->getFechaHasta()->format('d') == '31') {
                        $dias = $dias - 1;
                    }
                    if ($arCierreDetalle->getDia31() == 1) {
                        if ($arCierreDetalle->getFechaHasta()->format('d') == '31') {
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
                if ($arCierreDetalle->getPeriodo() == "M") {
                    if ($arCierreDetalle->getLunes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCierreDetalle->getMartes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCierreDetalle->getMiercoles()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCierreDetalle->getJueves()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCierreDetalle->getViernes()) {
                        $diasOrdinarios += 4;
                    }
                    if ($arCierreDetalle->getSabado()) {
                        $diasSabados = 4;
                    }
                    if ($arCierreDetalle->getDomingo()) {
                        $diasDominicales = 4;
                    }
                    if ($arCierreDetalle->getFestivo()) {
                        $diasFestivos = 2;
                    }
                    $totalDias = $diasOrdinarios + $diasSabados + $diasDominicales + $diasFestivos;
                    $horasRealesDiurnas = $arConcepto->getHorasDiurnas() * $totalDias;
                    $horasRealesNocturnas = $arConcepto->getHorasNocturnas() * $totalDias;
                } else {
                    $arFestivos = $em->getRepository(TurFestivo::class)->fecha($arCierreDetalle->getFechaDesde()->format('Y-m-d'), $arCierreDetalle->getFechaHasta()->format('Y-m-d'));
                    $fecha = $arCierreDetalle->getFechaDesde()->format('Y-m-j');
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
                                if ($arCierreDetalle->getLunes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 2) {
                                $diasOrdinarios += 1;
                                if ($arCierreDetalle->getMartes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 3) {
                                $diasOrdinarios += 1;
                                if ($arCierreDetalle->getMiercoles() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 4) {
                                $diasOrdinarios += 1;
                                if ($arCierreDetalle->getJueves() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 5) {
                                $diasOrdinarios += 1;
                                if ($arCierreDetalle->getViernes() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 6) {
                                $diasSabados += 1;
                                if ($arCierreDetalle->getSabado() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                            if ($diaSemana == 7) {
                                $diasDominicales += 1;
                                if ($arCierreDetalle->getDomingo() == 1) {
                                    $horasRealesDiurnas += $arConcepto->getHorasDiurnas();
                                    $horasRealesNocturnas += $arConcepto->getHorasNocturnas();
                                }
                            }
                        }
                    }
                }

                $horasRealesDiurnas =  $horasRealesDiurnas * $arCierreDetalle->getCantidad();
                $horasRealesNocturnas = $horasRealesNocturnas * $arCierreDetalle->getCantidad();
                $horas = $horasRealesDiurnas + $horasRealesNocturnas;
                $valorBaseServicio = $arCierreDetalle->getVrSalarioBase() * $arCierre->getSectorRel()->getPorcentaje();
                if ($arCierre->getCodigoSectorFk() == "D" && $arCierre->getEstrato() >= 4) {
                    //Cambiar porcentaje para residencial mayor a estrato 4
                    //$porcentajeModalidad = $arContratoDetalle->getModalidadServicioRel()->getPorcentajeEspecial();
                    $porcentajeModalidad = $arCierreDetalle->getModalidadRel()->getPorcentaje();
                } else {
                    $porcentajeModalidad = $arCierreDetalle->getModalidadRel()->getPorcentaje();
                }


                $valorBaseServicioMes = $valorBaseServicio + ($valorBaseServicio * $porcentajeModalidad / 100);
                $valorHoraDiurna = ((($valorBaseServicioMes * 55.97) / 100) / 30) / 15;
                $valorHoraNocturna = ((($valorBaseServicioMes * 44.03) / 100) / 30) / 9;

                $precio = ($horasRealesDiurnas * $valorHoraDiurna) + ($horasRealesNocturnas * $valorHoraNocturna);
                $valorMinimoServicio = $precio;


                if ($arCierreDetalle->getVrPrecioAjustado() != 0) {
                    $valorServicio = $arCierreDetalle->getVrPrecioAjustado() * $arCierreDetalle->getCantidad();
                    $precio = $arCierreDetalle->getVrPrecioAjustado();
                } else {
                    $valorServicio = $valorMinimoServicio;
                }


                $subTotalDetalle = $valorServicio;
                $baseAiuDetalle = $subTotalDetalle * ($arCierreDetalle->getPorcentajeBaseIva() / 100);
                $ivaDetalle = $baseAiuDetalle * ($arCierreDetalle->getPorcentajeIva() / 100);
                $totalDetalle = $subTotalDetalle + $ivaDetalle;

                $arCierreDetalle->setVrSubtotal($subTotalDetalle);
                $arCierreDetalle->setVrBaseAiu($baseAiuDetalle);
                $arCierreDetalle->setVrIva($ivaDetalle);
                $arCierreDetalle->setVrTotalDetalle($totalDetalle);
                $arCierreDetalle->setVrPrecioMinimo($valorMinimoServicio);
                $arCierreDetalle->setVrPrecio($precio);
                $arCierreDetalle->setHoras($horas);
                $arCierreDetalle->setHorasDiurnas($horasRealesDiurnas);
                $arCierreDetalle->setHorasNocturnas($horasRealesNocturnas);
                $arCierreDetalle->setDias($dias);
                $em->persist($arCierreDetalle);

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

        $arCierre->setHoras($totalHoras);
        $arCierre->setHorasDiurnas($totalHorasDiurnas);
        $arCierre->setHorasNocturnas($totalHorasNocturnas);
        $arCierre->setVrTotalServicio($totalServicio);
        $arCierre->setVrTotalPrecioMinimo($totalMinimoServicio);
        $arCierre->setVrTotalCosto($totalCostoCalculado);
        $arCierre->setVrSubtotal($subtotalGeneral);
        $arCierre->setVrBaseAiu($baseAuiGeneral);
        $arCierre->setVrIva($ivaGeneral);
        $arCierre->setVrTotal($totalGeneral);
        $em->persist($arCierre);
        $em->flush();
    }

    /**
     * @param $arCierre TurCierre
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCierre)
    {
        $em = $this->getEntityManager();
        if (!$arCierre->getEstadoAutorizado()) {
            $em->getRepository(TurDistribucion::class)->generar($arCierre->getAnio(), $arCierre->getMes());
            $em->getRepository(TurDistribucionEmpleado::class)->generar($arCierre);
            $em->getRepository(TurCostoEmpleado::class)->generar($arCierre);
            $em->getRepository(TurCostoServicio::class)->generar($arCierre);
            $arCierre->setEstadoAutorizado(1);
            $em->persist($arCierre);
            $em->flush();
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arCierre TurCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCierre)
    {
        $em = $this->getEntityManager();
        if ($arCierre->getEstadoAutorizado()) {
            $em->createQueryBuilder()->delete(TurDistribucion::class, 'd')
                ->where("d.anio = " . $arCierre->getAnio() . " AND d.mes = " . $arCierre->getMes())->getQuery()->execute();
            $em->createQueryBuilder()->delete(TurDistribucionEmpleado::class, 'de')
                ->where("de.anio = " . $arCierre->getAnio() . " AND de.mes = " . $arCierre->getMes())->getQuery()->execute();
            $em->createQueryBuilder()->delete(TurCostoEmpleado::class, 'ce')
                ->where("ce.codigoCierreFk = " . $arCierre->getCodigoCierrePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(TurCostoEmpleadoServicio::class, 'ces')
                ->where("ces.codigoCierreFk = " . $arCierre->getCodigoCierrePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(TurCostoServicio::class, 'cs')
                ->where("cs.codigoCierreFk = " . $arCierre->getCodigoCierrePk())->getQuery()->execute();
            $arCierre->setEstadoAutorizado(0);
            $em->persist($arCierre);
            $em->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arCierre TurCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCierre){
        $em = $this->getEntityManager();
        if($arCierre->getEstadoAutorizado() == 1 ) {
            if($arCierre->getEstadoAprobado() == 0){
                $arCierre->setEstadoAprobado(1);
                $em->persist($arCierre);
                $em->flush();
            }else{
                Mensajes::error('El cierre ya esta aprobada');
            }

        } else {
            Mensajes::error('El cierre ya esta desautorizada');
        }
    }




}
