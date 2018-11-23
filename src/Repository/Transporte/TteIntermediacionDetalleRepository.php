<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
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
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacionDetalle::class);
    }

    public function detalle($codigoIntermediacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionDetalle::class, 'id')
            ->select('id.codigoIntermediacionDetallePk')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrPago')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('id.vrIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->leftJoin('id.clienteRel', 'c')
            ->where('id.codigoIntermediacionFk = ' . $codigoIntermediacion)
        ->orderBy('id.codigoClienteFk');
        return $queryBuilder->getQuery()->execute();
    }

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionDetalle::class, 'id')
            ->select('id.codigoIntermediacionDetallePk')
            ->addSelect('id.anio')
            ->addSelect('id.mes')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrPago')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('id.vrIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->leftJoin('id.clienteRel', 'c')
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
            ->addSelect('id.estadoContabilizado')
            ->where('id.codigoIntermediacionDetallePk = ' . $codigo);
        $arIntermediacionDetalle = $queryBuilder->getQuery()->getSingleResult();
        return $arIntermediacionDetalle;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            foreach ($arr AS $codigo) {
                $arIntermediacionDetalle = $em->getRepository(TteIntermediacionDetalle::class)->registroContabilizar($codigo);
                if($arIntermediacionDetalle) {
                    if($arIntermediacionDetalle['estadoContabilizado'] == 0) {

                        //$arComprobante = $em->getRepository(FinComprobante::class)->find($arFactura['codigoComprobanteFk']);
                        //$arTercero = $em->getRepository(TteCliente::class)->terceroFinanciero($arFactura['codigoClienteFk']);

//                        //Cuenta del ingreso flete
//                        if($arFactura['codigoCuentaIngresoFleteFk']) {
//                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaIngresoFleteFk']);
//                            if(!$arCuenta) {
//                                $error = "No se encuentra la cuenta del flete " . $arFactura['codigoCuentaIngresoFleteFk'];
//                                break;
//                            }
//                            $arRegistro = new FinRegistro();
//                            $arRegistro->setTerceroRel($arTercero);
//                            $arRegistro->setCuentaRel($arCuenta);
//                            $arRegistro->setComprobanteRel($arComprobante);
//                            if($arCuenta->getExigeCentroCosto()) {
//                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
//                                $arRegistro->setCentroCostoRel($arCentroCosto);
//                            }
//                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
//                            $arRegistro->setNumero($arFactura['numero']);
//                            $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
//                            $arRegistro->setNumeroReferencia($numeroReferencia);
//                            $arRegistro->setFecha($arFactura['fecha']);
//                            if($arFactura['naturalezaCuentaIngreso'] == 'D') {
//                                $arRegistro->setVrDebito($arFactura['vrFlete']);
//                                $arRegistro->setNaturaleza('D');
//                            } else {
//                                $arRegistro->setVrCredito($arFactura['vrFlete']);
//                                $arRegistro->setNaturaleza('C');
//                            }
//                            $arRegistro->setDescripcion('INGRESO FLETE');
//                            $em->persist($arRegistro);
//                        } else {
//                            $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por flete";
//                            break;
//                        }
//
//                        //Cuenta del ingreso manejo
//                        if($arFactura['codigoCuentaIngresoManejoFk']) {
//                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaIngresoManejoFk']);
//                            if(!$arCuenta) {
//                                $error = "No se encuentra la cuenta del manejo " . $arFactura['codigoCuentaIngresoManejoFk'];
//                                break;
//                            }
//                            $arRegistro = new FinRegistro();
//                            $arRegistro->setTerceroRel($arTercero);
//                            $arRegistro->setCuentaRel($arCuenta);
//                            $arRegistro->setComprobanteRel($arComprobante);
//                            if($arCuenta->getExigeCentroCosto()) {
//                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
//                                $arRegistro->setCentroCostoRel($arCentroCosto);
//                            }
//                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
//                            $arRegistro->setNumero($arFactura['numero']);
//                            $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
//                            $arRegistro->setNumeroReferencia($numeroReferencia);
//                            $arRegistro->setFecha($arFactura['fecha']);
//                            if($arFactura['naturalezaCuentaIngreso'] == 'D') {
//                                $arRegistro->setVrDebito($arFactura['vrManejo']);
//                                $arRegistro->setNaturaleza('D');
//                            } else {
//                                $arRegistro->setVrCredito($arFactura['vrManejo']);
//                                $arRegistro->setNaturaleza('C');
//                            }
//                            $arRegistro->setDescripcion('INGRESO MANEJO');
//                            $em->persist($arRegistro);
//                        } else {
//                            $error = "El tipo de factura no tiene configurada la cuenta para el ingreso por manejo";
//                            break;
//                        }
//
//                        //Cuenta cliente
//                        if($arFactura['codigoCuentaClienteFk']) {
//                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arFactura['codigoCuentaClienteFk']);
//                            if(!$arCuenta) {
//                                $error = "No se encuentra la cuenta cliente " . $arFactura['codigoCuentaClienteFk'];
//                                break;
//                            }
//                            $arRegistro = new FinRegistro();
//                            $arRegistro->setTerceroRel($arTercero);
//                            $arRegistro->setCuentaRel($arCuenta);
//                            $arRegistro->setComprobanteRel($arComprobante);
//                            if($arCuenta->getExigeCentroCosto()) {
//                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arFactura['codigoCentroCostoFk']);
//                                $arRegistro->setCentroCostoRel($arCentroCosto);
//                            }
//                            $arRegistro->setNumeroPrefijo($arFactura['prefijo']);
//                            $arRegistro->setNumero($arFactura['numero']);
//                            $arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
//                            $arRegistro->setNumeroReferencia($numeroReferencia);
//                            $arRegistro->setFecha($arFactura['fecha']);
//                            if($arFactura['naturalezaCuentaCliente'] == 'D') {
//                                $arRegistro->setVrDebito($arFactura['vrTotal']);
//                                $arRegistro->setNaturaleza('D');
//                            } else {
//                                $arRegistro->setVrCredito($arFactura['vrTotal']);
//                                $arRegistro->setNaturaleza('C');
//                            }
//                            $arRegistro->setDescripcion('CLIENTES');
//                            $em->persist($arRegistro);
//                        } else {
//                            $error = "El tipo de factura no tiene configurada la cuenta cliente";
//                            break;
//                        }


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