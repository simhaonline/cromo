<?php

namespace App\Repository\Tesoreria;

use App\Entity\Cartera\CarCliente;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCompra;
use App\Entity\Tesoreria\TesCompraDetalle;
use App\Entity\Tesoreria\TesCompraTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesCompraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCompra::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCompra = null;
        $codigoTercero = null;
        $compraTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCompra = $filtros['codigoCompra'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $compraTipo = $filtros['compraTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesCompra::class, 'e')
            ->select('e.codigoCompraPk')
            ->addSelect('et.nombre as tipo')
            ->addSelect('t.nombreCorto as tercero')
            ->addSelect('e.numero')
            ->addSelect('e.fecha')
            ->addSelect('e.fechaPago')
            ->addSelect('e.estadoAnulado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAutorizado')
            ->addSelect('e.estadoImpreso')
            ->leftJoin('e.compraTipoRel', 'et')
            ->leftJoin('e.terceroRel', 't')
            ->where('e.codigoCompraPk <> 0');
        if ($codigoCompra) {
            $queryBuilder->andWhere("e.codigoCompraPk = '{$codigoCompra}'");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("e.codigoTerceroFk = '{$codigoTercero}'");
        }
        if ($compraTipo) {
            $queryBuilder->andWhere("e.codigoCompraTipoFk = '{$compraTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("e.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("e.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('e.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('e.codigoCompraPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $debito = 0;
        $credito = 0;
        $arCompra = $em->getRepository(TesCompra::class)->find($id);
        $arComprasDetalle = $em->getRepository(TesCompraDetalle::class)->findBy(array('codigoCompraFk' => $id));
        foreach ($arComprasDetalle as $arCompraDetalle) {
            if($arCompraDetalle->getNaturaleza() == 'D') {
                $debito += $arCompraDetalle->getVrPrecio();
            } else {
                $credito += $arCompraDetalle->getVrPrecio();
            }
        }
        $totalNeto = $debito - $credito;
        $arCompra->setVrTotalNeto($totalNeto);
        $em->persist($arCompra);
        $em->flush();
        return true;
    }
    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arCompra ComCompra
         *
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TesCompra::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TesCompraDetalle::class)->findBy(['codigoCompraFk' => $arRegistro->getCodigoCompraPk()])) <= 0) {
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

    public function autorizar($arCompra)
    {
        $em = $this->getEntityManager();
        if ($arCompra->getEstadoAutorizado() == 0) {
            $error = false;
            if($arCompra->getVrTotalNeto() >= 0) {
                $arComprasDetalles = $em->getRepository(TesCompraDetalle::class)->findBy(array('codigoCompraFk' => $arCompra->getCodigoCompraPk()));
                if ($arComprasDetalles) {
                    if ($error == false) {
                        $arCompra->setEstadoAutorizado(1);
                        $em->persist($arCompra);
                        $em->flush();
                    }
                } else {
                    Mensajes::error("No se puede autorizar un recibo sin detalles");
                }
            } else {
                Mensajes::error("No se puede autorizar un compra negativo");
            }
        }
    }

    public function desAutorizar($arCompra)
    {
        $em = $this->getEntityManager();
        $arCompra->setEstadoAutorizado(0);
        $em->persist($arCompra);
        $em->flush();
    }

    public function aprobar($arCompra)
    {
        $em = $this->getEntityManager();
        if ($arCompra->getEstadoAutorizado()) {
            $arCompraTipo = $em->getRepository(TesCompraTipo::class)->find($arCompra->getCodigoCompraTipoFk());
            if ($arCompra->getNumero() == 0 || $arCompra->getNumero() == NULL) {
                $arCompra->setNumero($arCompraTipo->getConsecutivo());
                $arCompraTipo->setConsecutivo($arCompraTipo->getConsecutivo() + 1);
                $em->persist($arCompraTipo);
            }
            $arCompra->setEstadoAprobado(1);
            $em->persist($arCompra);
            $em->flush();
        }
    }

    /**
     * @param $arCompra ComCompra
     * @return array
     */
    public function anular($arCompra)
    {
        $em = $this->getEntityManager();
        $respuesta = [];
        if ($arCompra->getEstadoAprobado() == 1) {
            $arComprasDetalle = $em->getRepository(ComCompraDetalle::class)->findBy(array('codigoCompraFk' => $arCompra->getCodigoCompraPk()));
            foreach ($arComprasDetalle as $arCompraDetalle) {
                if ($arCompraDetalle->getCodigoCuentaPagarFk()) {
                    $arCuentaPagarAplicacion = $em->getRepository(ComCuentaPagar::class)->find($arCompraDetalle->getCodigoCuentaPagarFk());
                    if ($arCuentaPagarAplicacion->getVrSaldo() <= $arCompraDetalle->getVrPagoAfectar() || $arCuentaPagarAplicacion->getVrSaldo() == 0) {
                        $saldo = $arCuentaPagarAplicacion->getVrSaldo() + $arCompraDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                        $arCuentaPagarAplicacion->setVrSaldo($saldo);
                        $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                        $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() - $arCompraDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaPagarAplicacion);
                    }
                }
                $arCompraDetalle->setVrDescuento(0);
                $arCompraDetalle->setVrAjustePeso(0);
                $arCompraDetalle->setVrRetencionIca(0);
                $arCompraDetalle->setVrRetencionIva(0);
                $arCompraDetalle->setVrRetencionFuente(0);
                $arCompraDetalle->setVrPago(0);
                $arCompraDetalle->setVrPagoAfectar(0);
            }
            $arCompra->setVrPago(0);
            $arCompra->setVrPagoTotal(0);
            $arCompra->setVrTotalDescuento(0);
            $arCompra->setVrTotalAjustePeso(0);
            $arCompra->setVrTotalRetencionIca(0);
            $arCompra->setVrTotalRetencionIva(0);
            $arCompra->setVrTotalRetencionFuente(0);
            $arCompra->setEstadoAnulado(1);
            $this->_em->persist($arCompra);
            $this->_em->flush();
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCompra::class, 'e')
            ->select('e.codigoCompraPk')
            ->addSelect('e.numero')
            ->addSelect('e.fecha')
            ->addSelect('e.fechaPago')
            ->addSelect('e.vrTotalNeto')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoContabilizado')
            ->addSelect('e.codigoTerceroFk')
            ->addSelect('et.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->leftJoin('e.compraTipoRel', 'et')
            ->leftJoin('e.cuentaRel', 'c')
            ->where('e.codigoCompraPk = ' . $codigo);
        $arRecibo = $queryBuilder->getQuery()->getSingleResult();
        return $arRecibo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarCompra();
            foreach ($arr AS $codigo) {
                $arCompra = $em->getRepository(TesCompra::class)->registroContabilizar($codigo);
                if ($arCompra) {
                    if ($arCompra['estadoAprobado'] == 1 && $arCompra['estadoContabilizado'] == 0) {
                        if ($arCompra['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arCompra['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $fecha = $arCompra['fechaPago'];
                                $arTercero = $em->getRepository(TesTercero::class)->terceroFinanciero($arCompra['codigoTerceroFk']);
                                $arCompraDetalles = $em->getRepository(TesCompraDetalle::class)->listaContabilizar($codigo);
                                foreach ($arCompraDetalles as $arCompraDetalle) {
                                    //Cuenta proveedor
                                    if ($arCompraDetalle['vrPago'] > 0) {
                                        $arTerceroDetalle = null;
                                        if($arCompraDetalle['codigoTerceroFk']) {
                                            $arTerceroDetalle = $em->getRepository(TesTercero::class)->terceroFinanciero($arCompraDetalle['codigoTerceroFk']);
                                        }
                                        $descripcion = "PROVEEDORES DOC " . $arCompraDetalle['numeroDocumento'] ;
                                        $cuenta = $arCompraDetalle['codigoCuentaFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTerceroDetalle);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arCompra['numero']);
                                            $arRegistro->setNumeroReferencia($arCompraDetalle['numeroDocumento']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($fecha);
                                            if($arCompraDetalle['naturaleza'] == 'D') {
                                                $arRegistro->setVrDebito($arCompraDetalle['vrPago']);
                                            } else {
                                                $arRegistro->setVrCredito($arCompraDetalle['vrPago']);
                                            }
                                            $arRegistro->setNaturaleza($arCompraDetalle['naturaleza']);
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('TesCompra');
                                            $arRegistro->setCodigoDocumento($arCompra['codigoCompraPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "La cuenta no existe" . $descripcion;
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                $cuenta = $arCompra['codigoCuentaContableFk'];
                                if ($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    //$arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arCompra['numero']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrCredito($arCompra['vrTotalNeto']);
                                    $arRegistro->setNaturaleza('C');
                                    $arRegistro->setDescripcion("Compra");
                                    $arRegistro->setCodigoModeloFk('TesCompra');
                                    $arRegistro->setCodigoDocumento($arCompra['codigoCompraPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arCompra['numero'];
                                    break;
                                }

                                $arCompraAct = $em->getRepository(TesCompra::class)->find($arCompra['codigoCompraPk']);
                                $arCompraAct->setEstadoContabilizado(1);
                                $em->persist($arCompraAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo compra] del compra " . $arCompra['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo compra] del compra " . $arCompra['numero'];
                            break;
                        }
                    }
                } else {
                    $error = "El compra " . $codigo . " no existe";
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
