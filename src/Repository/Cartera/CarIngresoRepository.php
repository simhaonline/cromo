<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarIngreso;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\CarIngresoTipo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenImpuesto;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarIngresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarIngreso::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $numero = null;
        $codigoIngreso = null;
        $codigoIngresoTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoIngreso = $filtros['codigoIngreso'] ?? null;
            $codigoIngresoTipo = $filtros['codigoIngresoTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarIngreso::class, 'i')
            ->select('i.codigoIngresoPk')
            ->addSelect('i.numero')
            ->addSelect('it.nombre as ingresoTipo')
            ->addSelect('i.fecha')
            ->addSelect('i.fechaPago')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('cu.nombre as cuenta')
            ->addSelect('i.vrTotalNeto')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->leftJoin('i.ingresoTipoRel', 'it')
            ->leftJoin('i.clienteRel', 'cl')
            ->leftJoin('i.cuentaRel', 'cu');
        if ($codigoIngreso) {
            $queryBuilder->andWhere("i.codigoIngresoPk = '{$codigoIngreso}'");
        }
        if ($codigoCliente) {
            $queryBuilder->andWhere("i.codigoClienteFk = '{$codigoCliente}'");
        }
        if ($codigoIngresoTipo) {
            $queryBuilder->andWhere("it.codigoIngresoTipoPk = '{$codigoIngresoTipo}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("i.numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("i.fechaPago >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("i.fechaPago <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('i.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('i.numero', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $debito = 0;
        $credito = 0;
        $retencionTotal = 0;
        $arIngreso = $em->getRepository(CarIngreso::class)->find($id);
        $arIngresosDetalle = $em->getRepository(CarIngresoDetalle::class)->findBy(array('codigoIngresoFk' => $id));
        foreach ($arIngresosDetalle as $arIngresoDetalle) {
            if ($arIngresoDetalle->getNaturaleza() == 'D') {
                $debito += $arIngresoDetalle->getVrPago();
            } else {
                $credito += $arIngresoDetalle->getVrPago();
            }

            $retencionTotal += $arIngresoDetalle->getVrRetencion();
        }
        $totalBruto = $credito - $debito;
        $totalNeto = $totalBruto - $retencionTotal;
        $arIngreso->setVrTotalBruto($totalBruto);
        $arIngreso->setVrRetencion($retencionTotal);
        $arIngreso->setVrTotalNeto($totalNeto);
        $em->persist($arIngreso);
        $em->flush();
        return true;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CarIngreso::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(CarIngresoDetalle::class)->findBy(['codigoIngresoFk' => $arRegistro->getCodigoIngresoPk()])) <= 0) {
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

    public function autorizar($arIngreso)
    {
        $em = $this->getEntityManager();
        if ($arIngreso->getEstadoAutorizado() == 0) {
            $error = false;
            if ($arIngreso->getVrTotalNeto() >= 0) {
                $arIngresosDetalles = $em->getRepository(CarIngresoDetalle::class)->findBy(array('codigoIngresoFk' => $arIngreso->getCodigoIngresoPk()));
                if ($arIngresosDetalles) {
                    foreach ($arIngresosDetalles AS $arIngresoDetalle) {
                        if ($arIngresoDetalle->getCodigoCuentaCobrarFk()) {
                            $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arIngresoDetalle->getCodigoCuentaCobrarFk());
                            if ($arCuentaCobrarAplicacion->getVrSaldo() >= $arIngresoDetalle->getVrPago()) {
                                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() - $arIngresoDetalle->getVrPago();
                                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                                $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() + $arIngresoDetalle->getVrPago());
                                $em->persist($arCuentaCobrarAplicacion);
                            } else {
                                Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getVrSaldo());
                                $error = true;
                                break;
                            }
                        }
                        if (!$arIngresoDetalle->getCodigoCuentaFk()) {
                            Mensajes::error('En detalle ' . $arIngresoDetalle->getCodigoIngresoDetallePk() . " no tiene asignada una cuenta");
                            $error = true;
                            break;
                        }
                    }
                    if ($error == false) {
                        $arIngreso->setEstadoAutorizado(1);
                        $em->persist($arIngreso);
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

    public function desAutorizar($arIngreso)
    {
        $em = $this->getEntityManager();
        $arIngresoDetalles = $em->getRepository(CarIngresoDetalle::class)->findBy(array('codigoIngresoFk' => $arIngreso->getCodigoIngresoPk()));
        foreach ($arIngresoDetalles AS $arIngresoDetalle) {
            if ($arIngresoDetalle->getCodigoCuentaCobrarFk()) {
                $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arIngresoDetalle->getCodigoCuentaCobrarFk());
                $saldo = $arCuentaCobrar->getVrSaldo() + $arIngresoDetalle->getVrPago();
                $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                $arCuentaCobrar->setVrSaldo($saldo);
                $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arIngresoDetalle->getVrPago());
                $em->persist($arCuentaCobrar);
            }
        }
        $arIngreso->setEstadoAutorizado(0);
        $em->persist($arIngreso);
        $em->flush();
    }

    public function aprobar($arIngreso)
    {
        $em = $this->getEntityManager();
        if ($arIngreso->getEstadoAutorizado()) {
            $arIngresoTipo = $em->getRepository(CarIngresoTipo::class)->find($arIngreso->getCodigoIngresoTipoFk());
            if ($arIngreso->getNumero() == 0 || $arIngreso->getNumero() == NULL) {
                $arIngreso->setNumero($arIngresoTipo->getConsecutivo());
                $arIngresoTipo->setConsecutivo($arIngresoTipo->getConsecutivo() + 1);
                $em->persist($arIngresoTipo);
            }
            $arIngreso->setFecha(new \DateTime('now'));
            $arIngreso->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arIngreso);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arIngreso CarIngreso
     * @return array
     */
    public function anular($arIngreso)
    {
        $em = $this->getEntityManager();
        $respuesta = '';
        if ($arIngreso->getEstadoContabilizado() == 0) {
            if ($arIngreso->getEstadoAprobado() == 1) {
                $arIngresosDetalle = $em->getRepository(CarIngresoDetalle::class)->findBy(array('codigoIngresoFk' => $arIngreso->getCodigoIngresoPk()));
                foreach ($arIngresosDetalle as $arIngresoDetalle) {
                    if ($arIngresoDetalle->getCodigoCuentaCobrarFk()) {
                        $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arIngresoDetalle->getCodigoCuentaCobrarFk());
                        if ($arCuentaCobrarAplicacion->getVrSaldo() <= $arIngresoDetalle->getVrPago() || $arCuentaCobrarAplicacion->getVrSaldo() == 0) {
                            $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arIngresoDetalle->getVrPago();
                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                            $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                            $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arIngresoDetalle->getVrPago());
                            $em->persist($arCuentaCobrarAplicacion);
                        }
                    }
                    $arIngresoDetalle->setVrPago(0);
                    $arIngresoDetalle->setVrRetencion(0);
                }
                $arIngreso->setVrRetencion(0);
                $arIngreso->setVrTotalBruto(0);
                $arIngreso->setVrTotalBruto(0);
                $arIngreso->setEstadoAnulado(1);
                $this->_em->persist($arIngreso);
                $this->_em->flush();
            }
        } else {
            $respuesta = "El registro se encuentra contabilizado";
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarIngreso::class, 'i')
            ->select('i.codigoIngresoPk')
            ->addSelect('i.numero')
            ->addSelect('i.fechaPago')
            ->addSelect('i.vrTotalNeto')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoContabilizado')
            ->addSelect('i.codigoClienteFk')
            ->addSelect('it.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->leftJoin('i.ingresoTipoRel', 'it')
            ->leftJoin('i.cuentaRel', 'c')
            ->where('i.codigoIngresoPk = ' . $codigo);
        $arRecibo = $queryBuilder->getQuery()->getSingleResult();
        return $arRecibo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarIngreso();
            foreach ($arr AS $codigo) {
                $arIngreso = $em->getRepository(CarIngreso::class)->registroContabilizar($codigo);
                if ($arIngreso) {
                    if ($arIngreso['estadoAprobado'] == 1 && $arIngreso['estadoContabilizado'] == 0) {
                        if ($arIngreso['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arIngreso['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $fecha = $arIngreso['fechaPago'];
                                $arCliente = $em->getRepository(CarCliente::class)->terceroFinanciero($arIngreso['codigoClienteFk']);
                                $arIngresoDetalles = $em->getRepository(CarIngresoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arIngresoDetalles as $arIngresoDetalle) {
                                    //Cuenta proveedor
                                    if ($arIngresoDetalle['vrPago'] > 0) {
                                        $arClienteDetalle = null;
                                        if ($arIngresoDetalle['codigoClienteFk']) {
                                            $arClienteDetalle = $em->getRepository(CarCliente::class)->terceroFinanciero($arIngresoDetalle['codigoClienteFk']);
                                        }
                                        $descripcion = "CLIENTES DOC " . $arIngresoDetalle['numero'];
                                        $cuenta = $arIngresoDetalle['codigoCuentaFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();

                                            $arRegistro->setTerceroRel($arClienteDetalle);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arIngreso['numero']);
                                            $arRegistro->setNumeroReferencia($arIngresoDetalle['numero']);
                                            $arRegistro->setFecha($fecha);
                                            $arRegistro->setFechaVence($fecha);
                                            if ($arIngresoDetalle['naturaleza'] == 'D') {
                                                $arRegistro->setVrDebito($arIngresoDetalle['vrPago']);
                                            } else {
                                                $arRegistro->setVrCredito($arIngresoDetalle['vrPago']);
                                            }
                                            $arRegistro->setNaturaleza($arIngresoDetalle['naturaleza']);
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarIngreso');
                                            $arRegistro->setCodigoDocumento($arIngreso['codigoIngresoPk']);
                                            $em->persist($arRegistro);

                                            if ($arIngresoDetalle['codigoImpuestoRetencionFk'] != "R00") {
                                                if ($arIngresoDetalle['vrRetencion'] > 0) {
                                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arIngresoDetalle['codigoImpuestoRetencionFk']);
                                                    $cuenta = $arImpuesto->getCodigoCuentaFk();
                                                    if ($cuenta) {
                                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                                        if (!$arCuenta) {
                                                            $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                            break;
                                                        }
                                                        $arRegistro = new FinRegistro();
                                                        $arRegistro->setTerceroRel($arClienteDetalle);
                                                        $arRegistro->setCuentaRel($arCuenta);
                                                        $arRegistro->setComprobanteRel($arComprobante);
                                                        $arRegistro->setNumero($arIngreso['numero']);
                                                        $arRegistro->setNumeroReferencia($arIngresoDetalle['numero']);
                                                        $arRegistro->setFecha($fecha);
                                                        $arRegistro->setFechaVence($fecha);
                                                        $arRegistro->setVrDebito($arIngresoDetalle['vrRetencion']);
                                                        $arRegistro->setNaturaleza("D");
                                                        $arRegistro->setDescripcion("Retencion");
                                                        $arRegistro->setCodigoModeloFk('CarIngreso');
                                                        $arRegistro->setCodigoDocumento($arIngreso['codigoIngresoPk']);
                                                        $em->persist($arRegistro);
                                                    } else {
                                                        $error = "La cuenta no existe" . $descripcion;
                                                        break;
                                                    }
                                                }
                                            }
                                        } else {
                                            $error = "La cuenta no existe" . $descripcion;
                                            break;
                                        }
                                    }
                                }

                                //Cuenta banco
                                $cuenta = $arIngreso['codigoCuentaContableFk'];
                                if ($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    //$arRegistro->setTerceroRel($arCliente);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arIngreso['numero']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrDebito($arIngreso['vrTotalNeto']);
                                    $arRegistro->setNaturaleza('D');
                                    $arRegistro->setDescripcion("Ingreso");
                                    $arRegistro->setCodigoModeloFk('CarIngreso');
                                    $arRegistro->setCodigoDocumento($arIngreso['codigoIngresoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arIngreso['numero'];
                                    break;
                                }

                                $arIngresoAct = $em->getRepository(CarIngreso::class)->find($arIngreso['codigoIngresoPk']);
                                $arIngresoAct->setEstadoContabilizado(1);
                                $em->persist($arIngresoAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo egreso] del egreso " . $arIngreso['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo egreso] del egreso " . $arIngreso['numero'];
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