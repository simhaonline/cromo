<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteMonitoreo;
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('dr.numero')
            ->addSelect('dr.fecha')
            ->addSelect('dr.codigoOperacionFk')
            ->addSelect('dr.codigoVehiculoFk')
            ->addSelect('cond.nombreCorto AS conductorNombreCorto')
            ->addSelect('dr.codigoRutaRecogidaFk')
            ->addSelect('dr.cantidad')
            ->addSelect('dr.unidades')
            ->addSelect('dr.pesoReal')
            ->addSelect('dr.pesoVolumen')
            ->addSelect('dr.vrFletePago')
            ->addSelect('dr.estadoAutorizado')
            ->addSelect('dr.estadoAprobado')
            ->addSelect('dr.estadoAnulado')
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
        if ($session->get('filtroTteOperacion') != '') {
            $queryBuilder->andWhere("dr.codigoOperacionFk = '{$session->get('filtroTteOperacion')}'");
        }
        if ($session->get('filtroTteCodigoDespachoRecogida') != '') {
            $queryBuilder->andWhere("dr.codigoDespachoRecogidaPk = '{$session->get('filtroTteCodigoDespachoRecogida')}'");
        }
        if ($session->get('filtroTteNumeroDespachoRecogida') != '') {
            $queryBuilder->andWhere("dr.numero = '{$session->get('filtroTteNumeroDespachoRecogida')}'");
        }
        if ($session->get('filtroTteDespachoRecogidaVehiculoCodigo') != '') {
            $queryBuilder->andWhere("dr.codigoVehiculoFk = '{$session->get('filtroTteDespachoRecogidaVehiculoCodigo')}'");
        }
        if ($session->get('filtroTteDespachoRecogidaEstadoAprobado') != '') {
            $queryBuilder->andWhere("dr.estadoAprobado = {$session->get('filtroTteDespachoRecogidaEstadoAprobado')}");
        }
        if ($session->get('filtroTteDespachoRecogidaEstadoAutorizado') != '') {
            $queryBuilder->andWhere("dr.estadoAutorizado = {$session->get('filtroTteDespachoRecogidaEstadoAutorizado')}");
        }
        $queryBuilder->orderBy('dr.fecha', 'DESC');

        return $queryBuilder;
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
                                if (count($this->getEntityManager()->getRepository(TteDespachoRecogidaAuxiliar::class)->findBy(['codigoDespachoRecogidaFk' => $arRegistro->getCodigoDespachoRecogidaPk()])) <= 0) {
                                    $this->getEntityManager()->remove($arRegistro);
                                } else {
                                    $respuesta = 'No se puede eliminar, el registro tiene auxiliares asignados';
                                }
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
    public function autorizar($arDespachoRecogida)
    {
        if ($this->getEntityManager()->getRepository(TteRecogida::class)->contarDetalles($arDespachoRecogida->getCodigoDespachoRecogidaPk()) > 0) {
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
    public function desautorizar($arDespachoRecogida)
    {
        $arDespachoRecogida->setEstadoAutorizado(0);
        $this->getEntityManager()->persist($arDespachoRecogida);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arDespachoRecogida)
    {
        $em = $this->getEntityManager();
        if ($arDespachoRecogida->getEstadoAutorizado() == 1 && $arDespachoRecogida->getEstadoAprobado() == 0) {
            $arDespachoRecogidaTipo = $em->getRepository(TteDespachoRecogidaTipo::class)->find($arDespachoRecogida->getCodigoDespachoRecogidaTipoFk());
            if ($arDespachoRecogida) {
                if ($arDespachoRecogida->getNumero() == 0 || $arDespachoRecogida->getNumero() == NULL) {
                    $arDespachoRecogida->setNumero($arDespachoRecogidaTipo->getConsecutivo());
                    $arDespachoRecogidaTipo->setConsecutivo($arDespachoRecogidaTipo->getConsecutivo() + 1);
                    $em->persist($arDespachoRecogidaTipo);
                }
                $arDespachoRecogida->setEstadoAprobado(1);
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
                if ($arConfiguracion['contabilidadAutomatica']) {
                    $this->contabilizar(array($arDespachoRecogida->getCodigoDespachoRecogidaPk()));
                }
                //Generar monitoreo
                if ($arDespachoRecogidaTipo->getGeneraMonitoreo()) {
                    $arMonitoreo = new TteMonitoreo();
                    $arMonitoreo->setVehiculoRel($arDespachoRecogida->getVehiculoRel());
                    $arMonitoreo->setDespachoRecogidaRel($arDespachoRecogida);
                    $arMonitoreo->setCiudadDestinoRel($arDespachoRecogida->getCiudadRel());
                    $arMonitoreo->setFechaRegistro(new \DateTime('now'));
                    $arMonitoreo->setFechaInicio(new \DateTime('now'));
                    $arMonitoreo->setFechaFin(new \DateTime('now'));
                    $em->persist($arMonitoreo);
                }
                $em->flush();
                $em->persist($arDespachoRecogida);
            } else {
                Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
            }
        }
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arDespachoRecogida)
    {
        $em = $this->getEntityManager();
        if ($arDespachoRecogida->getEstadoAutorizado() == 1) {
            if ($arDespachoRecogida->getEstadoAnulado() == 0) {
                $arRecogidas = $em->getRepository(TteRecogida::class)->findBy(['codigoDespachoRecogidaFk' => $arDespachoRecogida->getCodigoDespachoRecogidaPk()]);
                foreach ($arRecogidas AS $arRecogida) {
                    $arRecogida->setDespachoRecogidaRel(null);
                    $arRecogida->setEstadoProgramado(0);
                    $em->persist($arRecogida);
                }
                $arDespachoRecogida->setEstadoAnulado(1);
                $arDespachoRecogida->setCantidad(0);
                $arDespachoRecogida->setUnidades(0);
                $arDespachoRecogida->setPesoReal(0);
                $arDespachoRecogida->setPesoVolumen(0);
                $arDespachoRecogida->setVrFletePago(0);
                $arDespachoRecogida->setVrAnticipo(0);
                $arDespachoRecogida->setVrDeclara(0);
                $arDespachoRecogida->setVrIndustriaComercio(0);
                $arDespachoRecogida->setVrRetencionFuente(0);
                $arDespachoRecogida->setVrDescuentoPapeleria(0);
                $arDespachoRecogida->setVrDescuentoSeguridad(0);
                $arDespachoRecogida->setVrDescuentoCargue(0);
                $arDespachoRecogida->setVrDescuentoEstampilla(0);
                $arDespachoRecogida->setVrSaldo(0);
                $arDespachoRecogida->setVrTotal(0);
                $em->persist($arDespachoRecogida);
                $em->flush();
            } else {
                Mensajes::error('El despacho ya esta anulado');
            }

        } else {
            Mensajes::error('El despacho no esta autorizada');
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
            ->leftJoin('dr.conductorRel', 'cond')
            ->leftJoin('dr.despachoRecogidaTipoRel', 'drt')
            ->where('dr.estadoContabilizado =  0')
            ->andWhere('dr.estadoAprobado = 1')
            ->andWhere('drt.contabilizar = 1');
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
            ->addSelect('drt.contabilizar')
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
                    if ($arDespachoRecogida['contabilizar']) {
                        if ($arDespachoRecogida['estadoAprobado'] == 1 && $arDespachoRecogida['estadoContabilizado'] == 0) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arDespachoRecogida['codigoComprobanteFk']);
                            $arTercero = $em->getRepository(TtePoseedor::class)->terceroFinanciero($arDespachoRecogida['codigoPropietarioFk']);

                            //Cuenta flete pagado
                            if ($arDespachoRecogida['vrFletePago'] > 0) {
                                if ($arDespachoRecogida['codigoCuentaFleteFk']) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arDespachoRecogida['codigoCuentaFleteFk']);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta del flete " . $arDespachoRecogida['codigoCuentaFleteFk'];
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
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
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                    $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                    $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                    break;
                                }
                            }

                            //Saldo

                            $descripcion = "POR PAGAR";
                            $cuenta = $arDespachoRecogida['codigoCuentaPagarFk'];
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
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespachoRecogida['codigoCentroCostoFk']);
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
                                $arRegistro->setCodigoModeloFk('TteDespachoRecogida');
                                $arRegistro->setCodigoDocumento($arDespachoRecogida['codigoDespachoRecogidaPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                                break;
                            }


                            $arDespachoAct = $em->getRepository(TteDespachoRecogida::class)->find($arDespachoRecogida['codigoDespachoRecogidaPk']);
                            $arDespachoAct->setEstadoContabilizado(1);
                            $em->persist($arDespachoAct);
                        }
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

    public function recogidas($codigoRecogida)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('dr.numero')
            ->addSelect('dr.fecha')
            ->addSelect('dr.codigoOperacionFk')
            ->addSelect('dr.codigoVehiculoFk')
            ->addSelect('c.nombreCorto AS conductor')
            ->addSelect('rc.nombre AS ruta')
            ->addSelect('dr.estadoAutorizado')
            ->addSelect('dr.estadoAprobado')
            ->addSelect('dr.estadoAnulado')
            ->where("r.codigoRecogidaPk = {$codigoRecogida}")
            ->leftJoin('dr.recogidasDespachoRecogidaRel', 'r')
            ->leftJoin('dr.conductorRel', 'c')
            ->leftJoin('dr.rutaRecogidaRel', 'rc');

        return $queryBuilder->getQuery()->getResult();
    }

    public function fletePago($fechaDesde, $fechaHasta)
    {
        $valor = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'd')
            ->select("SUM(d.vrFletePago) as fletePago")
            ->where("d.fecha >='" . $fechaDesde . "' AND d.fecha <= '" . $fechaHasta . "'")
            ->andWhere('d.estadoAprobado = 1');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();
        if ($arrResultado['fletePago']) {
            $valor = $arrResultado['fletePago'];
        }
        return $valor;
    }

    public function fletePagoDetallado($fechaDesde, $fechaHasta)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'd')
            ->select("d.codigoPoseedorFk")
            ->addSelect('d.codigoDespachoRecogidaTipoFk')
            ->addSelect("SUM(d.vrFletePago) as fletePago")
            ->leftJoin('d.despachoRecogidaTipoRel', 'dt')
            ->where("d.fecha >='" . $fechaDesde . "' AND d.fecha <= '" . $fechaHasta . "'")
            ->andWhere('d.estadoAprobado = 1')
            ->groupBy('d.codigoPoseedorFk')
            ->addGroupBy('d.codigoDespachoRecogidaTipoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();
        return $arrResultado;
    }

}
