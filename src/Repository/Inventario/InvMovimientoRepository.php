<?php

namespace App\Repository\Inventario;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenImpuesto;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvTercero;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvMovimientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimiento::class);
    }


    public function lista($codigoDocumento)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm');
        $queryBuilder
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoDocumentoFk')
            ->addSelect('m.numero')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->addSelect('m.fecha')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrDescuento')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrTotal')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAutorizado')
            ->leftJoin('m.terceroRel', 't')
            ->where("m.codigoDocumentoFk = '" . $codigoDocumento . "'");
        if ($session->get('filtroInvMovimientoNumero') != "") {
            $queryBuilder->andWhere("m.numero = " . $session->get('filtroInvMovimientoNumero'));
        }
        if ($session->get('filtroInvMovimientoCodigo') != "") {
            $queryBuilder->andWhere("m.codigoMovimientoPk = " . $session->get('filtroInvMovimientoCodigo'));
        }
        if ($session->get('filtroInvCodigoTercero')) {
            $queryBuilder->andWhere("m.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        switch ($session->get('filtroInvMovimientoEstadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("m.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAutorizado = 1");
                break;
        }
        switch ($session->get('filtroInvMovimientoEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("m.estadoAprobado= 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAprobado = 1");
                break;
        }
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("m.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        $queryBuilder->orderBy('m.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('m.fecha', 'DESC');
        return $queryBuilder;
    }


    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm');
        $queryBuilder
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoDocumentoFk')
            ->addSelect('m.numero')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->addSelect('m.fecha')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrDescuento')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrTotal')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('d.nombre as documentoNombre')
            ->leftJoin('m.terceroRel', 't')
            ->leftJoin('m.documentoRel', 'd')
            ->where("m.estadoAprobado = 1 ")
            ->andWhere('m.estadoContabilizado = 0')
            ->andWhere('d.contabilizar = 1');
        if ($session->get('filtroInvMovimientoNumero') != "") {
            $queryBuilder->andWhere("m.numero = " . $session->get('filtroInvMovimientoNumero'));
        }
        if ($session->get('filtroInvMovimientoCodigo') != "") {
            $queryBuilder->andWhere("m.codigoMovimientoPk = " . $session->get('filtroInvMovimientoCodigo'));
        }
        if ($session->get('filtroInvCodigoTercero')) {
            $queryBuilder->andWhere("m.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        switch ($session->get('filtroInvMovimientoEstadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("m.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAutorizado = 1");
                break;
        }
        switch ($session->get('filtroInvMovimientoEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("m.estadoAprobado= 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAprobado = 1");
                break;
        }
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("m.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        $queryBuilder->orderBy('m.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('m.fecha', 'DESC');
        return $queryBuilder;
    }

    public function listarPendientesNotaCredito($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm');
        $queryBuilder
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.fecha')
            ->addSelect('m.vrSubtotal')
            ->leftJoin('m.terceroRel', 't')
            ->where("m.codigoDocumentoTipoFk = 'FAC'")
            ->andWhere('m.codigoTerceroFk = ' . $codigoCliente)
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere('m.estadoAnulado = 0');
        $queryBuilder->addOrderBy('m.fecha', 'DESC');
        return $queryBuilder;
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function autorizar($arMovimiento, $usuario)
    {
        $em = $this->getEntityManager();
        $respuesta = $this->validarDetalles($arMovimiento, $usuario);
        if ($respuesta) {
            Mensajes::error($respuesta);
        } else {
            if ($em->getRepository(InvMovimientoDetalle::class)->contarDetalles($arMovimiento->getCodigoMovimientoPk()) > 0) {
                $arMovimiento->setEstadoAutorizado(1);
                $em->persist($arMovimiento);
                $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
                foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                    if ($arMovimientoDetalle->getCodigoImportacionDetalleFk()) {
                        $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($arMovimientoDetalle->getCodigoImportacionDetalleFk());
                        $arImportacionDetalle->setCantidadAfectada($arImportacionDetalle->getCantidadAfectada() + $arMovimientoDetalle->getCantidad());
                        $arImportacionDetalle->setCantidadPendiente($arImportacionDetalle->getCantidad() - $arImportacionDetalle->getCantidadAfectada());
                        $em->persist($arImportacionDetalle);
                    }
                    if ($arMovimientoDetalle->getCodigoRemisionDetalleFk()) {
                        $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($arMovimientoDetalle->getCodigoRemisionDetalleFk());
                        $arRemisionDetalle->setCantidadAfectada($arRemisionDetalle->getCantidadAfectada() + $arMovimientoDetalle->getCantidad());
                        $arRemisionDetalle->setCantidadPendiente($arRemisionDetalle->getCantidad() - $arRemisionDetalle->getCantidadAfectada());
                        $em->persist($arRemisionDetalle);
                    }
                    if ($arMovimientoDetalle->getCodigoPedidoDetalleFk()) {
                        $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arMovimientoDetalle->getCodigoPedidoDetalleFk());
                        $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() + $arMovimientoDetalle->getCantidad());
                        $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                        $em->persist($arPedidoDetalle);
                    }
                    if ($arMovimientoDetalle->getCodigoOrdenDetalleFk()) {
                        $arOrdenDetalle = $em->getRepository(InvOrdenDetalle::class)->find($arMovimientoDetalle->getCodigoOrdenDetalleFk());
                        $arOrdenDetalle->setCantidadAfectada($arOrdenDetalle->getCantidadAfectada() + $arMovimientoDetalle->getCantidad());
                        $arOrdenDetalle->setCantidadPendiente($arOrdenDetalle->getCantidad() - $arOrdenDetalle->getCantidadAfectada());
                        $em->persist($arOrdenDetalle);
                    }
                }
                $em->flush();
                if ($arMovimiento->getCodigoDocumentoTipoFk() == "TRA") {
                    $this->generarDetallesTraslado($arMovimiento);
                }
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
        $vrTotalGlobal = 0;
        $vrTotalNetoGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrRetencionFuenteGlobal = 0;
        $vrRetencionIvaGlobal = 0;
        $vrAutoretencion = 0;
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        $arrImpuestoRetenciones = $this->retencion($arMovimientoDetalles);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            if ($arMovimiento->getCodigoDocumentoTipoFk() == "SAL") {
                $arMovimientoDetalle->setVrPrecio($arMovimientoDetalle->getVrCosto());
            }
            $vrPrecio = $arMovimientoDetalle->getVrPrecio() - (($arMovimientoDetalle->getVrPrecio() * $arMovimientoDetalle->getPorcentajeDescuento()) / 100);
            if ($arMovimiento->getGeneraCostoPromedio()) {
                $arMovimientoDetalle->setVrCosto($vrPrecio);
            }
            $vrSubtotal = $vrPrecio * $arMovimientoDetalle->getCantidad();
            $vrDescuento = ($arMovimientoDetalle->getVrPrecio() * $arMovimientoDetalle->getCantidad()) - $vrSubtotal;
            $vrIva = ($vrSubtotal * ($arMovimientoDetalle->getPorcentajeIva()) / 100);
            $vrTotalBruto = $vrSubtotal;
            $vrTotal = $vrTotalBruto + $vrIva;
            $vrRetencionFuente = 0;
            $vrTotalGlobal += $vrTotal;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrIvaGlobal += $vrIva;
            $vrSubtotalGlobal += $vrSubtotal;
            if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'COM') {
                if ($arMovimientoDetalle->getCodigoImpuestoRetencionFk()) {
                    if ($arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()]['base'] == true) {
                        $vrRetencionFuente = $vrSubtotal * $arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()]['porcentaje'] / 100;
                    }
                }
            }
            $vrRetencionFuenteGlobal += $vrRetencionFuente;
            $arMovimientoDetalle->setVrSubtotal($vrSubtotal);
            $arMovimientoDetalle->setVrDescuento($vrDescuento);
            $arMovimientoDetalle->setVrIva($vrIva);
            $arMovimientoDetalle->setVrTotal($vrTotal);
            $arMovimientoDetalle->setVrRetencionFuente($vrRetencionFuente);
            $this->getEntityManager()->persist($arMovimientoDetalle);
        }
        //Calcular retenciones en Ventas
        if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC') {
            //Liquidar retencion de iva para las ventas, solo los grandes contribuyentes y entidades del estado nos retienen 50% iva
            $arrConfiguracion = $em->getRepository(InvConfiguracion::class)->liquidarMovimiento();
            if ($arMovimiento->getTerceroRel()->getRetencionIva() == 1) {
                //Validacion acordada con Luz Dary de que las devoluciones tambien validen la base
                if ($vrIvaGlobal >= $arrConfiguracion['vrBaseRetencionIvaVenta']) {
                    $vrRetencionIvaGlobal = ($vrIvaGlobal * $arrConfiguracion['porcentajeRetencionIva']) / 100;
                }
            }


            $arrConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->invLiquidarMovimiento();
            if ($arrConfiguracionGeneral['autoretencionVenta']) {
                $vrAutoretencion = $vrSubtotalGlobal * $arrConfiguracionGeneral['porcentajeAutoretencion'] / 100;
            }
        }


        $vrTotalNetoGlobal = $vrTotalGlobal - $vrRetencionFuenteGlobal - $vrRetencionIvaGlobal;
        $arMovimiento->setVrIva($vrIvaGlobal);
        $arMovimiento->setVrSubtotal($vrSubtotalGlobal);
        $arMovimiento->setVrTotal($vrTotalGlobal);
        $arMovimiento->setVrNeto($vrTotalNetoGlobal);
        $arMovimiento->setVrDescuento($vrDescuentoGlobal);
        $arMovimiento->setVrRetencionFuente($vrRetencionFuenteGlobal);
        $arMovimiento->setVrRetencionIva($vrRetencionIvaGlobal);
        $arMovimiento->setVrAutoretencion($vrAutoretencion);
        $this->getEntityManager()->persist($arMovimiento);
        if ($respuesta == '') {
            $em->flush();
        } else {
            Mensajes::error($respuesta);
        }
    }

    private function retencion($arMovimientoDetalles)
    {
        $em = $this->getEntityManager();
        $arrImpuestoRetenciones = array();
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getCodigoImpuestoRetencionFk()) {
                $vrPrecio = $arMovimientoDetalle->getVrPrecio() - (($arMovimientoDetalle->getVrPrecio() * $arMovimientoDetalle->getPorcentajeDescuento()) / 100);
                $vrSubtotal = $vrPrecio * $arMovimientoDetalle->getCantidad();
                if (!array_key_exists($arMovimientoDetalle->getCodigoImpuestoRetencionFk(), $arrImpuestoRetenciones)) {
                    $arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()] = array('codigo' => $arMovimientoDetalle->getCodigoImpuestoRetencionFk(),
                        'valor' => $vrSubtotal, 'base' => false, 'porcentaje' => 0);
                } else {
                    $arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()]['valor'] += $vrSubtotal;
                }
            }
        }

        if ($arrImpuestoRetenciones) {
            foreach ($arrImpuestoRetenciones as $arrImpuestoRetencion) {
                $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrImpuestoRetencion['codigo']);
                if ($arImpuesto) {
                    if ($arrImpuestoRetencion['valor'] >= $arImpuesto->getBase()) {
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['base'] = true;
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['porcentaje'] = $arImpuesto->getPorcentaje();
                    }
                }
            }
        }
        return $arrImpuestoRetenciones;
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arMovimiento)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAprobado() == 0) {
            //Se define que mueve inventario es al aprobar
            //$this->afectar($arMovimiento, -1);
            $arMovimiento->setEstadoAutorizado(0);
            $em->persist($arMovimiento);
            $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
            foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                if ($arMovimientoDetalle->getCodigoImportacionDetalleFk()) {
                    $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($arMovimientoDetalle->getCodigoImportacionDetalleFk());
                    $arImportacionDetalle->setCantidadAfectada($arImportacionDetalle->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                    $arImportacionDetalle->setCantidadPendiente($arImportacionDetalle->getCantidad() - $arImportacionDetalle->getCantidadAfectada());
                    $em->persist($arImportacionDetalle);
                }
                if ($arMovimientoDetalle->getCodigoRemisionDetalleFk()) {
                    $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($arMovimientoDetalle->getCodigoRemisionDetalleFk());
                    $arRemisionDetalle->setCantidadAfectada($arRemisionDetalle->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                    $arRemisionDetalle->setCantidadPendiente($arRemisionDetalle->getCantidad() - $arRemisionDetalle->getCantidadAfectada());
                    $em->persist($arRemisionDetalle);
                }
                if ($arMovimientoDetalle->getCodigoPedidoDetalleFk()) {
                    $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arMovimientoDetalle->getCodigoPedidoDetalleFk());
                    $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                    $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                    $em->persist($arPedidoDetalle);
                }
                if ($arMovimientoDetalle->getCodigoOrdenDetalleFk()) {
                    $arOrdenDetalle = $em->getRepository(InvOrdenDetalle::class)->find($arMovimientoDetalle->getCodigoOrdenDetalleFk());
                    $arOrdenDetalle->setCantidadAfectada($arOrdenDetalle->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                    $arOrdenDetalle->setCantidadPendiente($arOrdenDetalle->getCantidad() - $arOrdenDetalle->getCantidadAfectada());
                    $em->persist($arOrdenDetalle);
                }
            }

            $em->flush();
            if ($arMovimiento->getCodigoDocumentoTipoFk() == 'TRA') {
                $this->eliminarDetallesTraslado($arMovimiento);
            }
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @param $tipo
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function afectar($arMovimiento, $tipo)
    {
        $em = $this->getEntityManager();
        $validacion = true;
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        /* se deben crear los lotes primero ya que si no estan creados se crean duplicados */

        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $arItem = $this->getEntityManager()->getRepository(InvItem::class)->find($arMovimientoDetalle->getCodigoItemFk());
            if ($arItem->getAfectaInventario() == 1) {
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
                    $arLote->setFechaVencimiento($arMovimientoDetalle->getFechaVencimiento());
                    $em->persist($arLote);
                    $em->flush();
                }
            }
        }

        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $arItem = $this->getEntityManager()->getRepository(InvItem::class)->find($arMovimientoDetalle->getCodigoItemFk());
            if ($arItem->getAfectaInventario() == 1) {
                //Se hace en el detalle porque los traslados tienen un registro 1 y otro -1
                $operacionTransaccion = $arMovimientoDetalle->getOperacionInventario() * $tipo;
                $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                    ->findOneBy(['loteFk' => $arMovimientoDetalle->getLoteFk(), 'codigoItemFk' => $arMovimientoDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $arMovimientoDetalle->getCodigoBodegaFk()]);
                if (!$arLote) {
                    if ($arMovimientoDetalle->getOperacionInventario() == -1) {
                        Mensajes::error("El lote especificado en el detalle id " . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " no existe");
                        $validacion = false;
                        break;
                    }
                }

                if ($operacionTransaccion == -1) {
                    $disponible = $arLote->getCantidadDisponible();
                    if ($arMovimientoDetalle->getCodigoRemisionDetalleFk()) {
                        $disponible += $arMovimientoDetalle->getCantidad();
                    }
                    if ($arMovimientoDetalle->getCantidad() > $disponible) {
                        Mensajes::error("Detalle " . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . ": La cantidad disponible [" . $arLote->getCantidadDisponible() . "] del lote: " . $arMovimientoDetalle->getLoteFk() . " es insuficiente para la salida de [" . $arMovimientoDetalle->getCantidad() . "]");
                        $validacion = false;
                        break;
                    }
                }
                if ($arMovimientoDetalle->getCodigoRemisionDetalleFk()) {
                    if ($operacionTransaccion == -1) {
                        $arLote->setCantidadRemisionada($arLote->getCantidadRemisionada() - $arMovimientoDetalle->getCantidad());
                        $arItem->setCantidadRemisionada($arItem->getCantidadRemisionada() - $arMovimientoDetalle->getCantidad());
                    } else {
                        $arLote->setCantidadRemisionada($arLote->getCantidadRemisionada() + $arMovimientoDetalle->getCantidad());
                        $arItem->setCantidadRemisionada($arItem->getCantidadRemisionada() + $arMovimientoDetalle->getCantidad());
                    }
                }
                $existenciaAnterior = $arItem->getCantidadExistencia();
                $costoPromedio = $arItem->getVrCostoPromedio();
                $cantidadSaldo = $arItem->getCantidadExistencia() + ($arMovimientoDetalle->getCantidadOperada() * $tipo);
                $cantidadOperada = $arMovimientoDetalle->getCantidad() * $arMovimientoDetalle->getOperacionInventario();
                $cantidadAfectar = $arMovimientoDetalle->getCantidad() * $operacionTransaccion;
                $arLote->setCantidadExistencia($arLote->getCantidadExistencia() + $cantidadAfectar);
                $arLote->setCantidadDisponible($arLote->getCantidadExistencia() - $arLote->getCantidadRemisionada());
                $em->persist($arLote);
                $arMovimientoDetalle->setCantidadSaldo($cantidadSaldo);
                $arMovimientoDetalle->setCantidadOperada($cantidadOperada);
                if ($tipo == 1) {
                    if ($arMovimiento->getGeneraCostoPromedio()) {
                        if ($existenciaAnterior != 0) {
                            $precioBruto = $arMovimientoDetalle->getVrPrecio() - (($arMovimientoDetalle->getVrPrecio() * $arMovimientoDetalle->getPorcentajeDescuento()) / 100);
                            if ($cantidadSaldo != 0) {
                                $costoPromedio = (($existenciaAnterior * $costoPromedio) + (($arMovimientoDetalle->getCantidad() * $precioBruto))) / $cantidadSaldo;
                            }
                        } else {
                            $costoPromedio = $arMovimientoDetalle->getVrCosto();
                        }
                    }
                    $arMovimientoDetalle->setVrCosto($costoPromedio);
                }
                $em->persist($arMovimientoDetalle);
                $arItem->setCantidadExistencia($cantidadSaldo);
                $arItem->setCantidadDisponible($cantidadSaldo - $arItem->getCantidadRemisionada());
                $arItem->setVrCostoPromedio($costoPromedio);
                $em->persist($arItem);
            }
        }
        return $validacion;
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function anular($arMovimiento)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getEstadoAprobado() && !$arMovimiento->getEstadoAnulado() && !$arMovimiento->getEstadoContabilizado()) {
            $validarCartera = true;
            if ($arMovimiento->getDocumentoRel()->getGeneraCartera()) {
                $validarCartera = $em->getRepository(CarCuentaCobrar::class)->anularExterno('INV', $arMovimiento->getCodigoMovimientoPk());
            }
            if ($validarCartera) {
                if ($this->afectar($arMovimiento, -1)) {
                    $arMovimiento->setVrSubtotal(0);
                    $arMovimiento->setVrIva(0);
                    $arMovimiento->setVrTotal(0);
                    $arMovimiento->setVrRetencionFuente(0);
                    $arMovimiento->setVrRetencionIva(0);
                    $arMovimiento->setVrDescuento(0);
                    $arMovimiento->setVrNeto(0);
                    $arMovimiento->setEstadoAnulado(1);
                    $this->getEntityManager()->persist($arMovimiento);
                    $query = $em->createQuery('UPDATE App\Entity\inventario\InvMovimientoDetalle md set md.vrPrecio = 0, 
                      md.vrIva = 0, md.vrSubtotal = 0, md.vrTotal = 0, md.vrNeto = 0, md.vrDescuento = 0, md.porcentajeDescuento = 0, md.cantidad = 0, md.cantidadOperada = 0, md.cantidadSaldo = 0  
                      WHERE md.codigoMovimientoFk = :codigoMovimiento')
                        ->setParameter('codigoMovimiento', $arMovimiento->getCodigoMovimientoPk());
                    $query->execute();
                    $this->getEntityManager()->flush();
                }
            }
        } else {
            Mensajes::error('El registro debe estar aprobado, sin anular previamente y sin contabilizar');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @return array
     */
    public function validarDetalles($arMovimiento, $usuario)
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->validarDetalles($arMovimiento->getCodigoMovimientoPk());
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            if ($arMovimientoDetalle['afectaInventario']) {
                if ($arMovimientoDetalle['codigoBodegaFk'] == "" || $arMovimientoDetalle['loteFk'] == "" || $arMovimientoDetalle['fechaVencimiento'] == "") {
                    $respuesta = "El detalle con id " . $arMovimientoDetalle['codigoMovimientoDetallePk'] . " no tiene bodega, lote o fecha vence";
                    break;
                } else {
                    $arBodega = $this->getEntityManager()->getRepository(InvBodega::class)->find($arMovimientoDetalle['codigoBodegaFk']);
                    if ($arBodega) {
                        if ($arMovimiento->getCodigoDocumentoTipoFk() == "TRA") {
                            $arBodega = $this->getEntityManager()->getRepository(InvBodega::class)->find($arMovimientoDetalle['codigoBodegaDestinoFk']);
                            if (!$arBodega) {
                                $respuesta = 'La bodega destino ingresada en el detalle con id ' . $arMovimientoDetalle['codigoMovimientoDetallePk'] . ', no existe.';
                                break;
                            }
                        }
                    } else {
                        $respuesta = 'La bodega ingresada en el detalle con id ' . $arMovimientoDetalle['codigoMovimientoDetallePk'] . ', no existe.';
                        break;
                    }
                }
            }
            if ($arMovimientoDetalle['cantidad'] == 0) {
                $respuesta = 'El detalle con id ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . ' tiene cantidad 0.';
                break;
            }
            if ($arMovimiento->getCodigoDocumentoTipoFk() == "FAC") {
                $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                    ->findOneBy(['loteFk' => $arMovimientoDetalle['loteFk'], 'codigoItemFk' => $arMovimientoDetalle['codigoItemFk']]);
                $arItem = $this->getEntityManager()->getRepository(InvItem::class)
                    ->findOneBy(['codigoItemPk' => $arMovimientoDetalle['codigoItemFk']]);
                if ($arItem->getAfectaInventario() == true) {
                    if (!$arLote) {
                        $respuesta = 'El lote especificado en el detalle id ' . $arMovimientoDetalle['codigoMovimientoDetallePk'] . ' no existe.';
                    }

                }
            }
        }

        if ($respuesta == "") {
            $respuesta = $this->validarCantidadesAfectar($arMovimiento->getCodigoMovimientoPk());
        }
        if ($respuesta == "") {
            $arrConfiguracion = $em->getRepository(InvConfiguracion::class)->validarDetalles();
            $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->validarDetalles($arMovimiento->getCodigoMovimientoPk());
            foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                if ($arrConfiguracion['validarBodegaUsuario']) {
                    $arItem = $this->getEntityManager()->getRepository(InvItem::class)
                        ->findOneBy(['codigoItemPk' => $arMovimientoDetalle['codigoItemFk']]);
                    if ($arItem->getAfectaInventario() == true) {
                        $arrBodegas = $em->getRepository(InvMovimientoDetalle::class)->bodegaMovimiento($arMovimiento->getCodigoMovimientoPk());
                        foreach ($arrBodegas as $arrBodega) {
                            $arBodegaUsuario = $em->getRepository(InvBodegaUsuario::class)->findOneBy(array('codigoBodegaFk' => $arrBodega['codigoBodegaFk'], 'usuario' => $usuario));
                            if (!$arBodegaUsuario) {
                                $respuesta = 'El usuario no tiene permiso para mover cantidades de la bodega ' . $arrBodega['codigoBodegaFk'];
                                break;
                            }
                        }
                    }
                }
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
        $em = $this->getEntityManager();
        $arDocumento = $em->getRepository(InvDocumento::class)->find($arMovimiento->getCodigoDocumentoFk());
        if ($arMovimiento->getFacturaTipoRel() != '') {
            $arFacturaTipo = $em->getRepository(InvFacturaTipo::class)->find($arMovimiento->getCodigoFacturaTipoFk());
        }
        if ($arMovimiento->getEstadoAprobado() == 0) {
            if ($this->afectar($arMovimiento, 1)) {
                $stringFecha = $arMovimiento->getFecha()->format('Y-m-d');
                $plazo = $arMovimiento->getTerceroRel()->getPlazoPago();

                $fechaVencimiento = date_create($stringFecha);
                $fechaVencimiento->modify("+ " . (string)$plazo . " day");
                $arMovimiento->setFechaVence($fechaVencimiento);
                if ($arMovimiento->getNumero() == 0 || $arMovimiento->getNumero() == "") {
                    if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC') {
                        $arMovimiento->setNumero($arFacturaTipo->getConsecutivo());
                    } else {
                        $arMovimiento->setNumero($arDocumento->getConsecutivo());
                    }
                    $arMovimiento->setEstadoAprobado(1);
                    $arMovimiento->setFecha(new \DateTime('now'));
                    $this->getEntityManager()->persist($arMovimiento);

                    if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC') {
                        $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
                    } else {
                        $arDocumento->setConsecutivo($arDocumento->getConsecutivo() + 1);
                    }
                    $this->getEntityManager()->persist($arDocumento);
                }
                if ($arMovimiento->getDocumentoRel()->getGeneraCartera()) {
                    $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arMovimiento->getTerceroRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arMovimiento->getTerceroRel()->getNumeroIdentificacion()]);
                    if (!$arClienteCartera) {
                        $arClienteCartera = new CarCliente();
                        $arClienteCartera->setFormaPagoRel($arMovimiento->getTerceroRel()->getFormaPagoRel());
                        $arClienteCartera->setIdentificacionRel($arMovimiento->getTerceroRel()->getIdentificacionRel());
                        $arClienteCartera->setNumeroIdentificacion($arMovimiento->getTerceroRel()->getNumeroIdentificacion());
                        $arClienteCartera->setDigitoVerificacion($arMovimiento->getTerceroRel()->getDigitoVerificacion());
                        $arClienteCartera->setNombreCorto($arMovimiento->getTerceroRel()->getNombreCorto());
                        $arClienteCartera->setPlazoPago($arMovimiento->getTerceroRel()->getPlazoPago());
                        $arClienteCartera->setDireccion($arMovimiento->getTerceroRel()->getDireccion());
                        $arClienteCartera->setTelefono($arMovimiento->getTerceroRel()->getTelefono());
                        $arClienteCartera->setCorreo($arMovimiento->getTerceroRel()->getEmail());
                        $em->persist($arClienteCartera);
                    }

                    $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arMovimiento->getDocumentoRel()->getCodigoCuentaCobrarTipoFk());
                    $arCuentaCobrar = new CarCuentaCobrar();
                    $arCuentaCobrar->setClienteRel($arClienteCartera);
                    $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                    $arCuentaCobrar->setFecha($arMovimiento->getFecha());
                    $arCuentaCobrar->setFechaVence($arMovimiento->getFechaVence());
                    $arCuentaCobrar->setModulo("INV");
                    $arCuentaCobrar->setCodigoDocumento($arMovimiento->getCodigoMovimientoPk());
                    $arCuentaCobrar->setNumeroDocumento($arMovimiento->getNumero());
                    $arCuentaCobrar->setSoporte($arMovimiento->getSoporte());
                    $arCuentaCobrar->setVrSubtotal($arMovimiento->getVrSubtotal());
                    $arCuentaCobrar->setVrIva($arMovimiento->getVrIva());
                    $arCuentaCobrar->setVrTotal($arMovimiento->getVrTotal());
                    $arCuentaCobrar->setVrRetencionFuente($arMovimiento->getVrRetencionFuente());
                    $arCuentaCobrar->setVrRetencionIva($arMovimiento->getVrRetencionIva());
                    $arCuentaCobrar->setVrSaldo($arMovimiento->getVrTotal());
                    $arCuentaCobrar->setVrSaldoOperado($arMovimiento->getVrTotal() * $arCuentaCobrarTipo->getOperacion());
                    $arCuentaCobrar->setPlazo($arMovimiento->getPlazoPago());
                    $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
                    $arCuentaCobrar->setComentario($arMovimiento->getComentarios());
                    $arCuentaCobrar->setAsesorRel($arMovimiento->getAsesorRel());
                    $em->persist($arCuentaCobrar);
                }

                $this->getEntityManager()->flush();
            }
        } else {
            Mensajes::error("El movimiento ya fue aprobado aprobado");
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @param $arMovimiento InvMovimiento
     */
    public function generarDetallesTraslado($arMovimiento)
    {
        $em = $this->getEntityManager();
        $arMovimientosDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
            $arMovimientoDetalleEntrada = clone $arMovimientoDetalle;
            $arMovimientoDetalleEntrada->setOperacionInventario(1);
            $arMovimientoDetalleEntrada->setCantidadOperada($arMovimientoDetalleEntrada->getCantidad());
            $arMovimientoDetalleEntrada->setCodigoBodegaFk($arMovimientoDetalle->getCodigoBodegaDestinoFk());
            $arMovimientoDetalleEntrada->setCodigoBodegaDestinoFk(NUll);
            $em->persist($arMovimientoDetalleEntrada);
            $arMovimientoDetalleSalida = clone $arMovimientoDetalle;
            $arMovimientoDetalleSalida->setOperacionInventario(-1);
            $arMovimientoDetalleSalida->setCantidadOperada($arMovimientoDetalleSalida->getCantidad() * -1);
            $arMovimientoDetalleSalida->setCodigoBodegaDestinoFk(NUll);
            $em->persist($arMovimientoDetalleSalida);

        }
        $em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @param $arMovimiento InvMovimiento
     */
    public function eliminarDetallesTraslado($arMovimiento)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('DELETE App\Entity\Inventario\InvMovimientoDetalle md WHERE md.operacionInventario <> 0 AND md.codigoMovimientoFk = ' . $arMovimiento->getCodigoMovimientoPk());
        $query->execute();
        $em->flush();
    }

    private function validarCantidadesAfectar($codigoMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        //Validar importaciones
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoImportacionDetalleFk')
            ->addSelect("SUM(md.cantidad) AS cantidad")
            ->where("md.codigoMovimientoFk = {$codigoMovimiento} ")
            ->andWhere('md.codigoImportacionDetalleFk IS NOT NULL')
            ->groupBy('md.codigoImportacionDetalleFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();
        if ($arrResultado) {
            foreach ($arrResultado as $arrElemento) {
                $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($arrElemento['codigoImportacionDetalleFk']);
                if ($arImportacionDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                    $respuesta = "La importacion detalle " . $arrElemento['codigoImportacionDetalleFk'] . " tiene pendiente " . $arImportacionDetalle->getCantidadPendiente() .
                        " y no son suficientes para afectar " . $arrElemento['cantidad'];
                    break;
                }
            }
        }

        //Validar pedidos
        if ($respuesta == "") {
            $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
                ->select('md.codigoPedidoDetalleFk')
                ->addSelect("SUM(md.cantidad) AS cantidad")
                ->where("md.codigoMovimientoFk = {$codigoMovimiento} ")
                ->andWhere('md.codigoPedidoDetalleFk IS NOT NULL')
                ->groupBy('md.codigoPedidoDetalleFk');
            $arrResultado = $queryBuilder->getQuery()->getResult();
            if ($arrResultado) {
                foreach ($arrResultado as $arrElemento) {
                    $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arrElemento['codigoPedidoDetalleFk']);
                    if ($arPedidoDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                        $respuesta = "El pedido detalle " . $arrElemento['codigoPedidoDetalleFk'] . " tiene pendiente " . $arPedidoDetalle->getCantidadPendiente() .
                            " y no son suficientes para afectar " . $arrElemento['cantidad'];
                        break;
                    }
                }
            }
        }

        //Validar orden
        if ($respuesta == "") {
            $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
                ->select('md.codigoOrdenDetalleFk')
                ->addSelect("SUM(md.cantidad) AS cantidad")
                ->where("md.codigoMovimientoFk = {$codigoMovimiento} ")
                ->andWhere('md.codigoOrdenDetalleFk IS NOT NULL')
                ->groupBy('md.codigoOrdenDetalleFk');
            $arrResultado = $queryBuilder->getQuery()->getResult();
            if ($arrResultado) {
                foreach ($arrResultado as $arrElemento) {
                    $arOrdenDetalle = $em->getRepository(InvOrdenDetalle::class)->find($arrElemento['codigoOrdenDetalleFk']);
                    if ($arOrdenDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                        $respuesta = "La orden detalle " . $arrElemento['codigoOrdenDetalleFk'] . " tiene pendiente " . $arOrdenDetalle->getCantidadPendiente() .
                            " y no son suficientes para afectar " . $arrElemento['cantidad'];
                        break;
                    }
                }
            }
        }

        //Validar remision
        if ($respuesta == "") {
            $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
                ->select('md.codigoRemisionDetalleFk')
                ->addSelect("SUM(md.cantidad) AS cantidad")
                ->where("md.codigoMovimientoFk = {$codigoMovimiento} ")
                ->andWhere('md.codigoRemisionDetalleFk IS NOT NULL')
                ->groupBy('md.codigoRemisionDetalleFk');
            $arrResultado = $queryBuilder->getQuery()->getResult();
            if ($arrResultado) {
                foreach ($arrResultado as $arrElemento) {
                    $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($arrElemento['codigoRemisionDetalleFk']);
                    if ($arRemisionDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                        $respuesta = "La remision detalle " . $arrElemento['codigoRemisionDetalleFk'] . " tiene pendiente " . $arRemisionDetalle->getCantidadPendiente() .
                            " y no son suficientes para afectar " . $arrElemento['cantidad'];
                        break;
                    }
                }
            }
        }


        return $respuesta;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvMovimiento::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arRegistro->getCodigoMovimientoPk()])) <= 0) {
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

    public function terceroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoTerceroFk')
            ->where('m.codigoMovimientoPk = ' . $codigo);
        $arMovimiento = $queryBuilder->getQuery()->getSingleResult();
        return $arMovimiento;
    }
    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoDocumentoTipoFk')
            ->addSelect('m.codigoTerceroFk')
            ->addSelect('m.numero')
            ->addSelect('m.fecha')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrTotal')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrAutoretencion')
            ->addSelect('d.codigoComprobanteFk')
            ->addSelect('d.codigoCuentaProveedorFk')
            ->addSelect('d.codigoCuentaClienteFk')
            ->leftJoin('m.documentoRel', 'd')
            ->where('m.codigoMovimientoPk = ' . $codigo);
        $arMovimiento = $queryBuilder->getQuery()->getSingleResult();
        return $arMovimiento;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //Crear los terceros antes del proceso
            foreach ($arr AS $codigo) {
                $arMovimiento = $em->getRepository(InvMovimiento::class)->terceroContabilizar($codigo);
                if ($arMovimiento) {
                    $em->getRepository(InvTercero::class)->terceroFinanciero($arMovimiento['codigoTerceroFk']);
                }
            }
            $em->flush();

            foreach ($arr AS $codigo) {
                $arMovimiento = $em->getRepository(InvMovimiento::class)->registroContabilizar($codigo);
                if ($arMovimiento) {
                    if ($arMovimiento['estadoAprobado'] == 1 && $arMovimiento['estadoContabilizado'] == 0) {
                        if (!$arMovimiento['codigoComprobanteFk']) {
                            $error = "El comprobante no esta configurado";
                            break;
                        }
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arMovimiento['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(InvTercero::class)->terceroFinanciero($arMovimiento['codigoTerceroFk']);
                        //Contabilizar entradas
                        if ($arMovimiento['codigoDocumentoTipoFk'] == "COM") {
                            //Cuenta inventario transito
                            $arrInventariosTransito = $em->getRepository(InvMovimientoDetalle::class)->cuentaInventarioTransito($codigo);
                            foreach ($arrInventariosTransito as $arrInventarioTransito) {
                                if ($arrInventarioTransito['codigoCuentaInventarioTransitoFk']) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrInventarioTransito['codigoCuentaInventarioTransitoFk']);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $arrInventarioTransito['codigoCuentaInventarioTransitoFk'];
                                        break 2;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arMovimiento['numero']);
                                    $arRegistro->setFecha($arMovimiento['fecha']);
                                    $arRegistro->setVrDebito($arrInventarioTransito['vrSubtotal']);
                                    $arRegistro->setNaturaleza('D');
                                    $arRegistro->setDescripcion('INVENTARIO TRANSITO');
                                    $arRegistro->setCodigoModeloFk('InvMovimiento');
                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "No tiene configurada la cuenta de compra para el los item";
                                    break;
                                }
                            }

                            //Proveedor
                            if ($arMovimiento['codigoCuentaProveedorFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arMovimiento['codigoCuentaProveedorFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arMovimiento['codigoCuentaProveedorFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setNumero($arMovimiento['numero']);
                                $arRegistro->setFecha($arMovimiento['fecha']);
                                $arRegistro->setVrCredito($arMovimiento['vrSubtotal']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('PROVEEDORES EXTRANJEROS');
                                $arRegistro->setCodigoModeloFk('InvMovimiento');
                                $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de proveedores en el documento";
                                break;
                            }
                        }

                        //Contabilizar facturas
                        if ($arMovimiento['codigoDocumentoTipoFk'] == "FAC") {
                            //Cliente
                            if ($arMovimiento['codigoCuentaClienteFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arMovimiento['codigoCuentaClienteFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arMovimiento['codigoCuentaClienteFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setNumero($arMovimiento['numero']);
                                $arRegistro->setFecha($arMovimiento['fecha']);
                                $arRegistro->setVrDebito($arMovimiento['vrNeto']);
                                $arRegistro->setNaturaleza('D');
                                $arRegistro->setDescripcion('CLIENTE');
                                $arRegistro->setCodigoModeloFk('InvMovimiento');
                                $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de cliente en el documento";
                                break;
                            }


                            //Retenciones
                            $arrRetenciones = $em->getRepository(InvMovimientoDetalle::class)->retencionFacturaContabilizar($codigo);
                            foreach ($arrRetenciones as $arrRetencion) {
                                if ($arrRetencion['codigoImpuestoRetencionFk']) {
                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrRetencion['codigoImpuestoRetencionFk']);
                                    if ($arImpuesto) {
                                        if ($arImpuesto->getCodigoCuentaFk()) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaFk());
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                break 2;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arMovimiento['numero']);
                                            $arRegistro->setFecha($arMovimiento['fecha']);
                                            $arRegistro->setVrDebito($arrRetencion['vrRetencionFuente']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion('IMPUESTO RETENCION FUENTE');
                                            $arRegistro->setCodigoModeloFk('InvMovimiento');
                                            $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "No tiene configurada la cuenta del impuesto de retencion";
                                            break;
                                        }
                                    }
                                }
                            }

                            //Autoretencion
                            $arrConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->invLiquidarMovimiento();
                            if ($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk'] && $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setNumero($arMovimiento['numero']);
                                $arRegistro->setFecha($arMovimiento['fecha']);
                                $arRegistro->setVrDebito($arMovimiento['vrAutoretencion']);
                                $arRegistro->setNaturaleza('D');
                                $arRegistro->setDescripcion('AUTORETENCION');
                                $arRegistro->setCodigoModeloFk('InvMovimiento');
                                $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                $em->persist($arRegistro);

                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setNumero($arMovimiento['numero']);
                                $arRegistro->setFecha($arMovimiento['fecha']);
                                $arRegistro->setVrCredito($arMovimiento['vrAutoretencion']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('AUTORETENCION');
                                $arRegistro->setCodigoModeloFk('InvMovimiento');
                                $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                $em->persist($arRegistro);

                            } else {
                                $error = "No tiene cuentas para autoretencion en configuracion general";
                                break;
                            }

                            //Iva
                            $arrIvas = $em->getRepository(InvMovimientoDetalle::class)->ivaFacturaContabilizar($codigo);
                            foreach ($arrIvas as $arrIva) {
                                if ($arrIva['codigoImpuestoIvaFk']) {
                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrIva['codigoImpuestoIvaFk']);
                                    if ($arImpuesto) {
                                        if ($arImpuesto->getCodigoCuentaFk()) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaFk());
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                break 2;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arMovimiento['numero']);
                                            $arRegistro->setFecha($arMovimiento['fecha']);
                                            $arRegistro->setVrCredito($arrIva['vrIva']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion('IVA');
                                            $arRegistro->setCodigoModeloFk('InvMovimiento');
                                            $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "No tiene configurada la cuenta del impuesto de retencion";
                                            break;
                                        }
                                    }
                                }
                            }

                            //Cuenta de ingreso / ventas
                            $arrVentas = $em->getRepository(InvMovimientoDetalle::class)->ventaFacturaContabilizar($codigo);
                            foreach ($arrVentas as $arrVenta) {
                                if ($arrVenta['codigoCuentaVentaFk']) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrVenta['codigoCuentaVentaFk']);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $arrVenta['codigoCuentaVentaFk'];
                                        break 2;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arMovimiento['numero']);
                                    $arRegistro->setFecha($arMovimiento['fecha']);
                                    $arRegistro->setVrCredito($arrVenta['vrSubtotal']);
                                    $arRegistro->setNaturaleza('C');
                                    $arRegistro->setDescripcion('VENTA DE PRODUCTOS');
                                    $arRegistro->setCodigoModeloFk('InvMovimiento');
                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "No tiene configurada la cuenta de venta en item";
                                    break;
                                }
                            }
                        }

                        $arMovimientoAct = $em->getRepository(InvMovimiento::class)->find($arMovimiento['codigoMovimientoPk']);
                        $arMovimientoAct->setEstadoContabilizado(1);
                        $em->persist($arMovimientoAct);
                    }
                } else {
                    $error = "La importacion codigo " . $codigo . " no existe";
                    break;
                }
            }
            if ($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }

        }
        return true;
    }


}