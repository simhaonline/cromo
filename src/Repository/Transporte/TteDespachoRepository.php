<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\Entity\Compra\ComProveedor;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteCosto;
use App\Utilidades\Mensajes;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TtePoseedor;
use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespacho::class);
    }

    /**
     * @return string
     */
    public function listaDql(): string
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('td.codigoDespachoPk')
            ->addSelect('d.numero')
            ->addSelect('d.codigoOperacionFk')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('d.codigoRutaFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.unidades')
            ->addSelect('d.pesoReal')
            ->addSelect('d.pesoVolumen')
            ->addSelect('d.vrFlete')
            ->addSelect('d.vrManejo')
            ->addSelect('d.vrDeclara')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('d.estadoAnulado')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel', 'cd')
            ->leftJoin('d.conductorRel', 'c')
            ->orderBy('d.codigoDespachoPk', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'td')
            ->select('td.codigoDespachoPk')
            ->addSelect('td.fechaSalida')
            ->addSelect('td.numero')
            ->addSelect('td.codigoOperacionFk')
            ->addSelect('td.codigoVehiculoFk')
            ->addSelect('td.codigoRutaFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('td.cantidad')
            ->addSelect('td.unidades')
            ->addSelect('td.pesoReal')
            ->addSelect('td.pesoVolumen')
            ->addSelect('td.vrFlete')
            ->addSelect('td.vrManejo')
            ->addSelect('td.vrDeclara')
            ->addSelect('td.vrFletePago')
            ->addSelect('td.vrAnticipo')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('td.estadoAprobado')
            ->addSelect('td.estadoAutorizado')
            ->addSelect('td.estadoAnulado')
            ->addSelect('dt.nombre AS despachoTipo')
            ->addSelect('td.usuario')
            ->leftJoin('td.despachoTipoRel', 'dt')
            ->leftJoin('td.ciudadOrigenRel', 'co')
            ->leftJoin('td.ciudadDestinoRel ', 'cd')
            ->leftJoin('td.conductorRel', 'c')
            ->where('td.codigoDespachoPk <> 0');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteMovDespachoFiltroFecha') == true) {
            if ($session->get('filtroTteMovDespachoFechaDesde') != null) {
                $queryBuilder->andWhere("td.fechaSalida >= '{$session->get('filtroTteMovDespachoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("td.fechaSalida >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteMovDespachoFechaHasta') != null) {
                $queryBuilder->andWhere("td.fechaSalida <= '{$session->get('filtroTteMovDespachoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("td.fechaSalida <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        if ($session->get('filtroTteDespachoCodigoVehiculo') != '') {
            $queryBuilder->andWhere("td.codigoVehiculoFk = '{$session->get('filtroTteDespachoCodigoVehiculo')}'");
        }
        if ($session->get('filtroTteDespachoCodigo') != '') {
            $queryBuilder->andWhere("td.codigoDespachoPk = {$session->get('filtroTteDespachoCodigo')}");
        }
        if ($session->get('filtroTteDespachoNumero') != '') {
            $queryBuilder->andWhere("td.numero = {$session->get('filtroTteDespachoNumero')}");
        }
        if ($session->get('filtroTteDespachoCodigoCiudadOrigen')) {
            $queryBuilder->andWhere("td.codigoCiudadOrigenFk = {$session->get('filtroTteDespachoCodigoCiudadOrigen')}");
        }
        if ($session->get('filtroTteDespachoCodigoCiudadDestino')) {
            $queryBuilder->andWhere("td.codigoCiudadDestinoFk = {$session->get('filtroTteDespachoCodigoCiudadDestino')}");
        }
        if ($session->get('filtroTteDespachoTipo')) {
            $queryBuilder->andWhere("td.codigoDespachoTipoFk = '" . $session->get('filtroTteDespachoTipo') . "'");
        }
        if ($session->get('filtroTteDespachoOperacion')) {
            $queryBuilder->andWhere("td.codigoOperacionFk = '" . $session->get('filtroTteDespachoOperacion') . "'");
        }
        if ($session->get('filtroTteDespachoCodigoConductor')) {
            $queryBuilder->andWhere("td.codigoConductorFk = {$session->get('filtroTteDespachoCodigoConductor')}");
        }
        switch ($session->get('filtroTteDespachoEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("td.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("td.estadoAprobado = 1");
                break;
        }
        $queryBuilder->orderBy('td.fechaSalida', 'DESC');
        return $queryBuilder;

    }

    /**
     * @param $arDespacho TteDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arDespacho)
    {
        $em = $this->getEntityManager();
        if($arDespacho->getEstadoAutorizado() == 0) {
            if ($this->contarDetalles($arDespacho->getCodigoDespachoPk()) > 0) {
                $costoBaseTotal = 0;
                $arDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->findBy(array('codigoDespachoFk' => $arDespacho->getCodigoDespachoPk()));
                foreach ($arDespachoDetalles as $arDespachoDetalle) {
                    $arCosto = $em->getRepository(TteCosto::class)->findOneBy(array('codigoCiudadOrigenFk' => $arDespachoDetalle->getGuiaRel()->getCodigoCiudadOrigenFk(), 'codigoCiudadDestinoFk' => $arDespachoDetalle->getGuiaRel()->getCodigoCiudadDestinoFk() ));
                    if($arCosto) {
                        $costo = $arDespachoDetalle->getPesoCosto() * $arCosto->getVrPeso();
                        $costoBaseTotal += $costo;
                        $arDespachoDetalle->setVrCostoBase($costo);
                    }
                }
                $arDespacho->setVrCostoBase($costoBaseTotal);
                $arDespacho->setEstadoAutorizado(1);
                $em->persist($arDespacho);
                $em->flush();
            } else {
                Mensajes::error('No se puede autorizar, el registro no tiene detalles');
            }
        } else {
            Mensajes::error('El despacho ya esta autorizado');
        }
    }

    /**
     * @param $arDespacho TteDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function costos($arDespacho)
    {
        $em = $this->getEntityManager();
        $costoBaseTotal = 0;
        $arDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->findBy(array('codigoDespachoFk' => $arDespacho->getCodigoDespachoPk()));
        foreach ($arDespachoDetalles as $arDespachoDetalle) {
            $arCosto = $em->getRepository(TteCosto::class)->findOneBy(array('codigoCiudadOrigenFk' => $arDespachoDetalle->getGuiaRel()->getCodigoCiudadOrigenFk(), 'codigoCiudadDestinoFk' => $arDespachoDetalle->getGuiaRel()->getCodigoCiudadDestinoFk() ));
            if($arCosto) {
                $costo = $arDespachoDetalle->getPesoCosto() * $arCosto->getVrPeso();
                $costoBaseTotal += $costo;
                $arDespachoDetalle->setVrCostoBase($costo);
                $em->persist($arDespachoDetalle);
            }
        }
        $costoTotal = $arDespacho->getVrFletePago();
        foreach ($arDespachoDetalles as $arDespachoDetalle) {
            $participacion =  ($arDespachoDetalle->getVrCostoBase() / $costoBaseTotal) * 100;
            $costoParticipacionTotal = ($participacion * $costoTotal) / 100;
            $costoParticipacion = $costoParticipacionTotal - $arDespachoDetalle->getVrCostoBase();
            $costo = $arDespachoDetalle->getVrCostoBase() + $costoParticipacion;
            $arDespachoDetalle->setVrCostoParticipacion($costoParticipacion);
            $arDespachoDetalle->setPorcentajeParticipacionCosto($participacion);
            $arDespachoDetalle->setVrCosto($costo);
            $em->persist($arDespachoDetalle);
        }
        $arDespacho->setVrCostoBase($costoBaseTotal);
        $arDespacho->setEstadoAutorizado(1);
        $em->persist($arDespacho);
        $em->flush();
    }

    /**
     * @param $arDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arDespacho)
    {
        if ($arDespacho->getEstadoAutorizado() == 1 && $arDespacho->getEstadoAprobado() == 0) {
            $arDespacho->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arDespacho);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    public function liquidar($codigoDespacho): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(dd.codigoGuiaFk) as cantidad, SUM(dd.unidades+0) as unidades, SUM(dd.pesoReal+0) as pesoReal, SUM(dd.pesoVolumen+0) as pesoVolumen, SUM(dd.pesoCosto+0) as pesoCosto,
                  SUM(dd.vrFlete) as vrFlete, SUM(dd.vrManejo) as vrManejo, SUM(dd.vrCobroEntrega) as vrCobroEntrega, SUM(dd.vrDeclara) as vrDeclara
        FROM App\Entity\Transporte\TteDespachoDetalle dd
        WHERE dd.codigoDespachoFk = :codigoDespacho')
            ->setParameter('codigoDespacho', $codigoDespacho);
        $arrGuias = $query->getSingleResult();
        $total = intval($arrGuias['vrFlete']) + intval($arrGuias['vrManejo']);
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        $margen = 0;
        if ($total > 0) {
            $margen = ($arDespacho->getVrFletepago() / $total) * 100;
        }

        $arDespacho->setUnidades(intval($arrGuias['unidades']));
        $arDespacho->setPesoReal(intval($arrGuias['pesoReal']));
        $arDespacho->setPesoVolumen(intval($arrGuias['pesoVolumen']));
        $arDespacho->setPesoCosto(intval($arrGuias['pesoCosto']));
        $arDespacho->setCantidad(intval($arrGuias['cantidad']));
        $arDespacho->setVrFlete(intval($arrGuias['vrFlete']));
        $arDespacho->setVrManejo(intval($arrGuias['vrManejo']));
        $arDespacho->setVrCobroEntrega(intval($arrGuias['vrCobroEntrega']));
        $arDespacho->setVrDeclara(intval($arrGuias['vrDeclara']));
        $arDespacho->setPorcentajeRentabilidad($margen);
        //Totales
        $arrConfiguracionLiquidarDespacho = $em->getRepository(TteConfiguracion::class)->liquidarDespacho();
        $descuentos = $arDespacho->getVrDescuentoPapeleria() + $arDespacho->getVrDescuentoSeguridad() + $arDespacho->getVrDescuentoCargue() + $arDespacho->getVrDescuentoEstampilla();
        $retencionFuente = 0;
        if ($arDespacho->getVrFletePago() > $arrConfiguracionLiquidarDespacho['vrBaseRetencionFuente']) {
            $retencionFuente = $arDespacho->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeRetencionFuente'] / 100;
        }
        $industriaComercio = $arDespacho->getVrFletePago() * $arrConfiguracionLiquidarDespacho['porcentajeIndustriaComercio'] / 100;

        $total = $arDespacho->getVrFletePago() - ($arDespacho->getVrAnticipo() + $retencionFuente + $industriaComercio);
        $saldo = ($total + $arDespacho->getVrCobroEntregaRechazado()) - ($descuentos + $arDespacho->getVrCobroEntrega());
        $totalNeto = $arDespacho->getVrFletePago() - ($arDespacho->getVrAnticipo() + $retencionFuente + $industriaComercio + $descuentos);
        $arDespacho->setVrIndustriaComercio($industriaComercio);
        $arDespacho->setVrRetencionFuente($retencionFuente);
        $arDespacho->setVrTotal($total);
        $arDespacho->setVrSaldo($saldo);
        $arDespacho->setVrTotalNeto($totalNeto);

        $em->persist($arDespacho);
        $em->flush();
        return true;
    }

    public function aprobar($codigoDespacho): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if (!$arDespacho->getEstadoAprobado()) {
            if ($arDespacho->getCantidad() > 0) {
                $fechaActual = new \DateTime('now');
                $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoDespachado = 1, g.fechaDespacho=:fecha 
                      WHERE g.codigoDespachoFk = :codigoDespacho')
                    ->setParameter('codigoDespacho', $codigoDespacho)
                    ->setParameter('fecha', $fechaActual->format('Y-m-d H:i'));
                $query->execute();
                $arDespacho->setFechaSalida($fechaActual);
                $arDespacho->setEstadoAprobado(1);
                $arDespachoTipo = $em->getRepository(TteDespachoTipo::class)->find($arDespacho->getCodigoDespachoTipoFk());
                if ($arDespacho->getNumero() == 0 || $arDespacho->getNumero() == NULL) {
                    $arDespacho->setNumero($arDespachoTipo->getConsecutivo());
                    $arDespachoTipo->setConsecutivo($arDespachoTipo->getConsecutivo() + 1);
                    $em->persist($arDespachoTipo);
                }

                //Costos
                /*$query = $em->createQuery(
                    'SELECT dd.codigoDespachoDetallePk,
                  dd.unidades,
                  dd.pesoReal,
                  dd.pesoVolumen      
                FROM App\Entity\Transporte\TteDespachoDetalle dd
                WHERE dd.codigoDespachoFk = :despacho  
                ORDER BY dd.codigoDespachoFk DESC '
                )->setParameter('despacho', $codigoDespacho);
                $arDespachoDetalles = $query->execute();
                foreach ($arDespachoDetalles as $arDespachoDetalle) {
                    $arDespachoDetalleActualizar = $em->getRepository(TteDespachoDetalle::class)->find($arDespachoDetalle['codigoDespachoDetallePk']);
                    $costoUnidadTotal = $arDespacho->getVrFletePago() / $arDespacho->getUnidades();
                    $costoPesoTotal = $arDespacho->getVrFletePago() / $arDespacho->getPesoReal();
                    $costoVolumenTotal = $arDespacho->getVrFletePago() / $arDespacho->getPesoVolumen();
                    $costoUnidad = $costoUnidadTotal * $arDespachoDetalle['unidades'];
                    $costoPeso = $costoPesoTotal * $arDespachoDetalle['pesoReal'];
                    $costoVolumen = $costoVolumenTotal * $arDespachoDetalle['pesoVolumen'];
                    $costo = ($costoPeso + $costoVolumen + $costoUnidad) / 3;
                    $arDespachoDetalleActualizar->setVrCostoUnidad($costoUnidad);
                    $arDespachoDetalleActualizar->setVrCostoPeso($costoPeso);
                    $arDespachoDetalleActualizar->setVrCostoVolumen($costoVolumen);
                    $arDespachoDetalleActualizar->setVrCosto($costo);
                    $em->persist($arDespachoDetalleActualizar);
                }*/

                //Generar monitoreo
                if ($arDespachoTipo->getGeneraMonitoreo()) {
                    $arMonitoreo = new TteMonitoreo();
                    $arMonitoreo->setVehiculoRel($arDespacho->getVehiculoRel());
                    $arMonitoreo->setDespachoRel($arDespacho);
                    $arMonitoreo->setFechaRegistro(new \DateTime('now'));
                    $arMonitoreo->setFechaInicio(new \DateTime('now'));
                    $arMonitoreo->setFechaFin(new \DateTime('now'));
                    $arMonitoreo->setEstadoAutorizado(1);
                    $em->persist($arMonitoreo);
                }
                $em->persist($arDespacho);

                //Generar cuenta por pagar
                if ($arDespacho->getDespachoTipoRel()->getGeneraCuentaPagar()) {
                    $arPoseedor = $arDespacho->getVehiculoRel()->getPoseedorRel();
                    $arProveedor = $em->getRepository(ComProveedor::class)->findOneBy(['codigoIdentificacionFk' => $arPoseedor->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arPoseedor->getNumeroIdentificacion()]);
                    if (!$arProveedor) {
                        $arProveedor = new ComProveedor();
                        //$arProveedor->setFormaPagoRel($arFactura->getClienteRel()->getFormaPagoRel());
                        $arProveedor->setIdentificacionRel($arPoseedor->getIdentificacionRel());
                        $arProveedor->setNumeroIdentificacion($arPoseedor->getNumeroIdentificacion());
                        $arProveedor->setDigitoVerificacion($arPoseedor->getDigitoVerificacion());
                        $arProveedor->setNombreCorto($arPoseedor->getNombreCorto());
                        //$arProveedor->setPlazoPago($arPoseedor->getPlazoPago());
                        $arProveedor->setDireccion($arPoseedor->getDireccion());
                        $arProveedor->setTelefono($arPoseedor->getTelefono());
                        $arProveedor->setEmail($arPoseedor->getCorreo());
                        $em->persist($arProveedor);
                    }


                    $arCuentaPagarTipo = $em->getRepository(ComCuentaPagarTipo::class)->find($arDespacho->getDespachoTipoRel()->getCodigoCuentaPagarTipoFk());
                    $arCuentaPagar = new ComCuentaPagar();
                    $arCuentaPagar->setProveedorRel($arProveedor);
                    $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
                    $arCuentaPagar->setFechaFactura($arDespacho->getFechaSalida());
                    $arCuentaPagar->setFechaVence($arDespacho->getFechaSalida());
                    $arCuentaPagar->setModulo("TTE");
                    $arCuentaPagar->setCodigoDocumento($arDespacho->getCodigoDespachoPk());
                    $arCuentaPagar->setNumeroDocumento($arDespacho->getNumero());
                    $arCuentaPagar->setVrTotal($arDespacho->getVrTotalNeto());
                    $arCuentaPagar->setVrSaldo($arDespacho->getVrTotalNeto());
                    $arCuentaPagar->setVrSaldoOperado($arDespacho->getVrTotalNeto() * $arCuentaPagarTipo->getOperacion());
                    $arCuentaPagar->setPlazo(0);
                    $arCuentaPagar->setOperacion($arCuentaPagarTipo->getOperacion());
                    $em->persist($arCuentaPagar);
                }

                $em->flush();
            } else {
                $respuesta = "El despacho debe tener guias asignadas";
            }
        } else {
            $respuesta = "El despacho debe estar generado";
        }

        return $respuesta;
    }

    public function cerrar($codigoDespacho): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        //Actualizar los costos de transporte
//        $query = $em->createQuery(
//            'SELECT dd.codigoDespachoDetallePk,
//                  dd.unidades,
//                  dd.pesoReal,
//                  dd.pesoVolumen
//                FROM App\Entity\Transporte\TteDespachoDetalle dd
//                WHERE dd.codigoDespachoFk = :despacho
//                ORDER BY dd.codigoDespachoFk DESC '
//        )->setParameter('despacho', $codigoDespacho);
//        $arDespachoDetalles = $query->execute();
//        foreach ($arDespachoDetalles as $arDespachoDetalle) {
//            $arDespachoDetalleActualizar = $em->getRepository(TteDespachoDetalle::class)->find($arDespachoDetalle['codigoDespachoDetallePk']);
//            $costoUnidadTotal = $arDespacho->getVrFletePago() / $arDespacho->getUnidades();
//            $costoPesoTotal = $arDespacho->getVrFletePago() / $arDespacho->getPesoReal();
//            $costoVolumenTotal = $arDespacho->getVrFletePago() / $arDespacho->getPesoVolumen();
//            $costoUnidad = $costoUnidadTotal * $arDespachoDetalle['unidades'];
//            $costoPeso = $costoPesoTotal * $arDespachoDetalle['pesoReal'];
//            $costoVolumen = $costoVolumenTotal * $arDespachoDetalle['pesoVolumen'];
//            $costo = ($costoPeso + $costoVolumen + $costoUnidad) / 3;
//            $arDespachoDetalleActualizar->setVrCostoUnidad($costoUnidad);
//            $arDespachoDetalleActualizar->setVrCostoPeso($costoPeso);
//            $arDespachoDetalleActualizar->setVrCostoVolumen($costoVolumen);
//            $arDespachoDetalleActualizar->setVrCosto($costo);
//            $em->persist($arDespachoDetalleActualizar);
//        }
        $arDespacho->setEstadoCerrado(1);
        $em->flush();
        return $respuesta;
    }

    /**
     * @param $arDespacho TteDespacho
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arDespacho): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if ($arDespacho->getEstadoAnulado() == 0 && $arDespacho->getEstadoAprobado() == 1 && $arDespacho->getEstadoContabilizado() == 0 ) {
            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoDespachado = 0, 
                  g.estadoEmbarcado = 0, g.codigoDespachoFk = NULL
                  WHERE g.codigoDespachoFk = :codigoDespacho')
                ->setParameter('codigoDespacho', $arDespacho->getCodigoDespachoPk());
            $query->execute();

            $arDespacho->setVrFletePago(0);
            $arDespacho->setVrAnticipo(0);
            $arDespacho->setVrIndustriaComercio(0);
            $arDespacho->setVrRetencionFuente(0);
            $arDespacho->setVrTotal(0);
            $arDespacho->setVrTotalNeto(0);
            $arDespacho->setVrDescuentoCargue(0);
            $arDespacho->setVrDescuentoEstampilla(0);
            $arDespacho->setVrDescuentoPapeleria(0);
            $arDespacho->setVrDescuentoSeguridad(0);
            $arDespacho->setVrCobroEntrega(0);
            $arDespacho->setVrCobroEntregaRechazado(0);
            $arDespacho->setVrSaldo(0);
            $arDespacho->setEstadoAnulado(1);
            $arDespacho->setUnidades(0);
            $arDespacho->setCantidad(0);
            $arDespacho->setPesoVolumen(0);
            $arDespacho->setPesoCosto(0);
            $arDespacho->setPesoReal(0);
            $arDespacho->setVrFlete(0);
            $arDespacho->setVrManejo(0);
            $arDespacho->setVrDeclara(0);
            $em->persist($arDespacho);
            $em->flush();
        } else {
            $respuesta = "El despacho ya esta anulado";
        }

        return $respuesta;
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
                $arRegistro = $this->getEntityManager()->getRepository(TteDespacho::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteDespachoDetalle::class)->findBy(['codigoDespachoFk' => $arRegistro->getCodigoDespachoPk()])) <= 0) {
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

    public function retirarDetalle($arrDetalles): bool
    {
        $em = $this->getEntityManager();
        if ($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arDespachoDetalle = $em->getRepository(TteDespachoDetalle::class)->find($codigo);
                    $arGuia = $em->getRepository(TteGuia::class)->find($arDespachoDetalle->getCodigoGuiaFk());
                    $arGuia->setDespachoRel(null);
                    $arGuia->setEstadoEmbarcado(0);
                    $em->persist($arGuia);
                    $em->remove($arDespachoDetalle);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function dqlImprimirManifiesto($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk, 
        d.numero,
        d.fechaSalida, 
        d.codigoOperacionFk,
        d.codigoVehiculoFk,
        d.codigoRutaFk, 
        co.nombre AS ciudadOrigen, 
        cd.nombre AS ciudadDestino,
        d.unidades,
        d.pesoReal,
        d.pesoVolumen,
        d.cantidad,
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara,
        d.vrFletePago,
        d.vrRetencionFuente,
        d.vrIndustriaComercio,
        d.vrAnticipo,
        d.vrCobroEntrega,
        d.vrDescuentoCargue,
        d.vrSaldo,
        d.vrDescuentoPapeleria,
        d.comentario,
        c.numeroIdentificacion AS conductorIdentificacion,
        c.nombreCorto AS conductorNombre,
        c.direccion AS conductorDireccion,
        c.telefono AS conductorTelefono,
        c.numeroLicencia AS conductorNumeroLicencia,
        cc.nombre AS conductorCiudad,
        d.estadoAnulado,
        m.nombre as vehiculoMarca,
        v.placaRemolque as vehiculoPlacaRemolque,
        v.configuracion as vehiculoConfiguracion,
        v.pesoVacio as vehiculoPesoVacio, 
        v.numeroPoliza as vehiculoNumeroPoliza,
        v.fechaVencePoliza as vehiculoFechaVencePoliza,
        a.nombre as aseguradoraNombre,
        p.nombreCorto as poseedorNombre,
        p.numeroIdentificacion as poseedorNumeroIdentificacion,
        p.direccion as poseedorDireccion,
        p.telefono as poseedorTelefono,
        pc.nombre AS poseedorCiudad
        FROM App\Entity\Transporte\TteDespacho d         
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd
        LEFT JOIN d.conductorRel c
        LEFT JOIN c.ciudadRel cc
        LEFT JOIN d.vehiculoRel v
        LEFT JOIN v.marcaRel m
        LEFT JOIN v.aseguradoraRel a
        LEFT JOIN v.poseedorRel p
        LEFT JOIN p.ciudadRel pc
        WHERE d.codigoDespachoPk = :codigoDespacho
        ORDER BY d.codigoDespachoPk DESC'
        )->setParameter('codigoDespacho', $codigoDespacho);
        $arDespacho = $query->getSingleResult();
        return $arDespacho;

    }

    public function dqlRndc($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk,
        d.fechaSalida,
        d.codigoConductorFk,
        v.codigoPoseedorFk,
        v.codigoPropietarioFk,
        d.codigoVehiculoFk,
        d.pesoReal,
        d.numero,
        d.vrFletePago,
        d.vrAnticipo,
        d.vrRetencionFuente,
        d.codigoCiudadOrigenFk,
        d.codigoCiudadDestinoFk,
        co.codigoInterface as codigoCiudadOrigen,
        cd.codigoInterface as codigoCiudadDestino
        FROM App\Entity\Transporte\TteDespacho d          
        LEFT JOIN d.vehiculoRel v 
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd          
        WHERE d.codigoDespachoPk = :codigoDespacho
        ORDER BY d.codigoDespachoPk DESC '
        )->setParameter('codigoDespacho', $codigoDespacho);
        $arDespacho = $query->getSingleResult();
        return $arDespacho;

    }

    public function reportarRndc($arDespacho): string
    {
        $em = $this->getEntityManager();
        if ($arDespacho->getNumeroRndc() == "") {
            if ($arDespacho->getEstadoAprobado() == 1 && $arDespacho->getEstadoAnulado() == 0) {
                try {
                    $opciones = ['http' => ['user_agent' => 'PHPSoapClient']];
                    $contexto = stream_context_create($opciones);

                    $wsdlUrl = 'http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices';
                    $soapOpciones = [
                        'stream_context' => $contexto,
                        'cache_wsdl' => WSDL_CACHE_NONE
                    ];
                    libxml_disable_entity_loader(false);
                    $cliente = new SoapClient($wsdlUrl, $soapOpciones);
//                    $cliente = new \SoapClient("http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices");
                    $arConfiguracionTransporte = $em->getRepository(TteConfiguracion::class)->find(1);
                    $arrDespacho = $em->getRepository(TteDespacho::class)->dqlRndc($arDespacho->getCodigoDespachoPk());
                    $retorno = $this->reportarRndcTerceros($cliente, $arConfiguracionTransporte, $arrDespacho);
                    if ($retorno) {
                        $retorno = $this->reportarRndcVehiculo($cliente, $arConfiguracionTransporte, $arrDespacho);
                        if ($retorno) {
                            $retorno = $this->reportarRndcGuia($cliente, $arConfiguracionTransporte, $arrDespacho);
                            if ($retorno) {
                                $this->reportarRndcManifiesto($cliente, $arConfiguracionTransporte, $arrDespacho, $arDespacho);
                            }
                        }
                    }
                } catch (Exception $e) {
                    return "Error al conectar el control: " . $e;
                }
            } else {
                Mensajes::error("El despacho debe estar aprobado y sin anular");
            }
        } else {
            Mensajes::error("El viaje ya fue reportado al rndc");
        }
        return true;
    }

    public function reportarRndcTerceros($cliente, $arConfiguracionTransporte, $arrDespacho): string
    {
        $em = $this->getEntityManager();
        $retorno = true;
        $arrTerceros = array();

        $arrTercerosPoseedores = $em->getRepository(TtePoseedor::class)->dqlRndc($arrDespacho['codigoPoseedorFk'], $arrDespacho['codigoPropietarioFk']);
        foreach ($arrTercerosPoseedores as $arTerceroPoseedor) {
            $arrTerceros[] = array('identificacionTipo' => $arTerceroPoseedor['tipoIdentificacion'],
                'identificacion' => $arTerceroPoseedor['numeroIdentificacion'],
                'nombre1' => utf8_decode($arTerceroPoseedor['nombre1']),
                'apellido1' => utf8_decode($arTerceroPoseedor['apellido1']),
                'apellido2' => utf8_decode($arTerceroPoseedor['apellido2']),
                'telefono' => utf8_decode($arTerceroPoseedor['telefono']),
                'movil' => $arTerceroPoseedor['movil'],
                'direccion' => utf8_decode($arTerceroPoseedor['direccion']),
                'codigoCiudad' => $arTerceroPoseedor['codigoCiudad'],
                'conductor' => 0,
                'codigoSede' => 1,
                'nombreSede' => "PRINCIPAL");
        }

        $arrConductor = $em->getRepository(TteConductor::class)->dqlRndc($arrDespacho['codigoConductorFk']);
        $arrTerceros[] = array('identificacionTipo' => $arrConductor['tipoIdentificacion'],
            'identificacion' => $arrConductor['numeroIdentificacion'],
            'nombre1' => utf8_decode($arrConductor['nombre1']),
            'apellido1' => utf8_decode($arrConductor['apellido1']),
            'apellido2' => utf8_decode($arrConductor['apellido2']),
            'telefono' => utf8_decode($arrConductor['telefono']),
            'movil' => $arrConductor['movil'],
            'direccion' => utf8_decode($arrConductor['direccion']),
            'codigoCiudad' => $arrConductor['codigoCiudad'],
            'conductor' => 1,
            'fechaVenceLicencia' => $arrConductor['fechaVenceLicencia'],
            'numeroLicencia' => $arrConductor['numeroLicencia'],
            'categoriaLicencia' => $arrConductor['categoriaLicencia'],
            'codigoSede' => 1,
            'nombreSede' => "PRINCIPAL");
        //Remitente
        $arrTerceros[] = array('identificacionTipo' => "C",
            'identificacion' => "222222222",
            'nombre1' => "VARIOS REMITENTE",
            'apellido1' => "VARIOS",
            'apellido2' => "VARIOS",
            'direccion' => "CALLE PRINCIPAL",
            'telefono' => "",
            'movil' => "",
            'conductor' => 0,
            'codigoCiudad' => $arrDespacho['codigoCiudadOrigen'],
            'codigoSede' => $arrDespacho['codigoCiudadOrigenFk'],
            'nombreSede' => $arrDespacho['codigoCiudadOrigen'] . "PRINCIPAL");

        //Destinatario
        $arrTerceros[] = array('identificacionTipo' => "C",
            'identificacion' => "333333333",
            'nombre1' => "VARIOS DESTINATARIO",
            'apellido1' => "VARIOS",
            'apellido2' => "VARIOS",
            'direccion' => "CALLE PRINCIPAL",
            'telefono' => "",
            'movil' => "",
            'conductor' => 0,
            'codigoCiudad' => $arrDespacho['codigoCiudadDestino'],
            'codigoSede' => $arrDespacho['codigoCiudadDestinoFk'],
            'nombreSede' => $arrDespacho['codigoCiudadDestino'] . "PRINCIPAL");

        foreach ($arrTerceros as $arrTercero) {
            $strPoseedorXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                            <root>
                                <acceso>
                                    <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                    <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                                </acceso>
                                <solicitud>
                                    <tipo>1</tipo>
                                    <procesoid>11</procesoid>
                                </solicitud>
                                <variables>
                                    <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                    <CODTIPOIDTERCERO>" . $arrTercero['identificacionTipo'] . "</CODTIPOIDTERCERO>
                                    <NUMIDTERCERO>" . $arrTercero['identificacion'] . "</NUMIDTERCERO>
                                    <NOMIDTERCERO>" . utf8_decode($arrTercero['nombre1']) . "</NOMIDTERCERO>";
            if ($arrTercero['identificacionTipo'] == "C") {
                $strPoseedorXML .= "<PRIMERAPELLIDOIDTERCERO>" . utf8_decode($arrTercero['apellido1']) . "</PRIMERAPELLIDOIDTERCERO>
                                                            <SEGUNDOAPELLIDOIDTERCERO>" . utf8_decode($arrTercero['apellido2']) . "</SEGUNDOAPELLIDOIDTERCERO>";
            }
            $strPoseedorXML .= "<CODSEDETERCERO>" . $arrTercero['codigoSede'] . "</CODSEDETERCERO>";
            $strPoseedorXML .= "<NOMSEDETERCERO>" . $arrTercero['nombreSede'] . "</NOMSEDETERCERO>";
            if ($arrTercero['telefono'] != "") {
                $strPoseedorXML .= "<NUMTELEFONOCONTACTO>" . $arrTercero['telefono'] . "</NUMTELEFONOCONTACTO>";
            }
            if ($arrTercero['movil'] != "" && $arrTercero['identificacionTipo'] == "C") {
                $strPoseedorXML .= "<NUMCELULARPERSONA>" . $arrTercero['movil'] . "</NUMCELULARPERSONA>";
            }
            $strPoseedorXML .= "
                                                        <NOMENCLATURADIRECCION>" . utf8_decode($arrTercero['direccion']) . "</NOMENCLATURADIRECCION>
                                                        <CODMUNICIPIORNDC>" . $arrTercero['codigoCiudad'] . "</CODMUNICIPIORNDC>";
            if ($arrTercero['conductor'] == 1) {
                $strPoseedorXML .= "
                                        <CODCATEGORIALICENCIACONDUCCION>" . $arrTercero['categoriaLicencia'] . "</CODCATEGORIALICENCIACONDUCCION>
                                        <NUMLICENCIACONDUCCION>" . $arrTercero['numeroLicencia'] . "</NUMLICENCIACONDUCCION>
                                        <FECHAVENCIMIENTOLICENCIA>" . $arrTercero['fechaVenceLicencia']->format('d/m/Y') . "</FECHAVENCIMIENTOLICENCIA>";
            }

            $strPoseedorXML .= "</variables>
                                                        </root>";

            $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strPoseedorXML));
            $cadena_xml = simplexml_load_string($respuesta);
            if ($cadena_xml->ErrorMSG != "") {
                $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
                if (substr($errorRespuesta, 0, 9) != "DUPLICADO") {
                    $retorno = false;
                    Mensajes::error($errorRespuesta . " Reportando tercero identificacion: " . $arrTercero['identificacion']);
                    break;
                }
            }
        }

        return $retorno;

    }

    public function reportarRndcVehiculo($cliente, $arConfiguracionTransporte, $arrDespacho): string
    {
        $em = $this->getEntityManager();
        $retorno = true;
        $arVehiculo = $em->getRepository(TteVehiculo::class)->dqlRndc($arrDespacho['codigoVehiculoFk']);
        $strVehiculoXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                        <root>
                            <acceso>
                                <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                            </acceso>
                            <solicitud>
                                <tipo>1</tipo>
                                <procesoid>12</procesoid>
                            </solicitud>
                            <variables>
                                <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                <NUMPLACA>" . $arVehiculo['codigoVehiculoPk'] . "</NUMPLACA>
                                <CODCONFIGURACIONUNIDADCARGA>" . $arVehiculo['configuracion'] . "</CODCONFIGURACIONUNIDADCARGA>
                                <NUMEJES>" . $arVehiculo['numeroEjes'] . "</NUMEJES>
                                <CODMARCAVEHICULOCARGA>" . $arVehiculo['codigoMarca'] . "</CODMARCAVEHICULOCARGA>
                                <CODLINEAVEHICULOCARGA>" . $arVehiculo['codigoLinea'] . "</CODLINEAVEHICULOCARGA>
                                <ANOFABRICACIONVEHICULOCARGA>" . $arVehiculo['modelo'] . "</ANOFABRICACIONVEHICULOCARGA>
                                <CODTIPOCOMBUSTIBLE>" . $arVehiculo['tipoCombustible'] . "</CODTIPOCOMBUSTIBLE>
                                <PESOVEHICULOVACIO>" . $arVehiculo['pesoVacio'] . "</PESOVEHICULOVACIO>
                                <CODCOLORVEHICULOCARGA>" . $arVehiculo['codigoColor'] . "</CODCOLORVEHICULOCARGA>
                                <CODTIPOCARROCERIA>" . $arVehiculo['tipoCarroceria'] . "</CODTIPOCARROCERIA>
                                <CODTIPOIDPROPIETARIO>" . $arVehiculo['tipoIdentificacionPropietario'] . "</CODTIPOIDPROPIETARIO>
                                <NUMIDPROPIETARIO>" . $arVehiculo['numeroIdentificacionPropietario'] . "</NUMIDPROPIETARIO>
                                <CODTIPOIDTENEDOR>" . $arVehiculo['tipoIdentificacionPoseedor'] . "</CODTIPOIDTENEDOR>
                                <NUMIDTENEDOR>" . $arVehiculo['numeroIdentificacionPoseedor'] . "</NUMIDTENEDOR> 
                                <NUMSEGUROSOAT>" . $arVehiculo['numeroPoliza'] . "</NUMSEGUROSOAT> 
                                <FECHAVENCIMIENTOSOAT>" . $arVehiculo['fechaVencePoliza']->format('d/m/Y') . "</FECHAVENCIMIENTOSOAT>
                                <NUMNITASEGURADORASOAT>" . $arVehiculo['numeroIdentificacionAseguradora'] . "</NUMNITASEGURADORASOAT>
                                <CAPACIDADUNIDADCARGA>" . $arVehiculo['capacidad'] . "</CAPACIDADUNIDADCARGA>
                                <UNIDADMEDIDACAPACIDAD>1</UNIDADMEDIDACAPACIDAD>
                            </variables>
                        </root>";

        $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strVehiculoXML));
        $cadena_xml = simplexml_load_string($respuesta);
        if ($cadena_xml->ErrorMSG != "") {
            $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
            if (substr($errorRespuesta, 0, 9) != "DUPLICADO") {
                $retorno = false;
                Mensajes::error($errorRespuesta);
            }
        }

        return $retorno;

    }

    public function reportarRndcGuia($cliente, $arConfiguracionTransporte, $arrDespacho): string
    {
        $em = $this->getEntityManager();
        $retorno = true;
        $strGuiaXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                        <root>
                            <acceso>
                                <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                            </acceso>
                            <solicitud>
                                <tipo>1</tipo>
                                <procesoid>3</procesoid>
                            </solicitud>
                            <variables>
                                <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                <CONSECUTIVOREMESA>" . $arrDespacho['numero'] . "</CONSECUTIVOREMESA>
                                <CODOPERACIONTRANSPORTE>P</CODOPERACIONTRANSPORTE>
                                <CODTIPOEMPAQUE>0</CODTIPOEMPAQUE>
                                <CODNATURALEZACARGA>1</CODNATURALEZACARGA>                                                  
                                <DESCRIPCIONCORTAPRODUCTO>PAQUETES VARIOS</DESCRIPCIONCORTAPRODUCTO>
                                <MERCANCIAREMESA>009880</MERCANCIAREMESA>
                                <CANTIDADCARGADA>" . $arrDespacho['pesoReal'] . "</CANTIDADCARGADA>
                                <UNIDADMEDIDACAPACIDAD>1</UNIDADMEDIDACAPACIDAD>
                                <CODTIPOIDREMITENTE>C</CODTIPOIDREMITENTE>
                                <NUMIDREMITENTE>222222222</NUMIDREMITENTE>
                                <CODSEDEREMITENTE>" . $arrDespacho['codigoCiudadOrigenFk'] . "</CODSEDEREMITENTE>
                                <CODTIPOIDDESTINATARIO>C</CODTIPOIDDESTINATARIO>
                                <NUMIDDESTINATARIO>333333333</NUMIDDESTINATARIO>
                                <CODSEDEDESTINATARIO>" . $arrDespacho['codigoCiudadDestinoFk'] . "</CODSEDEDESTINATARIO>
                                <CODTIPOIDPROPIETARIO>C</CODTIPOIDPROPIETARIO>
                                <NUMIDPROPIETARIO>222222222</NUMIDPROPIETARIO>
                                <CODSEDEPROPIETARIO>" . $arrDespacho['codigoCiudadOrigenFk'] . "</CODSEDEPROPIETARIO>
                                <DUENOPOLIZA>E</DUENOPOLIZA>
                                <NUMPOLIZATRANSPORTE>" . $arConfiguracionTransporte->getNumeroPoliza() . "</NUMPOLIZATRANSPORTE>
                                <FECHAVENCIMIENTOPOLIZACARGA>" . $arConfiguracionTransporte->getFechaVencePoliza()->format('d/m/Y') . "</FECHAVENCIMIENTOPOLIZACARGA>
                                <COMPANIASEGURO>" . $arConfiguracionTransporte->getNumeroIdentificacionAseguradora() . "</COMPANIASEGURO>
                                <HORASPACTOCARGA>24</HORASPACTOCARGA>
                                <MINUTOSPACTOCARGA>00</MINUTOSPACTOCARGA>
                                <FECHACITAPACTADACARGUE>21/08/2013</FECHACITAPACTADACARGUE>
                                <HORACITAPACTADACARGUE>22:00</HORACITAPACTADACARGUE>
                                <HORASPACTODESCARGUE>72</HORASPACTODESCARGUE>
                                <MINUTOSPACTODESCARGUE>00</MINUTOSPACTODESCARGUE>
                                <FECHACITAPACTADADESCARGUE>25/08/2013</FECHACITAPACTADADESCARGUE>
                                <HORACITAPACTADADESCARGUEREMESA>08:00</HORACITAPACTADADESCARGUEREMESA>
                            </variables>
		  		</root>";

        $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strGuiaXML));
        $cadena_xml = simplexml_load_string($respuesta);
        if ($cadena_xml->ErrorMSG != "") {
            $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
            if (substr($errorRespuesta, 0, 9) != "DUPLICADO") {
                $retorno = false;
                Mensajes::error($errorRespuesta);
            }
        }

        return $retorno;

    }

    /**
     * @param $cliente
     * @param $arConfiguracionTransporte
     * @param $arrDespacho
     * @param $arDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reportarRndcManifiesto($cliente, $arConfiguracionTransporte, $arrDespacho, $arDespacho): string
    {
        $em = $this->getEntityManager();
        $retorno = true;
        $guia = $arrDespacho['numero'];
        $arrPoseedor = $em->getRepository(TtePoseedor::class)->dqlRndcManifiesto($arrDespacho['codigoPoseedorFk']);
        $arrConductor = $em->getRepository(TteConductor::class)->dqlRndcManifiesto($arrDespacho['codigoConductorFk']);
        $strManifiestoXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                                            <root>
                                             <acceso>
                                                <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                                <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                                             </acceso>
                                             <solicitud>
                                              <tipo>1</tipo>
                                              <procesoid>4</procesoid>
                                             </solicitud>
                                             <variables>
                                                <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                                <NUMMANIFIESTOCARGA>" . $arrDespacho['numero'] . "</NUMMANIFIESTOCARGA>
                                                <CODOPERACIONTRANSPORTE>P</CODOPERACIONTRANSPORTE>
                                                <FECHAEXPEDICIONMANIFIESTO>" . $arrDespacho['fechaSalida']->format('d/m/Y') . "</FECHAEXPEDICIONMANIFIESTO>
                                                <CODMUNICIPIOORIGENMANIFIESTO>" . $arrDespacho['codigoCiudadOrigen'] . "</CODMUNICIPIOORIGENMANIFIESTO>
                                                <CODMUNICIPIODESTINOMANIFIESTO>" . $arrDespacho['codigoCiudadDestino'] . "</CODMUNICIPIODESTINOMANIFIESTO>
                                                <CODIDTITULARMANIFIESTO>" . $arrPoseedor['tipoIdentificacion'] . "</CODIDTITULARMANIFIESTO>
                                                <NUMIDTITULARMANIFIESTO>" . $arrPoseedor['numeroIdentificacion'] . "</NUMIDTITULARMANIFIESTO>
                                                <NUMPLACA>" . $arrDespacho['codigoVehiculoFk'] . "</NUMPLACA>
                                                <CODIDCONDUCTOR>" . $arrConductor['tipoIdentificacion'] . "</CODIDCONDUCTOR>
                                                <NUMIDCONDUCTOR>" . $arrConductor['numeroIdentificacion'] . "</NUMIDCONDUCTOR>
                                                <VALORFLETEPACTADOVIAJE>" . $arrDespacho['vrFletePago'] . "</VALORFLETEPACTADOVIAJE>
                                                <RETENCIONFUENTEMANIFIESTO>" . $arrDespacho['vrRetencionFuente'] . "</RETENCIONFUENTEMANIFIESTO>
                                                <RETENCIONICAMANIFIESTOCARGA>0</RETENCIONICAMANIFIESTOCARGA>
                                                <VALORANTICIPOMANIFIESTO>" . $arrDespacho['vrAnticipo'] . "</VALORANTICIPOMANIFIESTO>
                                                <FECHAPAGOSALDOMANIFIESTO>" . $arrDespacho['fechaSalida']->format('d/m/Y') . "</FECHAPAGOSALDOMANIFIESTO>                                                
                                                <CODRESPONSABLEPAGOCARGUE>E</CODRESPONSABLEPAGOCARGUE>
                                                <CODRESPONSABLEPAGODESCARGUE>E</CODRESPONSABLEPAGODESCARGUE>
                                                <OBSERVACIONES>NADA</OBSERVACIONES>
                                                <CODMUNICIPIOPAGOSALDO>05001000</CODMUNICIPIOPAGOSALDO>
						<REMESASMAN procesoid='43'><REMESA><CONSECUTIVOREMESA>$guia</CONSECUTIVOREMESA></REMESA></REMESASMAN>
                                    </variables>
                    </root>";

        $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strManifiestoXML));
        $cadena_xml = simplexml_load_string($respuesta);
        if ($cadena_xml->ErrorMSG != "") {
            $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
            //if(substr($errorRespuesta, 0, 9) != "DUPLICADO") {
            $retorno = false;
            Mensajes::error($errorRespuesta);
            //}
        } else {
            if ($cadena_xml->ingresoid) {
                $arDespacho->setNumeroRndc(utf8_decode($cadena_xml->ingresoid));
                $em->persist($arDespacho);
                $em->flush();
            }
        }


        return $retorno;

    }

    public function cumplirRndc($codigo): string
    {
        $em = $this->getEntityManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigo);
        if ($arDespacho->getEstadoCumplirRndc() == 0) {
            try {
                $cliente = new \SoapClient("http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices");
                $arConfiguracionTransporte = $em->getRepository(TteConfiguracion::class)->find(1);
                $strXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                            <root>
                                <acceso>
                                    <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                    <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                                </acceso>
                                <solicitud>
                                    <tipo>1</tipo>
                                    <procesoid>5</procesoid>
                                </solicitud>
                                <variables>
                                    <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                    <CONSECUTIVOREMESA>" . $arDespacho->getNumero() . "</CONSECUTIVOREMESA>
                                    <NUMMANIFIESTOCARGA>" . $arDespacho->getNumero() . "</NUMMANIFIESTOCARGA>
                                    <TIPOCUMPLIDOREMESA>C</TIPOCUMPLIDOREMESA>
                                    <FECHALLEGADACARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHALLEGADACARGUE>
                                    <HORALLEGADACARGUEREMESA>14:00</HORALLEGADACARGUEREMESA>
                                    <FECHAENTRADACARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHAENTRADACARGUE>
                                    <HORAENTRADACARGUEREMESA>16:00</HORAENTRADACARGUEREMESA>
                                    <FECHASALIDACARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHASALIDACARGUE>
                                    <HORASALIDACARGUEREMESA>17:00</HORASALIDACARGUEREMESA>
                                                                        
                                    <FECHALLEGADADESCARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHALLEGADADESCARGUE>
                                    <HORALLEGADADESCARGUECUMPLIDO>18:00</HORALLEGADADESCARGUECUMPLIDO>
                                    <FECHAENTRADADESCARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHAENTRADADESCARGUE>
                                    <HORAENTRADADESCARGUECUMPLIDO>19:00</HORAENTRADADESCARGUECUMPLIDO>
                                    <FECHASALIDADESCARGUE>" . $arDespacho->getFechaSalida()->format('d/m/Y') . "</FECHASALIDADESCARGUE>
                                    <HORASALIDADESCARGUECUMPLIDO>20:00</HORASALIDADESCARGUECUMPLIDO>                                    
                                    <CANTIDADENTREGADA>" . $arDespacho->getCantidad() . "</CANTIDADENTREGADA>";
                $strXML .= "</variables>
                              </root>";
                $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strXML));
                $cadena_xml = simplexml_load_string($respuesta);
                if ($cadena_xml->ErrorMSG != "") {
                    $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
                    Mensajes::error($errorRespuesta);
                } else {
                    if ($cadena_xml->ingresoid) {
                        $strXML = "<?xml version='1.0' encoding='ISO-8859-1' ?>
                            <root>
                                <acceso>
                                    <username>" . $arConfiguracionTransporte->getUsuarioRndc() . "</username>
                                    <password>" . $arConfiguracionTransporte->getClaveRndc() . "</password>
                                </acceso>
                                <solicitud>
                                    <tipo>1</tipo>
                                    <procesoid>6</procesoid>
                                </solicitud>
                                <variables>
                                    <NUMNITEMPRESATRANSPORTE>" . $arConfiguracionTransporte->getEmpresaRndc() . "</NUMNITEMPRESATRANSPORTE>
                                    <NUMMANIFIESTOCARGA>" . $arDespacho->getNumero() . "</NUMMANIFIESTOCARGA>
                                    <TIPOCUMPLIDOMANIFIESTO>C</TIPOCUMPLIDOMANIFIESTO>
                                    <FECHAENTREGADOCUMENTOS>" . $arDespacho->getFechaSoporte()->format('d/m/Y') . "</FECHAENTREGADOCUMENTOS>
                                    <VALORADICIONALHORASCARGUE>0</VALORADICIONALHORASCARGUE>                                    
                                    <VALORSOBREANTICIPO>0</VALORSOBREANTICIPO>";

                        $strXML .= "</variables>
                                                        </root>";

                        $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strXML));
                        $cadena_xml = simplexml_load_string($respuesta);
                        if ($cadena_xml->ErrorMSG != "") {
                            $errorRespuesta = utf8_decode($cadena_xml->ErrorMSG);
                            Mensajes::error($errorRespuesta);
                        } else {
                            if ($cadena_xml->ingresoid) {
                                $arDespacho->setEstadoCumplirRndc(1);
                                $em->persist($arDespacho);
                                $em->flush();
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                return "Error al conectar el control: " . $e;
            }
        } else {
            Mensajes::error("El viaje ya fue cumplidor en el rndc");
        }
        return true;
    }

    /**
     * @param $codigoSolicitud
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoDespacho)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoDetalle::class, 'dd')
            ->select("COUNT(dd.codigoDespachoDetallePk)")
            ->where("dd.codigoDespachoFk= {$codigoDespacho} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pendienteCumplirRndc()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('d.codigoDespachoPk')
            ->addSelect('d.fechaSalida')
            ->addSelect('d.fechaSoporte')
            ->addSelect('d.numero')
            ->addSelect('d.codigoOperacionFk')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('d.codigoRutaFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.cantidad')
            ->addSelect('d.unidades')
            ->addSelect('d.pesoReal')
            ->addSelect('d.pesoVolumen')
            ->addSelect('d.vrFlete')
            ->addSelect('d.vrManejo')
            ->addSelect('d.vrDeclara')
            ->addSelect('d.vrFletePago')
            ->addSelect('d.vrAnticipo')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('d.estadoAprobado')
            ->addSelect('d.estadoAutorizado')
            ->addSelect('d.estadoAnulado')
            ->addSelect('d.estadoSoporte')
            ->addSelect('dt.nombre AS despachoTipo')
            ->addSelect('d.usuario')
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel ', 'cd')
            ->leftJoin('d.conductorRel', 'c')
            ->where('d.numeroRndc <> 0 AND d.estadoCumplirRndc = 0');
        $queryBuilder->orderBy('d.fechaSalida', 'DESC');
        return $queryBuilder;

    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function rentabilidad($fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('d.codigoDespachoPk')
            ->addSelect('d.fechaSalida')
            ->addSelect('d.numero')
            ->addSelect('d.codigoOperacionFk')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('d.codigoRutaFk')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.cantidad')
            ->addSelect('d.unidades')
            ->addSelect('d.pesoReal')
            ->addSelect('d.pesoVolumen')
            ->addSelect('d.vrFlete')
            ->addSelect('d.vrManejo')
            ->addSelect('d.vrFlete + d.vrManejo AS vrTotalIngreso')
            ->addSelect('d.vrDeclara')
            ->addSelect('d.vrCosto')
            ->addSelect('d.vrFletePago')
            ->addSelect('d.vrAnticipo')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('d.estadoAprobado')
            ->addSelect('d.estadoAutorizado')
            ->addSelect('d.estadoAnulado')
            ->addSelect('d.porcentajeRentabilidad')
            ->addSelect('dt.nombre as despachoTipo')
            ->addSelect('r.nombre as ruta')
            ->leftJoin('d.ciudadDestinoRel ', 'cd')
            ->leftJoin('d.conductorRel', 'c')
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->leftJoin('d.rutaRel', 'r')
            ->where("d.fechaSalida >= '" . $fechaDesde . " 00:00:00' AND d.fechaSalida <='" . $fechaHasta . " 23:59:59'")
            ->andWhere('d.estadoAprobado = 1')
            ->orderBy('d.codigoDespachoTipoFk', 'ASC')
            ->addOrderBy('d.fechaSalida', 'DESC');
        return $queryBuilder;

    }

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('d.codigoDespachoPk')
            ->addSelect('d.fechaSalida')
            ->addSelect('d.numero')
            ->addSelect('d.codigoOperacionFk')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('d.codigoRutaFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.cantidad')
            ->addSelect('d.unidades')
            ->addSelect('d.pesoReal')
            ->addSelect('d.pesoVolumen')
            ->addSelect('d.vrFlete')
            ->addSelect('d.vrManejo')
            ->addSelect('d.vrDeclara')
            ->addSelect('d.vrFletePago')
            ->addSelect('d.vrAnticipo')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('d.estadoAprobado')
            ->addSelect('d.estadoAutorizado')
            ->addSelect('d.estadoAnulado')
            ->addSelect('dt.nombre AS despachoTipo')
            ->addSelect('d.usuario')
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel ', 'cd')
            ->leftJoin('d.conductorRel', 'c')
            ->where('d.estadoContabilizado =  0')
            ->andWhere('d.estadoAprobado = 1')
            ->andWhere('dt.viaje = 1');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteDespachoFiltroFecha') == true) {
            if ($session->get('filtroTteDespachoFechaDesde') != null) {
                $queryBuilder->andWhere("d.fechaSalida >= '{$session->get('filtroTteDespachoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("d.fechaSalida >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteDespachoFechaHasta') != null) {
                $queryBuilder->andWhere("d.fechaSalida <= '{$session->get('filtroTteDespachoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("d.fechaSalida <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        };
        $queryBuilder->orderBy('d.fechaSalida', 'ASC');
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
            ->addSelect('d.vrTotalNeto')
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
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arDespacho['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(TtePoseedor::class)->terceroFinanciero($arDespacho['codigoPropietarioFk']);

                        //Cuenta flete pagado
                        if ($arDespacho['vrFletePago'] > 0) {
                            if ($arDespacho['codigoCuentaFleteFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arDespacho['codigoCuentaFleteFk']);
                                if (!$arCuenta) {
                                    $error = "No se encuentra la cuenta del flete " . $arDespacho['codigoCuentaFleteFk'];
                                    break;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
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
                        $descripcion = "POR PAGAR";
                        $cuenta = $arDespacho['codigoCuentaPagarFk'];
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
                                $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arDespacho['codigoCentroCostoFk']);
                                $arRegistro->setCentroCostoRel($arCentroCosto);
                            }
                            $arRegistro->setNumero($arDespacho['numero']);
                            $arRegistro->setNumeroReferencia($arDespacho['numero']);
                            $arRegistro->setFecha($arDespacho['fechaSalida']);
                            $naturaleza = "C";
                            if ($naturaleza == 'D') {
                                $arRegistro->setVrDebito($arDespacho['vrTotalNeto']);
                                $arRegistro->setNaturaleza('D');
                            } else {
                                $arRegistro->setVrCredito($arDespacho['vrTotalNeto']);
                                $arRegistro->setNaturaleza('C');
                            }
                            $arRegistro->setDescripcion($descripcion);
                            $em->persist($arRegistro);
                        } else {
                            $error = "El tipo de despacho no tiene configurada la cuenta " . $descripcion;
                            break;
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

    /**
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function despachoPorDiaMesAtual()
    {
        $fecha = new \DateTime('now');
        $fechaDesde = $fecha->format('Y-m') . '-01';
        //Obtenemos el ultimo dia del mes seleccionado
        $fechaHasta = FuncionesController::ultimoDia($fecha);
        $sql = "SELECT DAY(fecha_registro) as dia, 
                COUNT(codigo_despacho_pk) as cantidad
                FROM tte_despacho 
                WHERE fecha_registro >= '" . $fechaDesde . " 00:00:00' AND fecha_registro <='" . $fechaHasta . " 23:59:59'  
                GROUP BY DAY(fecha_registro)";
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function despachoPorMesAnioActual()
    {
        $fecha = new \DateTime('now');
        $fechaDesde = $fecha->format('Y') . "-01-01";
        $fechaHasta = $fecha->format('Y') . "-12-31";
        $sql = "SELECT MONTH(fecha_registro) as mes, 
                COUNT(codigo_despacho_pk) as cantidad
                FROM tte_despacho 
                WHERE fecha_registro >= '" . $fechaDesde . " 00:00:00' AND fecha_registro <='" . $fechaHasta . " 23:59:59'  
                GROUP BY MONTH(fecha_registro)";
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }

    public function listaSoporte($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select('d.codigoDespachoPk')
            ->addSelect('d.codigoOperacionFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('d.codigoVehiculoFk')
            ->addSelect('con.nombreCorto AS conductor')
            ->addSelect('d.cantidad')
            ->addSelect('d.unidades')
            ->addSelect('d.pesoReal')
            ->addSelect('d.pesoVolumen')
            ->addSelect('d.fechaRegistro')
            ->leftJoin('d.ciudadOrigenRel', 'co')
            ->leftJoin('d.ciudadDestinoRel', 'cd')
            ->leftJoin('d.conductorRel', 'con')
            ->where('d.codigoDespachoPk = ' . $codigoDespacho)
            ->andWhere('d.estadoAprobado = 1')
            ->andWhere('d.estadoSoporte = 0')
            ->andWhere('d.estadoAnulado = 0');
        $queryBuilder->orderBy('d.codigoDespachoPk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @param $codigoDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function soporte($codigoDespacho)
    {
        $em = $this->getEntityManager();
        $arrDespachoDetalles = $em->getRepository(TteDespachoDetalle::class)->validarGuiasSoporte($codigoDespacho);
        $arrGuiasSinSoporte = [];
        foreach ($arrDespachoDetalles AS $arDespachoDetalle) {
            if ($arDespachoDetalle['estadoSoporte'] == false && $arDespachoDetalle['codigoDespachoGuiaFk'] == $codigoDespacho) {
                $arrGuiasSinSoporte[] = $arDespachoDetalle['codigoGuiaFk'];
            }
        }
        if (count($arrGuiasSinSoporte) == 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
            $arDespacho->setFechaSoporte(new \DateTime("now"));
            $arDespacho->setEstadoSoporte(1);
            $em->persist($arDespacho);
            $em->flush();
        } else {
            $strErrores = implode('-', $arrGuiasSinSoporte);
            Mensajes::error('Las siguientes guias no cuentan con soporte: ' . $strErrores);
        }
    }

    public function fletePago($fechaDesde, $fechaHasta)
    {
        $valor = 0;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select("SUM(d.vrFletePago) as fletePago")
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->where("d.fechaSalida >='" . $fechaDesde . "' AND d.fechaSalida <= '" . $fechaHasta . "'")
        ->andWhere('dt.viaje = 1')
        ->andWhere('d.estadoAprobado = 1');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();
        if($arrResultado['fletePago']) {
            $valor = $arrResultado['fletePago'];
        }
        return $valor;
    }

    public function fletePagoDetallado($fechaDesde, $fechaHasta)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'd')
            ->select("d.codigoDespachoTipoFk")
            ->addSelect("SUM(d.vrFletePago) as fletePago")
            ->leftJoin('d.despachoTipoRel', 'dt')
            ->where("d.fechaSalida >='" . $fechaDesde . "' AND d.fechaSalida <= '" . $fechaHasta . "'")
            ->andWhere('dt.viaje = 1')
            ->andWhere('d.estadoAprobado = 1')
            ->groupBy('d.codigoDespachoTipoFk');
        $arrResultado = $queryBuilder->getQuery()->getResult();
        return $arrResultado;
    }

}

