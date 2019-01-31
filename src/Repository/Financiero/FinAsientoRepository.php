<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinAsientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinAsiento::class);
    }


    /**
     * @param $codigoAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoAsiento)
    {
        $em = $this->getEntityManager();
        $arAsiento = $em->getRepository(FinAsiento::class)->find($codigoAsiento);
        $arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(['codigoAsientoFk' => $codigoAsiento]);
        $debitoGeneral = 0;
        $creditoGeneral = 0;
        foreach ($arAsientoDetalles as $arAsientoDetalle) {
            //$arAsientoDetalleAct = $em->getRepository(FinAsientoDetalle::class)->find($arAsientoDetalle->getCodigoAsientoDetallePk());
            $debitoGeneral += $arAsientoDetalle->getVrDebito();
            $creditoGeneral += $arAsientoDetalle->getVrCredito();
            //$arAsientoDetalleAct->setVrIva($iva);
            //$arAsientoDetalleAct->setVrTotal($total);
            //$em->persist($arAsientoDetalleAct);
        }
        $arAsiento->setVrDebito($debitoGeneral);
        $arAsiento->setVrCredito($creditoGeneral);
        $em->persist($arAsiento);
        $em->flush();
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arAsiento)
    {
        $em = $this->getEntityManager();
        if (!$arAsiento->getEstadoAutorizado()) {
            $registros = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
                ->select('COUNT(ad.codigoAsientoDetallePk) AS registros')
                ->addSelect('SUM(ad.vrDebito) AS debito, SUM(ad.vrCredito) AS credito')
                ->where('ad.codigoAsientoFk = ' . $arAsiento->getCodigoAsientoPk())
                ->getQuery()->getResult();


            if ($registros[0]['registros'] > 0) {
                if ($registros[0]['debito'] != $registros[0]['credito']) {
                    Mensajes::error("Debitos y Creditos deben ser iguales");
                } else {
                    $arAsiento->setEstadoAutorizado(1);
                    $em->persist($arAsiento);
                    $em->flush();
                }
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arAsiento)
    {
        $em = $this->getEntityManager();
        if ($arAsiento->getEstadoAutorizado()) {
            $arAsiento->setEstadoAutorizado(0);
            $em->persist($arAsiento);
            $em->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arAsiento)
    {
        $em = $this->getEntityManager();
        if ($arAsiento->getEstadoAutorizado() == 1 && $arAsiento->getEstadoAprobado() == 0) {
            if ($arAsiento->getVrDebito() == $arAsiento->getVrCredito()) {
                //$arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(array('codigoAsientoFk' => $arAsiento->getCodigoAsientoPk()));
                /*foreach ($arAsientoDetalles AS $arAsientoDetalle) {
                    $arRegistro = new FinRegistro();
                    $arRegistro->setComprobanteRel($arAsiento->getComprobanteRel());
                    $arRegistro->setFecha($arAsiento->getFechaContable());
                    $arRegistro->setNumero($arAsiento->getNumero());
                    $arRegistro->setNumeroReferencia($arAsiento->getNumero());
                    $arRegistro->setVrDebito($arAsientoDetalle->getVrDebito());
                    $arRegistro->setVrCredito($arAsientoDetalle->getVrCredito());
                    if ($arAsientoDetalle->getVrDebito() > 0) {
                        $arRegistro->setNaturaleza("D");
                    } else {
                        $arRegistro->setNaturaleza("C");
                    }
                    $arRegistro->setVrBase($arAsientoDetalle->getVrBase());
                    $arRegistro->setCuentaRel($arAsientoDetalle->getCuentaRel());
                    $arRegistro->setDescripcion($arAsientoDetalle->getCuentaRel()->getNombre());
                    $arRegistro->setTerceroRel($arAsientoDetalle->getTerceroRel());
                    $em->persist($arRegistro);
                }*/
                $arComprobante = $em->getRepository(FinComprobante::class)->find($arAsiento->getCodigoComprobanteFk());
                if ($arAsiento->getNumero() == 0 || $arAsiento->getNumero() == NULL) {
                    $arAsiento->setNumero($arComprobante->getConsecutivo());
                    $arComprobante->setConsecutivo($arComprobante->getConsecutivo() + 1);
                    $em->persist($arComprobante);
                    $arAsiento->setFecha(new \DateTime('now'));
                }
                $arAsiento->setEstadoAprobado(1);
                $em->persist($arAsiento);
                $em->flush();
            } else {
                Mensajes::error('El asiento esta descuadrado y no se puede aprobar');
            }

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $codigoAsiento
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigoAsiento, $arrControles)
    {
        $em = $this->getEntityManager();
        $error = false;
        if ($this->contarDetalles($codigoAsiento) > 0) {
            $arrCuenta = $arrControles['arrCuenta'];
            $arrTercero = $arrControles['arrTercero'];
            $arrCodigo = $arrControles['TxtCodigo'];
            $arrDebito = $arrControles['TxtDebito'];
            $arrCredito = $arrControles['TxtCredito'];
            $arrBase = $arrControles['TxtBase'];
            foreach ($arrCodigo as $codigo) {
                $arAsientoDetalle = $em->getRepository(FinAsientoDetalle::class)->find($codigo);

                if ($arrCuenta[$codigo]) {
                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$codigo]);
                    if ($arCuenta) {
                        $arAsientoDetalle->setCuentaRel($arCuenta);
                    } else {
                        $arAsientoDetalle->setCuentaRel(null);
                    }
                } else {
                    $arAsientoDetalle->setCuentaRel(null);
                }
                //if ($arCuenta->getPermiteMovimiento()) {
                    // validacion de tercero
                    //if ($arCuenta->getExigeTercero()) {
                        if ($arrTercero[$codigo]) {
                            $arTercero = $em->getRepository(FinTercero::class)->find($arrTercero[$codigo]);
                            if ($arTercero) {
                                $arAsientoDetalle->setTerceroRel($arTercero);
                            } else {
                                $error = true;
                                Mensajes::error("El tercero no existe.");
                            }
                        } else {
                            $error = true;
                            Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige tercero.");
                        }
                    //} else {
                    //    $arAsientoDetalle->setTerceroRel(null);
                    //}
                    // validaciones de base
                    if ($arCuenta->getExigeBase()) {
                        if ($arrBase[$codigo] == 0) {
                            $error = true;
                            Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige base.");
                        } else {
                            $arAsientoDetalle->setVrBase($arrBase[$codigo]);
                        }
                    } else {
                        $arAsientoDetalle->setVrBase(0);
                    }

                    //validacion debitos y creditos
                    $arAsientoDetalle->setVrDebito($arrDebito[$codigo] != '' ? $arrDebito[$codigo] : 0);
                    $arAsientoDetalle->setVrCredito($arrCredito[$codigo] != '' ? $arrCredito[$codigo] : 0);
                    if ($arrDebito[$codigo] > 0 && $arrCredito[$codigo] > 0) {
                        $error = true;
                        Mensajes::error("Por cada linea solo el debito o credito puede tener valor mayor a cero.");
                    }
                    $em->persist($arAsientoDetalle);
//                } else {
//                    $error = true;
//                    Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " no permite movimiento.");
//                }
            }
        }
        if ($error == false) {
            $em->flush();
            $this->liquidar($codigoAsiento);
        }
        return $error;

    }

    /**
     * @param $codigo
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoAsiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select("COUNT(ad.codigoAsientoDetallePk)")
            ->where("ad.codigoAsientoFk = {$codigoAsiento} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }


    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsiento::class, 'a')
            ->select('a.codigoAsientoPk')
            ->addSelect('a.fechaContable')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoContabilizado')
            ->addSelect('a.codigoComprobanteFk')
            ->where('a.codigoAsientoPk = ' . $codigo);
        $ar = $queryBuilder->getQuery()->getSingleResult();
        return $ar;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarRecibo();
            foreach ($arr AS $codigo) {
                $arAsiento = $em->getRepository(FinAsiento::class)->registroContabilizar($codigo);
                if ($arAsiento) {
                    if ($arAsiento['estadoAprobado'] == 1 && $arAsiento['estadoContabilizado'] == 0) {
                        if ($arAsiento['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arRecibo['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $arTercero = $em->getRepository(CarCliente::class)->terceroFinanciero($arRecibo['codigoClienteFk']);
                                $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->listaContabilizar($codigo);
                                foreach ($arReciboDetalles as $arReciboDetalle) {
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
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arRecibo['numero']);
                                            $arRegistro->setNumeroPrefijo($arRecibo['prefijo']);
                                            $arRegistro->setNumeroReferencia($arReciboDetalle['numeroDocumento']);
                                            $arRegistro->setNumeroReferenciaPrefijo($arReciboDetalle['prefijo']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setVrCredito($arReciboDetalle['vrPagoAfectar']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
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
                                            $arRegistro->setVrDebito($arReciboDetalle['vrAjustePeso']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion;
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
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)";
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
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)";
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
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)";
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
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta " . $descripcion . " (DESCUENTO RAPIDO)";
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
                                                    $error = "La cuenta configurada para concepto de descuento " . $arDescuentoConcepto->getNombre() . " no existe";
                                                    break;
                                                }

                                            } else {
                                                $error = "No tiene configurada la cuenta configurada para concepto de descuento " . $arDescuentoConcepto->getNombre();
                                                break;
                                            }
                                        } else {
                                            $error = "El recibo tiene un valor para otro descuento y no tiene concepto";
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
                                                    $error = "La cuenta configurada para concepto de ingreso " . $arIngresoConcepto->getNombre() . " no existe";
                                                    break;
                                                }

                                            } else {
                                                $error = "No tiene configurada la cuenta configurada para concepto de ingreso " . $arIngresoConcepto->getNombre();
                                                break;
                                            }
                                        } else {
                                            $error = "El recibo tiene un valor para otro ingreso y no tiene concepto";
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
                                            $error = "El tipo de recibo es cruce de cuentas y no esta espeficicado el tercero  ";
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
                                                $error = "Error en el detalle ID: " . $arReciboDetalle['codigoReciboDetallePk'] . " el documento de aplicacion no tiene cuenta aplicacion";
                                                break;
                                            }
                                        } else {
                                            $banco += $arReciboDetalle['vrPago'];
                                        }
                                    }
                                    if($banco > 0) {
                                        $descripcion = "BANCO/CAJA";
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
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarRecibo');
                                            $arRegistro->setCodigoDocumento($arRecibo['codigoReciboPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arRecibo['numero'];
                                            break;
                                        }
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
                            $error = "El asiento no tiene comprobante " . $arAsiento['numero'];
                            break;
                        }

                    }
                } else {
                    $error = "La asiento codigo " . $codigo . " no existe";
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