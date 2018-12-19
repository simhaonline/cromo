<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvSucursal;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\Session;

class InvMovimientoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimientoDetalle::class);
    }

    public function listaDetalle($codigoMovimiento, $tipo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoItemFk')
            ->addSelect('md.loteFk')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.codigoBodegaDestinoFk')
            ->addSelect('md.fechaVencimiento')
            ->addSelect('md.cantidad')
            ->addSelect('md.vrPrecio')
            ->addSelect('md.vrSubtotal')
            ->addSelect('md.porcentajeDescuento')
            ->addSelect('md.vrDescuento')
            ->addSelect('md.porcentajeIva')
            ->addSelect('md.vrIva')
            ->addSelect('md.vrTotal')
            ->addSelect('md.codigoRemisionDetalleFk')
            ->addSelect('md.codigoPedidoDetalleFk')
            ->addSelect('md.codigoImportacionDetalleFk')
            ->addSelect('md.codigoMovimientoDetalleFk')
            ->addSelect('md.codigoImpuestoRetencionFk')
            ->addSelect('md.codigoImpuestoIvaFk')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('i.referencia AS itemReferencia')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoFk = ' . $codigoMovimiento);
        if ($tipo == "TRA") {
            $queryBuilder->andWhere('md.operacionInventario = 0');
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arMovimiento
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arMovimiento, $arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $em->getRepository(InvMovimientoDetalle::class)->find($codigoMovimientoDetalle);
                if ($arMovimientoDetalle) {
                    $em->remove($arMovimientoDetalle);
                }
            }
            $em->flush();
        }
    }

    public function duplicar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $em->getRepository(InvMovimientoDetalle::class)->find($codigoMovimientoDetalle);
                if ($arMovimientoDetalle) {
                    $arMovimientoDetalleNuevo = clone $arMovimientoDetalle;
                    $em->persist($arMovimientoDetalleNuevo);
                }
            }
            $em->flush();
        }
    }

    public function listarItems($nombreItem = '', $codigoItem = '')
    {
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvItem', 'ii')
            ->select('ii.codigoItemPk')
            ->addSelect('ii.nombre')
            ->addSelect('ii.cantidadExistencia')
            ->addSelect('ii.cantidadOrdenCompra')
            ->addSelect('ii.cantidadSolicitud')
            ->addSelect('ii.stockMinimo')
            ->addSelect('ii.stockMaximo')
            ->where('ii.codigoItemPk <> 0');
        if ($codigoItem != '') {
            $qb->andWhere("ii.codigoItemPk = {$codigoItem}");
        }
        if ($nombreItem != '') {
            $qb->andWhere("ii.nombre LIKE '%{$nombreItem}%' ");
        }
        $qb->orderBy('ii.codigoItemPk', 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @param $arrControles array
     * @param $arMovimiento InvMovimiento
     * @param $form FormInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arMovimiento)
    {
        $em = $this->getEntityManager();
        $this->getEntityManager()->persist($arMovimiento);
        if ($this->contarDetalles($arMovimiento->getCodigoMovimientoPk()) > 0) {
            $arrBodega = $arrControles['arrBodega'];
            $arrBodegaDestino = [];
            if ($arMovimiento->getCodigoDocumentoTipoFk() == "TRA") {
                $arrBodegaDestino = $arrControles['arrBodegaDestino'];
            }

            $arrLote = $arrControles['arrLote'];
            $arrCantidad = $arrControles['arrCantidad'];
            $arrPrecio = $arrControles['arrValor'];
            $arrPorcentajeDescuento = $arrControles['arrDescuento'];
            $arrCodigo = $arrControles['arrCodigo'];
            $arrFechaVencimiento = $arrControles['arrFechaVencimiento'];
            $mensajeError = "";
            foreach ($arrCodigo as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->find($codigoMovimientoDetalle);
                $arMovimientoDetalle->setCodigoBodegaFk($arrBodega[$codigoMovimientoDetalle]);
                if ($arMovimiento->getCodigoDocumentoTipoFk() == "TRA") {
                    $arMovimientoDetalle->setCodigoBodegaDestinoFk($arrBodegaDestino[$codigoMovimientoDetalle]);
                }
                $arMovimientoDetalle->setLoteFk($arrLote[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setCantidad($arrCantidad[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setVrPrecio($arrPrecio[$codigoMovimientoDetalle]);
                $fecha = $arrFechaVencimiento[$codigoMovimientoDetalle];
                $arMovimientoDetalle->setFechaVencimiento(date_create($fecha));
                if ($arMovimiento->getGeneraCostoPromedio()) {
                    $arMovimientoDetalle->setVrCosto($arrPrecio[$codigoMovimientoDetalle]);
                }
                $arMovimientoDetalle->setPorcentajeDescuento($arrPorcentajeDescuento[$codigoMovimientoDetalle]);
                $em->persist($arMovimientoDetalle);
            }
            if ($mensajeError == "") {
                $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error($mensajeError);
            }
        }

    }

    /**
     * @param $codigoMovimiento
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoMovimiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'imd')
            ->select("COUNT(imd.codigoMovimientoDetallePk)")
            ->where("imd.codigoMovimientoFk = {$codigoMovimiento} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function informacionRegenerarKardex($codigoItem)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.cantidadOperada')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.loteFk')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('m.estadoAutorizado = 1')
            ->andWhere('md.operacionInventario != 0')
            ->andWhere('md.codigoItemFk = ' . $codigoItem);
        return $queryBuilder->getQuery()->execute();
    }

    public function listaRegenerarExistencia()
    {
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoItemFk')
            ->addSelect('md.loteFk')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect("SUM(md.cantidadOperada) AS cantidad")
            ->leftJoin('md.itemRel', 'i')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('i.afectaInventario = 1')
            ->andWhere('md.operacionInventario <> 0')
            ->andWhere('m.estadoAprobado = 1')
            ->groupBy('md.codigoItemFk')
            ->addGroupBy('md.loteFk')
            ->addGroupBy('md.codigoBodegaFk');
        $arrExistencias = $queryBuilder->getQuery()->getResult();
        return $arrExistencias;
    }

    public function listaRegenerarExistenciaItem()
    {
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoItemFk')
            ->addSelect("SUM(md.cantidadOperada) AS cantidad")
            ->leftJoin('md.itemRel', 'i')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('i.afectaInventario = 1')
            ->andWhere('md.operacionInventario <> 0')
            ->andWhere('m.estadoAprobado = 1')
            ->groupBy('md.codigoItemFk');
        $arrExistencias = $queryBuilder->getQuery()->getResult();
        return $arrExistencias;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function regenerarExistencia()
    {
        $em = $this->getEntityManager();
        //Se limpian las existencias en los lotes y en el inventario
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvLote::class, 'l')
            ->set('l.cantidadDisponible', 0)
            ->set('l.cantidadExistencia', 0);
        $queryBuilder->getQuery()->execute();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvItem::class, 'i')
            ->set('i.cantidadExistencia', 0)
            ->set('i.cantidadDisponible', 0);
        $queryBuilder->getQuery()->execute();
        $mensajesError = "";

        $arMovimientosDetalles = $this->listaRegenerarExistencia();
        foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
            $arLote = $em->getRepository(InvLote::class)->findOneBy(array('codigoItemFk' => $arMovimientoDetalle['codigoItemFk'],
                'loteFk' => $arMovimientoDetalle['loteFk'], 'codigoBodegaFk' => $arMovimientoDetalle['codigoBodegaFk']));
            if ($arLote) {
                $arLote->setCantidadExistencia($arMovimientoDetalle['cantidad']);
                $arLote->setCantidadDisponible($arMovimientoDetalle['cantidad'] - $arLote->getCantidadRemisionada());
                $em->persist($arLote);
            } else {
                Mensajes::error('Misteriosamente un lote no esta creado' . $arMovimientoDetalle['codigoItemFk'] . " " . $arMovimientoDetalle['loteFk'] . " " . $arMovimientoDetalle['codigoBodegaFk']);
                break;
            }
        }
        $arMovimientosDetalles = $this->listaRegenerarExistenciaItem();
        foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
            $arItem = $em->getRepository(InvItem::class)->find($arMovimientoDetalle['codigoItemFk']);
            $arItem->setCantidadExistencia($arMovimientoDetalle['cantidad']);
            $arItem->setCantidadDisponible($arMovimientoDetalle['cantidad'] - $arItem->getCantidadRemisionada());
            $em->persist($arItem);
        }

        if ($mensajesError == "") {
            $em->flush();
        }
    }


    public function listaRegenerarCostos($codigoItem)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.cantidad')
            ->addSelect('md.cantidadOperada')
            ->addSelect('md.vrCosto')
            ->addSelect('md.vrPrecio')
            ->addSelect('md.porcentajeDescuento')
            ->addSelect('m.generaCostoPromedio')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('m.estadoAprobado = 1')
            ->andWhere('md.codigoItemFk = ' . $codigoItem);
        return $queryBuilder->getQuery()->execute();
    }

    public function regenerarCosto()
    {
        $em = $this->getEntityManager();
        $arItemes = $em->getRepository(InvItem::class)->listaRegenerar();
        foreach ($arItemes as $arItem) {
            $costoPromedio = 0;
            $existenciaAnterior = 0;
            $arMovimientosDetalles = $em->getRepository(InvMovimientoDetalle::class)->listaRegenerarCostos($arItem['codigoItemPk']);
            foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
                $arMovimientoDetalleAct = $em->getRepository(InvMovimientoDetalle::class)->find($arMovimientoDetalle['codigoMovimientoDetallePk']);
                if ($arMovimientoDetalle['generaCostoPromedio']) {
                    if ($existenciaAnterior != 0) {
                        $existenciaTotal = $existenciaAnterior + $arMovimientoDetalle['cantidad'];
                        $costoPromedio = (($existenciaAnterior * $costoPromedio) + (($arMovimientoDetalle['cantidad'] * $arMovimientoDetalle['vrCosto']))) / $existenciaTotal;
                    } else {
                        $precioBruto = $arMovimientoDetalle['vrPrecio'] - (($arMovimientoDetalle['vrPrecio'] * $arMovimientoDetalle['porcentajeDescuento']) / 100);
                        if ($arMovimientoDetalle['vrCosto'] != $precioBruto) {
                            $arMovimientoDetalleAct->setVrCosto($precioBruto);
                            $costoPromedio = $precioBruto;
                        } else {
                            $costoPromedio = $arMovimientoDetalle['vrCosto'];
                        }
                    }
                } else {
                    $arMovimientoDetalleAct->setVrCosto($costoPromedio);
                }
                $existenciaAnterior += $arMovimientoDetalle['cantidadOperada'];
                $arMovimientoDetalleAct->setCantidadSaldo($existenciaAnterior);
                $em->persist($arMovimientoDetalleAct);
            }
            $arItemAct = $em->getRepository(InvItem::class)->find($arItem['codigoItemPk']);
            $arItemAct->setVrCostoPromedio($costoPromedio);
            $arItemAct->setCantidadExistencia($existenciaAnterior);
            $em->persist($arItemAct);
        }
        $em->flush();
    }

    public function listaKardex()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoItemFk')
            ->addSelect('i.nombre AS nombreItem')
            ->addSelect('i.referencia as itemReferencia')
            ->addSelect('md.cantidad')
            ->addSelect('md.cantidadOperada')
            ->addSelect('md.cantidadSaldo')
            ->addSelect('md.vrCosto')
            ->addSelect('md.vrPrecio')
            ->addSelect('md.loteFk')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('m.fecha')
            ->addSelect('m.numero AS numeroMovimiento')
            ->addSelect('d.nombre AS nombreDocumento')
            ->addSelect('md.codigoRemisionDetalleFk')
            ->leftJoin('md.movimientoRel', 'm')
            ->leftJoin('m.documentoRel', 'd')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoDetallePk != 0')
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere('m.estadoAnulado = 0')
            ->andWhere('md.operacionInventario <> 0')
            ->orderBy('m.fecha', 'ASC');
        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("md.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
        }
        if ($session->get('filtroInvKardexLote') != '') {
            $queryBuilder->andWhere("md.loteFk = '{$session->get('filtroInvKardexLote')}' ");
        }
        if ($session->get('filtroInvKardexLoteBodega') != '') {
            $queryBuilder->andWhere("md.codigoBodegaFk = '{$session->get('filtroInvKardexLoteBodega')}' ");
        }
        if ($session->get('filtroInvCodigoDocumento')) {
            $queryBuilder->andWhere("m.codigoDocumentoFk = '{$session->get('filtroInvCodigoDocumento')}'");
        }
        return $queryBuilder;
    }

    public function informeDetalles()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('dt.nombre AS documentoTipo')
            ->addSelect('m.fecha AS fechaMovimiento')
            ->addSelect('m.numero AS movimientoNumero')
            ->addSelect('md.cantidad')
            ->addSelect('i.nombre AS item')
            ->addSelect('i.referencia AS referenciaItem')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.loteFk')
            ->addSelect('md.vrCosto')
            ->leftJoin('md.movimientoRel', 'm')
            ->leftJoin('m.documentoTipoRel', 'dt')
            ->leftJoin('md.itemRel', 'i')
            ->orderBy('m.fecha', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("md.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
        }
        if ($session->get('filtroInvLote') != '') {
            $queryBuilder->andWhere("md.loteFk = '{$session->get('filtroInvLote')}' ");
        }
        if ($session->get('filtroInvBodega') != '') {
            $queryBuilder->andWhere("md.codigoBodegaFk = '{$session->get('filtroInvBodega')}' ");
        }
        if ($session->get('filtroInvCodigoDocumento')) {
            $queryBuilder->andWhere("m.codigoDocumentoFk = '{$session->get('filtroInvCodigoDocumento')}'");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroInvMovimientoFechaDesde') != null) {
                $queryBuilder->andWhere("m.fecha >= '{$session->get('filtroInvMovimientoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("m.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroInvMovimientoFechaHasta') != null) {
                $queryBuilder->andWhere("m.fecha <= '{$session->get('filtroInvMovimientoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("m.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }


        return $queryBuilder;
    }

    public function registroFecha($codigoItem, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoItemFk')
            ->addSelect('md.vrCosto')
            ->addSelect('md.cantidadSaldo')
            ->addSelect('m.fecha')
            ->leftJoin('md.movimientoRel', 'm')
            ->where('md.codigoItemFk = ' . $codigoItem)
            ->andWhere('m.estadoAnulado = 0')
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere("m.fecha <= '" . $fechaHasta . " 23:59:59'")
            ->orderBy('m.fecha', 'DESC')
            ->setMaxResults(1);
        $resultado = $queryBuilder->getQuery()->getResult();
        return $resultado;
    }

    /**
     * @param $codigoPedidoDetalle
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cantidadAfectaPedido($codigoPedidoDetalle)
    {
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select("SUM(md.cantidad)")
            ->leftJoin("md.movimientoRel", "m")
            ->where("md.codigoPedidoDetalleFk = {$codigoPedidoDetalle} ")
            ->andWhere('m.estadoAutorizado = 1');
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }

    /**
     * @param $codigoOrdenDetalle
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cantidadAfectaOrden($codigoOrdenDetalle)
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select("SUM(r.cantidad)")
            ->leftJoin("md.movimientoRel", "m")
            ->where("md.codigoOrdenDetalleFk = {$codigoOrdenDetalle} ")
            ->andWhere('m.estadoAutorizado = 1');
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }

    /**
     * @param $codigoRemisionDetalle
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cantidadAfectaRemision($codigoRemisionDetalle)
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select("SUM(md.cantidad)")
            ->leftJoin("md.movimientoRel", "m")
            ->where("md.codigoRemisionDetalleFk = {$codigoRemisionDetalle} ")
            ->andWhere('m.estadoAutorizado = 1');
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }

    public function validarDetalles($codigoMovimiento)
    {
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoItemFk')
            ->addSelect('md.loteFk')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.codigoBodegaDestinoFk')
            ->addSelect('md.fechaVencimiento')
            ->addSelect('md.cantidad')
            ->addSelect('i.afectaInventario')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoFk=' . $codigoMovimiento);
        $arrDetalles = $queryBuilder->getQuery()->getResult();
        return $arrDetalles;
    }

    public function bodegaMovimiento($codigoMovimiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoBodegaFk')
            ->where('md.codigoMovimientoFk=' . $codigoMovimiento)
            ->groupBy('md.codigoBodegaFk');
        $arrDetalles = $queryBuilder->getQuery()->getResult();
        return $arrDetalles;
    }

    public function cuentaInventarioTransito($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('i.codigoCuentaInventarioTransitoFk')
            ->addSelect('SUM(md.vrSubtotal) as vrSubtotal')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoFk = ' . $codigo)
            ->groupBy('i.codigoCuentaInventarioTransitoFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function retencionFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoImpuestoRetencionFk')
            ->addSelect('SUM(md.vrRetencionFuente) as vrRetencionFuente')
            ->where('md.codigoMovimientoFk = ' . $codigo)
            ->andWhere('md.vrRetencionFuente > 0')
            ->groupBy('md.codigoImpuestoRetencionFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function ivaFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoImpuestoIvaFk')
            ->addSelect('SUM(md.vrIva) as vrIva')
            ->where('md.codigoMovimientoFk = ' . $codigo)
            ->andWhere('md.vrIva > 0')
            ->groupBy('md.codigoImpuestoIvaFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function ventaFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('i.codigoCuentaVentaFk')
            ->addSelect('SUM(md.vrSubtotal) as vrSubtotal')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoFk = ' . $codigo)
            ->groupBy('i.codigoCuentaVentaFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function actualizarImportacion($arMovimiento)
    {
        $em = $this->getEntityManager();
        if (!$arMovimiento->getEstadoContabilizado()) {
            $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
            foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                if ($arMovimientoDetalle->getCodigoImportacionDetalleFk()) {
                    $arMovimientoDetalle->setVrPrecio($arMovimientoDetalle->getImportacionDetalleRel()->getVrPrecioLocalTotal());
                    $em->persist($arMovimientoDetalle);
                }
            }
            $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
        } else {
            Mensajes::error('El documento no se puede actualizar porque esta contabilizado');
        }

    }
}