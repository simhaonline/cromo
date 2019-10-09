<?php

namespace App\Repository\Tesoreria;


use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesMovimientoTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesMovimientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimiento::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $clase = $raw['codigoMovimientoClase'] ?? null;

        $codigoMovimiento = null;
        $numero = null;
        $codigoTercero = null;
        $movimientoTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoMovimiento = $filtros['codigoMovimiento'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $movimientoTipo = $filtros['movimientoTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesMovimiento::class, 'e')
            ->select('e.codigoMovimientoPk')
            ->addSelect('et.nombre as tipo')
            ->addSelect('t.nombreCorto as tercero')
            ->addSelect('e.numero')
            ->addSelect('e.fecha')
            ->addSelect('e.vrTotalNeto')
            ->addSelect('e.estadoAnulado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAutorizado')
            ->leftJoin('e.movimientoTipoRel', 'et')
            ->leftJoin('e.terceroRel', 't')
            ->where("e.codigoMovimientoClaseFk = '{$clase}'");
        if ($codigoMovimiento) {
            $queryBuilder->andWhere("e.codigoMovimientoPk = {$codigoMovimiento}");
        }
        if ($numero) {
            $queryBuilder->andWhere("e.numero = {$numero}");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("e.codigoTerceroFk = '{$codigoTercero}'");
        }
        if ($movimientoTipo) {
            $queryBuilder->andWhere("e.codigoMovimientoTipoFk = '{$movimientoTipo}'");
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
        $queryBuilder->addOrderBy('e.codigoMovimientoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $debito = 0;
        $credito = 0;
        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $id));
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            if($arMovimientoDetalle->getNaturaleza() == 'D') {
                $debito += $arMovimientoDetalle->getVrPago();
            } else {
                $credito += $arMovimientoDetalle->getVrPago();
            }
        }
        $totalNeto = $debito - $credito;
        $arMovimiento->setVrTotalNeto($totalNeto);
        $em->persist($arMovimiento);
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
         * @var $arMovimiento ComMovimiento
         *
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TesMovimiento::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arRegistro->getCodigoMovimientoPk()])) <= 0) {
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

    public function autorizar($arMovimiento)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getEstadoAutorizado() == 0) {
            $error = false;
            if($arMovimiento->getVrTotalNeto() >= 0) {
                $arMovimientosDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
                if ($arMovimientosDetalles) {
                    foreach ($arMovimientosDetalles AS $arMovimientoDetalle) {
                        if ($arMovimientoDetalle->getCodigoCuentaPagarFk()) {
                            $arCuentaPagarAplicacion = $em->getRepository(TesCuentaPagar::class)->find($arMovimientoDetalle->getCodigoCuentaPagarFk());
                            if ($arCuentaPagarAplicacion->getVrSaldo() >= $arMovimientoDetalle->getVrPago()) {
                                $saldo = $arCuentaPagarAplicacion->getVrSaldo() - $arMovimientoDetalle->getVrPago();
                                $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                                $arCuentaPagarAplicacion->setVrSaldo($saldo);
                                $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                                $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() + $arMovimientoDetalle->getVrPago());
                                $em->persist($arCuentaPagarAplicacion);
                            } else {
                                Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaPagarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaPagarAplicacion->getVrSaldo());
                                $error = true;
                                break;
                            }

                        }
                    }
                    if ($error == false) {
                        $arMovimiento->setEstadoAutorizado(1);
                        $em->persist($arMovimiento);
                        $em->flush();
                    }
                } else {
                    Mensajes::error("No se puede autorizar un recibo sin detalles");
                }
            } else {
                Mensajes::error("No se puede autorizar un movimiento negativo");
            }
        }
    }

    public function desAutorizar($arMovimiento)
    {
        $em = $this->getEntityManager();
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if($arMovimientoDetalle->getCodigoCuentaPagarFk()) {
                $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($arMovimientoDetalle->getCodigoCuentaPagarFk());
                $saldo = $arCuentaPagar->getVrSaldo() + $arMovimientoDetalle->getVrPago();
                $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
                $arCuentaPagar->setVrSaldo($saldo);
                $arCuentaPagar->setVrSaldoOperado($saldoOperado);
                $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() - $arMovimientoDetalle->getVrPago());
                $em->persist($arCuentaPagar);
            }
        }
        $arMovimiento->setEstadoAutorizado(0);
        $em->persist($arMovimiento);
        $em->flush();
    }

    public function aprobar($arMovimiento)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getEstadoAutorizado()) {
            $arMovimientoTipo = $em->getRepository(TesMovimientoTipo::class)->find($arMovimiento->getCodigoMovimientoTipoFk());
            if ($arMovimiento->getNumero() == 0 || $arMovimiento->getNumero() == NULL) {
                $arMovimiento->setNumero($arMovimientoTipo->getConsecutivo());
                $arMovimientoTipo->setConsecutivo($arMovimientoTipo->getConsecutivo() + 1);
                $em->persist($arMovimientoTipo);
            }
            $arMovimiento->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arMovimiento);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arMovimiento ComMovimiento
     * @return array
     */
    public function anular($arMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = [];
        if ($arMovimiento->getEstadoAprobado() == 1) {
            $arMovimientosDetalle = $em->getRepository(ComMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
            foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                if ($arMovimientoDetalle->getCodigoCuentaPagarFk()) {
                    $arCuentaPagarAplicacion = $em->getRepository(ComCuentaPagar::class)->find($arMovimientoDetalle->getCodigoCuentaPagarFk());
                    if ($arCuentaPagarAplicacion->getVrSaldo() <= $arMovimientoDetalle->getVrPagoAfectar() || $arCuentaPagarAplicacion->getVrSaldo() == 0) {
                        $saldo = $arCuentaPagarAplicacion->getVrSaldo() + $arMovimientoDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                        $arCuentaPagarAplicacion->setVrSaldo($saldo);
                        $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                        $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() - $arMovimientoDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaPagarAplicacion);
                    }
                }
                $arMovimientoDetalle->setVrDescuento(0);
                $arMovimientoDetalle->setVrAjustePeso(0);
                $arMovimientoDetalle->setVrRetencionIca(0);
                $arMovimientoDetalle->setVrRetencionIva(0);
                $arMovimientoDetalle->setVrRetencionFuente(0);
                $arMovimientoDetalle->setVrPago(0);
                $arMovimientoDetalle->setVrPagoAfectar(0);
            }
            $arMovimiento->setVrPago(0);
            $arMovimiento->setVrPagoTotal(0);
            $arMovimiento->setVrTotalDescuento(0);
            $arMovimiento->setVrTotalAjustePeso(0);
            $arMovimiento->setVrTotalRetencionIca(0);
            $arMovimiento->setVrTotalRetencionIva(0);
            $arMovimiento->setVrTotalRetencionFuente(0);
            $arMovimiento->setEstadoAnulado(1);
            $this->_em->persist($arMovimiento);
            $this->_em->flush();
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.fecha')
            ->addSelect('m.vrTotalNeto')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.codigoTerceroFk')
            ->addSelect('mt.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->leftJoin('m.movimientoTipoRel', 'mt')
            ->leftJoin('m.cuentaRel', 'c')
            ->where('m.codigoMovimientoPk = ' . $codigo);
        $arRecibo = $queryBuilder->getQuery()->getSingleResult();
        return $arRecibo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarMovimiento();
            foreach ($arr AS $codigo) {
                $arMovimiento = $em->getRepository(TesMovimiento::class)->registroContabilizar($codigo);
                if ($arMovimiento) {
                    if ($arMovimiento['estadoAprobado'] == 1 && $arMovimiento['estadoContabilizado'] == 0) {
                        if ($arMovimiento['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arMovimiento['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $fecha = $arMovimiento['fecha'];
                                $arTercero = $em->getRepository(TesTercero::class)->terceroFinanciero($arMovimiento['codigoTerceroFk']);
                                $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                                    //Cuenta proveedor
                                    if ($arMovimientoDetalle['vrPago'] > 0) {
                                        $arTerceroDetalle = null;
                                        if($arMovimientoDetalle['codigoTerceroFk']) {
                                            $arTerceroDetalle = $em->getRepository(TesTercero::class)->terceroFinanciero($arMovimientoDetalle['codigoTerceroFk']);
                                        }
                                        $descripcion = "DOC " . $arMovimientoDetalle['numeroDocumento'] . " " . $arMovimientoDetalle['detalle'];
                                        $cuenta = $arMovimientoDetalle['codigoCuentaFk'];
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
                                            $arRegistro->setNumero($arMovimiento['numero']);
                                            $arRegistro->setNumeroReferencia($arMovimientoDetalle['numeroDocumento']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($fecha);
                                            if($arMovimientoDetalle['naturaleza'] == 'D') {
                                                $arRegistro->setVrDebito($arMovimientoDetalle['vrPago']);
                                            } else {
                                                $arRegistro->setVrCredito($arMovimientoDetalle['vrPago']);
                                            }
                                            $arRegistro->setNaturaleza($arMovimientoDetalle['naturaleza']);
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('TesMovimiento');
                                            $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "La cuenta no existe" . $descripcion;
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                $cuenta = $arMovimiento['codigoCuentaContableFk'];
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
                                    $arRegistro->setNumero($arMovimiento['numero']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrCredito($arMovimiento['vrTotalNeto']);
                                    $arRegistro->setNaturaleza('C');
                                    $arRegistro->setDescripcion("Movimiento");
                                    $arRegistro->setCodigoModeloFk('TesMovimiento');
                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arMovimiento['numero'];
                                    break;
                                }

                                $arMovimientoAct = $em->getRepository(TesMovimiento::class)->find($arMovimiento['codigoMovimientoPk']);
                                $arMovimientoAct->setEstadoContabilizado(1);
                                $em->persist($arMovimientoAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo movimiento] del movimiento " . $arMovimiento['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo movimiento] del movimiento " . $arMovimiento['numero'];
                            break;
                        }
                    }
                } else {
                    $error = "El movimiento " . $codigo . " no existe";
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
