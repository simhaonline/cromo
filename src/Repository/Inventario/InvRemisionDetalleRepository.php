<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;

class InvRemisionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvRemisionDetalle::class);
    }

    public function remision($codigoRemision)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class,'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoRemisionFk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.fechaVencimiento')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('i.nombre as itemNombre')
            ->addSelect('i.referencia as itemReferencia')
            ->addSelect('m.nombre as itemMarcaNombre')
            ->addSelect('rd.cantidadAfectada')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.porcentajeIva')
            ->addSelect('rd.vrIva')
            ->addSelect('rd.vrSubtotal')
            ->addSelect('rd.vrNeto')
            ->addSelect('rd.codigoPedidoDetalleFk')
            ->addSelect('rd.codigoRemisionDetalleFk')
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('i.marcaRel','m')
            ->addSelect('rd.vrTotal')
            ->where("rd.codigoRemisionFk = {$codigoRemision}");

        return $queryBuilder;
    }

    public function informeDetalles()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class,'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoRemisionFk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('i.nombre as itemNombre')
            ->addSelect('i.referencia as itemReferencia')
            ->addSelect('m.nombre as itemMarcaNombre')
            ->addSelect('rd.cantidadAfectada')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.porcentajeIva')
            ->addSelect('rd.vrIva')
            ->addSelect('rd.vrSubtotal')
            ->addSelect('rd.vrNeto')
            ->leftJoin('rd.remisionRel','r')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('i.marcaRel','m')
        ->orderBy('r.numero', 'ASC');
        if($session->get('filtroInvInformeRemisionDetalleCodigoTercero')){
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroInvInformeRemisionDetalleCodigoTercero')}");
        }
        if($session->get('filtroInvBodega')) {
            $queryBuilder->andWhere("rd.codigoBodegaFk = '{$session->get('filtroInvBodega')}'");
        }
        if($session->get('filtroInvRemisionDetalleLote')){
            $queryBuilder->andWhere("rd.loteFk = '{$session->get('filtroInvRemisionDetalleLote')}'");
        }

        return $queryBuilder;
    }

    /**
     * @param $arRemision
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arRemision, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arRemision->getEstadoAutorizado()) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes($raw,$codigoTercero)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $tercero = null;
        $numero = null;
        $lote = null;
        $bodega = null;
        if ($filtros) {
            $tercero =  $filtros['tercero'] ??null;
            $numero =  $filtros['numero'] ??null;
            $lote =  $filtros['lote'] ??null;
            $bodega =  $filtros['bodega'] ??null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('rt.nombre as tipo')
            ->leftJoin('rd.itemRel', 'i')
            ->leftJoin('rd.remisionRel', 'r')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.remisionTipoRel','rt')
            ->where('r.estadoAnulado = 0')
            ->andWhere('r.estadoAprobado = 1')
            ->andWhere('rd.cantidadPendiente > 0')
            ->andWhere('r.codigoTerceroFk = ' . $codigoTercero)
            ->andWhere('rt.devolucion = 0')
            ->orderBy('r.numero', 'ASC');
        if($numero){
            $queryBuilder->andWhere("r.numero = '{$numero}'");
        }
        if($lote){
            $queryBuilder->andWhere("rd.loteFk = '{$lote}'");
        }
        if($bodega) {
            $queryBuilder->andWhere("rd.codigoBodegaFk = '{$bodega}'");
        }
        if($tercero) {
            $queryBuilder->andWhere("t.codigoTerceroPk = '{$tercero}'");
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function pendientes(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class,'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoRemisionFk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.cantidad')
            ->addSelect('i.nombre')
            ->addSelect('r.numero')
            ->addSelect('r.fecha as fechaPedido')
            ->addSelect('rt.nombre as pedidoTipo')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('a.nombre as asesorNombre')
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('rd.remisionRel','r')
            ->leftJoin('r.remisionTipoRel','rt')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.asesorRel', 'a')
            ->where('r.estadoAprobado = 1')
            ->andWhere('r.estadoAnulado = 0')
            ->andWhere('rd.cantidadPendiente > 0')
        ->andWhere('rt.devolucion = 0');
        if ($session->get('filtroInvRemisionPendienteFechaDesde') != null) {
            $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroInvRemisionPendienteFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroInvRemisionPendienteFechaHasta') != null) {
            $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroInvRemisionPendienteFechaHasta')} 23:59:59'");
        }
        if($session->get('filtroInvInformeRemisionPendienteCodigoTercero')){
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroInvInformeRemisionPendienteCodigoTercero')}");
        }
        if($session->get('filtroInvRemisionTipo')){
            $queryBuilder->andWhere("r.codigoRemisionTipoFk = '{$session->get('filtroInvRemisionTipo')}'");
        }
        if($session->get('filtroInvBodega')) {
            $queryBuilder->andWhere("rd.codigoBodegaFk = '{$session->get('filtroInvBodega')}'");
        }
        if($session->get('filtroInvRemisionDetalleLote')){
            $queryBuilder->andWhere("rd.loteFk = '{$session->get('filtroInvRemisionDetalleLote')}'");
        }
        if($session->get('filtroInvCodigoAsesor')){
            $queryBuilder->andWhere("r.codigoAsesorFk = '{$session->get('filtroInvCodigoAsesor')}'");
        }
        return $queryBuilder;
    }

    public function validarDetalles($codigoRemision){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.cantidad')
            ->addSelect('i.afectaInventario')
            ->leftJoin('rd.itemRel', 'i')
            ->where('rd.codigoRemisionFk=' . $codigoRemision);
        $arrDetalles = $queryBuilder->getQuery()->getResult();
        return $arrDetalles;
    }

    public function contarDetalles($codigoRemision)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select("COUNT(rd.codigoRemisionDetallePk)")
            ->where("rd.codigoRemisionFk = {$codigoRemision} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function listaRegenerarCantidadAfectada()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadAfectada')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.operacionInventario');
        $arrRemisionsDetalles = $queryBuilder->getQuery()->getResult();
        return $arrRemisionsDetalles;
    }

    public function listaRegenerarRemisionada(){
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoItemFk')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect("SUM(rd.cantidadPendiente) AS cantidad")
            ->leftJoin('rd.itemRel', 'i')
            ->leftJoin('rd.remisionRel', 'r')
            ->where('i.afectaInventario = 1')
            ->andWhere('rd.operacionInventario = 1')
            ->andWhere('r.estadoAprobado = 1')
            ->groupBy('rd.codigoItemFk')
            ->addGroupBy('rd.loteFk')
            ->addGroupBy('rd.codigoBodegaFk');
        $arrExistencias = $queryBuilder->getQuery()->getResult();
        return $arrExistencias;
    }

    public function listaRegenerarRemisionadaItem(){
        $cantidad = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoItemFk')
            ->addSelect("SUM(rd.cantidadPendiente) AS cantidad")
            ->leftJoin('rd.itemRel', 'i')
            ->leftJoin('rd.remisionRel', 'r')
            ->where('i.afectaInventario = 1')
            ->andWhere('rd.operacionInventario <> 0')
            ->andWhere('r.estadoAprobado = 1')
            ->groupBy('rd.codigoItemFk');
        $arrExistencias = $queryBuilder->getQuery()->getResult();
        return $arrExistencias;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerarCantidadAfectada() {
        $em = $this->getEntityManager();
        $arRemisionsDetalles = $em->getRepository(InvRemisionDetalle::class)->listaRegenerarCantidadAfectada();
        foreach ($arRemisionsDetalles as $arRemisionDetalle) {
            $cantidad = $arRemisionDetalle['cantidad'];
            $cantidadAfectada = $em->getRepository(InvMovimientoDetalle::class)->cantidadAfectaRemision($arRemisionDetalle['codigoRemisionDetallePk']);
            $cantidadAfectadaDevolucion = $em->getRepository(InvRemisionDetalle::class)->cantidadAfectaDevolucion($arRemisionDetalle['codigoRemisionDetallePk']);
            $cantidadAfectada += $cantidadAfectadaDevolucion;
            $cantidadPendiente = $cantidad - $cantidadAfectada;
            //Para que no queden pendientes las devoluciones de remision
            if($arRemisionDetalle['operacionInventario'] == -1) {
                $cantidadPendiente = 0;
            }
            if($cantidadAfectada != $arRemisionDetalle['cantidadAfectada'] || $cantidadPendiente != $arRemisionDetalle['cantidadPendiente']) {
                $arRemisionDetalleAct = $em->getRepository(InvRemisionDetalle::class)->find($arRemisionDetalle['codigoRemisionDetallePk']);
                $arRemisionDetalleAct->setCantidadAfectada($cantidadAfectada);
                $arRemisionDetalleAct->setCantidadPendiente($cantidadPendiente);
                $em->persist($arRemisionDetalleAct);

            }
        }
        $em->flush();

        $mensajesError = "";
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvLote::class, 'l')
            ->set('l.cantidadRemisionada', 0);
        $queryBuilder->getQuery()->execute();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvItem::class, 'i')
            ->set('i.cantidadRemisionada', 0);
        $queryBuilder->getQuery()->execute();

        $arRemisionDetalles = $this->listaRegenerarRemisionada();
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
//            if($arRemisionDetalle['codigoItemFk'] == 76 && $arRemisionDetalle['loteFk'] == 'AA190917-17' && $arRemisionDetalle['codigoBodegaFk'] == 'BUC') {
//                echo "hola";
//            }
            $arLote = $em->getRepository(InvLote::class)->findOneBy(array('codigoItemFk' => $arRemisionDetalle['codigoItemFk'],
                'loteFk' => $arRemisionDetalle['loteFk'], 'codigoBodegaFk' => $arRemisionDetalle['codigoBodegaFk']));
            if($arLote) {
                $arLote->setCantidadRemisionada($arRemisionDetalle['cantidad']);
                $arLote->setCantidadDisponible($arLote->getCantidadExistencia() - $arLote->getCantidadRemisionada());
                $em->persist($arLote);
            } else {
                Mensajes::error('Misteriosamente un lote no esta creado' . $arRemisionDetalle['codigoItemFk'] . " " . $arRemisionDetalle['loteFk'] . " " . $arRemisionDetalle['codigoBodegaFk']);
                break;
            }
        }
        $arRemisionDetalles = $this->listaRegenerarRemisionadaItem();
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            $arItem = $em->getRepository(InvItem::class)->find($arRemisionDetalle['codigoItemFk']);
            $arItem->setCantidadRemisionada($arRemisionDetalle['cantidad']);
            $arItem->setCantidadDisponible($arItem->getCantidadExistencia() - $arItem->getCantidadRemisionada());
            $em->persist($arItem);
        }

        if($mensajesError == "") {
            $em->flush();
        }

    }

    public function listaKardex()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('i.nombre AS nombreItem')
            ->addSelect('i.referencia')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadOperada')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.fechaVencimiento')
            ->addSelect('r.fecha')
            ->addSelect('r.numero AS numeroRemision')
            ->addSelect('rt.nombre AS remisionTipo')
            ->leftJoin('rd.remisionRel', 'r')
            ->leftJoin('r.remisionTipoRel', 'rt')
            ->leftJoin('rd.itemRel', 'i')
            ->where('rd.codigoRemisionDetallePk != 0')
            ->andWhere('r.estadoAprobado = 1')
            ->andWhere('r.estadoAnulado = 0')
            ->andWhere('rd.operacionInventario <> 0')
            ->orderBy('r.fecha', 'ASC');
        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("rd.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
        }
        if ($session->get('filtroInvLote') != '') {
            $queryBuilder->andWhere("rd.loteFk = '{$session->get('filtroInvLote')}' ");
        }
        if ($session->get('filtroInvBodega') != '') {
            $queryBuilder->andWhere("rd.codigoBodegaFk = '{$session->get('filtroInvBodega')}' ");
        }
        if ($session->get('filtroInvCodigoRemisionTipo')) {
            $queryBuilder->andWhere("r.codigoRemisionTipoFk = '{$session->get('filtroInvCodigoRemisionTipo')}'");
        }
        if ($session->get('filtroInvKardexFechaDesde') != null) {
            $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroInvKardexFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroInvKardexFechaHasta') != null) {
            $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroInvKardexFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function bodegaRemision($codigoRemision){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoBodegaFk')
            ->where('rd.codigoRemisionFk=' . $codigoRemision)
            ->groupBy('rd.codigoBodegaFk');
        $arrDetalles = $queryBuilder->getQuery()->getResult();
        return $arrDetalles;
    }

    public function cantidadAfectaDevolucion($codigoRemisionDetalle)
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $queryBuilder = $em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select("SUM(rd.cantidad)")
            ->leftJoin("rd.remisionRel", "r")
            ->where("rd.codigoRemisionDetalleFk = {$codigoRemisionDetalle} ")
            ->andWhere('r.estadoAutorizado = 1')
        ->andWhere('r.estadoAprobado = 1')
        ->andWhere('r.estadoAnulado = 0');
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }

}