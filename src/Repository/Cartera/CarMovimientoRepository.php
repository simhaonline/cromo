<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarMovimiento;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\Cartera\CarMovimientoTipo;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenImpuesto;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class CarMovimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarMovimiento::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $clase = $raw['codigoMovimientoClase'] ?? null;

        $codigoCliente = null;
        $numero = null;
        $codigoMovimiento = null;
        $codigoMovimientoTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $estadoContabilizado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoMovimiento = $filtros['codigoMovimiento'] ?? null;
            $codigoMovimientoTipo = $filtros['codigoMovimientoTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $estadoContabilizado = $filtros['estadoContabilizado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimiento::class, 'i')
            ->select('i.codigoMovimientoPk')
            ->addSelect('i.numero')
            ->addSelect('it.nombre as movimientoTipo')
            ->addSelect('i.fecha')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('cu.nombre as cuenta')
            ->addSelect('i.vrTotalNeto')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->addSelect('i.estadoContabilizado')
            ->leftJoin('i.movimientoTipoRel', 'it')
            ->leftJoin('i.clienteRel', 'cl')
            ->leftJoin('i.cuentaRel', 'cu')
            ->where("i.codigoMovimientoClaseFk = '{$clase}'");
        if ($codigoMovimiento) {
            $queryBuilder->andWhere("i.codigoMovimientoPk = '{$codigoMovimiento}'");
        }
        if ($codigoCliente) {
            $queryBuilder->andWhere("i.codigoClienteFk = '{$codigoCliente}'");
        }
        if ($codigoMovimientoTipo) {
            $queryBuilder->andWhere("it.codigoMovimientoTipoPk = '{$codigoMovimientoTipo}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("i.numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("i.fecha >= '{$fechaDesde}'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("i.fecha <= '{$fechaHasta}'");
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
        switch ($estadoContabilizado) {
            case '0':
                $queryBuilder->andWhere("i.estadoContabilizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoContabilizado = 1");
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
        $arMovimiento = $em->getRepository(CarMovimiento::class)->find($id);
        $arMovimientosDetalle = $em->getRepository(CarMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $id));
        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getNaturaleza() == 'D') {
                $debito += $arMovimientoDetalle->getVrPago();
            } else {
                $credito += $arMovimientoDetalle->getVrPago();
            }

            $retencionTotal += $arMovimientoDetalle->getVrRetencion();
        }
        $totalBruto = $credito - $debito;
        $totalNeto = $totalBruto - $retencionTotal;
        $arMovimiento->setVrTotalBruto($totalBruto);
        $arMovimiento->setVrRetencion($retencionTotal);
        $arMovimiento->setVrTotalNeto($totalNeto);
        $em->persist($arMovimiento);
        $em->flush();
        return true;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CarMovimiento::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(CarMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arRegistro->getCodigoMovimientoPk()])) <= 0) {
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
                $arMovimientosDetalles = $em->getRepository(CarMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
                if ($arMovimientosDetalles) {
                    foreach ($arMovimientosDetalles AS $arMovimientoDetalle) {
                        if ($arMovimientoDetalle->getCodigoCuentaCobrarFk()) {
                            $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arMovimientoDetalle->getCodigoCuentaCobrarFk());
                            if ($arCuentaCobrarAplicacion->getVrSaldo() >= $arMovimientoDetalle->getVrPago()) {
                                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() - $arMovimientoDetalle->getVrPago();
                                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                                $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() + $arMovimientoDetalle->getVrPago());
                                $em->persist($arCuentaCobrarAplicacion);
                            } else {
                                Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getVrSaldo());
                                $error = true;
                                break;
                            }
                        }
                        if ($arMovimientoDetalle->getCodigoCuentaFk()) {
                            /** @var $arCuenta FinCuenta 7168*/
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arMovimientoDetalle->getCodigoCuentaFk());
                            if ($arCuenta){
                                if ($arCuenta->getExigeCentroCosto() && $arMovimientoDetalle->getCodigoCentroCostoFk() == null){
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " la cuenta exige centro de costos y no tiene");
                                    $error = true;
                                    break;
                                }
                                if($arCuenta->getExigeBase() && $arMovimientoDetalle->getVrBase() <= 0) {
                                    Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " exige base no tiene");
                                    $error = true;
                                    break;
                                }
                                if($arCuenta->getExigeDocumentoReferencia() && $arMovimientoDetalle->getCodigoCuentaCobrarFk() == null) {
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
                        }else{
                            Mensajes::error('En detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk() . " no tiene asignada una cuenta");
                            $error = true;
                            break;
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
                Mensajes::error("No se puede autorizar un egreso negativo");
            }
        }
    }

    public function desAutorizar($arMovimiento)
    {
        $em = $this->getEntityManager();
        $arMovimientoDetalles = $em->getRepository(CarMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getCodigoCuentaCobrarFk()) {
                $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arMovimientoDetalle->getCodigoCuentaCobrarFk());
                $saldo = $arCuentaCobrar->getVrSaldo() + $arMovimientoDetalle->getVrPago();
                $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                $arCuentaCobrar->setVrSaldo($saldo);
                $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arMovimientoDetalle->getVrPago());
                $em->persist($arCuentaCobrar);
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
            $arMovimientoTipo = $em->getRepository(CarMovimientoTipo::class)->find($arMovimiento->getCodigoMovimientoTipoFk());
            if ($arMovimiento->getNumero() == 0 || $arMovimiento->getNumero() == NULL) {
                $arMovimiento->setNumero($arMovimientoTipo->getConsecutivo());
                $arMovimientoTipo->setConsecutivo($arMovimientoTipo->getConsecutivo() + 1);
                $em->persist($arMovimientoTipo);
            }
            $arMovimiento->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arMovimiento);
            $this->getEntityManager()->flush();
            $em->getRepository(CarMovimiento::class)->contabilizar(['codigoMovimientoPk' => $arMovimiento->getCodigoMovimientoPk()]);
        }
    }

    /**
     * @param $arMovimiento CarMovimiento
     * @return array
     */
    public function anular($arMovimiento)
    {
        $em = $this->getEntityManager();
        $respuesta = '';
        if ($arMovimiento->getEstadoContabilizado() == 0) {
            if ($arMovimiento->getEstadoAprobado() == 1) {
                $arMovimientosDetalle = $em->getRepository(CarMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
                foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                    if ($arMovimientoDetalle->getCodigoCuentaCobrarFk()) {
                        $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arMovimientoDetalle->getCodigoCuentaCobrarFk());
                        if ($arCuentaCobrarAplicacion->getVrSaldo() <= $arMovimientoDetalle->getVrPago() || $arCuentaCobrarAplicacion->getVrSaldo() == 0) {
                            $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arMovimientoDetalle->getVrPago();
                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                            $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                            $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arMovimientoDetalle->getVrPago());
                            $em->persist($arCuentaCobrarAplicacion);
                        }
                    }
                    $arMovimientoDetalle->setVrPago(0);
                    $arMovimientoDetalle->setVrRetencion(0);
                    $arMovimientoDetalle->setVrBase(0);
                    $em->persist($arMovimientoDetalle);

                }
                $arMovimiento->setVrRetencion(0);
                $arMovimiento->setVrTotalBruto(0);
                $arMovimiento->setVrTotalNeto(0);
                $arMovimiento->setEstadoAnulado(1);
                $em->persist($arMovimiento);
                $em->flush();
            }
        } else {
            $respuesta = "El registro se encuentra contabilizado";
        }
        return $respuesta;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimiento::class, 'i')
            ->select('i.codigoMovimientoPk')
            ->addSelect('i.numero')
            ->addSelect('i.fecha')
            ->addSelect('i.vrTotalNeto')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoContabilizado')
            ->addSelect('i.codigoClienteFk')
            ->addSelect('it.codigoComprobanteFk')
            ->addSelect('c.codigoCuentaContableFk')
            ->leftJoin('i.movimientoTipoRel', 'it')
            ->leftJoin('i.cuentaRel', 'c')
            ->where('i.codigoMovimientoPk = ' . $codigo);
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
                $arMovimiento = $em->getRepository(CarMovimiento::class)->registroContabilizar($codigo);
                if ($arMovimiento) {
                    if ($arMovimiento['estadoAprobado'] == 1 && $arMovimiento['estadoContabilizado'] == 0) {
                        if ($arMovimiento['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arMovimiento['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $fecha = $arMovimiento['fecha'];
                                $arCliente = $em->getRepository(CarCliente::class)->terceroFinanciero($arMovimiento['codigoClienteFk']);
                                $arMovimientoDetalles = $em->getRepository(CarMovimientoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                                    //Cuenta proveedor
                                    if ($arMovimientoDetalle['vrPago'] > 0) {
                                        $arClienteDetalle = null;
                                        if ($arMovimientoDetalle['codigoClienteFk']) {
                                            $arClienteDetalle = $em->getRepository(CarCliente::class)->terceroFinanciero($arMovimientoDetalle['codigoClienteFk']);
                                        }
                                        $descripcion = "CLIENTES DOC " . $arMovimientoDetalle['numero'];
                                        $cuenta = $arMovimientoDetalle['codigoCuentaFk'];
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
                                            $arRegistro->setNumero($arMovimiento['numero']);
                                            $arRegistro->setNumeroReferencia($arMovimientoDetalle['numero']);
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
                                            $arRegistro->setCodigoModeloFk('CarMovimiento');
                                            $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                            if($arMovimientoDetalle['codigoCentroCostoFk']) {
                                                $arRegistro->setCentroCostoRel($em->getReference(FinCentroCosto::class, $arMovimientoDetalle['codigoCentroCostoFk']));
                                            }
                                            $em->persist($arRegistro);

                                            if ($arMovimientoDetalle['codigoImpuestoRetencionFk'] != "R00") {
                                                if ($arMovimientoDetalle['vrRetencion'] > 0) {
                                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arMovimientoDetalle['codigoImpuestoRetencionFk']);
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
                                                        $arRegistro->setNumero($arMovimiento['numero']);
                                                        $arRegistro->setNumeroReferencia($arMovimientoDetalle['numero']);
                                                        $arRegistro->setFecha($fecha);
                                                        $arRegistro->setFechaVence($fecha);
                                                        $arRegistro->setVrDebito($arMovimientoDetalle['vrRetencion']);
                                                        $arRegistro->setVrBase($arMovimientoDetalle['vrBase']);
                                                        $arRegistro->setNaturaleza("D");
                                                        $arRegistro->setDescripcion("Retencion");
                                                        $arRegistro->setCodigoModeloFk('CarMovimiento');
                                                        $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                                        if($arMovimientoDetalle['codigoCentroCostoFk']) {
                                                            $arRegistro->setCentroCostoRel($em->getReference(FinCentroCosto::class, $arMovimientoDetalle['codigoCentroCostoFk']));
                                                        }
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
                                $cuenta = $arMovimiento['codigoCuentaContableFk'];
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
                                    $arRegistro->setNumero($arMovimiento['numero']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrDebito($arMovimiento['vrTotalNeto']);
                                    $arRegistro->setNaturaleza('D');
                                    $arRegistro->setDescripcion("Movimiento");
                                    $arRegistro->setCodigoModeloFk('CarMovimiento');
                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el recibo " . $arMovimiento['numero'];
                                    break;
                                }

                                $arMovimientoAct = $em->getRepository(CarMovimiento::class)->find($arMovimiento['codigoMovimientoPk']);
                                $arMovimientoAct->setEstadoContabilizado(1);
                                $em->persist($arMovimientoAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo egreso] del egreso " . $arMovimiento['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo egreso] del egreso " . $arMovimiento['numero'];
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

    public function listaFormatoMasivo($arrDatos)
    {
        $codigoReciboDesde = null;
        $codigoReciboHasta = null;
        $fechaDesde=null;
        $fechaHasta=null;
        if ($arrDatos) {
            $codigoReciboDesde = $arrDatos['codigoReciboDesde'] ?? null;
            $codigoReciboHasta = $arrDatos['codigoReciboHasta'] ?? null;
            $fechaDesde = $arrDatos['fechaDesde'] ?? null;
            $fechaHasta = $arrDatos['fechaHasta'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarMovimiento::class, 'm')
            ->select('m.codigoMovimientoPk')
            ->addSelect('m.fecha')
            ->addSelect('m.numero')
            ->addSelect('m.vrTotalNeto')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoContabilizado')
            ->addSelect('m.comentarios')
            ->addSelect('cl.nombreCorto')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.direccion')
            ->addSelect('cl.telefono')
            ->leftJoin('m.movimientoTipoRel', 'it')
            ->leftJoin('m.clienteRel', 'cl')
            ->leftJoin('m.cuentaRel', 'cu');
        if ($codigoReciboDesde) {
            $queryBuilder->andWhere("m.numero >= '{$codigoReciboDesde}'");
        }
        if ($codigoReciboHasta) {
            $queryBuilder->andWhere("m.numero <= '{$codigoReciboHasta}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("m.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("m.fecha <= '{$fechaHasta} 23:59:59'");
        }
        return $queryBuilder->getQuery()->getResult();

    }

}