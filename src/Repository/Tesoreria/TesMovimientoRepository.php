<?php

namespace App\Repository\Tesoreria;


use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoClase;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesMovimientoTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TesMovimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        $estadoContabilizado = null;

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
            $estadoContabilizado = $filtros['estadoContabilizado'] ?? null;
        }

        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.codigoMovimientoClaseFk')
            ->addSelect('mt.nombre as tipo')
            ->addSelect('t.nombreCorto as tercero')
            ->addSelect('m.numero')
            ->addSelect('m.numeroDocumento')
            ->addSelect('m.fecha')
            ->addSelect('m.vrTotalNeto')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.estadoContabilizado')
            ->leftJoin('m.movimientoTipoRel', 'mt')
            ->leftJoin('m.terceroRel', 't')
            ->where("m.codigoMovimientoClaseFk = '{$clase}'");
        if ($codigoMovimiento) {
            $queryBuilder->andWhere("m.codigoMovimientoPk = {$codigoMovimiento}");
        }
        if ($numero) {
            $queryBuilder->andWhere("m.numero = {$numero}");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("m.codigoTerceroFk = '{$codigoTercero}'");
        }
        if ($movimientoTipo) {
            $queryBuilder->andWhere("m.codigoMovimientoTipoFk = '{$movimientoTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("m.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("m.fecha <= '{$fechaHasta} 23:59:59'");
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
                $queryBuilder->andWhere("m.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("m.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoAnulado = 1");
                break;
        }
        switch ($estadoContabilizado) {
            case '0':
                $queryBuilder->andWhere("m.estadoContabilizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("m.estadoContabilizado = 1");
                break;
        }
        $queryBuilder->addOrderBy('m.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('m.codigoMovimientoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $debito = 0;
        $credito = 0;
        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        $arDocumento = $em->getRepository(TesMovimientoClase::class)->find($arMovimiento->getCodigoMovimientoClaseFk());
        $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $id));
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getNaturaleza() == 'D') {
                $debito += $arMovimientoDetalle->getVrPago();
            } else {
                $credito += $arMovimientoDetalle->getVrPago();
            }
        }
        $totalNeto = 0;
        if($arDocumento->getNaturaleza() == 'D') {
            $totalNeto = $debito - $credito;
        }
        if($arDocumento->getNaturaleza() == 'C') {
            $totalNeto = $credito - $debito;
        }
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
            if ($arMovimiento->getVrTotalNeto() >= 0) {
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
                        if ($arMovimientoDetalle->getCodigoCuentaFk()) {
                            /** @var $arCuenta FinCuenta 7168 */
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arMovimientoDetalle->getCodigoCuentaFk());
                            if ($arCuenta) {
                                if ($arCuenta->getExigeCentroCosto() && $arMovimientoDetalle->getCodigoCentroCostoFk() == null) {
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoIngresoDetallePk() . " la cuenta exige centro de costos y no tiene");
                                    $error = true;
                                    break;
                                }
                                if($arCuenta->getExigeBase() && $arMovimientoDetalle->getVrBase() <= 0) {
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " exige base no tiene");
                                    $error = true;
                                    break;
                                }
                                if($arCuenta->getExigeDocumentoReferencia() && $arMovimientoDetalle->getCodigoCuentaPagarFk() == null && $arMovimiento->getMovimientoClaseRel() == 'EG') {
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " exige documento referencia y no lo tiene");
                                    $error = true;
                                    break;
                                }
                                if(!$arCuenta->getPermiteMovimiento()) {
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " la cuenta no permite movimiento");
                                    $error = true;
                                    break;
                                }
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
            if ($arMovimientoDetalle->getCodigoCuentaPagarFk()) {
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
            $em->persist($arMovimiento);
            if ($arMovimiento->getMovimientoTipoRel()->getGeneraCuentaPagar()) {
                $this->generarCuentaPagar($arMovimiento);
            }
            $em->flush();
            $em->getRepository(TesMovimiento::class)->contabilizar(['codigoMovimientoPk' => $arMovimiento->getCodigoMovimientoPk()]);
        }
    }

    /**
     * @param $arMovimiento TesMovimiento
     * @return array
     */
    public function anular($arMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = [];
        if ($arMovimiento->getEstadoAprobado() == 1 && $arMovimiento->getEstadoContabilizado() == 0) {
            $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
            foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                if ($arMovimientoDetalle->getCodigoCuentaPagarFk()) {
                    $arCuentaPagarAplicacion = $em->getRepository(TesCuentaPagar::class)->find($arMovimientoDetalle->getCodigoCuentaPagarFk());
                    if ($arCuentaPagarAplicacion->getVrSaldo() <= $arMovimientoDetalle->getVrPago() || $arCuentaPagarAplicacion->getVrSaldo() == 0) {
                        $saldo = $arCuentaPagarAplicacion->getVrSaldo() + $arMovimientoDetalle->getVrPago();
                        $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                        $arCuentaPagarAplicacion->setVrSaldo($saldo);
                        $arCuentaPagarAplicacion->setVrSaldoOperado($saldoOperado);
                        $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() - $arMovimientoDetalle->getVrPago());
                        $em->persist($arCuentaPagarAplicacion);
                    }
                }
                $arMovimientoDetalle->setVrPago(0);
            }
            $arMovimiento->setVrTotalNeto(0);
            $arMovimiento->setEstadoAnulado(1);
            $this->_em->persist($arMovimiento);
            $this->_em->flush();
        } else {
            Mensajes::error();
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.numero')
            ->addSelect('m.numeroDocumento')
            ->addSelect('m.fecha')
            ->addSelect('m.vrTotalNeto')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.codigoTerceroFk')
            ->addSelect('mt.codigoComprobanteFk')
            ->addSelect('mt.codigoCuentaFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->addSelect('m.codigoMovimientoClaseFk')
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
                                        if ($arMovimientoDetalle['codigoTerceroFk']) {
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
                                            if($arMovimiento['codigoMovimientoClaseFk'] == 'CP') {
                                                $arRegistro->setNumeroReferencia($arMovimientoDetalle['numero']);
                                            } else {
                                                $arRegistro->setNumeroReferencia($arMovimientoDetalle['numeroDocumento']);
                                            }

                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($fecha);
                                            if ($arMovimientoDetalle['naturaleza'] == 'D') {
                                                $arRegistro->setVrDebito($arMovimientoDetalle['vrPago']);
                                            } else {
                                                $arRegistro->setVrCredito($arMovimientoDetalle['vrPago']);
                                            }
                                            $arRegistro->setVrBase($arMovimientoDetalle['vrBase']);
                                            $arRegistro->setNaturaleza($arMovimientoDetalle['naturaleza']);
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('TesMovimiento');
                                            $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                            if($arMovimientoDetalle['codigoCentroCostoFk']) {
                                                $arRegistro->setCentroCostoRel($em->getReference(FinCentroCosto::class, $arMovimientoDetalle['codigoCentroCostoFk']));
                                            }
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "La cuenta " . $cuenta . " no existe movimiento " . $arMovimiento['numero'];
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                if ($arMovimiento['codigoMovimientoClaseFk'] == 'EG') {
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
                                }

                                //Cuenta por pagar
                                if ($arMovimiento['codigoMovimientoClaseFk'] == 'CP') {

                                    $cuenta = $arMovimiento['codigoCuentaFk'];
                                    if ($cuenta) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta  " . $cuenta;
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arMovimiento['numero']);
                                        if($arCuenta->getExigeDocumentoReferencia()) {
                                            $arRegistro->setNumeroReferencia($arMovimiento['numeroDocumento']);
                                        }
                                        $arRegistro->setFecha($fecha);
                                        $arRegistro->setVrCredito($arMovimiento['vrTotalNeto']);
                                        $arRegistro->setNaturaleza('C');
                                        $arRegistro->setDescripcion("Compra");
                                        $arRegistro->setCodigoModeloFk('TesMovimiento');
                                        $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo no tiene configurada la cuenta contable " . $arMovimiento['numero'];
                                        break;
                                    }
                                }

                                //Cuenta por pagar
                                if ($arMovimiento['codigoMovimientoClaseFk'] == 'NC') {
                                    $cuenta = $arMovimiento['codigoCuentaFk'];
                                    if ($cuenta) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta  " . $cuenta;
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arMovimiento['numero']);
                                        if($arCuenta->getExigeDocumentoReferencia()) {
                                            $arRegistro->setNumeroReferencia($arMovimiento['numeroDocumento']);
                                        }
                                        $arRegistro->setFecha($fecha);
                                        $arRegistro->setVrDebito($arMovimiento['vrTotalNeto']);
                                        $arRegistro->setNaturaleza('D');
                                        $arRegistro->setDescripcion("Nota credito");
                                        $arRegistro->setCodigoModeloFk('TesMovimiento');
                                        $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo no tiene configurada la cuenta contable " . $arMovimiento['numero'];
                                        break;
                                    }
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

    /**
     * @param $arMovimiento TesMovimiento
     * @throws \Doctrine\ORM\ORMException
     */
    public function generarCuentaPagar($arMovimiento)
    {
        $em = $this->getEntityManager();
        if ($arMovimiento->getMovimientoTipoRel()->getCodigoCuentaPagarTipoFk()) {
            if ($arMovimiento->getVrTotalNeto() > 0) {
                $arCuentaPagar = New TesCuentaPagar();
                $arCuentaPagar->setCuentaPagarTipoRel($arMovimiento->getMovimientoTipoRel()->getCuentaPagarTipoRel());
                $arCuentaPagar->setTerceroRel($arMovimiento->getTerceroRel());
                $arCuentaPagar->setModulo('tes');
                $arCuentaPagar->setModelo('TesMovimiento');
                $arCuentaPagar->setCodigoDocumento($arMovimiento->getCodigoMovimientoPk());
                $arCuentaPagar->setNumeroDocumento($arMovimiento->getNumeroDocumento());
                $arCuentaPagar->setNumeroReferencia($arMovimiento->getNumero());
                $arCuentaPagar->setFecha($arMovimiento->getFecha());
                $arCuentaPagar->setFechaVence($arMovimiento->getFecha());
                $arCuentaPagar->setVrTotal($arMovimiento->getVrTotalNeto());
                $arCuentaPagar->setVrSaldoOriginal($arMovimiento->getVrTotalNeto());
                $arCuentaPagar->setVrSaldo($arMovimiento->getVrTotalNeto());
                $arCuentaPagar->setVrSaldoOperado($arMovimiento->getVrTotalNeto() * $arMovimiento->getMovimientoTipoRel()->getCuentaPagarTipoRel()->getOperacion());
                $arCuentaPagar->setEstadoAutorizado(1);
                $arCuentaPagar->setEstadoAprobado(1);
                $arCuentaPagar->setOperacion($arMovimiento->getMovimientoTipoRel()->getCuentaPagarTipoRel()->getOperacion());
                $em->persist($arCuentaPagar);
            }
        } else {
            Mensajes::error("El tipo de movimiento no tiene configurada un tipo de cuenta por pagar");
        }

    }

    public function listaFormatoMasivo($arrDatos)
    {
        $codigoMovimientoPk = null;
        $codigoMovimientoDesde = null;
        $codigoMovimientoHasta = null;
        $codigoMovimientoClase = null;
        $fechaDesde=null;
        $fechaHasta=null;
        if ($arrDatos) {
            $codigoMovimientoPk = $arrDatos['codigoMovimientoPk'] ?? null;
            $codigoMovimientoDesde = $arrDatos['codigoMovimientoDesde'] ?? null;
            $codigoMovimientoHasta = $arrDatos['codigoMovimientoHasta'] ?? null;
            $codigoMovimientoClase = $arrDatos['codigoMovimientoClase'] ?? null;
            $fechaDesde = $arrDatos['fechaDesde'] ?? null;
            $fechaHasta = $arrDatos['fechaHasta'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.fecha')
            ->addSelect('m.numero')
            ->addSelect('m.vrTotalNeto')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.comentarios')
            ->addSelect('tl.nombreCorto')
            ->addSelect('tl.numeroIdentificacion')
            ->addSelect('tl.direccion')
            ->addSelect('tl.telefono')
            ->leftJoin('m.movimientoTipoRel', 'it')
            ->leftJoin('m.terceroRel', 'tl')
            ->leftJoin('m.cuentaRel', 'cu');
        if($codigoMovimientoPk){
            $queryBuilder->where("m.codigoMovimientoPk = {$codigoMovimientoPk}");
        } else {
            if ($codigoMovimientoDesde) {
                $queryBuilder->andWhere("m.numero >= '{$codigoMovimientoDesde}'");
            }
            if ($codigoMovimientoHasta) {
                $queryBuilder->andWhere("m.numero <= '{$codigoMovimientoHasta}'");
            }
            if ($fechaDesde) {
                $queryBuilder->andWhere("m.fecha >= '{$fechaDesde} 00:00:00'");
            }
            if ($fechaHasta) {
                $queryBuilder->andWhere("m.fecha <= '{$fechaHasta} 23:59:59'");
            }
            if ($codigoMovimientoClase) {
                $queryBuilder->andWhere("m.codigoMovimientoClaseFk = '{$codigoMovimientoClase}'");
            }
        }

        return $queryBuilder->getQuery()->getResult();

    }

}
