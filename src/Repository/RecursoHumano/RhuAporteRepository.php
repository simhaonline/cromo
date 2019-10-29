<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteEntidad;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuAporteTipo;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracionProvision;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporte::class);
    }

    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $anio = null;
        $mes = null;

        if ($filtros) {
            $anio = $filtros['anio'] ?? null;
            $mes = $filtros['mes'] ?? null;
        }

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporte::class, 'a')
            ->select('a.codigoAportePk')
            ->addSelect('a.numero')
            ->addSelect('a.anio')
            ->addSelect('a.mes')
            ->addSelect('s.nombre as sucursalNombre')
            ->addSelect('a.cantidadContratos')
            ->addSelect('a.cantidadEmpleados')
            ->addSelect('a.formaPresentacion')
            ->addSelect('a.vrTotal')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoAnulado')
            ->addSelect('a.estadoContabilizado')
            ->leftJoin('a.sucursalRel', 's')
            ->orderBy('a.codigoAportePk', 'DESC');
        if ($anio) {
            $queryBuilder->andWhere("a.anio  = '{$anio}'");
        }
        if ($mes) {
            $queryBuilder->andWhere("a.mes  = '{$mes}'");
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function autorizar($arAporte)
    {
        $em = $this->getEntityManager();
        if(!$arAporte->getEstadoAutorizado()) {
            $em->getRepository(RhuAporteSoporte::class)->generar($arAporte);
            $em->getRepository(RhuAporteDetalle::class)->generar($arAporte);
            $arAporte->setEstadoAutorizado(1);
            $em->persist($arAporte);
            $em->flush();
        }
    }

    public function desAutorizar($arAporte)
    {
        $em = $this->getEntityManager();
        if ($arAporte->getEstadoAutorizado() == 1 && $arAporte->getEstadoAprobado() == 0) {
            $arAporte->setEstadoAutorizado(0);
            $em->persist($arAporte);
            $em->createQueryBuilder()->delete(RhuAporteDetalle::class,'ad')->andWhere("ad.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(RhuAporteSoporte::class,'aso')->andWhere("aso.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(RhuAporteEntidad::class,'ae')->andWhere("ae.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    /**
     * @param $arAporte RhuAporte
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arAporte): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if (!$arAporte->getEstadoAprobado() && $arAporte->getEstadoAutorizado()) {
            $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->findBy(['codigoAporteFk' => $arAporte->getCodigoAportePk()]);
            foreach ($arAporteContratos as $arAporteContrato) {
                $saludCotizacion = 0;
                $pensionCotizacion = 0;
                $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
                    ->select('SUM(ad.cotizacionSalud) as cotizacionSalud')
                    ->addSelect('SUM(ad.cotizacionPension) as cotizacionPension')
                    ->addSelect('SUM(ad.cotizacionCaja) as cotizacionCaja')
                    ->addSelect('SUM(ad.cotizacionRiesgos) as cotizacionRiesgos')
                    ->addSelect('SUM(ad.cotizacionSena) as cotizacionSena')
                    ->addSelect('SUM(ad.cotizacionIcbf) as cotizacionIcbf')
                    ->where("ad.codigoAporteContratoFk = {$arAporteContrato->getCodigoAporteContratoPk()}")
                    ->groupBy('ad.codigoAporteContratoFk');
                $arAporteDetalle = $queryBuilder->getQuery()->getSingleResult();
                if($arAporteDetalle) {
                    $arAporteContrato->setVrPensionCotizacion($arAporteDetalle['cotizacionPension']);
                    $arAporteContrato->setVrSaludCotizacion($arAporteDetalle['cotizacionSalud']);
                    $arAporteContrato->setVrCaja($arAporteDetalle['cotizacionCaja']);
                    $arAporteContrato->setVrRiesgos($arAporteDetalle['cotizacionRiesgos']);
                    $arAporteContrato->setVrSena($arAporteDetalle['cotizacionSena']);
                    $arAporteContrato->setVrIcbf($arAporteDetalle['cotizacionIcbf']);
                    $em->persist($arAporteContrato);
                    $saludCotizacion = $arAporteDetalle['cotizacionSalud'];
                    $pensionCotizacion = $arAporteDetalle['cotizacionPension'];
                }
                $saludEmpleado = $em->getRepository(RhuPagoDetalle::class)->descuentoSalud($arAporteContrato->getCodigoContratoFk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                $arAporteContrato->setVrSaludEmpleado($saludEmpleado);
                $pensionEmpleado = $em->getRepository(RhuPagoDetalle::class)->descuentoPension($arAporteContrato->getCodigoContratoFk(), $arAporte->getFechaDesde()->format('Y-m-d'), $arAporte->getFechaHasta()->format('Y-m-d'));
                $arAporteContrato->setVrPensionEmpleado($pensionEmpleado);

                $salud = $saludCotizacion - $saludEmpleado;
                if($salud >= 0) {
                    $arAporteContrato->setVrSalud($salud);
                } else {
                    $arAporteContrato->setVrSalud(0);
                }
                $pension = $pensionCotizacion - $pensionEmpleado;
                if($pension > 0) {
                    $arAporteContrato->setVrPension($pension);
                } else {
                    $arAporteContrato->setVrPension(0);
                }

            }
            if($arAporte->getNumero() == 0) {
                $arAporteTipo = $em->getRepository(RhuAporteTipo::class)->find($arAporte->getCodigoAporteTipoFk());
                $arAporte->setNumero($arAporteTipo->getConsecutivo());
                $arAporteTipo->setConsecutivo($arAporteTipo->getConsecutivo() + 1);
                $em->persist($arAporteTipo);
            }
            $arAporte->setEstadoAprobado(1);
            $em->persist($arAporte);
            $em->flush();
        } else {
            $respuesta = "El documento no puede estar previamente aprobado y debe estar autorizado";
        }

        return $respuesta;
    }

    public function anular($arAporte): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if($arAporte->getEstadoContabilizado() == 0) {
            if($arAporte->getEstadoAprobado() == 1) {
                if($arAporte->getEstadoAnulado() == 0) {
                    $arAporte->setEstadoAnulado(1);
                    $em->persist($arAporte);
                    $em->flush();
                } else {
                    Mensajes::error("La factura no puede estar previamente anulada");
                }
            } else {
                Mensajes::error("La factura debe estar aprobada");
            }
        } else {
            Mensajes::error("La factura ya esta contabilizada");
        }

        return $respuesta;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuAporte::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(RhuAporteDetalle::class)->findBy(['codigoAporteFk' => $arRegistro->getCodigoAportePk()])) <= 0) {
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

    public function liquidar($arAporte) {
        $em = $this->getEntityManager();
        //Salud
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadSaludFk')
            ->addSelect('SUM(ad.cotizacionSalud) as cotizacionSalud')
            ->groupBy('ad.codigoEntidadSaludFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('SALUD');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadSaludFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionSalud']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Pension
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadPensionFk')
            ->addSelect('SUM(ad.cotizacionPension) as cotizacionPension')
            ->groupBy('ad.codigoEntidadPensionFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('PENSION');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadPensionFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionPension']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Arl
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadRiesgoFk')
            ->addSelect('SUM(ad.cotizacionRiesgos) as cotizacionRiesgos')
            ->groupBy('ad.codigoEntidadRiesgoFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('RIESGO');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadRiesgoFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionRiesgos']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Caja
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadCajaFk')
            ->addSelect('SUM(ad.cotizacionCaja) as cotizacionCaja')
            ->groupBy('ad.codigoEntidadCajaFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('CAJA');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadCajaFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionCaja']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();
    }

    public function contabilizar($arr): bool
    {
        /**
         * @var $arVacacion RhuVacacion
         */
        $em = $this->getEntityManager();
        if ($arr) {
            $this->contabilizarCrearTercero($arr);
            $error = "";
            foreach ($arr AS $codigo) {
                /** @var $arAporte RhuAporte */
                $arAporte = $em->getRepository(RhuAporte::class)->find($codigo);
                if ($arAporte->getEstadoAprobado() == 1 && $arAporte->getEstadoContabilizado() == 0) {
                    $arComprobante = $em->getRepository(FinComprobante::class)->find($arAporte->getAporteTipoRel()->getCodigoComprobanteFk());
                    if ($arComprobante) {

                        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->findBy(['codigoAporteFk' => $codigo]);
                        /** @var $arAporteContrato RhuAporteContrato*/
                        foreach ($arAporteContratos as $arAporteContrato) {
                            $arContrato = $arAporteContrato->getContratoRel();
                            $arCentroCosto = $arContrato->getCentroCostoRel();
                            //Pension
                            if ($arAporteContrato->getVrPension() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadPensionFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'PEN', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrPension());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS PENSION');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrPension());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS PENSION CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para pension de esta clase de costo";
                                    break;
                                }
                            }

                            //Salud
                            if ($arAporteContrato->getVrSalud() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadSaludFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'SAL', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrSalud());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS SALUD');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrSalud());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS SALUD CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para salud de esta clase de costo";
                                    break;
                                }
                            }

                            //Caja
                            if ($arAporteContrato->getVrCaja() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadCajaFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'CAJ', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrCaja());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS CAJA');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrCaja());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS CAJA CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para caja de esta clase de costo";
                                    break;
                                }
                            }

                            //Riesgos
                            if ($arAporteContrato->getVrRiesgos() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadRiesgosFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'RIE', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrRiesgos());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS RIESGOS');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrRiesgos());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS RIESGOS CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para pension de esta clase de costo";
                                    break;
                                }
                            }

                            //Sena
                            if ($arAporteContrato->getVrSena() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadPensionFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'PEN', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrSena());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS SENA');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrSena());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS SENA CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para sena de esta clase de costo";
                                    break;
                                }
                            }

                            //Icbf
                            if ($arAporteContrato->getVrIcbf() > 0) {
                                $arTercero = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteContrato->getCodigoEntidadPensionFk());
                                $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'ICB', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                if ($arConfiguracionProvision) {
                                    $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                    $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                    if ($arCuentaDebito && $arCuentaCredito) {
                                        //Debito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaDebito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrDebito($arAporteContrato->getVrIcbf());
                                        $arRegistro->setNaturaleza("D");
                                        $arRegistro->setDescripcion('SS ICBF');
                                        if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                        //Credito
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setCuentaRel($arCuentaCredito);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arAporte->getNumero());
                                        $arRegistro->setNumeroReferencia($arAporte->getNumero());
                                        $arRegistro->setFecha($arAporte->getFechaDesde());
                                        $arRegistro->setVrCredito($arAporteContrato->getVrIcbf());
                                        $arRegistro->setNaturaleza("C");
                                        $arRegistro->setDescripcion('SS ICBF CXP');
                                        if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                        }
                                        $arRegistro->setCodigoModeloFk('RhuAporte');
                                        $arRegistro->setCodigoDocumento($arAporte->getCodigoAportePk());
                                        $em->persist($arRegistro);

                                    } else {
                                        $error = "La cuenta debito o credito no existe";
                                        break;
                                    }
                                } else {
                                    $error = "No esta configurada la cuenta para icbf de esta clase de costo";
                                    break;
                                }
                            }
                        }


                        $arAporte->setEstadoContabilizado(1);
                        $em->persist($arAporte);
                    } else {
                        $error = "El comprobante " . $arAporte->getAporteTipoRel()->getCodigoComprobanteFk() . " no existe";
                        break;
                    }
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

    public function contabilizarCrearTercero($arr)
    {
        $em = $this->getEntityManager();
        foreach ($arr AS $codigo) {
            $arAporteEntidades = $em->getRepository(RhuAporteEntidad::class)->findBy(['codigoAporteFk' => $codigo]);
            foreach ($arAporteEntidades as $arAporteEntidad) {
                $em->getRepository(RhuEntidad::class)->terceroFinanciero($arAporteEntidad->getCodigoEntidadFk());
            }
        }
    }

    public function ibcMesAnterior($anio, $mes, $codigoEmpleado)
    {
        if ($mes == 1) {
            $anio -= 1;
            $mes = 12;
        } else {
            $mes -= 1;
        }
        $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.ibcSalud) as ibcSalud, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporte ssoa "
            . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
            . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $resultados = $arrayResultado[0];
        if ($resultados['ibcPension'] == null) {
            if ($mes == 1) {
                $anio -= 1;
                $mes = 12;
            } else {
                $mes -= 1;
            }
            $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
            $em = $this->getEntityManager();
            $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.ibcSalud) as ibcSalud,SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporte ssoa "
                . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
                . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();
            $resultados = $arrayResultado[0];
            if ($resultados['ibcPension'] == null) {
                $arrResultado['ibc'] = 0;
                $arrResultado['dias'] = 0;
                $arrResultado['respuesta'] = false;
            } else {
                $arrResultado['ibc'] = $resultados['ibcPension'];
                $arrResultado['dias'] = $resultados['diasPension'];
                if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                    $arrResultado['ibc'] = $resultados['ibcSalud'];
                    $arrResultado['dias'] = $resultados['diasSalud'];
                }
                $arrResultado['respuesta'] = true;
            }
        } else {
            $arrResultado['ibc'] = $resultados['ibcPension'];
            $arrResultado['dias'] = $resultados['diasPension'];
            if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                $arrResultado['ibc'] = $resultados['ibcSalud'];
                $arrResultado['dias'] = $resultados['diasSalud'];
            }
            $arrResultado['respuesta'] = true;
        }
        return $arrResultado;
    }


}