<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvMovimientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimiento::class);
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @param $arrCantidad array
     * @param $arrDescuento array
     * @param $arrIva array
     */
    public function actualizar($arMovimiento, $arrValor, $arrCantidad, $arrDescuento, $arrIva)
    {
        $vrTotalGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $arMovimientoDetalles = $this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        if (count($arMovimientoDetalles) > 0) {
            foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                $id = $arMovimientoDetalle->getCodigoMovimientoDetallePk();
                $vrUnitario = $arrValor[$id] != '' ? $arrValor[$id] : 0;
                $cantidad = $arrCantidad[$id] != '' ? $arrCantidad[$id] : 0;
                $porIva = $arrIva[$id] != '' ? $arrIva[$id] : 0;
                $porDescuento = $arrDescuento[$id] != '' ? $arrDescuento[$id] : 0;

                $vrSubtotal = $vrUnitario * $cantidad;
                $vrDescuento = $vrSubtotal * ($porDescuento / 100);
                $vrIva = $vrSubtotal * ($porIva / 100);
                $vrTotal = $vrSubtotal + $vrIva - $vrDescuento;

                $vrTotalGlobal += $vrTotal;
                $vrDescuentoGlobal += $vrDescuento;
                $vrIvaGlobal += $vrIva;
                $vrSubtotalGlobal += $vrSubtotal;

                $arMovimientoDetalle->setPorDescuento($porDescuento);
                $arMovimientoDetalle->setPorIva($porIva);
                $arMovimientoDetalle->setCantidad($cantidad);
                $arMovimientoDetalle->setVrUnitario($vrUnitario);
                $arMovimientoDetalle->setVrSubtotal($vrSubtotal);
                $arMovimientoDetalle->setVrDescuento($vrDescuento);
                $arMovimientoDetalle->setVrIva($vrIva);
                $arMovimientoDetalle->setVrTotal($vrTotal);
                $this->_em->persist($arMovimientoDetalle);
            }

            $arMovimiento->setVrIva($vrIvaGlobal);
            $arMovimiento->setVrSubtotal($vrSubtotalGlobal);
            $arMovimiento->setVrTotal($vrTotalGlobal);
            $arMovimiento->setVrDescuento($vrDescuentoGlobal);
            $this->_em->persist($arMovimiento);
        } else {
            $arMovimiento->setVrIva(0);
            $arMovimiento->setVrSubtotal(0);
            $arMovimiento->setVrTotal(0);
            $arMovimiento->setVrDescuento(0);
            $this->_em->persist($arMovimiento);
        }
        $this->_em->flush();
    }

    /**
     * @param $arMovimiento InvMovimiento
     */
    public function desautorizar($arMovimiento)
    {
        if ($arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAprobado() == 0) {
            $arMovimiento->setEstadoAutorizado(0);
            $this->_em->persist($arMovimiento);
            $this->_em->flush();
        } else {
            MensajesController::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     */
    public function autorizar($arMovimiento)
    {
        if (count($this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()])) > 0) {
            $arMovimiento->setEstadoAutorizado(1);
            $this->_em->persist($arMovimiento);
            $this->_em->flush();
        } else {
            MensajesController::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     */
    public function aprobar($arMovimiento)
    {
        $arDocumento = $this->_em->getRepository('App:Inventario\InvDocumento')->find($arMovimiento->getCodigoDocumentoFk());
        if (!$arMovimiento->getEstadoAprobado()) {
            $arDocumento->setConsecutivo($arDocumento->getConsecutivo() + 1);
            $arMovimiento->setEstadoAprobado(1);
            $arMovimiento->setNumero($arDocumento->getConsecutivo());
            $this->_em->persist($arMovimiento);
            $this->_em->persist($arDocumento);
        }
        $arMovimientoDetalles = $this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $arItem = $this->_em->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arMovimientoDetalle->getCodigoItemFk()]);
            if ($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()) {
                $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->find($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk());
                if ($arOrdenCompraDetalle) {
                    $arOrdenCompraDetalle->setCantidadPendiente($arOrdenCompraDetalle->getCantidadPendiente() - $arMovimientoDetalle->getCantidad());
                    $arItem->setCantidadOrdenCompra($arItem->getCantidadOrdenCompra() - $arMovimientoDetalle->getCantidad());
                    $this->_em->persist($arOrdenCompraDetalle);
                }
            }
            if($arDocumento->getOperacionInventario() == 1){
                $arItem->setCantidadExistencia($arItem->getCantidadExistencia() + $arMovimientoDetalle->getCantidad());
            } else {
                $arItem->setCantidadExistencia($arItem->getCantidadExistencia() - $arMovimientoDetalle->getCantidad());
            }
            $this->_em->persist($arItem);
        }
        $this->_em->flush();
    }

    /**
     * @param $arMovimiento InvMovimiento
     */
    public function anular($arMovimiento)
    {
        if ($arMovimiento->getEstadoAprobado() == 1) {
            $arMovimiento->setEstadoAnulado(1);
            $this->_em->persist($arMovimiento);
        }
        $arMovimientoDetalles = $this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $arItem = $this->_em->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arMovimientoDetalle->getCodigoItemFk()]);
            $arItem->setCantidadExistencia($arItem->getCantidadExistencia() - $arMovimientoDetalle->getCantidad());
            $this->_em->persist($arItem);
        }
        $this->_em->flush();
    }
}