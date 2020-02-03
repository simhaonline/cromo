<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenImpuesto;
use App\Entity\General\GenResolucion;
use App\Entity\General\GenResolucionFactura;
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
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\FacturaElectronica;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvMovimientoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvMovimiento::class);
    }


    public function lista($raw, $codigoDocumento, $usuario)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoMovimineto = null;
        $numero = null;
        $asesor = null;
        $codigoTercero = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;

        if ($filtros) {
            $numero = $filtros['numero'] ?? null;
            $codigoMovimineto = $filtros['codigoMovimineto'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $asesor = $filtros['asesor'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm');
        $queryBuilder
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoDocumentoFk')
            ->addSelect('m.codigoDocumentoTipoFk')
            ->addSelect('m.numero')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->addSelect('c.nombre AS terceroCiudad')
            ->addSelect('t.direccion AS direccionTercero')
            ->addSelect('m.fecha')
            ->addSelect('m.fechaDocumento')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrDescuento')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrTotal')
            ->addSelect('m.usuario')
            ->addSelect('m.soporte')
            ->addSelect('m.comentarios')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAutorizado')
            ->leftJoin('m.terceroRel', 't')
            ->leftJoin('t.ciudadRel', 'c')
            ->where("m.codigoDocumentoFk = '{$codigoDocumento}' ");

        if ($fechaDesde) {
            $queryBuilder->andWhere("m.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("m.fecha <= '{$fechaHasta} 23:59:59'");
        }
        if ($numero) {
            $queryBuilder->andWhere("m.numero = {$numero}");
        }
        if ($codigoMovimineto) {
            $queryBuilder->andWhere("m.codigoMovimientoPk = {$codigoMovimineto}");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("m.codigoTerceroFk = {$codigoTercero}");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("m.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("m.estadoAprobado= 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAprobado = 1");
                break;
        }
        if ($asesor) {
            $queryBuilder->andWhere("m.codigoAsesorFk = '{$asesor}'");
        }
        if ($usuario) {
            if ($usuario->getRestringirMovimientos()) {
                $queryBuilder->andWhere("m.usuario='" . $usuario->getUsername() . "'");
            }
        }
        $queryBuilder->orderBy('m.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('m.fecha', 'DESC');
        return $queryBuilder->getQuery()->getResult();
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
            $respuesta = $this->validarEncabezado($arMovimiento);
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
        $retencionFuente = $arMovimiento->getTerceroRel()->getRetencionFuente();
        $retencionFuenteSinBase = $arMovimiento->getTerceroRel()->getRetencionFuenteSinBase();
        $vrTotalBrutoGlobal = 0;
        $vrTotalGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrBaseIvaGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrRetencionFuenteGlobal = 0;
        $vrRetencionIvaGlobal = 0;
        $vrAutoretencion = 0;
        $arMovimientoDetalles = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        $arrImpuestoRetenciones = $this->retencion($arMovimientoDetalles, $retencionFuenteSinBase);
        /** @var $arMovimientoDetalle InvMovimientoDetalle */
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
            $vrBaseIva = 0;
            $vrIva = 0;
            if($arMovimientoDetalle->getCodigoImpuestoIvaFk()) {
                if($arMovimientoDetalle->getCodigoImpuestoIvaFk() != 'I00') {
                    $vrBaseIva = $vrSubtotal;
                    $vrIva = ($vrSubtotal * ($arMovimientoDetalle->getPorcentajeIva()) / 100);
                }
            }
            $vrTotalBruto = $vrSubtotal;
            $vrTotal = $vrTotalBruto + $vrIva;
            $vrRetencionFuente = 0;
            $vrTotalGlobal += $vrTotal;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrBaseIvaGlobal += $vrBaseIva;
            $vrIvaGlobal += $vrIva;
            $vrSubtotalGlobal += $vrSubtotal;
            if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'NC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'ND' || $arMovimiento->getCodigoDocumentoTipoFk() == 'COM') {
                if ($arMovimientoDetalle->getCodigoImpuestoRetencionFk()) {
                    if ($retencionFuente) {
                        if ($arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()]['base'] == true || $retencionFuenteSinBase) {
                            $vrRetencionFuente = $vrSubtotal * $arrImpuestoRetenciones[$arMovimientoDetalle->getCodigoImpuestoRetencionFk()]['porcentaje'] / 100;
                        }
                    }
                }
            }
            $vrRetencionFuenteGlobal += $vrRetencionFuente;
            $arMovimientoDetalle->setVrSubtotal($vrSubtotal);
            $arMovimientoDetalle->setVrDescuento($vrDescuento);
            $arMovimientoDetalle->setVrBaseIva($vrBaseIva);
            $arMovimientoDetalle->setVrIva($vrIva);
            $arMovimientoDetalle->setVrTotal($vrTotal);
            $arMovimientoDetalle->setVrRetencionFuente($vrRetencionFuente);
            $this->getEntityManager()->persist($arMovimientoDetalle);
        }
        //Calcular retenciones en Ventas
        if ($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'NC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'ND') {
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
        $arMovimiento->setVrBaseIva($vrBaseIvaGlobal);
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

    private function retencion($arMovimientoDetalles, $retencionFuenteSinBase)
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
                    if ($arrImpuestoRetencion['valor'] >= $arImpuesto->getBase() || $retencionFuenteSinBase) {
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
                    $em->persist($arMovimiento);
                    $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
                    foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                        $arMovimientoDetalle->setVrPrecio(0);
                        $arMovimientoDetalle->setVrIva(0);
                        $arMovimientoDetalle->setVrSubtotal(0);
                        $arMovimientoDetalle->setVrTotal(0);
                        $arMovimientoDetalle->setVrNeto(0);
                        $arMovimientoDetalle->setVrDescuento(0);
                        $arMovimientoDetalle->setPorcentajeDescuento(0);
                        $arMovimientoDetalle->setCantidad(0);
                        $arMovimientoDetalle->setCantidadOperada(0);
                        $arMovimientoDetalle->setCantidadSaldo(0);
                        $arMovimientoDetalle->setVrCosto(0);
                        $em->persist($arMovimientoDetalle);
                    }
                    $em->flush();
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
            if (($arMovimiento->getCodigoDocumentoTipoFk() == "FAC" || $arMovimiento->getCodigoDocumentoTipoFk() == 'NC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'ND') && $arMovimiento->getOperacionInventario() == -1) {
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

    public function validarEncabezado($arMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        if($arMovimiento->getCodigoDocumentoTipoFk() == 'NC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'ND') {
            if(!$arMovimiento->getCodigoMovimientoFk()) {
                $respuesta = "Los documentos nota credito y nota debito deben tener documento referencia";
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
        if ($arMovimiento->getEstadoAprobado() == 0 && $arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAnulado() == 0) {
            if ($arMovimiento->getTerceroRel()->getBloqueoCartera() == 0) {
                if ($this->afectar($arMovimiento, 1)) {
                    $stringFecha = $arMovimiento->getFecha()->format('Y-m-d');
                    $plazo = $arMovimiento->getTerceroRel()->getPlazoPago();

                    $fechaVencimiento = date_create($stringFecha);
                    $fechaVencimiento->modify("+ " . (string)$plazo . " day");
                    $arMovimiento->setFechaVence($fechaVencimiento);
                    if ($arMovimiento->getNumero() == 0 || $arMovimiento->getNumero() == "") {
                        $arMovimiento->setNumero($arDocumento->getConsecutivo());
                        $arMovimiento->setFecha(new \DateTime('now'));
                        $arDocumento->setConsecutivo($arDocumento->getConsecutivo() + 1);
                        $em->persist($arDocumento);
                    }
                    $arMovimiento->setEstadoAprobado(1);
                    $em->persist($arMovimiento);
                    //Si el documento genera cartera
                    if ($arMovimiento->getDocumentoRel()->getGeneraCartera()) {
                        $this->generarCuentaCobrar($arMovimiento);
                    }

                    //Si el documento genera tesoreria
                    if ($arMovimiento->getDocumentoRel()->getGeneraTesoreria()) {
                        $this->generarCuentaPagar($arMovimiento);
                    }

                    $em->flush();

                    //Proceso de contabilizacion automatica
                    $arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
                    if ($arConfiguracion['contabilidadAutomatica']) {
                        if ($arMovimiento->getDocumentoRel()->getContabilizar()) {
                            $this->contabilizar([$arMovimiento->getCodigoMovimientoPk()]);
                        }
                    }

                    //Proceso notificacion
                    if ($arMovimiento->getCodigoAsesorFk()) {
                        FuncionesController::crearNotificacion(3, "numero " . $arMovimiento->getNumero(), array($arMovimiento->getAsesorRel()->getusuario()));
                    }
                }
            } else {
                Mensajes::error("El registro no se puede aprobar, el cliente se encuentra bloqueado por cartera");
            }
        } else {
            Mensajes::error("El movimiento ya fue aprobado aprobado o no esta autorizado");
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
            ->addSelect('m.soporte')
            ->addSelect('m.fecha')
            ->addSelect('m.fechaDocumento')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrTotal')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrAutoretencion')
            ->addSelect('d.codigoComprobanteFk')
            ->addSelect('d.codigoCuentaProveedorFk')
            ->addSelect('d.codigoCuentaClienteFk')
            ->addSelect('d.notaCredito')
            ->addSelect('d.prefijo')
            ->addSelect('d.compraExtranjera')
            ->leftJoin('m.documentoRel', 'd')
            ->where('m.codigoMovimientoPk = ' . $codigo);
        $arMovimiento = $queryBuilder->getQuery()->getSingleResult();
        return $arMovimiento;
    }

    /**
     * @param $arr
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
                            $fecha = $arMovimiento['fechaDocumento'];
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
                                $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                $arRegistro->setNumeroReferencia($arMovimiento['soporte']);
                                $arRegistro->setFecha($fecha);
                                $arRegistro->setVrCredito($arMovimiento['vrNeto']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('PROVEEDORES DOC ' . $arMovimiento['soporte']);
                                $arRegistro->setCodigoModeloFk('InvMovimiento');
                                $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de proveedores en el documento";
                                break;
                            }

                            if($arMovimiento['compraExtranjera']) {
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
                                        $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
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
                            } else {
                                //Retenciones
                                $arrRetenciones = $em->getRepository(InvMovimientoDetalle::class)->retencionFacturaContabilizar($codigo);
                                foreach ($arrRetenciones as $arrRetencion) {
                                    if ($arrRetencion['codigoImpuestoRetencionFk']) {
                                        $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrRetencion['codigoImpuestoRetencionFk']);
                                        if ($arImpuesto) {
                                            if ($arImpuesto->getCodigoCuentaFk()) {
                                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaFk());
                                                if ($arMovimiento['notaCredito']) {
                                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaDevolucionFk());
                                                }
                                                if (!$arCuenta) {
                                                    $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                    break 2;
                                                }
                                                $arRegistro = new FinRegistro();
                                                $arRegistro->setTerceroRel($arTercero);
                                                $arRegistro->setCuentaRel($arCuenta);
                                                $arRegistro->setComprobanteRel($arComprobante);
                                                $arRegistro->setNumero($arMovimiento['numero']);
                                                $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                                $arRegistro->setFecha($fecha);
                                                if ($arMovimiento['notaCredito']) {
                                                    $arRegistro->setVrDebito($arrRetencion['vrRetencionFuente']);
                                                    $arRegistro->setNaturaleza('D');
                                                } else {
                                                    $arRegistro->setVrCredito($arrRetencion['vrRetencionFuente']);
                                                    $arRegistro->setNaturaleza('C');
                                                }

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
                                                $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                                $arRegistro->setFecha($fecha);
                                                if ($arMovimiento['notaCredito']) {
                                                    $arRegistro->setVrCredito($arrIva['vrIva']);
                                                    $arRegistro->setNaturaleza('C');
                                                } else {

                                                    $arRegistro->setVrDebito($arrIva['vrIva']);
                                                    $arRegistro->setNaturaleza('D');
                                                }

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

                                //Cuenta de ingreso inventario
                                if ($arMovimiento['notaCredito']) {
                                    //$arrVentas = $em->getRepository(InvMovimientoDetalle::class)->ventaDevolucionFacturaContabilizar($codigo);
                                } else {
                                    $arrCompras = $em->getRepository(InvMovimientoDetalle::class)->compraFacturaContabilizar($codigo);
                                }
                                foreach ($arrCompras as $arrCompra) {
                                    if ($arrCompra['codigoCuentaFk']) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCompra['codigoCuentaFk']);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta " . $arrCompra['codigoCuentaFk'];
                                            break 2;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arMovimiento['numero']);
                                        $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                        $arRegistro->setFecha($fecha);
                                        if ($arMovimiento['notaCredito']) {
                                            $arRegistro->setVrCredito($arrCompra['vrSubtotal']);
                                            $arRegistro->setNaturaleza('C');
                                        } else {
                                            $arRegistro->setVrDebito($arrCompra['vrSubtotal']);
                                            $arRegistro->setNaturaleza('D');
                                        }

                                        $arRegistro->setDescripcion('COMPRA DE PRODUCTOS');
                                        $arRegistro->setCodigoModeloFk('InvMovimiento');
                                        $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "No tiene configurada la cuenta de compra en item";
                                        break;
                                    }
                                }
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
                                $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                $arRegistro->setFecha($arMovimiento['fecha']);
                                if ($arMovimiento['notaCredito']) {
                                    $arRegistro->setVrCredito($arMovimiento['vrNeto']);
                                    $arRegistro->setNaturaleza('C');
                                } else {
                                    $arRegistro->setVrDebito($arMovimiento['vrNeto']);
                                    $arRegistro->setNaturaleza('D');
                                }

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
                                            if ($arMovimiento['notaCredito']) {
                                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaDevolucionFk());
                                            }
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                break 2;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arMovimiento['numero']);
                                            $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                            $arRegistro->setFecha($arMovimiento['fecha']);
                                            if ($arMovimiento['notaCredito']) {
                                                $arRegistro->setVrCredito($arrRetencion['vrRetencionFuente']);
                                                $arRegistro->setNaturaleza('C');
                                            } else {
                                                $arRegistro->setVrDebito($arrRetencion['vrRetencionFuente']);
                                                $arRegistro->setNaturaleza('D');
                                            }

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
                            if ($arMovimiento['vrAutoretencion'] > 0) {
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
                                    $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                    $arRegistro->setFecha($arMovimiento['fecha']);
                                    if ($arMovimiento['notaCredito']) {
                                        $arRegistro->setVrCredito($arMovimiento['vrAutoretencion']);
                                        $arRegistro->setNaturaleza('C');
                                    } else {
                                        $arRegistro->setVrDebito($arMovimiento['vrAutoretencion']);
                                        $arRegistro->setNaturaleza('D');
                                    }

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
                                    $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                    $arRegistro->setFecha($arMovimiento['fecha']);
                                    if ($arMovimiento['notaCredito']) {
                                        $arRegistro->setVrDebito($arMovimiento['vrAutoretencion']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arMovimiento['vrAutoretencion']);
                                        $arRegistro->setNaturaleza('C');
                                    }

                                    $arRegistro->setDescripcion('AUTORETENCION');
                                    $arRegistro->setCodigoModeloFk('InvMovimiento');
                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                    $em->persist($arRegistro);

                                } else {
                                    $error = "No tiene cuentas para autoretencion en configuracion general";
                                    break;
                                }
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
                                            $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                            $arRegistro->setFecha($arMovimiento['fecha']);
                                            if ($arMovimiento['notaCredito']) {
                                                $arRegistro->setVrDebito($arrIva['vrIva']);
                                                $arRegistro->setNaturaleza('D');
                                            } else {
                                                $arRegistro->setVrCredito($arrIva['vrIva']);
                                                $arRegistro->setNaturaleza('C');
                                            }

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
                            if ($arMovimiento['notaCredito']) {
                                $arrVentas = $em->getRepository(InvMovimientoDetalle::class)->ventaDevolucionFacturaContabilizar($codigo);
                            } else {
                                $arrVentas = $em->getRepository(InvMovimientoDetalle::class)->ventaFacturaContabilizar($codigo);
                            }
                            foreach ($arrVentas as $arrVenta) {
                                if ($arrVenta['codigoCuentaFk']) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrVenta['codigoCuentaFk']);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $arrVenta['codigoCuentaFk'];
                                        break 2;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arMovimiento['numero']);
                                    $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
                                    $arRegistro->setFecha($arMovimiento['fecha']);
                                    if ($arMovimiento['notaCredito']) {
                                        $arRegistro->setVrDebito($arrVenta['vrSubtotal']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arrVenta['vrSubtotal']);
                                        $arRegistro->setNaturaleza('C');
                                    }

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

    public function ventaPorAsesor()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoAsesorFk')
            ->addSelect('a.nombre AS asesor')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('m.fecha')
            ->addSelect('m.codigoDocumentoFk as DOC')
            ->addSelect('m.numero')
            ->addSelect('m.vrSubtotal * m.operacionComercial as vrSubtotal')
            ->addSelect('m.vrIva * m.operacionComercial as vrIva')
            ->addSelect('m.vrTotal * m.operacionComercial as vrTotal')
            ->leftJoin('m.asesorRel', 'a')
            ->leftJoin('m.terceroRel', 't')
            ->where('m.codigoMovimientoPk <> 0')
            ->andWhere("m.codigoDocumentoTipoFk = 'FAC'")
            ->andWhere('m.estadoAnulado = 0')
            ->andWhere('m.estadoAprobado = 1')
            ->groupBy('m.codigoAsesorFk')
            ->addGroupBy('m.codigoMovimientoPk');
        $fecha = new \DateTime('now');
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroInformeVentasPorAsesorFechaDesde') != null) {
                $queryBuilder->andWhere("m.fecha >= '{$session->get('filtroInformeVentasPorAsesorFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("m.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroInformeVentasPorAsesorFechaHasta') != null) {
                $queryBuilder->andWhere("m.fecha <= '{$session->get('filtroInformeVentasPorAsesorFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("m.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function ventasSoloAsesor($codigoAsesor)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoAsesorFk')
            ->addSelect('a.nombre AS asesor')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('m.fecha')
            ->addSelect('m.codigoDocumentoFk as DOC')
            ->addSelect('m.numero')
            ->addSelect('m.vrSubtotal * m.operacionComercial as vrSubtotal')
            ->addSelect('m.vrIva * m.operacionComercial as vrIva')
            ->addSelect('m.vrTotal * m.operacionComercial as vrTotal')
            ->leftJoin('m.asesorRel', 'a')
            ->leftJoin('m.terceroRel', 't')
            ->where('m.codigoMovimientoPk <> 0')
            ->andWhere("m.codigoDocumentoTipoFk = 'FAC'")
            ->andWhere('m.estadoAnulado = 0')
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere("m.codigoAsesorFk = '" . $codigoAsesor . "'")
            ->orderBy('m.numero', 'DESC');
        if ($session->get('filtroInvInformeAsesorVentasFechaDesde') != null) {
            $queryBuilder->andWhere("m.fecha >= '{$session->get('filtroInvInformeAsesorVentasFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroInvInformeAsesorVentasFechaHasta') != null) {
            $queryBuilder->andWhere("m.fecha <= '{$session->get('filtroInvInformeAsesorVentasFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function trasladoSinAprobar()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('COUNT(m.codigoMovimientoPk) as cantidad')
            ->where("m.codigoDocumentoTipoFk = 'TRA' ")
            ->andWhere('m.estadoAprobado=0');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();

        return $arrResultado['cantidad'];
    }

    public function facturasSinAprobar()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('COUNT(m.codigoMovimientoPk) as cantidad')
            ->where("m.codigoDocumentoTipoFk = 'FAC' ")
            ->andWhere('m.estadoAprobado=0');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();

        return $arrResultado['cantidad'];
    }

    public function corregirFactura($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $nuemroFactura = null;

        if ($filtros) {
            $nuemroFactura = $filtros['nuemroFactura'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('ase.nombre AS asesor')
            ->addSelect('t.nombreCorto AS tercero')
            ->addSelect('m.fecha')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrDescuento')
            ->addSelect('m.vrNeto')
            ->addSelect('m.vrTotal')
            ->leftJoin('m.asesorRel', 'ase')
            ->leftJoin('m.terceroRel', 't')
            ->where("m.codigoDocumentoTipoFk = 'FAC' ")
            ->andWhere('m.estadoAnulado = 0');
        if ($nuemroFactura) {
            $queryBuilder->andWhere("m.numero = {$nuemroFactura}");
        }

        $queryBuilder->addOrderBy('m.codigoMovimientoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function generarCuentaPagar($arMovimiento) {
        $em = $this->getEntityManager();
        if($arMovimiento->getDocumentoRel()->getCodigoCuentaPagarTipoFk()) {
            $arTercero = $em->getRepository(InvTercero::class)->terceroTesoreria($arMovimiento->getTerceroRel());
            /** @var $arCuentaPagarTipo TesCuentaPagarTipo */
            $fecha = $arMovimiento->getFecha();
            if($arMovimiento->getCodigoDocumentoTipoFk() == 'COM') {
                $fecha = $arMovimiento->getFechaDocumento();
            }
            $arCuentaPagarTipo = $em->getRepository(TesCuentaPagarTipo::class)->find($arMovimiento->getDocumentoRel()->getCodigoCuentaPagarTipoFk());
            $arCuentaPagar = New TesCuentaPagar();
            $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
            $arCuentaPagar->setTerceroRel($arTercero);
            $arCuentaPagar->setModulo('Inv');
            $arCuentaPagar->setModelo('InvMovimiento');
            $arCuentaPagar->setCodigoDocumento($arMovimiento->getCodigoMovimientoPk());
            $arCuentaPagar->setNumeroDocumento($arMovimiento->getNumero());
            $arCuentaPagar->setSoporte($arMovimiento->getSoporte());
            $arCuentaPagar->setFecha($fecha);
            $arCuentaPagar->setFechaVence($arMovimiento->getFechaVence());
            $arCuentaPagar->setVrSubtotal($arMovimiento->getVrSubtotal());
            $arCuentaPagar->setVrTotal($arMovimiento->getVrTotal());
            $arCuentaPagar->setVrSaldoOriginal($arMovimiento->getVrNeto());
            $arCuentaPagar->setVrSaldo($arMovimiento->getVrNeto());
            $arCuentaPagar->setVrSaldoOperado($arMovimiento->getVrNeto() * $arCuentaPagarTipo->getOperacion());
            $arCuentaPagar->setEstadoAutorizado(1);
            $arCuentaPagar->setEstadoAprobado(1);
            $arCuentaPagar->setOperacion($arCuentaPagarTipo->getOperacion());
            $em->persist($arCuentaPagar);
        } else {
            Mensajes::error("El movimiento genera cuenta por pagar pero no se pudo crear porque el documento no tiene configurado el tipo de cuenta por pagar ");
        }
    }

    public function generarCuentaCobrar($arMovimiento) {
        $em = $this->getEntityManager();
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
        $arCuentaCobrar->setVrRetencionIva($arMovimiento->getVrRetencionIva());
        $arrConfiguracion = $em->getRepository(InvConfiguracion::class)->aprobarMovimiento();
        $saldo = round($arMovimiento->getVrNeto());
        if ($arrConfiguracion['impuestoRecaudo']) {
            $saldo = $arMovimiento->getVrTotal();
            $arCuentaCobrar->setVrRetencionFuente($arMovimiento->getVrRetencionFuente());
        }
        $arCuentaCobrar->setVrSaldoOriginal($saldo);
        $arCuentaCobrar->setVrSaldo($saldo);
        $arCuentaCobrar->setVrSaldoOperado($saldo * $arCuentaCobrarTipo->getOperacion());

        $arCuentaCobrar->setPlazo($arMovimiento->getPlazoPago());
        $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
        $arCuentaCobrar->setComentario($arMovimiento->getComentarios());
        $arCuentaCobrar->setAsesorRel($arMovimiento->getAsesorRel());
        $em->persist($arCuentaCobrar);
    }

    public function movimientoFacturaElectronica($codigoMovimiento) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.prefijo')
            ->addSelect('m.fecha')
            ->addSelect('m.fechaVence')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrBaseIva')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrTotal')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoElectronico')
            ->addSelect('m.codigoDocumentoFk')
            ->addSelect('m.codigoDocumentoTipoFk')
            ->addSelect('m.cue')
            ->addSelect('m.codigoExterno')
            ->addSelect('i.codigoEntidad as tipoIdentificacion')
            ->addSelect('t.numeroIdentificacion as numeroIdentificacion')
            ->addSelect('t.digitoVerificacion as digitoVerificacion')
            ->addSelect('t.nombreCorto as nombreCorto')
            ->addSelect('t.direccion')
            ->addSelect('t.email')
            ->addSelect('t.barrio')
            ->addSelect('t.codigoPostal')
            ->addSelect('t.telefono')
            ->addSelect('t.codigoCIUU')
            ->addSelect('tp.codigoInterface as tipoPersona')
            ->addSelect('r.codigoInterface as regimen')
            ->addSelect('rf.numero as resolucionNumero')
            ->addSelect('rf.prefijo as resolucionPrefijo')
            ->addSelect('rf.fechaDesde as resolucionFechaDesde')
            ->addSelect('rf.fechaHasta as resolucionFechaHasta')
            ->addSelect('rf.numeroDesde as resolucionNumeroDesde')
            ->addSelect('rf.numeroHasta as resolucionNumeroHasta')
            ->addSelect('rf.prueba as resolucionPrueba')
            ->addSelect('rf.pin as resolucionPin')
            ->addSelect('rf.claveTecnica as resolucionClaveTecnica')
            ->addSelect('rf.ambiente as resolucionAmbiente')
            ->addSelect('rf.setPruebas as resolucionSetPruebas')
            ->addSelect('ciu.nombre as ciudadNombre')
            ->addSelect('ciu.codigoDaneCompleto as ciudadCodigoDaneCompleto')
            ->addSelect('dep.nombre as departamentoNombre')
            ->addSelect('dep.codigoDaneMascara as departamentoCodigoDaneMascara')
            ->addSelect('mr.cue as referenciaCue')
            ->addSelect('mr.numero as referenciaNumero')
            ->addSelect('mr.prefijo as referenciaPrefijo')
            ->addSelect('mr.fecha as referenciaFecha')
            ->addSelect('mr.codigoExterno as referenciaCodigoExterno')
            ->leftJoin('m.terceroRel', 't')
            ->leftJoin('t.identificacionRel', 'i')
            ->leftJoin('t.tipoPersonaRel', 'tp')
            ->leftJoin('t.regimenRel', 'r')
            ->leftJoin('m.resolucionRel', 'rf')
            ->leftJoin('t.ciudadRel', 'ciu')
            ->leftJoin('ciu.departamentoRel', 'dep')
            ->leftJoin('m.movimientoRel', 'mr')
            ->where("m.codigoMovimientoPk = {$codigoMovimiento} ");
        $arrMovimiento = $queryBuilder->getQuery()->getResult();
        if($arrMovimiento) {
            $arrMovimiento = $arrMovimiento[0];
        }
        return $arrMovimiento;
    }

    public function listaFacturaElectronica()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.fecha')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrTotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.procesoFacturaElectronica')
            ->addSelect('t.numeroIdentificacion as clienteNumeroIdentificacion')
            ->addSelect('t.nombreCorto AS clienteNombre')
            ->leftJoin('m.terceroRel', 't')
            ->where('m.estadoElectronico =  0')
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere("m.codigoDocumentoTipoFk='FAC' or m.codigoDocumentoTipoFk='NC' or m.codigoDocumentoTipoFk='ND'")

            ->orderBy('m.fecha', 'DESC');
        $fecha =  new \DateTime('now');
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("m.fecha >= '{$session->get('filtroFechaDesde')}'");
            } else {
                $queryBuilder->andWhere("m.fecha >='" . $fecha->format('Y-m-d') . "'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("m.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("m.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function facturaElectronica($arr, $prueba = false): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $arrConfiguracion = $em->getRepository(GenConfiguracion::class)->facturaElectronica();
            foreach ($arr AS $codigo) {
                $arFactura = $em->getRepository(InvMovimiento::class)->movimientoFacturaElectronica($codigo);
                if($arFactura['estadoAprobado'] && !$arFactura['estadoElectronico']) {
                    $arrFactura = [
                        'dat_nitFacturador' => $arrConfiguracion['nit'],
                        'dat_claveTecnica' => $arFactura['resolucionClaveTecnica'],
                        'dat_setPruebas' => $arFactura['resolucionSetPruebas'],
                        'dat_pin' => $arFactura['resolucionPin'],
                        'dat_tipoAmbiente' => $arFactura['resolucionAmbiente'],
                        'res_numero' => $arFactura['resolucionNumero'],
                        'res_prefijo' => $arFactura['resolucionPrefijo'],
                        'res_fechaDesde' => $arFactura['resolucionFechaDesde']?$arFactura['resolucionFechaDesde']->format('Y-m-d'):null,
                        'res_fechaHasta' => $arFactura['resolucionFechaHasta']?$arFactura['resolucionFechaHasta']->format('Y-m-d'):null,
                        'res_desde' => $arFactura['resolucionNumeroDesde'],
                        'res_hasta' => $arFactura['resolucionNumeroHasta'],
                        'res_prueba' => $arFactura['resolucionPrueba'],
                        'doc_codigo' => $arFactura['codigoMovimientoPk'],
                        'doc_tipo' => $arFactura['codigoDocumentoTipoFk'],
                        'doc_codigoDocumento' => $arFactura['codigoDocumentoFk'],
                        'doc_cue' => $arFactura['cue'],
                        'doc_prefijo' => $arFactura['prefijo'],
                        'doc_numero' => $arFactura['numero'],
                        'doc_fecha' => $arFactura['fecha']->format('Y-m-d'),
                        'doc_fecha_vence' => $arFactura['fechaVence']->format('Y-m-d'),
                        'doc_hora' => '12:00:00-05:00',
                        'doc_hora2' => '12:00:00',
                        'doc_subtotal' => number_format($arFactura['vrSubtotal'], 2, '.', ''),
                        'doc_baseIva' => number_format($arFactura['vrBaseIva'], 2, '.', ''),
                        'doc_iva' => number_format($arFactura['vrIva'], 2, '.', ''),
                        'doc_inc' => number_format(0, 2, '.', ''),
                        'doc_ica' => number_format(0, 2, '.', ''),
                        'doc_total' => number_format($arFactura['vrTotal'], 2, '.', ''),
                        'ref_cue' => $arFactura['referenciaCue'],
                        'ref_codigoExterno' => $arFactura['referenciaCodigoExterno'],
                        'ref_numero' => $arFactura['referenciaNumero'],
                        'ref_prefijo' => $arFactura['referenciaPrefijo'],
                        'ref_fecha' => $arFactura['referenciaFecha']?$arFactura['referenciaFecha']->format('Y-m-d'):null,
                        'em_tipoPersona' => $arrConfiguracion['tipoPersona'],
                        'em_numeroIdentificacion' => $arrConfiguracion['nit'],
                        'em_digitoVerificacion' => $arrConfiguracion['digitoVerificacion'],
                        'em_nombreCompleto' => $arrConfiguracion['nombre'],
                        'em_matriculaMercantil' => $arrConfiguracion['matriculaMercantil'],
                        'em_codigoCiudad' => $arrConfiguracion['ciudadCodigoDaneCompleto'],
                        'em_nombreCiudad' => $arrConfiguracion['ciudadNombre'],
                        'em_codigoPostal' => '055460',
                        'em_codigoDepartamento' => $arrConfiguracion['departamentoCodigoDaneMascara'],
                        'em_nombreDepartamento' => $arrConfiguracion['departamentoNombre'],
                        'em_correo' => $arrConfiguracion['correo'],
                        'em_direccion' => $arrConfiguracion['direccion'],
                        'ad_tipoIdentificacion' => $arFactura['tipoIdentificacion'],
                        'ad_numeroIdentificacion' => $arFactura['numeroIdentificacion'],
                        'ad_digitoVerificacion' => $arFactura['digitoVerificacion'],
                        'ad_nombreCompleto' => $arFactura['nombreCorto'],
                        'ad_tipoPersona' => $arFactura['tipoPersona'],
                        'ad_regimen' => $arFactura['regimen'],
                        'ad_responsabilidadFiscal' => '',
                        'ad_direccion' => $arFactura['direccion'],
                        'ad_barrio' => $arFactura['barrio'],
                        'ad_codigoPostal' => $arFactura['codigoPostal'],
                        'ad_telefono' => $arFactura['telefono'],
                        'ad_correo' => $arFactura['email'],
                        'ad_codigoCIUU' => $arFactura['codigoCIUU'],
                        'ad_codigoCiudad' => $arrConfiguracion['ciudadCodigoDaneCompleto'],
                        'ad_nombreCiudad' => $arrConfiguracion['ciudadNombre'],
                        'ad_codigoDepartamento' => $arrConfiguracion['departamentoCodigoDaneMascara'],
                        'ad_nombreDepartamento' => $arrConfiguracion['departamentoNombre'],
                    ];
                    $arrItem = [];
                    $cantidadItemes = 0;
                    $arFacturaDetalles = $em->getRepository(InvMovimientoDetalle::class)->facturaElectronica($arFactura['codigoMovimientoPk']);
                    foreach ($arFacturaDetalles as $arFacturaDetalle) {
                        $cantidadItemes++;
                        $arrItem[] = [
                            "item_id" => $cantidadItemes,
                            "item_codigo" => $arFacturaDetalle['codigoItemFk'],
                            "item_nombre" => $arFacturaDetalle['itemNombre'],
                            "item_cantidad" => number_format($arFacturaDetalle['cantidad'], 2, '.', ''),
                            "item_precio" => number_format($arFacturaDetalle['vrPrecio'], 2, '.', ''),
                            "item_subtotal" => number_format($arFacturaDetalle['vrSubtotal'], 2, '.', ''),
                            "item_baseIva" => number_format($arFacturaDetalle['vrBaseIva'], 2, '.', ''),
                            "item_iva" => number_format($arFacturaDetalle['vrIva'], 2, '.', ''),
                            "item_porcentaje_iva" => number_format($arFacturaDetalle['porcentajeIva'], 2, '.', '')
                        ];

                    }
                    $arrFactura['doc_itemes'] = $arrItem;
                    $arrFactura['doc_cantidad_item'] = $cantidadItemes;
                    $facturaElectronica = new FacturaElectronica($em);
                    $respuesta = $facturaElectronica->validarDatos($arrFactura);
                    if($respuesta['estado'] == 'ok') {
                        //$procesoFacturaElectronica = $facturaElectronica->enviarDispapeles($arrFactura);
                        $procesoFacturaElectronica = $facturaElectronica->enviarSoftwareEstrategico($arrFactura);
                        if($prueba == true) {
                            for ($i = 1; $i <= 60; $i++) {
                                $arrFactura['doc_numero'] = 800 + $i;
                                $facturaElectronica->enviarSoftwareEstrategico($arrFactura);
                            }
                        }
                        if($procesoFacturaElectronica['estado'] == 'CN') {
                            break;
                        }
                        if($procesoFacturaElectronica['estado'] == 'ER') {
                            $arFactura = $em->getRepository(InvMovimiento::class)->find($codigo);
                            $arFactura->setProcesoFacturaElectronica('ER');
                            $em->persist($arFactura);
                            $em->flush();
                        }
                        if($procesoFacturaElectronica['estado'] == 'EX') {
                            $arFactura = $em->getRepository(InvMovimiento::class)->find($codigo);
                            $arFactura->setEstadoElectronico(1);
                            $arFactura->setCodigoExterno($procesoFacturaElectronica['codigoExterno']);
                            $em->persist($arFactura);
                            $em->flush();
                        }
                    } else {
                        Mensajes::error($respuesta['mensaje']);
                        break;
                    }
                } else {
                    Mensajes::error("El documento debe estar aprobado y sin enviar a facturacion electronica");
                    break;
                }
            }
        }
        return true;
    }


    public function generarCue($codigoMovimiento) {
        $em = $this->getEntityManager();
        /** @var $arMovimiento InvMovimiento */
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($codigoMovimiento);
        /** @var $arResolucion GenResolucion */
        $arResolucion = $em->getRepository(GenResolucion::class)->find($arMovimiento->getCodigoResolucionFk());
        $arConfiguracion = $em->getRepository(GenConfiguracion::class)->facturaElectronica();
        $prefijo = $arResolucion->getPrefijo();
        $numero = $arMovimiento->getNumero();
        $fecha = $arMovimiento->getFecha()->format('Y-m-d');
        $hora = '12:00:00-05:00';
        $subtotal = number_format($arMovimiento->getVrSubtotal(), 2, '.', '');
        $iva = number_format($arMovimiento->getVrIva(), 2, '.', '');
        $inc = number_format(0, 2, '.', '');
        $ica = number_format(0, 2, '.', '');
        $total = number_format($arMovimiento->getVrTotal(), 2, '.', '');
        $identificacionEmisor = $arConfiguracion['nit'];

        $identificacionAdquiriente = null;
        if($arMovimiento->getCodigoTerceroFk()) {
            $identificacionAdquiriente = $arMovimiento->getTerceroRel()->getNumeroIdentificacion();
        }
        $llaveTecnica = null;
        $pin = null;
        $ambiente = null;
        if($arMovimiento->getCodigoResolucionFk()) {
            $llaveTecnica = $arResolucion->getClaveTecnica();
            $pin = $arResolucion->getPin();
            $ambiente = $arResolucion->getAmbiente();
        }
        $cue = null;
        if($arMovimiento->getCodigoDocumentoTipoFk() == 'FAC') {
            $cue = $prefijo.$numero.$fecha.$hora.$subtotal.'01'.$iva.'04'.$inc.'03'.$ica.$total.$identificacionEmisor.$identificacionAdquiriente.$llaveTecnica.$ambiente;
        }

        if($arMovimiento->getCodigoDocumentoTipoFk() == 'NC' || $arMovimiento->getCodigoDocumentoTipoFk() == 'ND') {
            $cue = $prefijo.$numero.$fecha.$hora.$subtotal.'01'.$iva.'04'.$inc.'03'.$ica.$total.$identificacionEmisor.$identificacionAdquiriente.$pin.$ambiente;
        }
        $arMovimiento->setCue($cue);
        $em->persist($arMovimiento);
    }

    public function listaReferencia($codigoTercero)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.fecha')
            ->addSelect('m.fechaDocumento')
            ->addSelect('m.vrSubtotal')
            ->addSelect('m.vrIva')
            ->addSelect('m.vrTotal')
            ->addSelect('m.vrDescuento')
            ->addSelect('m.vrNeto')
            ->addSelect('m.usuario')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAnulado')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->leftJoin('m.terceroRel', 't')
            ->where("m.codigoTerceroFk = '" . $codigoTercero . "'")
            ->andWhere("m.codigoDocumentoTipoFk = 'FAC'")
            ->andWhere('m.estadoAprobado = 1');
        $queryBuilder->orderBy("m.codigoMovimientoPk", 'DESC');
        return $queryBuilder;
    }

}