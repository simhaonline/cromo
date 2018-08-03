<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
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

    /**
     * @param $arMovimiento InvMovimiento
     * @param $arrSeleccionados array
     */
    public function eliminar($arMovimiento, $arrSeleccionados)
    {
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->find($codigoMovimientoDetalle);
                if ($arMovimientoDetalle) {
                    if ($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()) {
                        $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findOneBy(['codigoOrdenCompraDetallePk' => $arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()]);
                        if ($arOrdenCompraDetalle) {
                            $arOrdenCompraDetalle->setCantidadPendiente($arOrdenCompraDetalle->getCantidadPendiente() + $arMovimientoDetalle->getCantidad());
                            $this->_em->persist($arOrdenCompraDetalle);
                        }
                    }
                    $this->_em->remove($arMovimientoDetalle);
                }
            }
            $this->_em->flush();
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
        $this->getEntityManager()->persist($arMovimiento);
        if ($this->contarDetalles($arMovimiento->getCodigoMovimientoPk()) > 0) {
            $arrBodega = $arrControles['arrBodega'];
            $arrLote = $arrControles['arrLote'];
            $arrCantidad = $arrControles['arrCantidad'];
            $arrPrecio = $arrControles['arrValor'];
            $arrPorcentajeDescuento = $arrControles['arrDescuento'];
            $arrPorcentajeIva = $arrControles['arrIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->find($codigoMovimientoDetalle);
                $arMovimientoDetalle->setCodigoBodegaFk($arrBodega[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setLoteFk($arrLote[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setCantidad($arrCantidad[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setVrPrecio($arrPrecio[$codigoMovimientoDetalle]);
                if($arMovimiento->getGeneraCostoPromedio()) {
                    $arMovimientoDetalle->setVrCosto($arrPrecio[$codigoMovimientoDetalle]);
                }
                $arMovimientoDetalle->setPorcentajeDescuento($arrPorcentajeDescuento[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoMovimientoDetalle]);
                $this->getEntityManager()->persist($arMovimientoDetalle);
            }
            $this->getEntityManager()->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
        }
        $this->getEntityManager()->flush();
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

    public function informacionRegenerarKardex($codigoItem){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class,'md')
            ->select('(md.cantidad * d.operacionInventario) AS cantidadOperada')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.loteFk')
            ->leftJoin('md.movimientoRel','m')
            ->leftJoin('m.documentoRel','d')
            ->where('m.estadoAutorizado = 1')
            ->andWhere('d.operacionInventario != 0')
            ->andWhere('md.codigoItemFk = '.$codigoItem);
        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function regenerarExistencia()
    {
        $arLote = new InvLote();
        //Se limpian las existencias en los lotes y en el inventario
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvLote::class, 'l')
            ->set('l.cantidadDisponible', 0)
            ->set('l.cantidadExistencia', 0);
        $queryBuilder->getQuery()->execute();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->update(InvItem::class, 'i')
            ->set('l.cantidadExistencia', 0);
        $queryBuilder->getQuery()->execute();
        $arItems = $this->getEntityManager()->getRepository(InvItem::class)->informacionRegenerarKardex();
        foreach ($arItems as $arItem) {
            $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->informacionRegenerarKardex($arItem['codigoItemPk']);
            if (count($arMovimientoDetalles) > 0) {
                foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                    if ($arLote->getCodigoBodegaFk() != $arMovimientoDetalle['codigoBodegaFk']
                        && $arLote->getCodigoItemFk() != $arMovimientoDetalle['codigoItemFk']
                        && $arLote->getLoteFk() != $arMovimientoDetalle['loteFk']) {
                        $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                            ->findOneBy(['codigoItemFk' => $arItem['codigoItemPk'], 'codigoBodegaFk' => $arMovimientoDetalle['codigoBodegaFk'], 'codigoLotePk' => $arMovimientoDetalle['lotePk']]);
                    }
                    if ($arLote) {
                        $arLote->setCantidadExistencia($arLote->getCantidadExistencia() + $arMovimientoDetalle['cantiadadOperada']);
                        $this->getEntityManager()->persist($arLote);
                    }
                }
            }
        }
    }

    public function listaRegenerarCostos($codigoItem){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class,'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.cantidad')
            ->addSelect('md.cantidadOperada')
            ->addSelect('md.vrCosto')
            ->addSelect('md.vrPrecio')
            ->addSelect('md.porcentajeDescuento')
            ->addSelect('m.generaCostoPromedio')
            ->leftJoin('md.movimientoRel','m')
            ->where('m.estadoAprobado = 1')
            ->andWhere('md.codigoItemFk = '.$codigoItem);
        return $queryBuilder->getQuery()->execute();
    }

    public function regenerarCosto() {
        $em = $this->getEntityManager();
        $arItemes = $em->getRepository(InvItem::class)->listaRegenerar();
        foreach ($arItemes as $arItem) {
            $costoPromedio = 0;
            $existenciaAnterior = 0;
            $arMovimientosDetalles = $em->getRepository(InvMovimientoDetalle::class)->listaRegenerarCostos($arItem['codigoItemPk']);
            foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
                $arMovimientoDetalleAct = $em->getRepository(InvMovimientoDetalle::class)->find($arMovimientoDetalle['codigoMovimientoDetallePk']);
                if($arMovimientoDetalle['generaCostoPromedio']) {
                    if($existenciaAnterior != 0) {
                        $existenciaTotal = $existenciaAnterior + $arMovimientoDetalle['cantidad'];
                        $costoPromedio = (($existenciaAnterior * $costoPromedio) + (($arMovimientoDetalle['cantidad'] * $arMovimientoDetalle['vrCosto']))) / $existenciaTotal;
                    } else {
                        $precioBruto = $arMovimientoDetalle['vrPrecio'] - (($arMovimientoDetalle['vrPrecio'] * $arMovimientoDetalle['porcentajeDescuento']) / 100);
                        if($arMovimientoDetalle['vrCosto'] != $precioBruto) {
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
            ->addSelect('md.cantidad')
            ->addSelect('md.cantidadOperada')
            ->addSelect('md.cantidadSaldo')
            ->addSelect('md.vrCosto')
            ->addSelect('md.vrPrecio')
            ->addSelect('m.fecha')
            ->addSelect('m.numero AS numeroMovimiento')
            ->addSelect('d.nombre AS nombreDocumento')
            ->leftJoin('md.movimientoRel', 'm')
            ->leftJoin('m.documentoRel', 'd')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoDetallePk != 0')
            ->andWhere('m.estadoAprobado = 1')
        ->orderBy('m.fecha', 'ASC');

        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("md.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
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

}