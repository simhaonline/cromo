<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionCompra;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\Transporte\TteIntermediacionRecogida;
use App\Entity\Transporte\TteIntermediacionVenta;
use App\Entity\Transporte\TtePoseedor;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacion::class, 'i')
            ->select('i.codigoIntermediacionPk')
            ->addSelect('i.anio')
            ->addSelect('i.mes')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.vrFletePago')
            ->addSelect('i.vrFleteCobro')
            ->orderBy('i.anio', 'DESC');
        if ($session->get('filtroTteIntermediacionAnio') != '') {
            $queryBuilder->andWhere("i.anio LIKE '%{$session->get('filtroTteIntermediacionAnio')}%' ");
        }
        if ($session->get('filtroTteIntermediacioneMes') != '') {
            $queryBuilder->andWhere("i.mes = {$session->get('filtroTteIntermediacioneMes')} ");
        }
        return $queryBuilder;
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if(!$arIntermediacion->getEstadoAutorizado()) {
            $ultimoDia = date("d", (mktime(0, 0, 0, $arIntermediacion->getMes() + 1, 1, $arIntermediacion->getAnio()) - 1));
            $fechaDesde = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-01 00:00:00";
            $fechaHasta = $arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-" . $ultimoDia . " 23:59:00";
            $fletePago = $em->getRepository(TteDespacho::class)->fletePago($fechaDesde, $fechaHasta);
            $fletePagoRecogida = $em->getRepository(TteDespachoRecogida::class)->fletePago($fechaDesde, $fechaHasta);
            $fletePagoTotal = $fletePago + $fletePagoRecogida;
            $fleteCobro = $em->getRepository(TteFactura::class)->fleteCobro($fechaDesde, $fechaHasta);
            $arIntermediacion->setVrFleteCobro($fleteCobro);
            $ingresoTotal = 0;



            $arrFleteCobroDetallados = $em->getRepository(TteFactura::class)->fleteCobroDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFleteCobroDetallados as $arrFleteCobroDetallado) {
                $arCliente = $em->getRepository(TteCliente::class)->find($arrFleteCobroDetallado['codigoClienteFk']);
                $arFacturaTipo = $em->getRepository(TteFacturaTipo::class)->find($arrFleteCobroDetallado['codigoFacturaTipoFk']);
                $fleteCobroFactura = $arrFleteCobroDetallado['flete'];
                $participacion = ($fleteCobroFactura / $fleteCobro) * 100;
                $fleteParticipacion = $fletePagoTotal * $participacion / 100;
                $fleteIngreso = $fleteCobroFactura - $fleteParticipacion;
                $arIntermediacionVenta = new TteIntermediacionVenta();
                $arIntermediacionVenta->setClienteRel($arCliente);
                $arIntermediacionVenta->setFacturaTipoRel($arFacturaTipo);
                $arIntermediacionVenta->setIntermediacionRel($arIntermediacion);
                $arIntermediacionVenta->setAnio($arIntermediacion->getAnio());
                $arIntermediacionVenta->setMes($arIntermediacion->getMes());
                $arIntermediacionVenta->setPorcentajeParticipacion($participacion);
                $arIntermediacionVenta->setVrFlete($fleteCobroFactura);
                $arIntermediacionVenta->setVrFleteParticipacion($fleteParticipacion);
                $arIntermediacionVenta->setVrFleteIngreso($fleteIngreso);
                $em->persist($arIntermediacionVenta);
                $ingresoTotal += $fleteIngreso;
            }

            $arrFletePagoDetallados = $em->getRepository(TteDespacho::class)->fletePagoDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFletePagoDetallados as $arrFletePagoDetallado) {
                $arPoseedor = $em->getRepository(TtePoseedor::class)->find($arrFletePagoDetallado['codigoPoseedorFk']);
                $arDespachoTipo = $em->getRepository(TteDespachoTipo::class)->find($arrFletePagoDetallado['codigoDespachoTipoFk']);
                $fletePagoFactura = $arrFletePagoDetallado['fletePago'];
                $participacion = ($fletePagoFactura / $fletePagoTotal) * 100;
                $fleteParticipacion = $fletePagoTotal * $participacion / 100;
                $arIntermediacionCompra = new TteIntermediacionCompra();
                $arIntermediacionCompra->setIntermediacionRel($arIntermediacion);
                $arIntermediacionCompra->setPoseedorRel($arPoseedor);
                $arIntermediacionCompra->setDespachoTipoRel($arDespachoTipo);
                $arIntermediacionCompra->setAnio($arIntermediacion->getAnio());
                $arIntermediacionCompra->setMes($arIntermediacion->getMes());
                $arIntermediacionCompra->setPorcentajeParticipacion($participacion);
                $arIntermediacionCompra->setVrFlete($fletePagoFactura);
                $arIntermediacionCompra->setVrFleteParticipacion($fleteParticipacion);
                $em->persist($arIntermediacionCompra);
            }

            $arrFletePagoRecogidaDetallados = $em->getRepository(TteDespachoRecogida::class)->fletePagoDetallado($fechaDesde, $fechaHasta);
            foreach ($arrFletePagoRecogidaDetallados as $arrFletePagoRecogidaDetallado) {
                $arPoseedor = $em->getRepository(TtePoseedor::class)->find($arrFletePagoRecogidaDetallado['codigoPoseedorFk']);
                $arDespachoRecogidaTipo = $em->getRepository(TteDespachoRecogidaTipo::class)->find($arrFletePagoRecogidaDetallado['codigoDespachoRecogidaTipoFk']);
                $fletePagoFactura = $arrFletePagoRecogidaDetallado['fletePago'];
                $participacion = ($fletePagoFactura / $fletePagoTotal) * 100;
                $fleteParticipacion = $fletePagoTotal * $participacion / 100;
                $arIntermediacionRecogida = new TteIntermediacionRecogida();
                $arIntermediacionRecogida->setIntermediacionRel($arIntermediacion);
                $arIntermediacionRecogida->setPoseedorRel($arPoseedor);
                $arIntermediacionRecogida->setDespachoRecogidaTipoRel($arDespachoRecogidaTipo);
                $arIntermediacionRecogida->setAnio($arIntermediacion->getAnio());
                $arIntermediacionRecogida->setMes($arIntermediacion->getMes());
                $arIntermediacionRecogida->setPorcentajeParticipacion($participacion);
                $arIntermediacionRecogida->setVrFlete($fletePagoFactura);
                $arIntermediacionRecogida->setVrFleteParticipacion($fleteParticipacion);
                $em->persist($arIntermediacionRecogida);
            }

            $arIntermediacion->setEstadoAutorizado(1);
            $arIntermediacion->setVrFleteCobro($fleteCobro);
            $arIntermediacion->setVrFletePago($fletePago);
            $arIntermediacion->setVrFletePagoRecogida($fletePagoRecogida);
            $arIntermediacion->setVrFletePagoTotal($fletePagoTotal);
            $arIntermediacion->setVrIngreso($ingresoTotal);
            $em->flush();
        } else {
            Mensajes::error("El documento ya esta autorizado");
        }
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desAutorizar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if($arIntermediacion->getEstadoAutorizado() && !$arIntermediacion->getEstadoAprobado()) {
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionVenta id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
            $numDeleted = $query->execute();
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionCompra id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
            $numDeleted = $query->execute();
            $query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteIntermediacionRecogida id WHERE id.codigoIntermediacionFk =' . $arIntermediacion->getCodigoIntermediacionPk());
            $numDeleted = $query->execute();
            $arIntermediacion->setEstadoAutorizado(0);
            $arIntermediacion->setVrFleteCobro(0);
            $arIntermediacion->setVrFletePago(0);
            $arIntermediacion->setVrFletePagoRecogida(0);
            $arIntermediacion->setVrFletePagoTotal(0);
            $arIntermediacion->setVrIngreso(0);
            $em->persist($arIntermediacion);
            $em->flush();
        } else {
            Mensajes::error("El documento no esta autorizado o esta aprobado");
        }
    }

    /**
     * @param $arIntermediacion TteIntermediacion
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arIntermediacion)
    {
        $em = $this->getEntityManager();
        if($arIntermediacion->getEstadoAutorizado() && !$arIntermediacion->getEstadoAprobado()) {
            $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
            $consecutivo = $arConsecutivo->getIntermediacion();
            $arConsecutivo->setIntermediacion($consecutivo + 1);
            $em->persist($arConsecutivo);
            $arIntermediacion->setNumero($consecutivo);
            $arIntermediacion->setEstadoAprobado(1);
            $em->persist($arIntermediacion);
            $em->flush();
        } else {
            Mensajes::error("El documento no esta autorizado o esta aprobado");
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteIntermediacion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if (count($this->getEntityManager()->getRepository(TteIntermediacionDetalle::class)->findBy(['codigoIntermediacionFk' => $arRegistro->getCodigoIntermediacionPk()])) <= 0) {
                            $this->getEntityManager()->remove($arRegistro);
                        } else {
                            $respuesta = 'No se puede eliminar, el registro tiene detalles';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado o autorizado';
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

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionDetalle::class, 'id')
            ->select('id.codigoIntermediacionDetallePk')
            ->addSelect('id.numero')
            ->addSelect('id.fecha')
            ->addSelect('id.anio')
            ->addSelect('id.mes')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrPago')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('id.vrIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('ft.nombre AS facturaTipoNombre')
            ->leftJoin('id.clienteRel', 'c')
            ->leftJoin('id.facturaTipoRel','ft')
            ->where('id.estadoContabilizado =  0');
        /*if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }*/

        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacion::class, 'i')
            ->select('i.codigoIntermediacionPk')
            ->addSelect('i.numero')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoContabilizado')
            ->addSelect('i.fecha')
            ->where('i.codigoIntermediacionPk = ' . $codigo);
        $arIntermediacion = $queryBuilder->getQuery()->getSingleResult();
        return $arIntermediacion;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->contabilizarIntermediacion();
            $arComprobante = $em->getRepository(FinComprobante::class)->find($arrConfiguracion['codigoComprobanteIntermediacionFk']);
            foreach ($arr AS $codigo) {
                $arIntermediacion = $em->getRepository(TteIntermediacion::class)->registroContabilizar($codigo);
                if($arIntermediacion['estadoAprobado']) {
                    if(!$arIntermediacion['estadoContabilizado']) {
                        if($arIntermediacion) {
                            if($arIntermediacion['estadoContabilizado'] == 0) {
                                //Contabilizar intermediacion parte ventas
                                $arrIntermediacionesVenta = $em->getRepository(TteIntermediacionVenta::class)->registroContabilizar($codigo);
                                foreach ($arrIntermediacionesVenta as $arrIntermediacionVenta) {
                                    $arTercero = $em->getRepository(TteCliente::class)->terceroFinanciero($arrIntermediacionVenta['codigoClienteFk']);
                                    //Flete
                                    if($arrIntermediacionVenta['codigoCuentaIngresoFleteFk']) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arrIntermediacionVenta['codigoCuentaIngresoFleteFk']);
                                        if(!$arCuenta) {
                                            $error = "No se encuentra la cuenta del flete " . $arrIntermediacionVenta['codigoCuentaIngresoFleteFk'];
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arIntermediacion['numero']);
                                        $arRegistro->setFecha($arIntermediacion['fecha']);
                                        if($arrIntermediacionVenta['codigoFacturaClaseFk'] == 'FA') {
                                            $arRegistro->setVrDebito($arrIntermediacionVenta['vrFlete']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion('INGRESO FLETE TERCERO DEV_FAC');
                                        } else {
                                            $arRegistro->setVrCredito($arrIntermediacionVenta['vrFlete']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion('INGRESO FLETE TERCERO DEV_FAC NC');
                                        }

                                        $arRegistro->setCodigoModeloFk('TteIntermediacion');
                                        $arRegistro->setCodigoDocumento($codigo);


                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por flete o flete intermediacion";
                                        break;
                                    }

                                    if($arrIntermediacionVenta['codigoCuentaIngresoFleteIntermediacionFk']) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arrIntermediacionVenta['codigoCuentaIngresoFleteIntermediacionFk']);
                                        if(!$arCuenta) {
                                            $error = "No se encuentra la cuenta del flete " . $arrIntermediacionVenta['codigoCuentaIngresoFleteIntermediacionFk'];
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arIntermediacion['numero']);
                                        $arRegistro->setFecha($arIntermediacion['fecha']);
                                        if($arrIntermediacionVenta['codigoFacturaClaseFk'] == 'FA') {
                                            $arRegistro->setVrCredito($arrIntermediacionVenta['vrFleteIngreso']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion('INGRESO REAL');
                                        } else {
                                            $arRegistro->setVrDebito($arrIntermediacionVenta['vrFleteIngreso']);
                                            $arRegistro->setNaturaleza('D');
                                            $arRegistro->setDescripcion('INGRESO REAL NC');
                                        }
                                        $arRegistro->setCodigoModeloFk('TteIntermediacion');
                                        $arRegistro->setCodigoDocumento($codigo);


                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo de factura no tiene configurada la cuenta para el ingreso flete intermediacion";
                                        break;
                                    }
                                }

                                //Contabilizar intermediacion parte compras
                                $arrIntermediacionesCompra = $em->getRepository(TteIntermediacionCompra::class)->registroContabilizar($codigo);
                                foreach ($arrIntermediacionesCompra as $arrIntermediacionCompra) {
                                    $arTercero = $em->getRepository(TtePoseedor::class)->terceroFinanciero($arrIntermediacionCompra['codigoPoseedorFk']);
                                    //Flete cobro
                                    if($arrIntermediacionCompra['codigoCuentaFleteFk']) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arrIntermediacionCompra['codigoCuentaFleteFk']);
                                        if(!$arCuenta) {
                                            $error = "No se encuentra la cuenta del flete " . $arrIntermediacionCompra['codigoCuentaFleteFk'];
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arIntermediacion['numero']);
                                        $arRegistro->setFecha($arIntermediacion['fecha']);
                                        $arRegistro->setVrCredito($arrIntermediacionCompra['vrFleteParticipacion']);
                                        $arRegistro->setNaturaleza('C');
                                        $arRegistro->setCodigoModeloFk('TteIntermediacion');
                                        $arRegistro->setCodigoDocumento($codigo);

                                        $arRegistro->setDescripcion('FLETE PAGO');
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo de despacho no tiene configurada la cuenta para el flete pagado";
                                        break;
                                    }

                                }

                                //Contabilizar intermediacion parte recogidas
                                $arrIntermediacionesRecogidas = $em->getRepository(TteIntermediacionRecogida::class)->registroContabilizar($codigo);
                                foreach ($arrIntermediacionesRecogidas as $arrIntermediacionesRecogida) {
                                    $arTercero = $em->getRepository(TtePoseedor::class)->terceroFinanciero($arrIntermediacionesRecogida['codigoPoseedorFk']);
                                    //Flete cobro
                                    if($arrIntermediacionesRecogida['codigoCuentaFleteFk']) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arrIntermediacionesRecogida['codigoCuentaFleteFk']);
                                        if(!$arCuenta) {
                                            $error = "No se encuentra la cuenta del flete " . $arrIntermediacionesRecogida['codigoCuentaFleteFk'];
                                            break;
                                        }
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arIntermediacion['numero']);
                                        $arRegistro->setFecha($arIntermediacion['fecha']);
                                        $arRegistro->setVrCredito($arrIntermediacionesRecogida['vrFleteParticipacion']);
                                        $arRegistro->setNaturaleza('C');
                                        $arRegistro->setCodigoModeloFk('TteIntermediacion');
                                        $arRegistro->setCodigoDocumento($codigo);

                                        $arRegistro->setDescripcion('FLETE PAGO RECOGIDA');
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El tipo de despacho no tiene configurada la cuenta para el flete pagado";
                                        break;
                                    }

                                }

                                $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($codigo);
                                $arIntermediacion->setEstadoContabilizado(1);
                                $em->persist($arIntermediacion);
                            }
                        } else {
                            $error = "El codigo " . $codigo . " no existe";
                            break;
                        }
                    } else {
                        Mensajes::error('El documento ya esta contabilizado');
                    }
                } else {
                    Mensajes::error('El documento debe estar aprobado');
                }
            }
            if($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }

        }
        return true;
    }



}