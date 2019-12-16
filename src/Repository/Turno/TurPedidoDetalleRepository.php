<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoDetalleCompuesto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurPedidoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.periodo')
            ->addSelect('pd.compuesto')
            ->addSelect('pd.codigoPuestoFk')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('pd.codigoModalidadFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('i.nombre as itemNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.itemRel', 'i')
            ->leftJoin('pd.puestoRel', 'p')
            ->where('pd.codigoPedidoFk = ' . $id);

        return $queryBuilder->getQuery()->getResult();
    }

    public function pendienteProgramar($raw)
    {

        $filtros = $raw['filtros'] ?? null;
        $codigoCliente = null;
        $codigoPedidoDetalle = null;
        $codigoPuesto = null;
        $anio = null;
        $mes = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $codigoPedidoDetalle = $filtros['codigoPedidoDetalle'] ?? null;
            $codigoPuesto = $filtros['codigoPuesto'] ?? null;
            $anio = $filtros['anio'] ?? null;
            $mes = $filtros['mes'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('cl.nombreCorto AS cliente')
            ->addSelect('pd.mes')
            ->addSelect('pd.anio')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('pu.nombre as puestoNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm')
            ->leftJoin('pd.pedidoRel', 'p')
            ->leftJoin('p.clienteRel', 'cl')
            ->leftJoin('pd.puestoRel', 'pu')
            ->where('pd.estadoProgramado = 0')
        ->orderBy('p.codigoPedidoPk', 'DESC');

        if ($codigoCliente) {
            $queryBuilder->andWhere("cl.codigoClientePk = '{$codigoCliente}'");
        }
        if ($codigoPedidoDetalle) {
            $queryBuilder->andWhere("pd.codigoPedidoDetallePk = '{$codigoPedidoDetalle}'");
        }
        if ($codigoPuesto) {
            $queryBuilder->andWhere("pd.codigoPuestoFk = '{$codigoPuesto}'");
        }
        if ($mes) {
            $queryBuilder->andWhere("pd.mes = '{$mes}'");
        }
        if ($anio) {
            $queryBuilder->andWhere("pd.anio = '{$anio}'");
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arPedido)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(TurPedido::class)->contarDetalles($arPedido->getCodigoPedidoPk()) > 0) {
            if(isset($arrControles['arrPrecioAjustado'])) {
                $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
            } else {
                $arrPrecioAjustado = [];
            }
            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoPedidoDetalle) {
                $arPedidoDetalle = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
                if(isset($arrPrecioAjustado[$codigoPedidoDetalle])){
                    $arPedidoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoPedidoDetalle]);
                }
                $arPedidoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoPedidoDetalle]);
                $arPedidoDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoPedidoDetalle]);
                $em->persist($arPedidoDetalle);
                $em->flush();
            }
            $em->getRepository(TurPedido::class)->liquidar($arPedido);
            $em->flush();
        }
    }

    /**
     * @param $arrDetallesSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($id, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurPedido::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigo);
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm');

        return $queryBuilder;
    }

    public function pendienteFacturar ($raw)
    {
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.diasReales')
            ->addSelect('pd.dias')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.vrPendiente')
            ->addSelect('pd.vrTotal')
            ->addSelect('p.numero')
            ->addSelect('p.fecha as pedidoFecha')
            ->addSelect('p.estadoAutorizado as pedidoEstadoAutorizado')
            ->addSelect('p.estadoProgramado as pedidoEstadoProgramado')
            ->addSelect('p.estadoFacturado as pedidoEstadoFacturado')
            ->addSelect('p.estadoAnulado as pedidoEstadoAnulado')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.codigoClientePk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('pu.nombre as puestoNombre')
            ->addSelect('cs.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('s.nombre as sectorNombre')
            ->addSelect('i.nombre as itemNombre')
            ->leftJoin("pd.pedidoRel", "p")
            ->leftJoin("p.clienteRel", "c")
            ->leftJoin("p.pedidoTipoRel", "pt")
            ->leftJoin("p.sectorRel", "s")
            ->leftJoin("pd.puestoRel", "pu")
            ->leftJoin("pd.conceptoRel", "cs")
            ->leftJoin("pd.itemRel", 'i')
            ->leftJoin("pd.modalidadRel", "m")
            ->where("pd.vrPendiente > 0")
            ->andWhere('p.estadoAutorizado = 1');

        if ($codigoCliente) {
            $queryBuilder->andWhere("c.codigoClientePk = '{$codigoCliente}'");
        }
        $queryBuilder->orderBy('pd.anio', 'DESC');
        $queryBuilder->addOrderBy('pd.mes', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function pendienteFacturarInforme($raw)
    {
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $numero = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoProgramado = null;
        $estadoFacturado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoProgramado = $filtros['estadoProgramado'] ?? null;
            $estadoFacturado = $filtros['estadoFacturado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.diasReales')
            ->addSelect('pd.dias')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.vrPendiente')
            ->addSelect('pd.vrTotal')
            ->addSelect('p.numero')
            ->addSelect('p.fecha')
            ->addSelect('p.estadoAutorizado as pedidoEstadoAutorizado')
            ->addSelect('p.estadoProgramado as pedidoEstadoProgramado')
            ->addSelect('p.estadoFacturado as pedidoEstadoFacturado')
            ->addSelect('p.estadoAnulado as pedidoEstadoAnulado')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.codigoClientePk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('pu.nombre as puesto')
            ->addSelect('cs.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('s.nombre as sectorNombre')
            ->leftJoin("pd.pedidoRel", "p")
            ->leftJoin("p.clienteRel", "c")
            ->leftJoin("p.pedidoTipoRel", "pt")
            ->leftJoin("p.sectorRel", "s")
            ->leftJoin("pd.puestoRel", "pu")
            ->leftJoin("pd.conceptoRel", "cs")
            ->leftJoin("pd.modalidadRel", "m")
            ->where("pd.vrPendiente > 0")
            ->andWhere('p.estadoAutorizado = 1');

        if ($codigoCliente) {
            $queryBuilder->andWhere("c.codigoClientePk = '{$codigoCliente}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("p.$numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("p.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("p.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAutorizado = 1");
                break;
        }
        switch ($estadoProgramado) {
            case '0':
                $queryBuilder->andWhere("p.$estadoProgramado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.$estadoProgramado = 1");
                break;
        }
        switch ($estadoFacturado) {
            case '0':
                $queryBuilder->andWhere("p.estadoFacturado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoFacturado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('pd.codigoPedidoDetallePk', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function corregirSaldos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.vrSubtotal');
        $arPedidoDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            $abonos = 0;
            $queryBuilder = $em->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
                ->Select("SUM(fd.vrSubtotal) as vrSubtotal")
                ->where("fd.codigoPedidoDetalleFk = " . $arPedidoDetalle['codigoPedidoDetallePk']);
            $arrResultado = $queryBuilder->getQuery()->getSingleResult();
            if ($arrResultado) {
                if ($arrResultado['vrSubtotal']) {
                    $abonos += $arrResultado['vrSubtotal'];
                }
            }

            $saldo = $arPedidoDetalle['vrSubtotal'] - $abonos;
            /** @var $arPedidoDetalleAct TurPedidoDetalle */
            $arPedidoDetalleAct = $em->getRepository(TurPedidoDetalle::class)->find($arPedidoDetalle['codigoPedidoDetallePk']);
            $arPedidoDetalleAct->setVrPendiente($saldo);
            $arPedidoDetalleAct->setVrAfectado($abonos);
            $em->persist($arPedidoDetalleAct);
        }
        $em->flush();
        return true;
    }

    public function liquidar($codigoPedidoDetalle)
    {
        $em = $this->getEntityManager();
        $strMensaje = "";

        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $subtotalGeneral = 0;
        $baseAiuTotalGeneral = 0;
        $douTotalServicio = 0;
        $totalIva = 0;
        $douTotalMinimoServicio = 0;
        $arPedidosDetalleCompuesto = $em->getRepository(TurPedidoDetalleCompuesto::class)->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));
        foreach ($arPedidosDetalleCompuesto as $arPedidoDetalleCompuesto) {
            $intDiasFacturar = 0;
            if ($arPedidoDetalleCompuesto->getPeriodo() == "D" || $arPedidoDetalleCompuesto->getDiasReales() == 1) {
                $intDias = $arPedidoDetalleCompuesto->getDiaHasta() - $arPedidoDetalleCompuesto->getDiaDesde();
                $intDias += 1;
                if ($arPedidoDetalleCompuesto->getDiaHasta() == 0 || $arPedidoDetalleCompuesto->getDiaDesde() == 0) {
                    $intDias = 0;
                }
            } else {
                $intDias = date("d", (mktime(0, 0, 0, $arPedidoDetalle->getPedidoRel()->getFecha()->format('m') + 1, 1, $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y')) - 1));
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;

            $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y-m') . "-" . $arPedidoDetalleCompuesto->getDiaDesde();
            $strFechaHasta = $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y-m') . "-" . $arPedidoDetalleCompuesto->getDiaHasta();
            $arFestivos = $em->getRepository(TurFestivo::class)->festivos($strFechaDesde, $strFechaHasta);
            $fecha = $strFechaDesde;
            for ($i = 0; $i < $intDias; $i++) {
                $nuevafecha = strtotime('+' . $i . ' day', strtotime($fecha));
                $nuevafecha = date('Y-m-j', $nuevafecha);
                $dateNuevaFecha = date_create($nuevafecha);
                $diaSemana = $dateNuevaFecha->format('N');
                if ($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                    $intDiasFestivos += 1;
                    if ($arPedidoDetalleCompuesto->getFestivo() == 1) {
                        $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                    }
                } else {
                    if ($diaSemana == 1) {
                        $intDiasOrdinarios += 1;
                        if ($arPedidoDetalleCompuesto->getLunes() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 2) {
                        $intDiasOrdinarios += 1;
                        if ($arPedidoDetalleCompuesto->getMartes() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 3) {
                        $intDiasOrdinarios += 1;
                        if ($arPedidoDetalleCompuesto->getMiercoles() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 4) {
                        $intDiasOrdinarios += 1;
                        if ($arPedidoDetalleCompuesto->getJueves() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 5) {
                        $intDiasOrdinarios += 1;
                        if ($arPedidoDetalleCompuesto->getViernes() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 6) {
                        $intDiasSabados += 1;
                        if ($arPedidoDetalleCompuesto->getSabado() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                    if ($diaSemana == 7) {
                        $intDiasDominicales += 1;
                        if ($arPedidoDetalleCompuesto->getDomingo() == 1) {
                            $intHorasRealesDiurnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas += $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas();
                        }
                    }
                }
            }
            if ($arPedidoDetalleCompuesto->getPeriodo() == 'M') {
                if ($arPedidoDetalle->getDiasReales() == 0) {
                    $intDiasOrdinarios = 0;
                    $intDiasSabados = 0;
                    $intDiasDominicales = 0;
                    $intDiasFestivos = 0;
                    if ($arPedidoDetalleCompuesto->getLunes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if ($arPedidoDetalleCompuesto->getMartes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if ($arPedidoDetalleCompuesto->getMiercoles() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if ($arPedidoDetalleCompuesto->getJueves() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if ($arPedidoDetalleCompuesto->getViernes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if ($arPedidoDetalleCompuesto->getSabado() == 1) {
                        $intDiasSabados = 4;
                    }
                    if ($arPedidoDetalleCompuesto->getDomingo() == 1) {
                        $intDiasDominicales = 4;
                    }
                    if ($arPedidoDetalleCompuesto->getFestivo() == 1) {
                        $intDiasFestivos = 2;
                    }
                    $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                    $intHorasDiurnasLiquidacion = $arPedidoDetalleCompuesto->getConceptoRel()->getHorasDiurnas() * $intTotalDias;
                    $intHorasNocturnasLiquidacion = $arPedidoDetalleCompuesto->getConceptoRel()->getHorasNocturnas() * $intTotalDias;
                } else {
                    $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                    $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
                }
            } else {
                $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;
            }
            $intHorasRealesDiurnas = $intHorasRealesDiurnas * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasRealesNocturnas = $intHorasRealesNocturnas * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasDiurnasLiquidacion = $intHorasDiurnasLiquidacion * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasNocturnasLiquidacion = $intHorasNocturnasLiquidacion * $arPedidoDetalleCompuesto->getCantidad();
            $douHoras = $intHorasRealesDiurnas + $intHorasRealesNocturnas;
            $arPedidoDetalleCompuestoActualizar = $em->getRepository(TurPedidoDetalleCompuesto::class)->find($arPedidoDetalleCompuesto->getCodigoPedidoDetalleCompuestoPk());

            $floValorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedidoDetalle->getPedidoRel()->getSectorRel()->getPorcentaje();
            if ($arPedidoDetalle->getPedidoRel()->getSectorRel()->getCodigoSectorPk() == 2 && $arPedidoDetalle->getPedidoRel()->getEstrato() >= 4) {
                $porcentajeModalidad = $arPedidoDetalleCompuesto->getModalidadRel()->getPorcentajeEspecial();
            } else {
                $porcentajeModalidad = $arPedidoDetalleCompuesto->getModalidadRel()->getPorcentaje();
            }
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $porcentajeModalidad / 100);
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 55.97) / 100) / 30) / 15;
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 44.03) / 100) / 30) / 9;
            if ($arPedidoDetalleCompuesto->getPeriodo() == 'M') {
                $precio = ($intHorasDiurnasLiquidacion * $floVrHoraDiurna) + ($intHorasNocturnasLiquidacion * $floVrHoraNocturna);
            } else {
                $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);
            }
            $precio = round($precio);
            $floVrMinimoServicio = $precio;

            $floVrServicio = 0;
            $subTotalDetalle = 0;
            if ($arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado() * $arPedidoDetalleCompuestoActualizar->getCantidad();
                $precio = $arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio * $arPedidoDetalle->getCantidad();
            }

            $subTotalDetalle = $floVrServicio;
            $subtotalGeneral += $subTotalDetalle;
            $baseAiuDetalle = $subTotalDetalle * $arPedidoDetalle->getPorcentajeBaseIva() / 100;
            $baseAiuTotalGeneral += $baseAiuDetalle;
            $ivaDetalle = $baseAiuDetalle * $arPedidoDetalleCompuesto->getPorcentajeIva() / 100;
            $totalIva += $ivaDetalle;
            $totalDetalle = $subTotalDetalle + $ivaDetalle;

            $arPedidoDetalleCompuestoActualizar->setVrSubtotal($subTotalDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrBaseIva($baseAiuDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrIva($ivaDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrTotal($totalDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arPedidoDetalleCompuestoActualizar->setVrPrecio($precio);

            $arPedidoDetalleCompuestoActualizar->setHoras($douHoras);
            $arPedidoDetalleCompuestoActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleCompuestoActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleCompuestoActualizar->setDias($intDias);

            $em->persist($arPedidoDetalleCompuestoActualizar);
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }

        $arPedidoDetalle->setHoras($douTotalHoras);
        $arPedidoDetalle->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedidoDetalle->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedidoDetalle->setVrPrecioMinimo($douTotalMinimoServicio);

        $total = $subtotalGeneral + $totalIva;
        $arPedidoDetalle->setVrSubtotal($subtotalGeneral);
        $arPedidoDetalle->setVrBaseIva($baseAiuTotalGeneral);
        $arPedidoDetalle->setVrIva($totalIva);
        $arPedidoDetalle->setVrTotal($total);
        $arPedidoDetalle->setVrPendiente(round($subtotalGeneral - $arPedidoDetalle->getVrAfectado(), 4));
        $em->persist($arPedidoDetalle);
        $em->flush();
        return true;
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
