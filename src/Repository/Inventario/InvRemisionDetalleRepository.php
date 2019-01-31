<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;

class InvRemisionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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

    public function listarDetallesPendientes($codigoTercero)
    {
        $session = new Session();
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
            ->leftJoin('rd.itemRel', 'i')
            ->leftJoin('rd.remisionRel', 'r')
            ->leftJoin('r.terceroRel', 't')
            ->where('r.estadoAnulado = 0')
            ->andWhere('r.estadoAprobado = 1')
            ->andWhere('rd.cantidadPendiente > 0')
            ->andWhere('r.codigoTerceroFk = ' . $codigoTercero)
            ->orderBy('r.numero', 'ASC');
        if($session->get('filtroInvRemisionNumero')){
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroInvRemisionNumero')}'");
        }
        if($session->get('filtroInvRemisionDetalleLote')){
            $queryBuilder->andWhere("rd.loteFk = '{$session->get('filtroInvRemisionDetalleLote')}'");
        }
        if($session->get('filtroInvBodega')) {
            $queryBuilder->andWhere("rd.codigoBodegaFk = '{$session->get('filtroInvBodega')}'");
        }
        if($session->get('filtroInvCodigoTercero')) {
            $queryBuilder->andWhere("t.codigoTerceroPk = '{$session->get('filtroInvCodigoTercero')}'");
        }

        return $queryBuilder;
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
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('rd.remisionRel','r')
            ->leftJoin('r.remisionTipoRel','rt')
            ->leftJoin('r.terceroRel', 't')
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
        return $queryBuilder->getQuery();
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
            ->addSelect('rd.cantidadPendiente');
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
            ->addSelect("SUM(rd.cantidadOperada) AS cantidad")
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
            $cantidadAfectadaDevolucion = $em->getRepository(InvRemisionDetalle::class)->cantidadAfectaDovolucion($arRemisionDetalle['codigoRemisionDetallePk']);
            $cantidadAfectada+=$cantidadAfectadaDevolucion;
            $cantidadPendiente = $cantidad - $cantidadAfectada;
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
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadOperada')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
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

    public function cantidadAfectaDovolucion($codigoRemisionDetalle)
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