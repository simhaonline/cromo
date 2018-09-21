<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
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
        if($session->get('filtroTteDespachoRecogidaVehiculoCodigo') != ''){
            $queryBuilder->andWhere("dr.codigoVehiculoFk = '{$session->get('filtroTteDespachoRecogidaVehiculoCodigo')}'");
        }
        if($session->get('filtroTteDespachoRecogidaEstadoAprobado') != ''){
            $queryBuilder->andWhere("dr.estadoAprobado = {$session->get('filtroTteDespachoRecogidaEstadoAprobado')}");
        }
        $queryBuilder->orderBy('dr.fecha', 'DESC');
        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('d.codigoDespachoPk')
            ->addSelect('v.codigoPropietarioFk')
            ->addSelect('d.numero')
            ->addSelect('d.fechaSalida')
            ->addSelect('d.estadoAprobado')
            ->addSelect('d.estadoContabilizado')
            ->addSelect('d.vrFletePago')
            ->addSelect('d.vrIndustriaComercio')
            ->addSelect('d.vrRetencionFuente')
            ->addSelect('d.vrAnticipo')
            ->addSelect('d.vrSaldo')
            ->addSelect('d.vrDescuentoCargue')
            ->addSelect('d.vrDescuentoEstampilla')
            ->addSelect('d.vrDescuentoPapeleria')
            ->addSelect('d.vrDescuentoSeguridad')
            ->addSelect('dt.codigoComprobanteFk')
            ->addSelect('dt.codigoCuentaFleteFk')
            ->addSelect('dt.codigoCuentaIndustriaComercioFk')
            ->addSelect('dt.codigoCuentaRetencionFuenteFk')
            ->addSelect('dt.codigoCuentaCargueFk')
            ->addSelect('dt.codigoCuentaSeguridadFk')
            ->addSelect('dt.codigoCuentaEstampillaFk')
            ->addSelect('dt.codigoCuentaAnticipoFk')
            ->addSelect('dt.codigoCuentaPapeleriaFk')
            ->addSelect('dt.codigoCuentaPagarFk')
            ->addSelect('o.codigoCentroCostoFk')
            ->leftJoin('d.vehiculoRel', 'v')
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->leftJoin('d.operacionRel', 'o')
            ->where('d.codigoDespachoPk = ' . $codigo);
        $arDespacho = $queryBuilder->getQuery()->getSingleResult();
        return $arDespacho;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            foreach ($arr AS $codigo) {
                $arDespacho = $em->getRepository(TteDespacho::class)->registroContabilizar($codigo);
                if ($arDespacho) {
                    if ($arDespacho['estadoAprobado'] == 1 && $arDespacho['estadoContabilizado'] == 0) {
                        $arComprobante = $em->getRepository(CtbComprobante::class)->find($arDespacho['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TtePoseedor::class)->terceroContabilidad($arDespacho['codigoPropietarioFk']);

                        //Cuenta flete pagado
                        if ($arDespacho['vrFletePago'] > 0) {
                            if ($arDespacho['codigoCuentaFleteFk']) {
                                $arCuenta = $em->getRepository(CtbCuenta::class)->find($arDespacho['codigoCuentaFleteFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta del flete " . $arDespacho['codigoCuentaFleteFk'];
                                    break;
                                }
                                $arRegistro = new CtbRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "D";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrFletePago']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrFletePago']);
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
                        if ($arDespacho['vrIndustriaComercio'] > 0) {
                            $descripcion = "INDUSTRIA COMERCIO";
                            $cuenta = $arDespacho['codigoCuentaIndustriaComercioFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrIndustriaComercio']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrIndustriaComercio']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                if ($arCuenta->getExigeBase()) {
                                    $arRegistro->setVrBase($arDespacho['vrFletePago']);
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Cuenta retencion fuente
                        if ($arDespacho['vrRetencionFuente'] > 0) {
                            $descripcion = "RETENCION FUENTE";
                            $cuenta = $arDespacho['codigoCuentaRetencionFuenteFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrRetencionFuente']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrRetencionFuente']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                if ($arCuenta->getExigeBase()) {
                                    $arRegistro->setVrBase($arDespacho['vrFletePago']);
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        //Descuento seguridad
                        if ($arDespacho['vrDescuentoSeguridad'] > 0) {
                            $descripcion = "DESCUENTO SEGURIDAD";
                            $cuenta = $arDespacho['codigoCuentaSeguridadFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrDescuentoSeguridad']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrDescuentoSeguridad']);
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
                        if ($arDespacho['vrDescuentoCargue'] > 0) {
                            $descripcion = "DESCUENTO CARGUE";
                            $cuenta = $arDespacho['codigoCuentaCargueFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrDescuentoCargue']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrDescuentoCargue']);
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
                        if ($arDespacho['vrDescuentoEstampilla'] > 0) {
                            $descripcion = "DESCUENTO ESTAMPILLA";
                            $cuenta = $arDespacho['codigoCuentaEstampillaFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrDescuentoEstampilla']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrDescuentoEstampilla']);
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
                        if ($arDespacho['vrDescuentoPapeleria'] > 0) {
                            $descripcion = "DESCUENTO PAPELERIA";
                            $cuenta = $arDespacho['codigoCuentaPapeleriaFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrDescuentoPapeleria']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrDescuentoPapeleria']);
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
                        if ($arDespacho['vrAnticipo'] > 0) {
                            $descripcion = "ANTICIPO";
                            $cuenta = $arDespacho['codigoCuentaAnticipoFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrAnticipo']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrAnticipo']);
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
                        if ($arDespacho['vrSaldo'] > 0) {
                            $descripcion = "POR PAGAR";
                            $cuenta = $arDespacho['codigoCuentaPagarFk'];
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
                                    $arCentroCosto = $em->getRepository(CtbCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $arRegistro->setNumero($arDespacho['numero']);
                                $arRegistro->setNumeroReferencia($arDespacho['numero']);
                                $arRegistro->setFecha($arDespacho['fechaSalida']);
                                $naturaleza = "C";
                                if ($naturaleza == 'D') {
                                    $arRegistro->setVrDebito($arDespacho['vrSaldo']);
                                    $arRegistro->setNaturaleza('D');
                                } else {
                                    $arRegistro->setVrCredito($arDespacho['vrSaldo']);
                                    $arRegistro->setNaturaleza('C');
                                }
                                $arRegistro->setDescripcion($descripcion);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }
                        }

                        $arDespachoAct = $em->getRepository(TteDespacho::class)->find($arDespacho['codigoDespachoPk']);
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