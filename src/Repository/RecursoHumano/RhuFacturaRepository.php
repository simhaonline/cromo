<?php

namespace App\Repository\RecursoHumano;


use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Crm\CrmVisita;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenImpuesto;
use App\Entity\Turno\RhuFactura;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFacturaTipo;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuFacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuFactura::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoClienteFk = null;
        $numero = null;
        $codigoFacturaPk = null;
        $codigoFacturaTipoFk = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;


        if ($filtros) {
            $codigoFacturaPk = $filtros['codigoFacturaPk'] ?? null;
            $codigoClienteFk = $filtros['codigoClienteFk'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoFacturaTipoFk = $filtros['codigoPedidoTipoFk'] ?? null;
            $fechaDesde = $filtros['$fechaDesde'] ?? null;
            $fechaHasta = $filtros['$fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('c.nombreCorto as cliente')
            ->addSelect('f.numero')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrIva')
            ->addSelect('f.vrNeto')
            ->addSelect('f.vrTotal')
            ->addSelect('f.usuario')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAnulado')
            ->leftJoin('f.clienteRel', 'c');

        if ($codigoFacturaPk) {
            $queryBuilder->andWhere("f.codigoFacturaPk = {$codigoFacturaPk}");
        }
        if ($codigoClienteFk) {
            $queryBuilder->andWhere("f.codigoClienteFk  = '{$codigoClienteFk}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("f.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("f.fecha <= '{$fechaHasta} 23:59:59'");
        }
        if ($numero) {
            $queryBuilder->andWhere("f.numero  = '{$numero}'");
        }
        if ($codigoFacturaTipoFk) {
            $queryBuilder->andWhere("f.codigoFacturaTipoFk  = '{$codigoFacturaTipoFk}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        $queryBuilder->addOrderBy('f.codigoFacturaPk', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function liquidar($arFactura)
    {
        /**
         * @var $arFactura TurFactura
         */
        $em = $this->getEntityManager();
        $respuesta = '';
//        $retencionFuente = $arFactura->getClienteRel()->getRetencionFuente();
//        $retencionFuenteSinBase = $arFactura->getClienteRel()->getRetencionFuenteSinBase();
        $retencionFuente = 1;
        $retencionFuenteSinBase = 0;
        $vrTotalBrutoGlobal = 0;
        $vrTotalGlobal = 0;
        $vrTotalNetoGlobal = 0;
        $vrDescuentoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrBaseAiuGlobal = 0;
        $vrSubtotalGlobal = 0;
        $vrRetencionFuenteGlobal = 0;
        $vrRetencionIvaGlobal = 0;
        $vrAutoretencion = 0;
        $arFacturaDetalles = $this->getEntityManager()->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
        $arrImpuestoRetenciones = $this->retencion($arFacturaDetalles, $retencionFuenteSinBase);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $vrPrecio = $arFacturaDetalle->getVrPrecio();
            $vrSubtotal = $vrPrecio * $arFacturaDetalle->getCantidad();
            $vrDescuento = ($arFacturaDetalle->getVrPrecio() * $arFacturaDetalle->getCantidad()) - $vrSubtotal;
            $vrBaseAiu = ($vrSubtotal * ($arFacturaDetalle->getPorcentajeBaseIva()) / 100);
            $vrIva = $vrBaseAiu * ($arFacturaDetalle->getPorcentajeIva() / 100);
            $vrTotalBruto = $vrSubtotal;
            $vrTotal = $vrTotalBruto + $vrIva;
            $vrRetencionFuente = 0;
            $vrTotalGlobal += $vrTotal;
            $vrTotalBrutoGlobal += $vrTotalBruto;
            $vrDescuentoGlobal += $vrDescuento;
            $vrIvaGlobal += $vrIva;
            $vrBaseAiuGlobal += $vrBaseAiu;
            $vrSubtotalGlobal += $vrSubtotal;
            if ($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                if ($retencionFuente) {
                    if ($arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['generaBase'] == true || $retencionFuenteSinBase) {
                        $vrRetencionFuente = ($vrSubtotal * $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['base'] / 100) * $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['porcentaje'] / 100;
                    }
                }
            }
            $vrRetencionFuenteGlobal += $vrRetencionFuente;
            $arFacturaDetalle->setVrSubtotal($vrSubtotal);
            $arFacturaDetalle->setVrIva($vrIva);
            $arFacturaDetalle->setVrTotal($vrTotal);
            $arFacturaDetalle->setVrRetencionFuente($vrRetencionFuente);
            $this->getEntityManager()->persist($arFacturaDetalle);
        }

        $vrTotalNetoGlobal = $vrTotalGlobal - $vrRetencionFuenteGlobal - $vrRetencionIvaGlobal;
        $arFactura->setVrIva($vrIvaGlobal);
        $arFactura->setVrBaseAiu($vrBaseAiuGlobal);
        $arFactura->setVrSubtotal($vrSubtotalGlobal);
        $arFactura->setVrTotal($vrTotalGlobal);
        $arFactura->setVrNeto($vrTotalNetoGlobal);
        $arFactura->setVrDescuento($vrDescuentoGlobal);
        $arFactura->setVrRetencionFuente($vrRetencionFuenteGlobal);
        $arFactura->setVrRetencionIva($vrRetencionIvaGlobal);
        $arFactura->setVrAutoretencion($vrAutoretencion);
        $this->getEntityManager()->persist($arFactura);
        if ($respuesta == '') {
            $em->flush();
        } else {
            Mensajes::error($respuesta);
        }
    }

    private function retencion($arFacturaDetalles, $retencionFuenteSinBase)
    {
        $em = $this->getEntityManager();
        $arrImpuestoRetenciones = array();
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            if ($arFacturaDetalle->getCodigoImpuestoRetencionFk()) {
                $vrPrecio = $arFacturaDetalle->getVrPrecio();
                $vrSubtotal = $vrPrecio * $arFacturaDetalle->getCantidad();
                if (!array_key_exists($arFacturaDetalle->getCodigoImpuestoRetencionFk(), $arrImpuestoRetenciones)) {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()] = array('codigo' => $arFacturaDetalle->getCodigoImpuestoRetencionFk(),
                        'valor' => $vrSubtotal, 'base' => false, 'porcentaje' => 0);
                } else {
                    $arrImpuestoRetenciones[$arFacturaDetalle->getCodigoImpuestoRetencionFk()]['valor'] += $vrSubtotal;
                }
            }
        }

        if ($arrImpuestoRetenciones) {
            foreach ($arrImpuestoRetenciones as $arrImpuestoRetencion) {
                $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrImpuestoRetencion['codigo']);
                if ($arImpuesto) {
                    if ($arrImpuestoRetencion['valor'] >= $arImpuesto->getBase() || $retencionFuenteSinBase) {
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['generaBase'] = true;
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['base'] = $arImpuesto->getBase();
                        $arrImpuestoRetenciones[$arrImpuestoRetencion['codigo']]['porcentaje'] = $arImpuesto->getPorcentaje();
                    }
                }
            }
        }
        return $arrImpuestoRetenciones;
    }

    public function autorizar($arFactura)
    {
        $em = $this->getEntityManager();
        if ($em->getRepository(TurFacturaDetalle::class)->contarDetalles($arFactura->getCodigoFacturaPk()) > 0) {
            $error = false;
            $arFacturaDetalles = $em->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
            /** @var TurFacturaDetalle $arFacturaDetalle */
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if ($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    /** @var TurPedidoDetalle $arPedidoDetalle */
                    $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                    if (round($arPedidoDetalle->getVrPendiente()) >= round($arFacturaDetalle->getVrSubtotal())) {
                        $arPedidoDetalle->setVrAfectado($arPedidoDetalle->getVrAfectado() + $arFacturaDetalle->getVrSubtotal());
                        $arPedidoDetalle->setVrPendiente($arPedidoDetalle->getVrPendiente() - $arFacturaDetalle->getVrSubtotal());
                        $em->persist($arPedidoDetalle);
                    } else {
                        Mensajes::error("El valor supera el pedido pendiente");
                        $error = true;
                    }
                }
            }
            if ($error == false) {
                $arFactura->setEstadoAutorizado(1);
                $em->persist($arFactura);
                $em->flush();
            }
        } else {
            Mensajes::error('La factura no contiene detalles');
        }
    }

    public function desautorizar($arFactura)
    {
        $em = $this->getEntityManager();
        if ($arFactura->getEstadoAutorizado()) {
            $arFacturaDetalles = $em->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()]);
            /** @var TurFacturaDetalle $arFacturaDetalle */
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if ($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    /** @var TurPedidoDetalle $arPedidoDetalle */
                    $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                    $arPedidoDetalle->setVrAfectado($arPedidoDetalle->getVrAfectado() - $arFacturaDetalle->getVrSubtotal());
                    $arPedidoDetalle->setVrPendiente($arPedidoDetalle->getVrPendiente() + $arFacturaDetalle->getVrSubtotal());
                    $em->persist($arPedidoDetalle);

                }
            }
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();

        } else {
            Mensajes::error('El registro no esta autorizado');
        }
    }

    /**
     * @param $arFactura TurFactura
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arFactura)
    {
        $em = $this->getEntityManager();
        $arFacturaTipo = $em->getRepository(TurFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
        if ($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAprobado() == 0) {
            if ($arFactura->getNumero() == 0) {
                $arFactura->setNumero($arFacturaTipo->getConsecutivo());
                $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
                $em->persist($arFacturaTipo);
            }
            $arFactura->setEstadoAprobado(1);
            $em->persist($arFactura);
            if ($arFacturaTipo->getGeneraCartera()) {

                $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arFactura->getClienteRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arFactura->getClienteRel()->getNumeroIdentificacion()]);
                if (!$arClienteCartera) {
                    $arClienteCartera = new CarCliente();
                    $arClienteCartera->setFormaPagoRel($arFactura->getClienteRel()->getFormaPagoRel());
                    $arClienteCartera->setIdentificacionRel($arFactura->getClienteRel()->getIdentificacionRel());
                    $arClienteCartera->setNumeroIdentificacion($arFactura->getClienteRel()->getNumeroIdentificacion());
                    $arClienteCartera->setDigitoVerificacion($arFactura->getClienteRel()->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arFactura->getClienteRel()->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    $arClienteCartera->setDireccion($arFactura->getClienteRel()->getDireccion());
                    $arClienteCartera->setTelefono($arFactura->getClienteRel()->getTelefono());
                    $arClienteCartera->setCorreo($arFactura->getClienteRel()->getCorreo());
                    $em->persist($arClienteCartera);
                }

                $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arFactura->getFacturaTipoRel()->getCodigoCuentaCobrarTipoFk());
                $arCuentaCobrar = new CarCuentaCobrar();
                $arCuentaCobrar->setClienteRel($arClienteCartera);
                $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                $arCuentaCobrar->setFecha($arFactura->getFecha());
                $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                $arCuentaCobrar->setModulo("TUR");
                $arCuentaCobrar->setCodigoDocumento($arFactura->getCodigoFacturaPk());
                $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                $arCuentaCobrar->setVrSubtotal($arFactura->getVrSubtotal());
                $arCuentaCobrar->setVrTotal($arFactura->getVrTotal());
                $arCuentaCobrar->setVrSaldoOriginal($arFactura->getVrTotal());
                $arCuentaCobrar->setVrRetencionFuente($arFactura->getVrRetencionFuente());
                $arCuentaCobrar->setVrSaldo($arFactura->getVrTotal());
                $arCuentaCobrar->setVrSaldoOperado($arFactura->getVrTotal() * $arCuentaCobrarTipo->getOperacion());
                $arCuentaCobrar->setPlazo($arFactura->getPlazoPago());
                $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
                $arCuentaCobrar->setAsesorRel($arFactura->getClienteRel()->getAsesorRel());
                $em->persist($arCuentaCobrar);
            }

            $em->flush();
        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TurFactura::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arRegistro->getCodigoFacturaPk()])) <= 0) {
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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f')
            ->leftJoin("f.clienteRel", "c");
        if ($session->get('filtroTurInformeComercialFacturaClienteCodigo') != null) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTurInformeComercialFacturaClienteCodigo')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAutorizado') != null) {
            $queryBuilder->andWhere("f.estadoAutorizado = {$session->get('filtroTurInformeComercialFacturaAutorizado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAnulado') != null) {
            $queryBuilder->andWhere("f.estadoAnulado = {$session->get('filtroTurInformeComercialFacturaAnulado ')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaAprobado') != null) {
            $queryBuilder->andWhere("f.estadoAprobado = {$session->get('filtroTurInformeComercialFacturaAprobado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaFechaDesde') != null) {
            $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroTurInformeComercialFacturaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeComercialFacturaFechaHasta') != null) {
            $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroTurInformeComercialFacturaFechaHasta')} 23:59:59'");
        }
        if ($session->get('filtroTurInformeComercialFacturaCiudad') != null) {
            $queryBuilder->andWhere("c.codigoCiudadFk = {$session->get('filtroTurInformeComercialFacturaCiudad')}");
        }
        return $queryBuilder;
    }

    /**
     * @param $arFactura TurFactura
     * @param $arPedido TurPedido
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arFactura)
    {
        $em = $this->getEntityManager();
        if ($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() != 0) {
            $arFacturaDetalles = $em->getRepository(TurFacturaDetalle::class)->findBy(array('codigoFacturaFk' => $arFactura->getCodigoFacturaPk()));

            //Devolver saldo a los pedidos
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if ($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    $arPedidoDetalleAct = $em->getRepository(TurPedidoDetalle::class)->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                    $floValorTotalPendiente = $arPedidoDetalleAct->getVrPendiente() + $arFacturaDetalle->getVrPrecio();
                    $arPedidoDetalleAct->setVrPendiente($floValorTotalPendiente);
                    $arPedido = $arPedidoDetalleAct->getPedidoRel();
                    $arPedido->setEstadoFacturado(0);
                    $em->persist($arPedido);
                    $em->persist($arPedidoDetalleAct);
                }
//                if ($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk()) {
//                    $arPedidoDetalleConceptoAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk());
//                    $floValorTotalPendiente = $arPedidoDetalleConceptoAct->getVrTotalPendiente() + $arFacturaDetalle->getVrPrecio();
//                    $arPedidoDetalleConceptoAct->setVrTotalPendiente($floValorTotalPendiente);
//                    $arPedidoDetalleConceptoAct->setEstadoFacturado(0);
//                    $arPedido = $arPedidoDetalleConceptoAct->getPedidoRel();
//                    $arPedido->setEstadoFacturado(0);
//                    $em->persist($arPedido);
//                    $em->persist($arPedidoDetalleConceptoAct);
//                }

            }
            //Actualizar los detalles de la factura a cero
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arFacturaDetalleAct = $em->getRepository(TurFacturaDetalle::class)->find($arFacturaDetalle->getCodigoFacturaDetallePk());
                $arFacturaDetalle->setVrPrecio(0);
                $arFacturaDetalle->setCantidad(0);
                $arFacturaDetalle->setVrSubtotal(0);
                $arFacturaDetalle->setVrBaseIva(0);
                $arFacturaDetalle->setVrIva(0);
                $arFacturaDetalle->setVrTotal(0);
                $em->persist($arFacturaDetalle);
            }

            $arFactura->setVrSubtotal(0);
            $arFactura->setVrRetencionFuente(0);
//            $arFactura->setVrRetencionRenta(0);
            $arFactura->setVrRetencionIva(0);
            $arFactura->setVrBaseAIU(0);
            $arFactura->setVrIva(0);
            $arFactura->setVrTotal(0);
            $arFactura->setVrNeto(0);
            $arFactura->setEstadoAnulado(1);
            $arFactura->setVrBaseAiu(0);;
            $em->persist($arFactura);

            //Anular cuenta por cobrar
            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->findOneBy(array('codigoCuentaCobrarTipoFk' => $arFactura->getFacturaTipoRel()->getCodigoDocumentoCartera(), 'numeroDocumento' => $arFactura->getNumero()));
            if ($arCuentaCobrar) {
                $arCuentaCobrar->setValorOriginal(0);
                $arCuentaCobrar->setAbono(0);
                $arCuentaCobrar->setSaldo(0);
                $arCuentaCobrar->setSaldoOperado(0);
                $arCuentaCobrar->setSubtotal(0);
                $em->persist($arCuentaCobrar);
            }
            $em->flush();
        } else {
            Mensajes::error('La factura debe estar autorizada y aprobada y no puede estar previamente anulada');
        }
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.codigoFacturaTipoFk')
            ->addSelect('f.codigoClienteFk')
            ->addSelect('f.numero')
            ->addSelect('f.soporte')
            ->addSelect('f.fecha')
            ->addSelect('f.fechaVence')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoContabilizado')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.vrNeto')
            ->addSelect('f.vrAutoretencion')
            ->addSelect('ft.codigoComprobanteFk')
            ->addSelect('ft.codigoCuentaProveedorFk')
            ->addSelect('ft.codigoCuentaClienteFk')
            ->addSelect('ft.notaCredito')
            ->addSelect('ft.prefijo')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->where('f.codigoFacturaPk = ' . $codigo);
        $arFactura = $queryBuilder->getQuery()->getSingleResult();
        return $arFactura;
    }

    public function clienteContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFactura::class, 'f')
            ->select('f.codigoClienteFk')
            ->where('f.codigoFacturaPk = ' . $codigo);
        $arFactura = $queryBuilder->getQuery()->getSingleResult();
        return $arFactura;
    }

    /**
     * @param $arr
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //Crear los terceros antes del proceso
            foreach ($arr AS $codigo) {
                $arFactura = $em->getRepository(TurFactura::class)->clienteContabilizar($codigo);
                if ($arFactura) {
                    $em->getRepository(TurCliente::class)->terceroFinanciero($arFactura['codigoClienteFk']);
                }
            }
            $em->flush();

            foreach ($arr AS $codigo) {
                $arFactura = $em->getRepository(TurFactura::class)->registroContabilizar($codigo);
                if ($arFactura) {
                    if ($arFactura['estadoAprobado'] == 1 && $arFactura['estadoContabilizado'] == 0) {
                        if (!$arFactura['codigoComprobanteFk']) {
                            $error = "El comprobante no esta configurado";
                            break;
                        }
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arFactura['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TurCliente::class)->terceroFinanciero($arFactura['codigoClienteFk']);

                        //Contabilizar facturas
                        if ($arFactura['codigoFacturaTipoFk']) {
                            //Cliente
                            if ($arFactura['codigoCuentaClienteFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaClienteFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arFactura['codigoCuentaClienteFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                $arRegistro->setNumero($arFactura['numero']);
                                $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                $arRegistro->setFecha($arFactura['fecha']);
                                if ($arFactura['notaCredito']) {
                                    $arRegistro->setVrCredito($arFactura['vrNeto']);
                                    $arRegistro->setNaturaleza('C');
                                } else {
                                    $arRegistro->setVrDebito($arFactura['vrNeto']);
                                    $arRegistro->setNaturaleza('D');
                                }

                                $arRegistro->setDescripcion('CLIENTE');
                                $arRegistro->setCodigoModeloFk('TurFactura');
                                $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de cliente en el documento";
                                break;
                            }

                            //Retenciones
                            $arrRetenciones = $em->getRepository(TurFacturaDetalle::class)->retencionFacturaContabilizar($codigo);
                            foreach ($arrRetenciones as $arrRetencion) {
                                if ($arrRetencion['codigoImpuestoRetencionFk']) {
                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrRetencion['codigoImpuestoRetencionFk']);
                                    if ($arImpuesto) {
                                        if ($arImpuesto->getCodigoCuentaFk()) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaFk());
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                break 2;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arFactura['numero']);
                                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                            $arRegistro->setFecha($arFactura['fecha']);
                                            if ($arFactura['notaCredito']) {
                                                $arRegistro->setVrCredito($arrRetencion['vrRetencionFuente']);
                                                $arRegistro->setNaturaleza('C');
                                            } else {
                                                $arRegistro->setVrDebito($arrRetencion['vrRetencionFuente']);
                                                $arRegistro->setNaturaleza('D');
                                            }

                                            $arRegistro->setDescripcion('IMPUESTO RETENCION FUENTE');
                                            $arRegistro->setCodigoModeloFk('TurFactura');
                                            $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "No tiene configurada la cuenta del impuesto de retencion";
                                            break;
                                        }
                                    }
                                }
                            }

                            //Autoretencion
//                            if ($arFactura['vrAutoretencion'] > 0) {
//                                $arrConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->invLiquidarMovimiento();
//                                if ($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk'] && $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk']) {
//                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk']);
//                                    if (!$arCuenta) {
//                                        $error = "No se encuentra la cuenta " . $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaFk'];
//                                        break;
//                                    }
//                                    $arRegistro = new FinRegistro();
//                                    $arRegistro->setTerceroRel($arTercero);
//                                    $arRegistro->setCuentaRel($arCuenta);
//                                    $arRegistro->setComprobanteRel($arComprobante);
//                                    $arRegistro->setNumero($arMovimiento['numero']);
//                                    $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
//                                    $arRegistro->setFecha($arMovimiento['fecha']);
//                                    if ($arMovimiento['notaCredito']) {
//                                        $arRegistro->setVrCredito($arMovimiento['vrAutoretencion']);
//                                        $arRegistro->setNaturaleza('C');
//                                    } else {
//                                        $arRegistro->setVrDebito($arMovimiento['vrAutoretencion']);
//                                        $arRegistro->setNaturaleza('D');
//                                    }
//
//                                    $arRegistro->setDescripcion('AUTORETENCION');
//                                    $arRegistro->setCodigoModeloFk('InvMovimiento');
//                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
//                                    $em->persist($arRegistro);
//
//                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk']);
//                                    if (!$arCuenta) {
//                                        $error = "No se encuentra la cuenta " . $arrConfiguracionGeneral['codigoCuentaAutoretencionVentaValorFk'];
//                                        break;
//                                    }
//                                    $arRegistro = new FinRegistro();
//                                    $arRegistro->setTerceroRel($arTercero);
//                                    $arRegistro->setCuentaRel($arCuenta);
//                                    $arRegistro->setComprobanteRel($arComprobante);
//                                    $arRegistro->setNumero($arMovimiento['numero']);
//                                    $arRegistro->setNumeroPrefijo($arMovimiento['prefijo']);
//                                    $arRegistro->setFecha($arMovimiento['fecha']);
//                                    if ($arMovimiento['notaCredito']) {
//                                        $arRegistro->setVrDebito($arMovimiento['vrAutoretencion']);
//                                        $arRegistro->setNaturaleza('D');
//                                    } else {
//                                        $arRegistro->setVrCredito($arMovimiento['vrAutoretencion']);
//                                        $arRegistro->setNaturaleza('C');
//                                    }
//
//                                    $arRegistro->setDescripcion('AUTORETENCION');
//                                    $arRegistro->setCodigoModeloFk('InvMovimiento');
//                                    $arRegistro->setCodigoDocumento($arMovimiento['codigoMovimientoPk']);
//                                    $em->persist($arRegistro);
//
//                                } else {
//                                    $error = "No tiene cuentas para autoretencion en configuracion general";
//                                    break;
//                                }
//                            }

                            //Iva
                            $arrIvas = $em->getRepository(TurFacturaDetalle::class)->ivaFacturaContabilizar($codigo);
                            foreach ($arrIvas as $arrIva) {
                                if ($arrIva['codigoImpuestoIvaFk']) {
                                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($arrIva['codigoImpuestoIvaFk']);
                                    if ($arImpuesto) {
                                        if ($arImpuesto->getCodigoCuentaFk()) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arImpuesto->getCodigoCuentaFk());
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta " . $arImpuesto->getCodigoCuentaFk();
                                                break 2;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arFactura['numero']);
                                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                            $arRegistro->setFecha($arFactura['fecha']);
                                            if ($arFactura['notaCredito']) {
                                                $arRegistro->setVrDebito($arrIva['vrIva']);
                                                $arRegistro->setNaturaleza('D');
                                            } else {
                                                $arRegistro->setVrCredito($arrIva['vrIva']);
                                                $arRegistro->setNaturaleza('C');
                                            }

                                            $arRegistro->setDescripcion('IVA');
                                            $arRegistro->setCodigoModeloFk('TurFactura');
                                            $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "No tiene configurada la cuenta del impuesto de retencion";
                                            break;
                                        }
                                    }
                                }
                            }

                            //Cuenta de ingreso / ventas
//                            if ($arFactura['notaCredito']) {
//                                $arrVentas = $em->getRepository(TurFacturaDetalle::class)->ventaDevolucionFacturaContabilizar($codigo);
//                            } else {
                            $arrVentas = $em->getRepository(TurFacturaDetalle::class)->ventaFacturaContabilizar($codigo);
                            foreach ($arrVentas as $arrVenta) {
                                if ($arrVenta['codigoCuentaFk']) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrVenta['codigoCuentaFk']);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $arrVenta['codigoCuentaFk'];
                                        break 2;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arFactura['numero']);
                                    $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                                    $arRegistro->setFecha($arFactura['fecha']);
                                    if ($arFactura['notaCredito']) {
                                        $arRegistro->setVrDebito($arrVenta['vrSubtotal']);
                                        $arRegistro->setNaturaleza('D');
                                    } else {
                                        $arRegistro->setVrCredito($arrVenta['vrSubtotal']);
                                        $arRegistro->setNaturaleza('C');
                                    }

                                    $arRegistro->setDescripcion('SERVICIO');
                                    $arRegistro->setCodigoModeloFk('TurFactura');
                                    $arRegistro->setCodigoDocumento($arFactura['codigoFacturaPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "No tiene configurada la cuenta de venta en item";
                                    break;
                                }
                            }
                        }

                        $arFacturaAct = $em->getRepository(TurFactura::class)->find($arFactura['codigoFacturaPk']);
                        $arFacturaAct->setEstadoContabilizado(1);
                        $em->persist($arFacturaAct);
                    }
                } else {
                    $error = "La importacion codigo " . $codigo . " no existe";
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
