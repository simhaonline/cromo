<?php

namespace App\Repository\Transporte;

use App\Entity\Contabilidad\CtbCentroCosto;
use App\Entity\Contabilidad\CtbComprobante;
use App\Entity\Contabilidad\CtbCuenta;
use App\Entity\Contabilidad\CtbRegistro;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TtePoseedor;
use App\Entity\Transporte\TteRecogida;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogida::class);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('dr.fecha')
            ->addSelect('dr.numero')
            ->addSelect('dr.codigoOperacionFk')
            ->addSelect('dr.codigoVehiculoFk')
            ->addSelect('dr.codigoRutaRecogidaFk')
            ->addSelect('dr.cantidad')
            ->addSelect('dr.unidades')
            ->addSelect('dr.pesoReal')
            ->addSelect('dr.pesoVolumen')
            ->addSelect('dr.estadoAutorizado')
            ->addSelect('dr.estadoAprobado')
            ->addSelect('dr.estadoAnulado')
            ->addSelect('dr.estadoDescargado')
            ->addSelect('dr.vrFletePago')
            ->addSelect('cond.nombreCorto AS conductorNombreCorto')
            ->where('dr.codigoDespachoRecogidaPk <> 0')
        ->leftJoin('dr.conductorRel', 'cond');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteDespachoRecogidaFiltroFecha') == true) {
            if ($session->get('filtroTteDespachoRecogidaFechaDesde') != null) {
                $queryBuilder->andWhere("dr.fecha >= '{$session->get('filtroTteDespachoRecogidaFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("dr.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteDespachoRecogidaFechaHasta') != null) {
                $queryBuilder->andWhere("dr.fecha <= '{$session->get('filtroTteDespachoRecogidaFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("dr.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        if($session->get('filtroTteDespachoRecogidaVehiculoCodigo') != ''){
            $queryBuilder->andWhere("dr.codigoVehiculoFk = '{$session->get('filtroTteDespachoRecogidaVehiculoCodigo')}'");
        }
        if($session->get('filtroTteDespachoRecogidaEstadoAprobado') != ''){
            $queryBuilder->andWhere("dr.estadoAprobado = {$session->get('filtroTteDespachoRecogidaEstadoAprobado')}");
        }
        $queryBuilder->orderBy('dr.fecha', 'DESC');
        return $queryBuilder->getQuery();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteDespachoRecogida::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteRecogida::class)->findBy(['codigoDespachoRecogidaFk' => $arRegistro->getCodigoDespachoRecogidaPk()])) <= 0) {
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

    public function liquidar($codigoDespachoRecogida): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades+0) as unidades, SUM(r.pesoReal+0) as pesoReal, SUM(r.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida')
            ->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);
        $arrRecogidas = $query->getSingleResult();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $arDespachoRecogida->setUnidades(intval($arrRecogidas['unidades']));
        $arDespachoRecogida->setPesoReal(intval($arrRecogidas['pesoReal']));
        $arDespachoRecogida->setPesoVolumen(intval($arrRecogidas['pesoVolumen']));
        $arDespachoRecogida->setCantidad(intval($arrRecogidas['cantidad']));
        //Totales
        $arrConfiguracionLiquidarDespacho = $em->getRepository(TteConfiguracion::class)->liquidarDespacho();
        $descuentos = $arDespachoRecogida->getVrDescuentoPapeleria() + $arDespachoRecogida->getVrDescuentoSeguridad() + $arDespachoRecogida->getVrDescuentoCargue() + $arDespachoRecogida->getVrDescuentoEstampilla();
        $retencionFuente = 0;
        if ($arDespachoRecogida->getVrFletePago() > $arrConfiguracionLiquidarDespacho['vrBaseRetencionFuente']) {
            $retencionFuente = $arDespachoRecogida->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeRetencionFuente'] / 100;
        }
        $industriaComercio = $arDespachoRecogida->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeIndustriaComercio'] / 100;

        $total = $arDespachoRecogida->getVrFletePago() - ($arDespachoRecogida->getVrAnticipo() + $retencionFuente + $industriaComercio);
        $saldo = $total - $descuentos;
        $arDespachoRecogida->setVrIndustriaComercio($industriaComercio);
        $arDespachoRecogida->setVrRetencionFuente($retencionFuente);
        $arDespachoRecogida->setVrTotal($total);
        $arDespachoRecogida->setVrSaldo($saldo);

        $em->persist($arDespachoRecogida);
        $em->flush();
        return true;
    }

    public function retirarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if ($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if ($arRecogida->getEstadoRecogido() == 0) {
                        $arRecogida->setDespachoRecogidaRel(null);
                        $arRecogida->setEstadoProgramado(0);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    public function descargarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if ($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if ($arRecogida->getEstadoRecogido() == 0 && $arRecogida->getUnidades() > 0 && $arRecogida->getPesoReal() > 0 && $arRecogida->getPesoVolumen() > 0) {
                        $arRecogida->setEstadoRecogido(1);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arDespachoRecogida){
        if($this->getEntityManager()->getRepository(TteRecogida::class)->contarDetalles($arDespachoRecogida->getCodigoDespachoRecogidaPk()) > 0){
            $arDespachoRecogida->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arDespachoRecogida);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no tiene detalles');
        }
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arDespachoRecogida){
        $arDespachoRecogida->setEstadoAutorizado(0);
        $this->getEntityManager()->persist($arDespachoRecogida);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arDespachoRecogida){
        $em = $this->getEntityManager();
        if($arDespachoRecogida->getEstadoAutorizado()){
            if ($arDespachoRecogida->getNumero() == 0 || $arDespachoRecogida->getNumero() == NULL) {
                $arDespachoRecogidaTipo = $em->getRepository(TteDespachoRecogidaTipo::class)->find($arDespachoRecogida->getCodigoDespachoRecogidaTipoFk());
                $arDespachoRecogida->setNumero($arDespachoRecogidaTipo->getConsecutivo());
                $arDespachoRecogidaTipo->setConsecutivo($arDespachoRecogidaTipo->getConsecutivo() + 1);
                $em->persist($arDespachoRecogidaTipo);
            }
            $arDespachoRecogida->setEstadoAprobado(1);
            $em->persist($arDespachoRecogida);
            $em->flush();
        }
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arDespachoRecogida){
        if($arDespachoRecogida->getEstadoAprobado()){
            $arDespachoRecogida->setEstadoAnulado(0);
            $this->getEntityManager()->persist($arDespachoRecogida);
            $this->getEntityManager()->flush();
        }
    }

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('dr.fecha')
            ->addSelect('dr.numero')
            ->addSelect('dr.codigoOperacionFk')
            ->addSelect('dr.codigoVehiculoFk')
            ->addSelect('dr.codigoRutaRecogidaFk')
            ->addSelect('dr.cantidad')
            ->addSelect('dr.unidades')
            ->addSelect('dr.pesoReal')
            ->addSelect('dr.pesoVolumen')
            ->addSelect('dr.estadoAutorizado')
            ->addSelect('dr.estadoAprobado')
            ->addSelect('dr.estadoAnulado')
            ->addSelect('dr.estadoDescargado')
            ->addSelect('dr.vrFletePago')
            ->addSelect('cond.nombreCorto AS conductorNombreCorto')
            ->where('dr.estadoContabilizado =  0')
            ->andWhere('dr.estadoAprobado = 1')
            ->leftJoin('dr.conductorRel', 'cond');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteDespachoRecogidaFiltroFecha') == true) {
            if ($session->get('filtroTteDespachoRecogidaFechaDesde') != null) {
                $queryBuilder->andWhere("dr.fecha >= '{$session->get('filtroTteDespachoRecogidaFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("dr.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteDespachoRecogidaFechaHasta') != null) {
                $queryBuilder->andWhere("dr.fecha <= '{$session->get('filtroTteDespachoRecogidaFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("dr.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        $queryBuilder->orderBy('dr.fecha', 'DESC');
        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('v.codigoPropietarioFk')
            ->addSelect('dr.numero')
            ->addSelect('dr.fecha')
            ->addSelect('dr.estadoAprobado')
            ->addSelect('dr.estadoContabilizado')
            ->addSelect('dr.vrFletePago')
            ->addSelect('dr.vrIndustriaComercio')
            ->addSelect('dr.vrRetencionFuente')
            ->addSelect('dr.vrAnticipo')
            ->addSelect('dr.vrSaldo')
            ->addSelect('dr.vrDescuentoCargue')
            ->addSelect('dr.vrDescuentoEstampilla')
            ->addSelect('dr.vrDescuentoPapeleria')
            ->addSelect('dr.vrDescuentoSeguridad')
            ->addSelect('drt.codigoComprobanteFk')
            ->addSelect('drt.codigoCuentaFleteFk')
            ->addSelect('drt.codigoCuentaIndustriaComercioFk')
            ->addSelect('drt.codigoCuentaRetencionFuenteFk')
            ->addSelect('drt.codigoCuentaCargueFk')
            ->addSelect('drt.codigoCuentaSeguridadFk')
            ->addSelect('drt.codigoCuentaEstampillaFk')
            ->addSelect('drt.codigoCuentaAnticipoFk')
            ->addSelect('drt.codigoCuentaPapeleriaFk')
            ->addSelect('drt.codigoCuentaPagarFk')
            ->addSelect('o.codigoCentroCostoFk')
            ->leftJoin('dr.vehiculoRel', 'v')
            ->leftJoin('dr.despachoRecogidaTipoRel', 'drt')
            ->leftJoin('dr.operacionRel', 'o')
            ->where('dr.codigoDespachoRecogidaPk = ' . $codigo);
        $arDespachoRecogida = $queryBuilder->getQuery()->getSingleResult();
        return $arDespachoRecogida;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            foreach ($arr AS $codigo) {
                $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->registroContabilizar($codigo);
                if ($arDespachoRecogida) {
                    if ($arDespachoRecogida['estadoAprobado'] == 1 && $arDespachoRecogida['estadoContabilizado'] == 0) {
                        $arComprobante = $em->getRepository(CtbComprobante::class)->find($arDespachoRecogida['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TtePoseedor::class)->terceroContabilidad($arDespachoRecogida['codigoPropietarioFk']);

                        //Cuenta flete pagado
                        if ($arDespachoRecogida['vrFletePago'] > 0) {
                            if ($arDespachoRecogida['codigoCuentaFleteFk']) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($arDespachoRecogida['codigoCuentaFleteFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta del flete " . $arDespachoRecogida['codigoCuentaFleteFk'];
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "D";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrFletePago']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrFletePago']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion('FLETE PAGADO');
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta para el flete pagado";
                                break;
                            }
                        }

                        //Cuenta industria y comercio
                        if ($arDespachoRecogida['vrIndustriaComercio'] > 0) {
                            $descripcion = "INDUSTRIA COMERCIO";
                            $cuenta = $arDespachoRecogida['codigoCuentaIndustriaComercioFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrIndustriaComercio']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrIndustriaComercio']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                if ($arCuenta->getExigeBase()) {
                                    $arRegistro->setVrBase($arDespachoRecogida['vrFletePago']);
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Cuenta retencion fuente
                        if ($arDespachoRecogida['vrRetencionFuente'] > 0) {
                            $descripcion = "RETENCION FUENTE";
                            $cuenta = $arDespachoRecogida['codigoCuentaRetencionFuenteFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrRetencionFuente']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrRetencionFuente']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                if ($arCuenta->getExigeBase()) {
                                    $arRegistro->setVrBase($arDespachoRecogida['vrFletePago']);
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Descuento seguridad
                        if ($arDespachoRecogida['vrDescuentoSeguridad'] > 0) {
                            $descripcion = "DESCUENTO SEGURIDAD";
                            $cuenta = $arDespachoRecogida['codigoCuentaSeguridadFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrDescuentoSeguridad']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrDescuentoSeguridad']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Descuento cargue
                        if ($arDespachoRecogida['vrDescuentoCargue'] > 0) {
                            $descripcion = "DESCUENTO CARGUE";
                            $cuenta = $arDespachoRecogida['codigoCuentaCargueFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrDescuentoCargue']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrDescuentoCargue']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Descuento estampilla
                        if ($arDespachoRecogida['vrDescuentoEstampilla'] > 0) {
                            $descripcion = "DESCUENTO ESTAMPILLA";
                            $cuenta = $arDespachoRecogida['codigoCuentaEstampillaFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrDescuentoEstampilla']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrDescuentoEstampilla']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Descuento papeleria
                        if ($arDespachoRecogida['vrDescuentoPapeleria'] > 0) {
                            $descripcion = "DESCUENTO PAPELERIA";
                            $cuenta = $arDespachoRecogida['codigoCuentaPapeleriaFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrDescuentoPapeleria']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrDescuentoPapeleria']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Anticipo
                        if ($arDespachoRecogida['vrAnticipo'] > 0) {
                            $descripcion = "ANTICIPO";
                            $cuenta = $arDespachoRecogida['codigoCuentaAnticipoFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrAnticipo']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrAnticipo']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Saldo
                        if ($arDespachoRecogida['vrSaldo'] > 0) {
                            $descripcion = "POR PAGAR";
                            $cuenta = $arDespachoRecogida['codigoCuentaPagarFk'];
                            if ($cuenta) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($cuenta);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespachoRecogida['numero']);
                                $arRegistro->setNumeroReferencia($arDespachoRecogida['numero']);
                                $arRegistro->setFecha($arDespachoRecogida['fecha']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespachoRecogida['vrSaldo']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespachoRecogida['vrSaldo']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        $arDespachoAct = $em->getRepository(TteDespachoRecogida::class)->find($arDespachoRecogida['codigoDespachoRecogidaPk']);
                        $arDespachoAct->setEstadoContabilizado(1);
                        $em->persist($arDespachoAct);
                    }
                } else {
                    $error = "La despacho codigo " . $codigo . " no existe";
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