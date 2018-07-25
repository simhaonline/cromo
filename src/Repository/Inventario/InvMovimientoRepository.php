<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvLote;
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
     * @throws \Doctrine\ORM\ORMException
     */
    public function autorizar($arMovimiento)
    {
        $respuesta = $this->validarDetalles($arMovimiento->getCodigoMovimientoPk());
        if (count($respuesta)) {
            foreach ($respuesta as $error) {
                Mensajes::error($error);
            }
        } else {
            if ($this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->contarDetalles($arMovimiento->getCodigoMovimientoPk()) > 0) {
                //$this->afectar($arMovimiento, 1);
                $arMovimiento->setEstadoAutorizado(1);
                $this->getEntityManager()->persist($arMovimiento);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error('El movimiento no contiene detalles');
            }
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @param $arMovimiento InvMovimiento
     */
    public function liquidar($arMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = '';
        $vrTotalBrutoGlobal = 0;
        $vrTotalNetoGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrRetencionFuenteGlobal = 0;
        $vrRetencionIvaGlobal = 0;
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $vrSubtotal = $arMovimientoDetalle->getVrPrecio() * $arMovimientoDetalle->getCantidad();
            $vrDescuento = 0;
            if($arMovimientoDetalle->getPorcentajeDescuento() > 0) {
                $vrDescuento = $vrSubtotal * ($arMovimientoDetalle->getPorcentajeDescuento() / 100);
            }
            $vrIva = $vrSubtotal * ($arMovimientoDetalle->getPorcentajeIva() / 100);
            $vrTotalBruto = $vrSubtotal - $vrDescuento + $vrIva;
            //$vrTotalNeto = $vrTotalBruto - $vrIva;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrIvaGlobal += $vrIva;
            $vrSubtotalGlobal += $vrSubtotal;

            $arMovimientoDetalle->setVrSubtotal($vrSubtotal);
            $arMovimientoDetalle->setVrDescuento($vrDescuento);
            $arMovimientoDetalle->setVrIva($vrIva);
            $arMovimientoDetalle->setVrTotal($vrTotalBruto);
            //$arMovimientoDetalle->setVrNeto($vrTotalNeto);

            $this->getEntityManager()->persist($arMovimientoDetalle);
        }
        $arrConfiguracion = $em->getRepository(InvConfiguracion::class)->liquidarMovimiento();
        //Calcular retenciones en Ventas
        if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC') {
            //Retencion en la fuente
            if ($arMovimiento->getTerceroRel()->getRetencionFuente()) {
                if ($vrTotalBrutoGlobal >= $arrConfiguracion['vrBaseRetencionFuenteVenta'] || $arMovimiento->getTerceroRel()->getRetencionFuenteSinBase()) {
                    $vrRetencionFuenteGlobal = ($vrTotalBrutoGlobal * $arrConfiguracion['porcentajeRetencionFuente']) / 100;
                }
            }

            //Liquidar retencion de iva para las ventas, solo los grandes contribuyentes y entidades del estado nos retienen 50% iva
            if ($arMovimiento->getTerceroRel()->getRetencionIva() == 1) {
                //Validacion acordada con Luz Dary de que las devoluciones tambien validen la base
                if ($vrIvaGlobal >=  $arrConfiguracion['vrBaseRetencionIvaVenta']) {
                    $vrRetencionIvaGlobal = ($vrIvaGlobal * $arrConfiguracion['porcentajeRetencionIva']) / 100;
                }
            }
        }
        $vrTotalNetoGlobal = $vrTotalBrutoGlobal - $vrRetencionFuenteGlobal - $vrRetencionIvaGlobal;
        $arMovimiento->setVrIva($vrIvaGlobal);
        $arMovimiento->setVrSubtotal($vrSubtotalGlobal);
        $arMovimiento->setVrTotal($vrTotalBrutoGlobal);
        $arMovimiento->setVrNeto($vrTotalNetoGlobal);
        $arMovimiento->setVrDescuento($vrDescuentoGlobal);
        $arMovimiento->setVrRetencionFuente($vrRetencionFuenteGlobal);
        $arMovimiento->setVrRetencionIva($vrRetencionIvaGlobal);
        $this->getEntityManager()->persist($arMovimiento);
        if ($respuesta == '') {
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error($respuesta);
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arMovimiento)
    {
        if ($arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAprobado() == 0) {
            //$this->afectar($arMovimiento, -1);
            $arMovimiento->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arMovimiento);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @param $tipo
     * @throws \Doctrine\ORM\ORMException
     */
    public function afectar($arMovimiento, $tipo)
    {
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {

            $arItem = $this->getEntityManager()->getRepository(InvItem::class)->find($arMovimientoDetalle->getCodigoItemFk());
            if($arItem->getAfectaInventario() == 1) {
                $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                    ->findOneBy(['loteFk' => $arMovimientoDetalle->getLoteFk(), 'codigoItemFk' => $arMovimientoDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $arMovimientoDetalle->getCodigoBodegaFk()]);
                if (!$arLote) {
                    $arBodega = $this->getEntityManager()->getRepository(InvBodega::class)->find($arMovimientoDetalle->getCodigoBodegaFk());
                    $arLote = new InvLote();
                    $arLote->setCodigoItemFk($arMovimientoDetalle->getCodigoItemFk());
                    $arLote->setItemRel($arItem);
                    $arLote->setCodigoBodegaFk($arMovimientoDetalle->getCodigoBodegaFk());
                    $arLote->setBodegaRel($arBodega);
                    $arLote->setLoteFk($arMovimientoDetalle->getLoteFk());
                    $arLote->setFechaVencimiento($arMovimientoDetalle->getFecha());
                    $this->getEntityManager()->persist($arLote);
                }
                if ($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()) {
                    $arOrdenCompraDetalle = $this->getEntityManager()->getRepository(InvOrdenCompraDetalle::class)->find($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk());
                    if ($arOrdenCompraDetalle) {
                        $arOrdenCompraDetalle->setCantidadPendiente($arOrdenCompraDetalle->getCantidadPendiente() + $arMovimientoDetalle->getCantidad() * $tipo);
                        $arItem->setCantidadOrdenCompra($arItem->getCantidadOrdenCompra() + $arMovimientoDetalle->getCantidad() * $tipo);
                        $this->getEntityManager()->persist($arOrdenCompraDetalle);
                    }
                }
                $existenciaAnterior = $arItem->getCantidadExistencia();
                $costoPromedio = $arItem->getVrCostoPromedio();
                $cantidadSaldo = $arItem->getCantidadExistencia() + ($arMovimientoDetalle->getCantidadOperada() * $tipo);
                $cantidadOperada = $arMovimientoDetalle->getCantidad() * $arMovimiento->getOperacionInventario();
                $arLote->setCantidadExistencia($arLote->getCantidadExistencia() + ($arMovimientoDetalle->getCantidad() * $arMovimiento->getOperacionInventario()) * $tipo);
                $arLote->setCantidadDisponible($arLote->getCantidadDisponible() + $arMovimientoDetalle->getCantidadOperada() * $tipo);

                $arMovimientoDetalle->setCantidadSaldo($cantidadSaldo);
                $arMovimientoDetalle->setCantidadOperada($cantidadOperada);
                if($tipo == 1) {
                    if($arMovimiento->getGeneraCostoPromedio()) {
                        if($existenciaAnterior != 0) {
                            $costoPromedio = (($existenciaAnterior * $costoPromedio) + (($arMovimientoDetalle->getCantidad() * $arMovimientoDetalle->getVrPrecio()))) / $cantidadSaldo;
                        }
                    }
                    $arMovimientoDetalle->setVrCosto($costoPromedio);
                }
                $arItem->setCantidadExistencia($cantidadSaldo);
                $arItem->setVrCostoPromedio($costoPromedio);
                $this->getEntityManager()->persist($arItem, $arLote, $arMovimientoDetalle);
            }
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function anular($arMovimiento)
    {
        if ($arMovimiento->getEstadoAprobado()) {
            $this->afectar($arMovimiento, -1);
            $arMovimiento->setEstadoAnulado(1);
            $this->getEntityManager()->persist($arMovimiento);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    /**
     * @param $codigoMovimiento
     * @return array
     */
    public function validarDetalles($codigoMovimiento)
    {
        $respuesta = [];
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $codigoMovimiento]);
        /** @var  $arMovimientoDetalle InvMovimientoDetalle */
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getItemRel()->getAfectaInventario()) {
                if (!$arMovimientoDetalle->getCodigoBodegaFk() || $arMovimientoDetalle->getCodigoBodegaFk() == '') {
                    $respuesta[] = 'El detalle con id ' . $arMovimientoDetalle->getCodigoBodegaFk() . ' no tiene asociada una bodega.';
                } else {
                    $arBodega = $this->getEntityManager()->getRepository(InvBodega::class)->find($arMovimientoDetalle->getCodigoBodegaFk());
                    if (!$arBodega) {
                        $respuesta[] = 'La bodega ingresada en el detalle con id ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . ', no existe.';
                    }
                }
                if ($this->getEntityManager()->getRepository(InvMovimiento::class)->find($codigoMovimiento)->getDocumentoRel()->getOperacionInventario() == -1) {
                    if (!$arMovimientoDetalle->getLoteFk() || $arMovimientoDetalle->getLoteFk() == '') {
                        $respuesta[] = 'El detalle con id ' . $arMovimientoDetalle->getCodigoBodegaFk() . ' no tiene asociada una bodega.';
                    }
                }
            }
            if ($arMovimientoDetalle->getCantidad() == 0) {
                $respuesta[] = 'El detalle con id ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . ' tiene cantidad 0.';
            }
        }
        return $respuesta;
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function aprobar($arMovimiento)
    {
        $arDocumento = $this->getEntityManager()->getRepository(InvDocumento::class)->find($arMovimiento->getCodigoDocumentoFk());
        if (!$arMovimiento->getEstadoAprobado()) {
            $this->afectar($arMovimiento, 1);
            $stringFecha = $arMovimiento->getFecha()->format('Y-m-d');
            $plazo = $arMovimiento->getTerceroRel()->getPlazoPago();

            $fechaVencimiento = date_create($stringFecha);
            $fechaVencimiento->modify("+ " . (string)$plazo . " day");
            $arMovimiento->setFechaVence($fechaVencimiento);

            $arDocumento->setConsecutivo($arDocumento->getConsecutivo() + 1);
            $arMovimiento->setEstadoAprobado(1);
            $arMovimiento->setNumero($arDocumento->getConsecutivo());
            $arMovimiento->setFecha(new \DateTime('now'));
            $this->getEntityManager()->persist($arMovimiento);
            $this->getEntityManager()->persist($arDocumento);
        }
        $this->getEntityManager()->flush();
    }
}