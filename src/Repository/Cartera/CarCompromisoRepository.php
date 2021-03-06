<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCompromisoDetalle;
use App\Entity\Cartera\CarConfiguracion;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarCompromisoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarCompromiso::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $codigoCompromiso = null;
        $fechaCompromisoDesde = null;
        $fechaCompromisoHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $codigoCompromiso = $filtros['codigoCompromiso'] ?? null;
            $fechaCompromisoDesde = $filtros['fechaCompromisoDesde'] ?? null;
            $fechaCompromisoHasta = $filtros['fechaCompromisoHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCompromiso::class, 'c')
            ->select('c.codigoCompromisoPk')
            ->addSelect('c.fecha')
            ->addSelect('c.fechaCompromiso')
            ->addSelect('c.usuario')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAnulado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->leftJoin('c.clienteRel', 'cl')
            ->where('c.codigoCompromisoPk <> 0')
            ->orderBy('c.estadoAprobado', 'ASC')
            ->addOrderBy('c.fecha', 'DESC');

        if ($fechaCompromisoDesde) {
            $queryBuilder->andWhere("c.fecha >= '{$fechaCompromisoDesde} 00:00:00'");
        }
        if ($fechaCompromisoHasta) {
            $queryBuilder->andWhere("c.fecha <= '{$fechaCompromisoHasta} 23:59:59'");
        }
        if ($codigoCliente) {
            $queryBuilder->andWhere("cl.codigoClientePk = '{$codigoCliente}'");
        }
        if ($codigoCompromiso) {
            $queryBuilder->andWhere("c.codigoCompromisoPk = '{$codigoCompromiso}'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('c.codigoCompromisoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    /**
     * @param $arCompromiso CarCompromiso
     */
    public function autorizar($arCompromiso)
    {
        if (count($this->_em->getRepository(CarCompromisoDetalle::class)->findBy(['codigoCompromisoFk' => $arCompromiso->getCodigoCompromisoPk()])) > 0) {
            $arCompromiso->setEstadoAutorizado(1);
            $this->_em->persist($arCompromiso);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arCompromiso CarCompromiso
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCompromiso)
    {
        if ($arCompromiso->getEstadoAutorizado()) {
            $arCompromiso->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arCompromiso);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El documento no esta autorizado');

        }
    }

    /**
     * @param $arCompromiso CarCompromiso
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCompromiso)
    {
        if($arCompromiso->getEstadoAutorizado() == 1 && $arCompromiso->getEstadoAprobado() == 0) {
            $arCompromiso->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arCompromiso);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $arRecibo CarRecibo
     * @throws \Doctrine\ORM\ORMException
     */
    public function anular($arRecibo)
    {
        $em = $this->getEntityManager();
        if ($arRecibo->getEstadoAprobado() && !$arRecibo->getEstadoAnulado() && !$arRecibo->getEstadoContabilizado()) {
            $arRecibo->setVrPago(0);
            $arRecibo->setVrPagoTotal(0);
            $arRecibo->setVrTotalAjustePeso(0);
            $arRecibo->setVrTotalDescuento(0);
            $arRecibo->setVrTotalOtroDescuento(0);
            $arRecibo->setVrTotalOtroIngreso(0);
            $arRecibo->setVrTotalRetencionFuente(0);
            $arRecibo->setVrTotalRetencionIca(0);
            $arRecibo->setVrTotalRetencionIva(0);
            $arRecibo->setEstadoAnulado(1);
            $em->persist($arRecibo);
            $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
            foreach ($arReciboDetalles as $arReciboDetalle) {

                if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                    $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                        $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                        $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                        $arCuentaCobrarAplicacion->setVrSaldoOperado($saldoOperado);
                        $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrarAplicacion);

                        //Cuenta por cobrar
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                        $saldo = $arCuentaCobrar->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                        $arCuentaCobrar->setVrSaldo($saldo);
                        $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                        $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrar);
                } else {
                    $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                    $saldo = $arCuentaCobrar->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                    $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                    $arCuentaCobrar->setVrSaldo($saldo);
                    $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                    $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                    $em->persist($arCuentaCobrar);
                }
                $arReciboDetalle->setVrPago(0);
                $arReciboDetalle->setVrAjustePeso(0);
                $arReciboDetalle->setVrDescuento(0);
                $arReciboDetalle->setVrOtroDescuento(0);
                $arReciboDetalle->setVrOtroIngreso(0);
                $arReciboDetalle->setVrPagoAfectar(0);
                $arReciboDetalle->setVrRetencionFuente(0);
                $arReciboDetalle->setVrRetencionIva(0);
                $arReciboDetalle->setVrRetencionIca(0);
                $em->persist($arReciboDetalle);
            }
            $em->flush();
        } else {
            Mensajes::error('El registro debe estar aprobado, sin anular previamente y sin contabilizar');
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CarCompromiso::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(CarCompromisoDetalle::class)->findBy(['codigoCompromisoFk' => $arRegistro->getCodigoCompromisoPk()])) <= 0) {
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

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarRecibo::class, 'r')
            ->select('r.codigoReciboPk')
            ->addSelect('c.nombre')
            ->addSelect('cr.nombreCorto')
            ->addSelect('cr.numeroIdentificacion')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.vrPagoTotal')
            ->addSelect('r.usuario')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.estadoImpreso')
            ->addSelect('r.estadoAprobado')
            ->addSelect('rt.nombre as reciboTipo')
            ->leftJoin('r.clienteRel', 'cr')
            ->leftJoin('r.cuentaRel', 'c')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->where('r.estadoContabilizado =  0')
            ->andWhere('r.estadoAprobado = 1')
            ->orderBy('r.fecha', 'ASC');
        /*if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }*/
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroCarReciboCodigoReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarReciboCodigoReciboTipo') . "'");
        }
        $fecha = new \DateTime('now');
        if ($session->get('filtroCarReciboFiltroFecha') == true) {
            if ($session->get('filtroCarReciboFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroCarReciboFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroCarReciboFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroCarReciboFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        };
        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarRecibo::class, 'r')
            ->select('r.codigoReciboPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('r.vrPago')
            ->addSelect('r.codigoClienteFk')
            ->addSelect('r.codigoTerceroFk')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoContabilizado')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.numeroReferenciaPrefijo')
            ->addSelect('rt.codigoComprobanteFk')
            ->addSelect('rt.cruceCuentas')
            ->addSelect('rt.prefijo')
            ->addSelect('c.codigoCuentaContableFk')
            ->addSelect('c.nombre as cuentaNombre')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->leftJoin('r.cuentaRel', 'c')
            ->where('r.codigoReciboPk = ' . $codigo);
        $arRecibo = $queryBuilder->getQuery()->getSingleResult();
        return $arRecibo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            $arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarRecibo();
            foreach ($arr AS $codigo) {
                $arRecibo = $em->getRepository(CarRecibo::class)->registroContabilizar($codigo);
                if ($arRecibo) {
                    if ($arRecibo['estadoAprobado'] == 1 && $arRecibo['estadoContabilizado'] == 0) {
                        $fecha = $arRecibo['fecha'];
                        if($arConfiguracion['contabilizarReciboFechaPago']) {
                            $fecha = $arRecibo['fechaPago'];
                        }
                        if ($arRecibo['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arRecibo['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $arTercero = $em->getRepository(CarCliente::class)->terceroFinanciero($arRecibo['codigoClienteFk']);
                                $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->listaContabilizar($codigo);
                                foreach ($arReciboDetalles as $arReciboDetalle) {
                                    $arCentroCosto = null;
                                    if($arReciboDetalle['codigoCentroCostoFk']) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arReciboDetalle['codigoCentroCostoFk']);
                                    }
                                    //Cuenta cliente
                                    if ($arReciboDetalle['vrPagoAfectar'] > 0) {
                                        $descripcion = "CLIENTES";
                                        $cuenta = $arReciboDetalle['codigoCuentaClienteFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);

                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arComprobanteReferencia = null;
                                            if($arReciboDetalle['codigoComprobanteFk']) {
                                                $arComprobanteReferencia = $em->getRepository(FinComprobante::class)->find($arReciboDetalle['codigoComprobanteFk']);
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($arReciboDetalle['fechaVence']);
                                            $arRegistro->setComprobanteReferenciaRel($arComprobanteReferencia);
                                            $arRegistro->setVrCredito($arReciboDetalle['vrPagoAfectar']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            if($arCuenta->getExigeCentroCosto()) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El [tipo cuenta cobrar] no tiene configurada la cuenta " . $descripcion;
                                            break;
                                        }
                                    }

                                    //Ajuste peso
                                    if ($arReciboDetalle['vrAjustePeso']) {
                                        $descripcion = "AJUSTE PESO";
                                        $cuenta = $arConfiguracion['codigoCuentaAjustePesoFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta . " recibo numero: " . $arRecibo['numero'];
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrDebito($arReciboDetalle['vrAjustePeso']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion. " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Cuenta retencion fuente (Descuento rapido)
                                    if ($arReciboDetalle['vrRetencionFuente'] > 0) {
                                        $descripcion = "RETENCION FUENTE";
                                        $cuenta = $arConfiguracion['codigoCuentaRetencionFuenteFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta. " recibo numero: " . $arRecibo['numero'];
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrDebito($arReciboDetalle['vrRetencionFuente']);
                                            $arRegistro->setNaturaleza('D');
                                            if ($arCuenta->getExigeBase()) {
                                                $arRegistro->setVrBase($arReciboDetalle['vrPagoAfectar']);
                                            }
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)" . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Industria comercio (Descuento rapido)
                                    if ($arReciboDetalle['vrRetencionIca'] > 0) {
                                        $descripcion = "INDUSTRIA COMERCIO";
                                        $cuenta = $arConfiguracion['codigoCuentaIndustriaComercioFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);

                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrDebito($arReciboDetalle['vrRetencionIca']);
                                            $arRegistro->setNaturaleza('D');
                                            if ($arCuenta->getExigeBase()) {
                                                $arRegistro->setVrBase($arReciboDetalle['vrPagoAfectar']);
                                            }
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)" . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Retencion iva (Descuento rapido)
                                    if ($arReciboDetalle['vrRetencionIva'] > 0) {
                                        $descripcion = "RETENCION IVA";
                                        $cuenta = $arConfiguracion['codigoCuentaRetencionIvaFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrDebito($arReciboDetalle['vrRetencionIva']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)" . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Descuento (Descuento rapido)
                                    if ($arReciboDetalle['vrDescuento'] > 0) {
                                        $descripcion = "DESCUENTO";
                                        $cuenta = $arConfiguracion['codigoCuentaDescuentoFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrDebito($arReciboDetalle['vrDescuento']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            if($arCuenta->getExigeCentroCosto()) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)" . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Otro descuento y/o retencion
                                    if ($arReciboDetalle['vrOtroDescuento'] > 0) {
                                        if ($arReciboDetalle['codigoDescuentoConceptoFk']) {
                                            $arDescuentoConcepto = $em->getRepository(CarDescuentoConcepto::class)->find($arReciboDetalle['codigoDescuentoConceptoFk']);
                                            $descripcion = $arDescuentoConcepto->getNombre();
                                            $cuenta = $arDescuentoConcepto->getCodigoCuentaFk();
                                            if ($cuenta) {
                                                $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                                if ($arCuenta) {
                                                    $arRegistro = new FinRegistro();
                                                    $arRegistro->setTerceroRel($arTercero);
                                                    $arRegistro->setCuentaRel($arCuenta);
                                                    $arRegistro->setComprobanteRel($arComprobante);
                                                    $arRegistro->setNumero($arRecibo['numero']);
                                                    $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                                    $arRegistro->setFecha($fecha);
                                                    $arRegistro->setVrDebito($arReciboDetalle['vrOtroDescuento']);
                                                    $arRegistro->setNaturaleza('D');
                                                    $arRegistro->setDescripcion($descripcion);
                                                    $arRegistro->setCodigoModeloFk('CarRecibo');
                                                    $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                                    $em->persist($arRegistro);
                                                } else {
                                                    $error = "La cuenta configurada para concepto de descuento " . $arDescuentoConcepto->getNombre() . " no existe" . " recibo numero: " . $arRecibo['numero'];
                                                    break;
                                                }

                                            } else {
                                                $error = "No tiene configurada la cuenta configurada para concepto de descuento " . $arDescuentoConcepto->getNombre() . " recibo numero: " . $arRecibo['numero'];
                                                break;
                                            }
                                        } else {
                                            $error = "El recibo tiene un valor para otro descuento y no tiene concepto". " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }

                                    //Otro ingreso
                                    if ($arReciboDetalle['vrOtroIngreso'] > 0) {
                                        if ($arReciboDetalle['codigoIngresoConceptoFk']) {
                                            $arIngresoConcepto = $em->getRepository(CarIngresoConcepto::class)->find($arReciboDetalle['codigoIngresoConceptoFk']);
                                            $descripcion = $arIngresoConcepto->getNombre();
                                            $cuenta = $arIngresoConcepto->getCodigoCuentaFk();
                                            if ($cuenta) {
                                                $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                                if ($arCuenta) {
                                                    $arRegistro = new FinRegistro();
                                                    $arRegistro->setTerceroRel($arTercero);
                                                    $arRegistro->setCuentaRel($arCuenta);
                                                    $arRegistro->setComprobanteRel($arComprobante);
                                                    $arRegistro->setNumero($arRecibo['numero']);
                                                    $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                                    $arRegistro->setFecha($fecha);
                                                    $arRegistro->setVrCredito($arReciboDetalle['vrOtroIngreso']);
                                                    $arRegistro->setNaturaleza('C');
                                                    $arRegistro->setDescripcion($descripcion);
                                                    $arRegistro->setCodigoModeloFk('CarRecibo');
                                                    $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                                    $em->persist($arRegistro);
                                                } else {
                                                    $error = "La cuenta configurada para concepto de ingreso " . $arIngresoConcepto->getNombre() . " no existe" . " recibo numero: " . $arRecibo['numero'];
                                                    break;
                                                }

                                            } else {
                                                $error = "No tiene configurada la cuenta configurada para concepto de ingreso " . $arIngresoConcepto->getNombre() . " recibo numero: " . $arRecibo['numero'];
                                                break;
                                            }
                                        } else {
                                            $error = "El recibo tiene un valor para otro ingreso y no tiene concepto" . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                if ($arRecibo['cruceCuentas']) {
                                    $descripcion = "CRUCE DE CUENTAS";
                                    $cuenta = $arRecibo['codigoCuentaContableFk'];
                                    if ($cuenta) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        if ($arRecibo['codigoTerceroFk']) {
                                            $arTerceroCruce = $em->getRepository(FinTercero::class)->find($arRecibo['codigoTerceroFk']);
                                            $arRegistro->setTerceroRel($arTerceroCruce);
                                        } else {
                                            $error = "El tipo de recibo es cruce de cuentas y no esta espeficicado el tercero  " . " recibo numero: " . $arRecibo['numero'];
                                            break;
                                        }


                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arRecibo['numero']);
                                        $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                        $arRegistro->setNumeroReferenciaPrefijo($arRecibo['numeroReferenciaPrefijo']);
                                        $arRegistro->setNumeroReferencia($arRecibo['numeroReferencia']);
                                        $arRegistro->setFecha($fecha);
                                        $arRegistro->setVrDebito($arRecibo['vrPago']);
                                        $arRegistro->setNaturaleza('D');
                                        $arRegistro->setDescripcion($descripcion);
                                        $arRegistro->setCodigoModeloFk('CarRecibo');
                                        $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arRecibo['numero'];
                                        break;
                                    }
                                } else {
                                    $banco = 0;
                                    foreach ($arReciboDetalles as $arReciboDetalle) {
                                        if($arReciboDetalle['codigoCuentaCobrarAplicacionFk']) {
                                            $descripcion = "APLICACION";
                                            $cuenta = $arReciboDetalle['codigoCuentaAplicacionFk'];
                                            if ($cuenta) {
                                                $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                                if (!$arCuenta) {
                                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                    break;
                                                }
                                                $arRegistro = new FinRegistro();
                                                $arRegistro->setTerceroRel($arTercero);
                                                $arRegistro->setCuentaRel($arCuenta);
                                                $arRegistro->setComprobanteRel($arComprobante);
                                                $arRegistro->setNumero($arRecibo['numero']);
                                                $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                                $arRegistro->setFecha($fecha);
                                                $arRegistro->setVrDebito($arReciboDetalle['vrPago']);
                                                $arRegistro->setNaturaleza('D');
                                                $arRegistro->setDescripcion($descripcion);
                                                $arRegistro->setCodigoModeloFk('CarRecibo');
                                                $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                                $em->persist($arRegistro);
                                            } else {
                                                $error = "Error en el detalle ID: " . $arReciboDetalle['codigoReciboDetallePk'] . " el documento de aplicacion no tiene cuenta aplicacion" . " recibo numero: " . $arRecibo['numero'];
                                                break;
                                            }
                                        } else {
                                            $banco += $arReciboDetalle['vrPago'];
                                        }
                                    }
                                    $cuenta = $arRecibo['codigoCuentaContableFk'];
                                    if ($cuenta) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arRecibo['numero']);
                                        $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                        $arRegistro->setFecha($fecha);
                                        $arRegistro->setVrDebito($arRecibo['vrPago']);
                                        $arRegistro->setNaturaleza('D');
                                        $arRegistro->setDescripcion($arRecibo['cuentaNombre']);
                                        $arRegistro->setCodigoModeloFk('CarRecibo');
                                        $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arRecibo['numero'];
                                        break;
                                    }
                                }

                                $arReciboAct = $em->getRepository(CarRecibo::class)->find($arRecibo['codigoReciboPk']);
                                $arReciboAct->setEstadoContabilizado(1);
                                $em->persist($arReciboAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo recibo] del recibo " . $arRecibo['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo recibo] del recibo " . $arRecibo['numero'];
                            break;
                        }

                    }
                } else {
                    $error = "La recibo codigo " . $codigo . " no existe";
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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarRecibo::class, 'r')
            ->select('r.codigoReciboPk')
            ->addSelect('r.numero')
            ->addSelect('rt.nombre AS tipo')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('cr.numeroIdentificacion AS nit')
            ->addSelect('cr.nombreCorto AS clienteNombre')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.usuario')
            ->addSelect('r.vrPagoTotal')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.estadoImpreso')
            ->addSelect('r.estadoAprobado')
            ->leftJoin('r.clienteRel', 'cr')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.estadoAprobado', 'ASC')
            ->addOrderBy('r.fecha', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroCarInformeReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarInformeReciboTipo') . "'");
        }
        if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroInformeReciboFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroInformeReciboFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroInformeReciboFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroInformeReciboFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        switch ($session->get('filtroCarInformeReciboEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }
        switch ($session->get('filtroCarInformeReciboEstadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
                break;
        }
        switch ($session->get('filtroCarInformeReciboEstadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }
        return $queryBuilder;
    }

    public function recaudo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarRecibo::class, 'r')
            ->select('r.codigoReciboPk')
            ->addSelect('r.numero')
            ->addSelect('rd.numeroFactura')
            ->addSelect('rt.nombre AS tipo')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('cr.numeroIdentificacion AS nit')
            ->addSelect('cr.nombreCorto AS clienteNombre')
            ->addSelect('cta.nombre AS cuenta')
            ->addSelect('r.codigoAsesorFk')
            ->addSelect('r.usuario')
            ->addSelect('rd.vrPago')
            ->addSelect('a.nombre as asesor')
            ->leftJoin('r.recibosDetallesRecibosRel', 'rd')
            ->leftJoin('r.clienteRel', 'cr')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->leftJoin('r.cuentaRel', 'cta')
            ->leftJoin('r.asesorRel', 'a')
            ->where('r.codigoReciboPk <> 0')
            ->andWhere('r.estadoAprobado = 1')
            ->groupBy('r.codigoAsesorFk')
            ->addGroupBy('r.codigoReciboPk')
            ->addGroupBy('rd.numeroFactura')
            ->addGroupBy('rd.vrPago');
        $fecha = new \DateTime('now');
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("r.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        if ($session->get('filtroCarInformeReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarInformeReciboTipo') . "'");
        }
        if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroInformeReciboFechaDesde') != null) {
                $queryBuilder->andWhere("r.fechaPago >= '{$session->get('filtroInformeReciboFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fechaPago >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroInformeReciboFechaHasta') != null) {
                $queryBuilder->andWhere("r.fechaPago <= '{$session->get('filtroInformeReciboFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fechaPago <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function compromisosDia()
    {
        $fecha = new \DateTime('now');
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCompromiso::class, 'c')
            ->select('c.codigoCompromisoPk')
            ->addSelect('clc.nombreCorto as clienteNombre')
            ->leftJoin('c.clienteRel', 'cli')
            ->where("c.fecha >= '" . $fecha->format('Y-m-d') . " 00:00:00'")
            ->andWhere("c.fecha <= '" . $fecha->format('Y-m-d') . " 23:00:00'");
        $arCompromisos = $queryBuilder->getQuery()->getResult();
        return $arCompromisos;
    }

}