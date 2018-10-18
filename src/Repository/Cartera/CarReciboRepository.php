<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarRecibo::class);
    }

    public function lista()
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
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.cuentaRel','c')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.estadoAprobado', 'ASC')
        ->addOrderBy('r.fecha', 'DESC');
        if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        switch ($session->get('filtroCarReciboEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }
        return $queryBuilder;
    }

    public function autorizar($arRecibo)
    {
        $em = $this->getEntityManager();
        if($arRecibo->getEstadoAutorizado() == 0){
            $error = false;
            $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
            if (count($em->getRepository(CarReciboDetalle::class)->findBy(['codigoReciboFk' => $arRecibo->getCodigoReciboPk()])) > 0){
                foreach ($arReciboDetalles AS $arReciboDetalle) {
                    if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                        $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                        if ($arCuentaCobrarAplicacion->getVrSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                            $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                            $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrarAplicacion);
                            //Cuenta por cobrar
                            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                            $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrar);
                        } else {
                            Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getVrSaldo());
                            $error = true;
                            break;
                        }

                    } else {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                        if($arCuentaCobrar->getVrSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrar);
                        } else {
                            Mensajes::error("El saldo " . $arCuentaCobrar->getVrSaldo() . " de la cuenta por cobrar numero: " . $arCuentaCobrar->getNumeroDocumento() . " es menor al recibo detalle " . $arReciboDetalle->getVrPagoAfectar());
                            $error = true;
                            break;
                        }
                    }
                }
                if($error == false){
                    $arRecibo->setEstadoAutorizado(1);
                    $em->persist($arRecibo);
                    $em->flush();
                }
            } else {
                Mensajes::error("No se puede autorizar un recibo sin detalles");
            }
        }
    }

    public function desAutorizar($arRecibo)
    {
        $em = $this->getEntityManager();
        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
        foreach ($arReciboDetalles AS $arReciboDetalle) {
            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
            $saldo = $arCuentaCobrar->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
            $arCuentaCobrar->setVrSaldo($saldo);
            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
            $em->persist($arCuentaCobrar);
            if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                $arCuentaCobrarAplicacion->setVrSaldoOperado($saldoOperado);
                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                $em->persist($arCuentaCobrarAplicacion);
            }
        }
        $arRecibo->setEstadoAutorizado(0);
        $em->persist($arRecibo);
        $em->flush();
    }

    public function aprobar($arRecibo){
        $em = $this->getEntityManager();
        if($arRecibo->getEstadoAutorizado()){
            $arReciboTipo = $em->getRepository(CarReciboTipo::class)->find($arRecibo->getCodigoReciboTipoFk());
            if ($arRecibo->getNumero() == 0 || $arRecibo->getNumero() == NULL) {
                $arRecibo->setNumero($arReciboTipo->getConsecutivo());
                $arReciboTipo->setConsecutivo($arReciboTipo->getConsecutivo() + 1);
                $em->persist($arReciboTipo);
            }
            $arRecibo->setFecha(new \DateTime('now'));
            $arRecibo->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arRecibo);
            $this->getEntityManager()->flush();
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
                $arRegistro = $this->getEntityManager()->getRepository(CarRecibo::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(CarReciboDetalle::class)->findBy(['codigoReciboFk' => $arRegistro->getCodigoReciboPk()])) <= 0) {
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
                if($respuesta != ''){
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
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.cuentaRel','c')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->where('r.estadoContabilizado =  0')
            ->andWhere('r.estadoAprobado = 1')
        ->orderBy('r.fecha', 'ASC');
        /*if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }*/
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroCarReciboCodigoReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarReciboCodigoReciboTipo')."'");
        }
        $fecha =  new \DateTime('now');
        if($session->get('filtroCarReciboFiltroFecha') == true){
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
            ->addSelect('r.vrPago')
            ->addSelect('r.codigoClienteFk')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoContabilizado')
            ->addSelect('r.numeroDocumento')
            ->addSelect('rt.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
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
            foreach ($arr AS $codigo) {
                $arRecibo = $em->getRepository(CarRecibo::class)->registroContabilizar($codigo);
                if($arRecibo) {
                    if($arRecibo['estadoAprobado'] == 1 && $arRecibo['estadoContabilizado'] == 0) {
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arRecibo['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(CarCliente::class)->terceroFinanciero($arRecibo['codigoClienteFk']);

                        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->listaContabilizar($codigo);
                        foreach ($arReciboDetalles as $arReciboDetalle) {
                            //Cuenta cliente
                            if($arReciboDetalle['vrPago'] > 0) {
                                $descripcion = "CLIENTES";
                                $cuenta = $arReciboDetalle['codigoCuentaClienteFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "C";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrPagoAfectar']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrPagoAfectar']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Cuenta retencion fuente
                            if($arReciboDetalle['vrRetencionFuente'] > 0) {
                                $descripcion = "RETENCION FUENTE";
                                $cuenta = $arReciboDetalle['codigoCuentaRetencionFuenteFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "D";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrRetencionFuente']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrRetencionFuente']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    if($arCuenta->getExigeBase()) {
                                        $arRegistro->setVrBase($arReciboDetalle['vrPagoAfectar']);
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Industria comercio
                            if($arReciboDetalle['vrRetencionIca'] > 0) {
                                $descripcion = "INDUSTRIA COMERCIO";
                                $cuenta = $arReciboDetalle['codigoCuentaIndustriaComercioFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "D";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrRetencionIca']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrRetencionIca']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    if($arCuenta->getExigeBase()) {
                                        $arRegistro->setVrBase($arReciboDetalle['vrPagoAfectar']);
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Retencion iva
                            if($arReciboDetalle['vrRetencionIva'] > 0) {
                                $descripcion = "RETENCION IVA";
                                $cuenta = $arReciboDetalle['codigoCuentaRetencionIvaFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "D";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrRetencionIva']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrRetencionIva']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Ajuste peso
                            if($arReciboDetalle['vrAjustePeso']) {
                                $descripcion = "AJUSTE PESO";
                                $cuenta = $arReciboDetalle['codigoCuentaAjustePesoFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "D";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrAjustePeso']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrAjustePeso']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Descuento
                            if($arReciboDetalle['vrDescuento'] > 0) {
                                $descripcion = "DESCUENTO";
                                $cuenta = $arReciboDetalle['codigoCuentaDescuentoFk'];
                                if($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if(!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    /*if($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }*/
                                    $arRegistro->setNumero($arRecibo['numero']);
                                    $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                    $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                    $arRegistro->setFecha($arRecibo['fecha']);
                                    $naturaleza = "D";
                                    if($naturaleza == 'D') {
                                        $arRegistro->setVrDebito($arReciboDetalle['vrDescuento']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arReciboDetalle['vrDescuento']);
                                        $arRegistro->setNaturaleza('C');
                                    }
                                    $arRegistro->setDescripcion($descripcion);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }
                        }

                        //Cuenta banco
                        $descripcion = "BANCO/CAJA";
                        $cuenta = $arRecibo['codigoCuentaContableFk'];
                        if($cuenta) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                break;
                            }
                            $arRegistro = new FinRegistro();
                            $arRegistro->setTerceroRel($arTercero);
                            $arRegistro->setCuentaRel($arCuenta);
                            $arRegistro->setComprobanteRel($arComprobante);
                            /*if($arCuenta->getExigeCentroCosto()) {
                                $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                            }*/
                            $arRegistro->setNumero($arRecibo['numero']);
                            if($arRecibo['numeroDocumento']) {
                                $arRegistro->setNumeroReferencia($arRecibo['numeroDocumento']);
                            }
                            $arRegistro->setFecha($arRecibo['fecha']);
                            $naturaleza = "D";
                            if($naturaleza == 'D') {
                                $arRegistro->setVrDebito($arRecibo['vrPago']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arRecibo['vrPago']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion($descripcion);
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo no tiene configurada la cuenta " . $descripcion;
                            break;
                        }


                        $arReciboAct = $em->getRepository(CarRecibo::class)->find($arRecibo['codigoReciboPk']);
                        $arReciboAct->setEstadoContabilizado(1);
                        $em->persist($arReciboAct);
                    }
                } else {
                    $error = "La despacho codigo " . $codigo . " no existe";
                    break;
                }
            }
            if($error == "") {
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
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.estadoAprobado', 'ASC')
            ->addOrderBy('r.fecha', 'DESC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroCarInformeReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarInformeReciboTipo')."'");
        }
        if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
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

}