<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\Transporte\TteIntermediacionVenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionVentaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacionVenta::class);
    }

    public function detalle($codigoIntermediacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionVenta::class, 'id')
            ->select('id.codigoIntermediacionVentaPk')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrFleteParticipacion')
            ->addSelect('id.vrFleteIngreso')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('ft.nombre AS facturaTipoNombre')
            ->leftJoin('id.clienteRel', 'c')
            ->leftJoin('id.facturaTipoRel','ft')
            ->where('id.codigoIntermediacionFk = ' . $codigoIntermediacion)
        ->orderBy('id.codigoClienteFk');
        return $queryBuilder->getQuery()->execute();
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionDetalle::class, 'id')
            ->select('id.codigoIntermediacionDetallePk')
            ->addSelect('id.numero')
            ->addSelect('id.fecha')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('ft.codigoCuentaIngresoFleteFk')
            ->addSelect('ft.codigoCuentaIngresoFleteIntermediacionFk')
            ->addSelect('ft.naturalezaCuentaIngreso')
            ->addSelect('ft.naturalezaCuentaIngresoFleteIntermediacion')
            ->addSelect('dt.codigoCuentaFletePagoIntermediacionFk')
            ->addSelect('dt.naturalezaCuentaFletePagoIntermediacion')
            ->addSelect('id.estadoContabilizado')
            ->addSelect('id.vrIngreso')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrPago')
            ->leftJoin('id.facturaTipoRel', 'ft')
            ->leftJoin('id.despachoTipoRel', 'dt')
            ->where('id.codigoIntermediacionDetallePk = ' . $codigo);
        $arIntermediacionDetalle = $queryBuilder->getQuery()->getSingleResult();
        return $arIntermediacionDetalle;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->contabilizarIntermediacion();
            $arComprobante = $em->getRepository(FinComprobante::class)->find($arrConfiguracion['codigoComprobanteIntermediacionFk']);
            foreach ($arr AS $codigo) {
                $arIntermediacionDetalle = $em->getRepository(TteIntermediacionDetalle::class)->registroContabilizar($codigo);
                if($arIntermediacionDetalle) {
                    if($arIntermediacionDetalle['estadoContabilizado'] == 0) {
                        $arTercero = $em->getRepository(TteCliente::class)->terceroFinanciero($arIntermediacionDetalle['codigoClienteFk']);
                        //Cuenta del ingreso flete cobro intermediacion
                        if($arIntermediacionDetalle['codigoCuentaIngresoFleteIntermediacionFk']) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arIntermediacionDetalle['codigoCuentaIngresoFleteIntermediacionFk']);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta del flete " . $arIntermediacionDetalle['codigoCuentaIngresoFleteIntermediacionFk'];
                                break;
                            }
                            $arRegistro = new FinRegistro();
                            $arRegistro->setTerceroRel($arTercero);
                            $arRegistro->setCuentaRel($arCuenta);
                            $arRegistro->setComprobanteRel($arComprobante);
                            /*if($arCuenta->getExigeCentroCosto()) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                            }*/
                            //$arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                            $arRegistro->setNumero($arIntermediacionDetalle['numero']);
                            //$arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                            //$arRegistro->setNumeroReferencia($numeroReferencia);
                            $arRegistro->setFecha($arIntermediacionDetalle['fecha']);
                            if($arIntermediacionDetalle['naturalezaCuentaIngresoFleteIntermediacion'] == 'D') {
                                $arRegistro->setVrDebito($arIntermediacionDetalle['vrFlete']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arIntermediacionDetalle['vrFlete']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion('INGRESO FLETE TERCERO DEV_FAC');
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por flete";
                            break;
                        }

                        //Cuenta del ingreso flete pago intermediacion
                        if($arIntermediacionDetalle['codigoCuentaFletePagoIntermediacionFk']) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arIntermediacionDetalle['codigoCuentaFletePagoIntermediacionFk']);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta del flete " . $arIntermediacionDetalle['codigoCuentaFletePagoIntermediacionFk'];
                                break;
                            }
                            $arRegistro = new FinRegistro();
                            $arRegistro->setTerceroRel($arTercero);
                            $arRegistro->setCuentaRel($arCuenta);
                            $arRegistro->setComprobanteRel($arComprobante);
                            /*if($arCuenta->getExigeCentroCosto()) {
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                            }*/
                            //$arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                            $arRegistro->setNumero($arIntermediacionDetalle['numero']);
                            //$arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                            //$arRegistro->setNumeroReferencia($numeroReferencia);
                            $arRegistro->setFecha($arIntermediacionDetalle['fecha']);
                            if($arIntermediacionDetalle['naturalezaCuentaFletePagoIntermediacion'] == 'D') {
                                $arRegistro->setVrDebito($arIntermediacionDetalle['vrPago']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arIntermediacionDetalle['vrPago']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion('INGRESO FLETE TERCERO DEV_DES');
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por flete";
                            break;
                        }

                        //Cuenta del ingreso
                        if($arIntermediacionDetalle['codigoCuentaIngresoFleteFk']) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arIntermediacionDetalle['codigoCuentaIngresoFleteFk']);
                            if(!$arCuenta) {
                                $error = "No se encuentra la cuenta del flete " . $arIntermediacionDetalle['codigoCuentaIngresoFleteFk'];
                                break;
                            }
                            $arRegistro = new FinRegistro();
                            $arRegistro->setTerceroRel($arTercero);
                            $arRegistro->setCuentaRel($arCuenta);
                            $arRegistro->setComprobanteRel($arComprobante);
//                            if($arCuenta->getExigeCentroCosto()) {
//                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
//                                $arRegistro->setCentroCostoRel($arCentroCosto);
//                            }
                            //$arRegistro->setNumeroPrefijo($arFactura['prefijo']);
                            $arRegistro->setNumero($arIntermediacionDetalle['numero']);
                            //$arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                            //$arRegistro->setNumeroReferencia($numeroReferencia);
                            $arRegistro->setFecha($arIntermediacionDetalle['fecha']);
                            if($arIntermediacionDetalle['naturalezaCuentaIngreso'] == 'D') {
                                $arRegistro->setVrDebito($arIntermediacionDetalle['vrIngreso']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arIntermediacionDetalle['vrIngreso']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion('INGRESO INTERMEDIACION');
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por flete";
                            break;
                        }


                        $arIntermediacionDetalle = $em->getRepository(TteIntermediacionDetalle::class)->find($arIntermediacionDetalle['codigoIntermediacionDetallePk']);
                        //$arIntermediacionDetalle->setEstadoContabilizado(1);
                        $em->persist($arIntermediacionDetalle);
                    }
                } else {
                    $error = "El codigo " . $codigo . " no existe";
                    break;
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