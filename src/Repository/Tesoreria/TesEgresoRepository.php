<?php

namespace App\Repository\Tesoreria;

use App\Entity\Cartera\CarCliente;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Entity\Compra\ComEgresoTipo;
use App\Entity\Compra\ComProveedor;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Entity\Tesoreria\TesEgresoTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesEgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgreso::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEgreso = null;
        $codigoTercero = null;
        $egresoTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoEgreso = $filtros['codigoEgreso'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $egresoTipo = $filtros['egresoTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TesEgreso::class, 'e')
            ->select('e.codigoEgresoPk')
            ->addSelect('et.nombre as tipo')
            ->addSelect('t.nombreCorto as tercero')
            ->addSelect('e.numero')
            ->addSelect('e.fecha')
            ->addSelect('e.fechaPago')
            ->addSelect('e.estadoAnulado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAutorizado')
            ->addSelect('e.estadoImpreso')
            ->leftJoin('e.egresoTipoRel', 'et')
            ->leftJoin('e.terceroRel', 't')
            ->where('e.codigoEgresoPk <> 0');
        if ($codigoEgreso) {
            $queryBuilder->andWhere("e.codigoEgresoPk = '{$codigoEgreso}'");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("e.codigoTerceroFk = '{$codigoTercero}'");
        }
        if ($egresoTipo) {
            $queryBuilder->andWhere("e.codigoEgresoTipoFk = '{$egresoTipo}'");
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
        $queryBuilder->addOrderBy('e.codigoEgresoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $debito = 0;
        $credito = 0;
        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
        $arEgresosDetalle = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $id));
        foreach ($arEgresosDetalle as $arEgresoDetalle) {
            if($arEgresoDetalle->getNaturaleza() == 'D') {
                $debito += $arEgresoDetalle->getVrPago();
            } else {
                $credito += $arEgresoDetalle->getVrPago();
            }
        }
        $totalNeto = $debito - $credito;
        $arEgreso->setVrTotalNeto($totalNeto);
        $em->persist($arEgreso);
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
         * @var $arEgreso ComEgreso
         *
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TesEgreso::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TesEgresoDetalle::class)->findBy(['codigoEgresoFk' => $arRegistro->getCodigoEgresoPk()])) <= 0) {
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

    public function autorizar($arEgreso)
    {
        $em = $this->getEntityManager();
        if ($arEgreso->getEstadoAutorizado() == 0) {
            $error = false;
            if($arEgreso->getVrTotalNeto() >= 0) {
                $arEgresosDetalles = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
                if ($arEgresosDetalles) {
                    foreach ($arEgresosDetalles AS $arEgresoDetalle) {
                        if ($arEgresoDetalle->getCodigoCuentaPagarFk()) {
                            $arCuentaPagarAplicacion = $em->getRepository(TesCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                            if ($arCuentaPagarAplicacion->getVrSaldo() >= $arEgresoDetalle->getVrPago()) {
                                $saldo = $arCuentaPagarAplicacion->getVrSaldo() - $arEgresoDetalle->getVrPago();
                                $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                                $arCuentaPagarAplicacion->setVrSaldo($saldo);
                                $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                                $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() + $arEgresoDetalle->getVrPago());
                                $em->persist($arCuentaPagarAplicacion);
                            } else {
                                Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaPagarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaPagarAplicacion->getVrSaldo());
                                $error = true;
                                break;
                            }

                        }
                    }
                    if ($error == false) {
                        $arEgreso->setEstadoAutorizado(1);
                        $em->persist($arEgreso);
                        $em->flush();
                    }
                } else {
                    Mensajes::error("No se puede autorizar un recibo sin detalles");
                }
            } else {
                Mensajes::error("No se puede autorizar un egreso negativo");
            }
        }
    }

    public function desAutorizar($arEgreso)
    {
        $em = $this->getEntityManager();
        $arEgresoDetalles = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            if($arEgresoDetalle->getCodigoCuentaPagarFk()) {
                $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                $saldo = $arCuentaPagar->getVrSaldo() + $arEgresoDetalle->getVrPago();
                $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
                $arCuentaPagar->setVrSaldo($saldo);
                $arCuentaPagar->setVrSaldoOperado($saldoOperado);
                $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() - $arEgresoDetalle->getVrPago());
                $em->persist($arCuentaPagar);
            }
        }
        $arEgreso->setEstadoAutorizado(0);
        $em->persist($arEgreso);
        $em->flush();
    }

    public function aprobar($arEgreso)
    {
        $em = $this->getEntityManager();
        if ($arEgreso->getEstadoAutorizado()) {
            $arEgresoTipo = $em->getRepository(TesEgresoTipo::class)->find($arEgreso->getCodigoEgresoTipoFk());
            if ($arEgreso->getNumero() == 0 || $arEgreso->getNumero() == NULL) {
                $arEgreso->setNumero($arEgresoTipo->getConsecutivo());
                $arEgresoTipo->setConsecutivo($arEgresoTipo->getConsecutivo() + 1);
                $em->persist($arEgresoTipo);
            }
            $arEgreso->setFecha(new \DateTime('now'));
            $arEgreso->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arEgreso);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arEgreso ComEgreso
     * @return array
     */
    public function anular($arEgreso)
    {
        $em = $this->getEntityManager();
        $respuesta = [];
        if ($arEgreso->getEstadoAprobado() == 1) {
            $arEgresosDetalle = $em->getRepository(ComEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
            foreach ($arEgresosDetalle as $arEgresoDetalle) {
                if ($arEgresoDetalle->getCodigoCuentaPagarFk()) {
                    $arCuentaPagarAplicacion = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                    if ($arCuentaPagarAplicacion->getVrSaldo() <= $arEgresoDetalle->getVrPagoAfectar() || $arCuentaPagarAplicacion->getVrSaldo() == 0) {
                        $saldo = $arCuentaPagarAplicacion->getVrSaldo() + $arEgresoDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                        $arCuentaPagarAplicacion->setVrSaldo($saldo);
                        $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                        $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() - $arEgresoDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaPagarAplicacion);
                    }
                }
                $arEgresoDetalle->setVrDescuento(0);
                $arEgresoDetalle->setVrAjustePeso(0);
                $arEgresoDetalle->setVrRetencionIca(0);
                $arEgresoDetalle->setVrRetencionIva(0);
                $arEgresoDetalle->setVrRetencionFuente(0);
                $arEgresoDetalle->setVrPago(0);
                $arEgresoDetalle->setVrPagoAfectar(0);
            }
            $arEgreso->setVrPago(0);
            $arEgreso->setVrPagoTotal(0);
            $arEgreso->setVrTotalDescuento(0);
            $arEgreso->setVrTotalAjustePeso(0);
            $arEgreso->setVrTotalRetencionIca(0);
            $arEgreso->setVrTotalRetencionIva(0);
            $arEgreso->setVrTotalRetencionFuente(0);
            $arEgreso->setEstadoAnulado(1);
            $this->_em->persist($arEgreso);
            $this->_em->flush();
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesEgreso::class, 'e')
            ->select('e.codigoEgresoPk')
            ->addSelect('e.numero')
            ->addSelect('e.fecha')
            ->addSelect('e.fechaPago')
            ->addSelect('e.vrTotalNeto')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoContabilizado')
            ->addSelect('e.codigoTerceroFk')
            ->addSelect('et.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->leftJoin('e.egresoTipoRel', 'et')
            ->leftJoin('e.cuentaRel', 'c')
            ->where('e.codigoEgresoPk = ' . $codigo);
        $arRecibo = $queryBuilder->getQuery()->getSingleResult();
        return $arRecibo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarEgreso();
            foreach ($arr AS $codigo) {
                $arEgreso = $em->getRepository(TesEgreso::class)->registroContabilizar($codigo);
                if ($arEgreso) {
                    if ($arEgreso['estadoAprobado'] == 1 && $arEgreso['estadoContabilizado'] == 0) {
                        if ($arEgreso['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arEgreso['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $fecha = $arEgreso['fechaPago'];
                                $arTercero = $em->getRepository(TesTercero::class)->terceroFinanciero($arEgreso['codigoTerceroFk']);
                                $arEgresoDetalles = $em->getRepository(TesEgresoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arEgresoDetalles as $arEgresoDetalle) {
                                    //Cuenta proveedor
                                    if ($arEgresoDetalle['vrPago'] > 0) {
                                        $arTerceroDetalle = null;
                                        if($arEgresoDetalle['codigoTerceroFk']) {
                                            $arTerceroDetalle = $em->getRepository(TesTercero::class)->terceroFinanciero($arEgresoDetalle['codigoTerceroFk']);
                                        }
                                        $descripcion = "PROVEEDORES DOC " . $arEgresoDetalle['numeroDocumento'] ;
                                        $cuenta = $arEgresoDetalle['codigoCuentaFk'];
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
                                            $arRegistro->setNumero($arEgreso['numero']);
                                            $arRegistro->setNumeroReferencia($arEgresoDetalle['numeroDocumento']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($fecha);
                                            if($arEgresoDetalle['naturaleza'] == 'D') {
                                                $arRegistro->setVrDebito($arEgresoDetalle['vrPago']);
                                            } else {
                                                $arRegistro->setVrCredito($arEgresoDetalle['vrPago']);
                                            }
                                            $arRegistro->setNaturaleza($arEgresoDetalle['naturaleza']);
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('TesEgreso');
                                            $arRegistro->setCodigoDocumento($arEgreso['codigoEgresoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "La cuenta no existe" . $descripcion;
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                $cuenta = $arEgreso['codigoCuentaContableFk'];
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
                                    $arRegistro->setNumero($arEgreso['numero']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrCredito($arEgreso['vrTotalNeto']);
                                    $arRegistro->setNaturaleza('C');
                                    $arRegistro->setDescripcion("Egreso");
                                    $arRegistro->setCodigoModeloFk('TesEgreso');
                                    $arRegistro->setCodigoDocumento($arEgreso['codigoEgresoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arEgreso['numero'];
                                    break;
                                }

                                $arEgresoAct = $em->getRepository(TesEgreso::class)->find($arEgreso['codigoEgresoPk']);
                                $arEgresoAct->setEstadoContabilizado(1);
                                $em->persist($arEgresoAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo egreso] del egreso " . $arEgreso['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo egreso] del egreso " . $arEgreso['numero'];
                            break;
                        }
                    }
                } else {
                    $error = "El egreso " . $codigo . " no existe";
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
