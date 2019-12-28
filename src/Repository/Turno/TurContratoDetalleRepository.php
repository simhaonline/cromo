<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurContratoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoContratoDetallePk')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('cd.cantidad')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->addSelect('cd.festivo')
            ->addSelect('cd.vrSalarioBase')
            ->addSelect('cd.vrPrecioMinimo')
            ->addSelect('cd.vrPrecioAjustado')
            ->addSelect('cd.porcentajeBaseIva')
            ->addSelect('cd.porcentajeIva')
            ->addSelect('cd.vrIva')
            ->addSelect('cd.vrSubtotal')
            ->addSelect('cd.fechaDesde')
            ->addSelect('cd.fechaHasta')
            ->addSelect('cd.horas')
            ->addSelect('cd.horasDiurnas')
            ->addSelect('cd.horasNocturnas')
            ->addSelect('cd.horaDesde')
            ->addSelect('cd.horaHasta')
            ->addSelect('cd.compuesto')
            ->addSelect('con.nombre as conceptoNombre')
            ->addSelect('i.nombre as itemNombre')
            ->addSelect('mod.nombre as modalidadNombre')
            ->leftJoin('cd.conceptoRel', 'con')
            ->leftJoin('cd.itemRel', 'i')
            ->leftJoin('cd.modalidadRel', 'mod')
            ->leftJoin('cd.puestoRel', 'p')
            ->where("cd.codigoContratoFk = {$id}")
            ->andWhere('cd.estadoTerminado = 0');

        return $queryBuilder->getQuery()->getResult();
    }

    public function cerrado($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoContratoDetallePk')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('cd.cantidad')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->addSelect('cd.festivo')
            ->addSelect('cd.vrSalarioBase')
            ->addSelect('cd.vrPrecioMinimo')
            ->addSelect('cd.vrPrecioAjustado')
            ->addSelect('cd.porcentajeBaseIva')
            ->addSelect('cd.porcentajeIva')
            ->addSelect('cd.vrIva')
            ->addSelect('cd.vrSubtotal')
            ->addSelect('cd.fechaDesde')
            ->addSelect('cd.fechaHasta')
            ->addSelect('cd.horas')
            ->addSelect('cd.horasDiurnas')
            ->addSelect('cd.horasNocturnas')
            ->addSelect('cd.horaDesde')
            ->addSelect('cd.horaHasta')
            ->addSelect('con.nombre as conceptoNombre')
            ->addSelect('mod.nombre as modalidadNombre')
            ->leftJoin('cd.conceptoRel', 'con')
            ->leftJoin('cd.modalidadRel', 'mod')
            ->leftJoin('cd.puestoRel', 'p')
            ->where("cd.codigoContratoFk = {$id}")
            ->andWhere('cd.estadoTerminado = 1');

        return $queryBuilder;
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arContratos
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arContratos)
    {
        $em = $this->getEntityManager();

        if ($this->getEntityManager()->getRepository(TurContrato::class)->contarDetalles($arContratos->getCodigoContratoPk()) > 0) {
            if(isset($arrControles['arrPrecioAjustado'])) {
                $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
            } else {
                $arrPrecioAjustado = [];
            }

            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoContratoDetalle) {
                $arContratoDetalle = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->find($codigoContratoDetalle);
                if(isset($arrPrecioAjustado[$codigoContratoDetalle])){
                    $arContratoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoContratoDetalle]);
                }
                $arContratoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoContratoDetalle]);
                $arContratoDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoContratoDetalle]);
                $em->persist($arContratoDetalle);
                $em->flush();
            }
            $em->getRepository(TurContrato::class)->liquidar($arContratos);
            $em->flush();
        }
    }

    /**
     * @param $arRegistro TurContrato
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrDetallesSeleccionados, $id)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurContrato::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                            $this->getEntityManager()->flush();
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd')
            ->select('cd')
            ->leftJoin('cd.contratoRel', 'c');
        if ($session->get('filtroRhuInformeContratoDetalleCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClienteFk  = '{$session->get('filtroRhuInformeContratoDetalleCodigoCliente')}'");
        }
        if ($session->get('filtroRhuInformeContratoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("cd.fechaDesde >= '{$session->get('filtroRhuInformeContratoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformeContratoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("cd.fechaHasta <= '{$session->get('filtroRhuInformeContratoDetalleFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function cerrarSeleccionados($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($codigo);
                if ($arContratoDetalle->getEstadoCerrado() == 0) {
                    $arContratoDetalle->setEstadoCerrado(1);
                }
            }
            $em->flush();
        }
    }

    public function abrirSeleccionados($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($codigo);
                if ($arContratoDetalle->getEstadoCerrado() == 1) {
                    $arContratoDetalle->setEstadoCerrado(0);
                }
            }
            $em->flush();
        }
    }

    public function liquidar($codigoContratoDetalle)
    {
        $em = $this->getEntityManager();
        $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($codigoContratoDetalle);
        $intCantidad = 0;
        $precio = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;

        $duoTotalBaseAiuDetalle = 0;
        $duoTotalIvaDetalle = 0;
        $arContratosDetalleCompuesto = $em->getRepository(TurContratoDetalleCompuesto::class)->findBy(array('codigoContratoDetalleFk' => $codigoContratoDetalle));
        foreach ($arContratosDetalleCompuesto as $arContratoDetalleCompuesto) {
            $intDias = 30;
            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if ($arContratoDetalleCompuesto->getPeriodo() == 'M') {
                if ($arContratoDetalleCompuesto->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if ($arContratoDetalleCompuesto->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if ($arContratoDetalleCompuesto->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if ($arContratoDetalleCompuesto->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if ($arContratoDetalleCompuesto->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if ($arContratoDetalleCompuesto->getSabado() == 1) {
                    $intDiasSabados = 4;
                }
                if ($arContratoDetalleCompuesto->getDomingo() == 1) {
                    $intDiasDominicales = 4;
                }
                if ($arContratoDetalleCompuesto->getFestivo() == 1) {
                    $intDiasFestivos = 2;
                }
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arContratoDetalleCompuesto->getConceptoRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arContratoDetalleCompuesto->getConceptoRel()->getHorasNocturnas() * $intTotalDias;
            }

            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas) * $arContratoDetalleCompuesto->getCantidad();
            $arContratoDetalleCompuestoActualizar = $em->getRepository(TurContratoDetalleCompuesto::class)->find($arContratoDetalleCompuesto->getCodigoContratoDetalleCompuestoPk());
            $floValorBaseServicio = $arContratoDetalle->getVrSalarioBase() * $arContratoDetalle->getContratoRel()->getSectorRel()->getPorcentaje();
            if ($arContratoDetalle->getContratoRel()->getCodigoSectorFk() == 'RES' && $arContratoDetalle->getContratoRel()->getEstrato() >= 4) {
                $porcentajeModalidad = $arContratoDetalleCompuesto->getModalidadRel()->getPorcentajeEspecial();
            } else {
                $porcentajeModalidad = $arContratoDetalleCompuesto->getModalidadRel()->getPorcentaje();
            }
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $porcentajeModalidad / 100);
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 55.97) / 100) / 30) / 15;
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 44.03) / 100) / 30) / 9;

            $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);
            $precio = round($precio);
            $floVrMinimoServicio = $precio;
            if ($arContratoDetalleCompuestoActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arContratoDetalleCompuestoActualizar->getVrPrecioAjustado() * $arContratoDetalleCompuesto->getCantidad();
                $precio = $arContratoDetalleCompuestoActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio * $arContratoDetalleCompuestoActualizar->getCantidad();
            }
            $subTotalDetalle = $floVrServicio;
            $baseAiuDetalle = $subTotalDetalle * $arContratoDetalle->getPorcentajeBaseIva() / 100;
            $duoTotalBaseAiuDetalle += $baseAiuDetalle;
            $ivaDetalle = $baseAiuDetalle * $arContratoDetalleCompuesto->getPorcentajeIva() / 100;
            $duoTotalIvaDetalle += $ivaDetalle;
            $totalDetalle = $subTotalDetalle + $ivaDetalle;


            $arContratoDetalleCompuestoActualizar->setVrSubtotal($subTotalDetalle);
            $arContratoDetalleCompuestoActualizar->setVrBaseAiu($baseAiuDetalle);
            $arContratoDetalleCompuestoActualizar->setVrIva($ivaDetalle);
            $arContratoDetalleCompuestoActualizar->setVrTotalDetalle($totalDetalle);
            $arContratoDetalleCompuestoActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arContratoDetalleCompuestoActualizar->setVrPrecio($precio);

            $arContratoDetalleCompuestoActualizar->setHoras($douHoras);
            $arContratoDetalleCompuestoActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arContratoDetalleCompuestoActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arContratoDetalleCompuestoActualizar->setDias($intDias);

            $em->persist($arContratoDetalleCompuestoActualizar);
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }

        $arContratoDetalle->setHoras($douTotalHoras);
        $arContratoDetalle->setHorasDiurnas($douTotalHorasDiurnas);
        $arContratoDetalle->setHorasNocturnas($douTotalHorasNocturnas);
        $arContratoDetalle->setVrPrecioMinimo($douTotalMinimoServicio);
        $subtotal = $douTotalServicio;
        $total = $subtotal + $duoTotalIvaDetalle;
        $arContratoDetalle->setVrSubtotal($subtotal);
        $arContratoDetalle->setVrBaseAiu($duoTotalBaseAiuDetalle);
        $arContratoDetalle->setVrIva($duoTotalIvaDetalle);
        $arContratoDetalle->setVrTotalDetalle($total);
        $em->persist($arContratoDetalle);
        $em->flush();
        return true;
    }

}
