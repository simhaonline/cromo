<?php

namespace App\Repository\Tesoreria;

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

/**
 * @method ComEgreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgreso[]    findAll()
 * @method ComEgreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesEgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgreso::class);
    }

    public function lista()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(ComEgreso::class, 'e')
            ->select('e.codigoEgresoPk')
            ->leftJoin('e.proveedorRel', 'p')
            ->leftJoin('e.egresoTipoRel', 'et')
            ->addSelect('e.numero')
            ->addSelect('e.codigoEgresoTipoFk')
            ->addSelect('et.nombre AS tipo')
            ->addSelect('e.fecha')
//            ->addSelect('cp.soporte')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('e.vrPagoTotal')
            ->addSelect('e.estadoAnulado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAutorizado')
            ->addSelect('e.estadoImpreso')
            ->where('e.codigoEgresoTipoFk <> 0')
            ->andWhere('e.estadoAprobado = 1')
            ->orderBy('e.codigoEgresoTipoFk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroComEgresoPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoEgresoPagarTipoFk = '" . $session->get('filtroComEgresoPAgarTipo') . "'");
        }
//        if ($session->get('filtroComNumeroReferencia') != '') {
//            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
//        }
//        if ($session->get('filtroComEgresoPagarNumero') != '') {
//            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroComEgresoPagarNumero')}");
//        }
//        if ($session->get('filtroComCodigoProveedor')) {
//            $queryBuilder->andWhere("cp.codigoProveedorFk = {$session->get('filtroComCodigoProveedor')}");
//        }
//        if ($session->get('filtroCarEgresoCobrarTipo')) {
//            $queryBuilder->andWhere("cc.codigoEgresoCobrarTipoFk = '" . $session->get('filtroCarEgresoCobrarTipo') . "'");
//        }
        if ($session->get('filtroComFiltrarPorFecha') == true) {
            if ($session->get('filtroComFechaDesde') != null) {
                $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroComFechaDesde')}'");
            }
            if ($session->get('filtroComFechaHasta') != null) {
                $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroComFechaHasta')}'");
            }
        }
        return $queryBuilder;
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
            $arEgresosDetalles = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
            if ($arEgresosDetalles) {
                foreach ($arEgresosDetalles AS $arEgresoDetalle) {
                    if ($arEgresoDetalle->getCodigoCuentaPagarFk()) {
                        $arCuentaPagarAplicacion = $em->getRepository(TesCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                        if ($arCuentaPagarAplicacion->getVrSaldo() >= $arEgresoDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaPagarAplicacion->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                            $arCuentaPagarAplicacion->setVrSaldo($saldo);
                            $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaPagarAplicacion);
                            //Cuenta por pagar
//                            $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
//                            $saldo = $arCuentaPagar->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
//                            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
//                            $arCuentaPagar->setVrSaldo($saldo);
//                            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
//                            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
//                            $em->persist($arCuentaPagar);
                        } else {
                            Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaPagarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaPagarAplicacion->getVrSaldo());
                            $error = true;
                            break;
                        }

                    } else {
                        $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                        if ($arCuentaPagar->getVrSaldo() >= $arEgresoDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaPagar->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
                            $arCuentaPagar->setVrSaldo($saldo);
                            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
                            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaPagar);
                        } else {
                            Mensajes::error("El saldo " . $arCuentaPagar->getVrSaldo() . " de la cuenta por cobrar numero: " . $arCuentaPagar->getNumeroDocumento() . " es menor al egreso detalle " . $arEgresoDetalle->getVrPagoAfectar());
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
        }
    }

    public function desAutorizar($arEgreso)
    {
        $em = $this->getEntityManager();
        $arEgresoDetalles = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
            $saldo = $arCuentaPagar->getVrSaldo() + $arEgresoDetalle->getVrPagoAfectar();
            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
            $arCuentaPagar->setVrSaldo($saldo);
            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() - $arEgresoDetalle->getVrPagoAfectar());
            $em->persist($arCuentaPagar);
//            if ($arEgresoDetalle->getCodigoCuentaCobrarAplicacionFk()) {
//                $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arEgresoDetalle->getCodigoCuentaCobrarAplicacionFk());
//                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arEgresoDetalle->getVrPagoAfectar();
//                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
//                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
//                $arCuentaCobrarAplicacion->setVrSaldoOperado($saldoOperado);
//                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arEgresoDetalle->getVrPagoAfectar());
//                $em->persist($arCuentaCobrarAplicacion);
//            }
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
            ->addSelect('e.vrPago')
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

                                $arTercero = $em->getRepository(TesTercero::class)->terceroFinanciero($arEgreso['codigoTerceroFk']);
                                $arEgresoDetalles = $em->getRepository(TesEgresoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arEgresoDetalles as $arEgresoDetalle) {
                                    //Cuenta proveedor
                                    if ($arEgresoDetalle['vrPago'] > 0) {
                                        $descripcion = "PROVEEDORES DOC " . $arEgresoDetalle['numeroDocumento'] ;
                                        $cuenta = $arEgresoDetalle['codigoCuentaProveedorFk'];
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
                                            $arRegistro->setNumero($arEgreso['numero']);
                                            $arRegistro->setNumeroReferencia($arEgresoDetalle['numeroDocumento']);
                                            $arRegistro->setFecha($arEgreso['fecha']);
                                            $arRegistro->setFechaVence($arEgreso['fecha']);
                                            $arRegistro->setVrCredito($arEgresoDetalle['vrPago']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('TesEgreso');
                                            $arRegistro->setCodigoDocumento($arEgreso['codigoEgresoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El [tipo cuenta cobrar] no tiene configurada la cuenta " . $descripcion;
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
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arEgreso['numero']);
                                    $arRegistro->setFecha($arEgreso['fecha']);
                                    $arRegistro->setVrDebito($arEgreso['vrPago']);
                                    $arRegistro->setNaturaleza('D');
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
