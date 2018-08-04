<?php

namespace App\Repository\Transporte;

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

    public function listaDql(): string
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk, 
        d.numero,
        d.codigoOperacionFk,
        d.codigoVehiculoFk,
        d.codigoRutaFk, 
        co.nombre AS ciudadOrigen, 
        cd.nombre AS ciudadDestino,
        d.unidades,
        d.pesoReal,
        d.pesoVolumen,
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara,
        c.nombreCorto AS conductorNombre,
        d.estadoAnulado
        FROM App\Entity\Transporte\TteDespacho d         
        LEFT JOIN d.ciudadOrigenRel co
        LEFT JOIN d.ciudadDestinoRel cd
        LEFT JOIN d.conductorRel c
        ORDER BY d.codigoDespachoPk DESC'
        );
        return $query->getDQL();

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
        if($session->get('filtroTteDespachoCodigoVehiculo') != ''){
            $queryBuilder->andWhere("td.codigoVehiculoFk = '{$session->get('filtroTteDespachoCodigoVehiculo')}'");
        }
        if($session->get('filtroTteDespachoCodigo') != ''){
            $queryBuilder->andWhere("td.codigoDespachoPk = {$session->get('filtroTteDespachoCodigo')}");
        }
        if($session->get('filtroTteDespachoNumero') != ''){
            $queryBuilder->andWhere("td.numero = {$session->get('filtroTteDespachoNumero')}");
        }
        if($session->get('filtroTteDespachoCodigoCiudadOrigen')){
            $queryBuilder->andWhere("td.codigoCiudadOrigenFk = {$session->get('filtroTteDespachoCodigoCiudadOrigen')}");
        }
        if($session->get('filtroTteDespachoCodigoCiudadDestino')){
            $queryBuilder->andWhere("td.codigoCiudadDestinoFk = {$session->get('filtroTteDespachoCodigoCiudadDestino')}");
        }
        if($session->get('filtroTteDespachoCodigoConductor')){
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
     * @param $arDespacho
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arDespacho)
    {
        if ($this->contarDetalles($arDespacho->getCodigoDespachoPk()) > 0) {
            $arDespacho->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arDespacho);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
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
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen,
                  SUM(g.vrFlete) as vrFlete, SUM(g.vrManejo) as vrManejo, SUM(g.vrCobroEntrega) as vrCobroEntrega 
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.codigoDespachoFk = :codigoDespacho')
            ->setParameter('codigoDespacho', $codigoDespacho);
        $arrGuias = $query->getSingleResult();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        $arDespacho->setUnidades(intval($arrGuias['unidades']));
        $arDespacho->setPesoReal(intval($arrGuias['pesoReal']));
        $arDespacho->setPesoVolumen(intval($arrGuias['pesoVolumen']));
        $arDespacho->setCantidad(intval($arrGuias['cantidad']));
        $arDespacho->setVrFlete(intval($arrGuias['vrFlete']));
        $arDespacho->setVrManejo(intval($arrGuias['vrManejo']));
        $arDespacho->setVrCobroEntrega(intval($arrGuias['vrCobroEntrega']));
        $em->persist($arDespacho);
        $em->flush();
        return true;
    }

    public function aprobar($codigoDespacho): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
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
                if ($arDespachoTipo->getGeneraMonitoreo()) {
                    $arMonitoreo = new TteMonitoreo();
                    $arMonitoreo->setVehiculoRel($arDespacho->getVehiculoRel());
                    $arMonitoreo->setDespachoRel($arDespacho);
                    $arMonitoreo->setFechaRegistro(new \DateTime('now'));
                    $arMonitoreo->setFechaInicio(new \DateTime('now'));
                    $arMonitoreo->setFechaFin(new \DateTime('now'));
                    $em->persist($arMonitoreo);
                }
                $em->persist($arDespacho);
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
        $em = $this->getEntityManager();
        $query = $em->createQuery(
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
            $costo = ($costoPeso + $costoVolumen) / 2;
            $arDespachoDetalleActualizar->setVrCostoUnidad($costoUnidad);
            $arDespachoDetalleActualizar->setVrCostoPeso($costoPeso);
            $arDespachoDetalleActualizar->setVrCostoVolumen($costoVolumen);
            $arDespachoDetalleActualizar->setVrCosto($costo);
            $em->persist($arDespachoDetalleActualizar);
        }
        $arDespacho->setEstadoCerrado(1);
        $em->flush();
        return $respuesta;
    }

    public function anular($codigoDespacho): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if (!$arDespacho->getEstadoAnulado()) {
            $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoDespachado = 0, 
                  g.estadoEmbarcado = 0, g.codigoDespachoFk = NULL
                  WHERE g.codigoDespachoFk = :codigoDespacho')
                ->setParameter('codigoDespacho', $codigoDespacho);
            $query->execute();
            $arDespacho->setEstadoAnulado(1);
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
                if($respuesta != ''){
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
        d.vrFlete,
        d.vrManejo,
        d.vrDeclara,
        d.vrFletePago,
        d.vrRetencionFuente,
        d.vrRetencionIca,
        d.vrAnticipo,
        d.vrCobroEntrega,
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
        d.codigoCiudadOrigenFk as codigoCiudadOrigen,
        d.codigoCiudadDestinoFk as codigoCiudadDestino
        FROM App\Entity\Transporte\TteDespacho d          
        LEFT JOIN d.vehiculoRel v             
        WHERE d.codigoDespachoPk = :codigoDespacho
        ORDER BY d.codigoDespachoPk DESC '
        )->setParameter('codigoDespacho', $codigoDespacho);
        $arDespacho = $query->getSingleResult();
        return $arDespacho;

    }

    public function reportarRndc($arDespacho): string
    {
        $em = $this->getEntityManager();
        if($arDespacho->getNumeroRndc() == "") {
            if($arDespacho->getEstadoAprobado() == 1 && $arDespacho->getEstadoAnulado() == 0) {
                try {
                    $cliente = new \SoapClient("http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices");
                    $arConfiguracionTransporte = $em->getRepository(TteConfiguracion::class)->find(1);
                    $arrDespacho = $em->getRepository(TteDespacho::class)->dqlRndc($arDespacho->getCodigoDespachoPk());
                    $retorno = $this->reportarRndcTerceros($cliente, $arConfiguracionTransporte, $arrDespacho);
                    if($retorno) {
                        $retorno = $this->reportarRndcVehiculo($cliente, $arConfiguracionTransporte, $arrDespacho);
                        if($retorno) {
                    //$respuesta = $this->reportarRndcGuia($cliente, $arConfiguracionTransporte, $arrDespacho);
                    //if($respuesta) {
                    //$respuesta = $this->reportarRndcManifiesto($cliente, $arConfiguracionTransporte, $arrDespacho);
                    //}
                        }
                    }

                } catch (Exception $e) {
                    return "Error al conectar el servicio: " . $e;
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
            $arrTerceros[] = array('identificacionTipo' => $arTerceroPoseedor['codigoIdentificacionFk'],
                'identificacion' => $arTerceroPoseedor['numeroIdentificacion'],
                'nombre1' => utf8_decode($arTerceroPoseedor['nombre1']),
                'apellido1' => utf8_decode($arTerceroPoseedor['apellido1']),
                'apellido2' => utf8_decode($arTerceroPoseedor['apellido2']),
                'telefono' => utf8_decode($arTerceroPoseedor['telefono']),
                'movil' => $arTerceroPoseedor['movil'],
                'direccion' => utf8_decode($arTerceroPoseedor['direccion']),
                'codigoCiudad' => $arTerceroPoseedor['codigoCiudad'],
                'conductor' => 0);
        }

        $arrConductor = $em->getRepository(TteConductor::class)->dqlRndc($arrDespacho['codigoConductorFk']);
        $arrTerceros[] = array('identificacionTipo' => $arrConductor['codigoIdentificacionFk'],
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
            'categoriaLicencia' => $arrConductor['categoriaLicencia']);

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
                                    <NOMIDTERCERO>" . $arrTercero['nombre1'] . "</NOMIDTERCERO>";
            if ($arrTercero['identificacionTipo'] == "C") {
                $strPoseedorXML .= "<PRIMERAPELLIDOIDTERCERO>" . $arrTercero['apellido1'] . "</PRIMERAPELLIDOIDTERCERO>
                                                            <SEGUNDOAPELLIDOIDTERCERO>" . $arrTercero['apellido2'] . "</SEGUNDOAPELLIDOIDTERCERO>";
            }
            $strPoseedorXML .= "<CODSEDETERCERO>1</CODSEDETERCERO>";
            $strPoseedorXML .= "<NOMSEDETERCERO>PRINCIPAL</NOMSEDETERCERO>";
            if ($arrTercero['telefono'] != "") {
                $strPoseedorXML .= "<NUMTELEFONOCONTACTO>" . $arrTercero['telefono'] . "</NUMTELEFONOCONTACTO>";
            }
            if ($arrTercero['movil'] != "" && $arrTercero['identificacionTipo'] == "C") {
                $strPoseedorXML .= "<NUMCELULARPERSONA>" . $arrTercero['movil'] . "</NUMCELULARPERSONA>";
            }
            $strPoseedorXML .= "
                                                        <NOMENCLATURADIRECCION>" . $arrTercero['direccion'] . "</NOMENCLATURADIRECCION>
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
                if(substr($errorRespuesta, 0, 9) != "DUPLICADO") {
                    $retorno = false;
                    Mensajes::error($errorRespuesta);
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
            $retorno = false;
            Mensajes::error($errorRespuesta);
        }

        return $retorno;

    }

    public function reportarRndcGuia($cliente, $arConfiguracionTransporte, $arrDespacho): string
    {
        $em = $this->getEntityManager();
        $respuesta = true;
        $destinatario = "100000" . $arrDespacho['numero'];
        $propietario = "500000" . $arrDespacho['numero'];
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
                                <NUMIDREMITENTE>$propietario</NUMIDREMITENTE>
                                <CODSEDEREMITENTE>1</CODSEDEREMITENTE>
                                <CODTIPOIDDESTINATARIO>C</CODTIPOIDDESTINATARIO>
                                <NUMIDDESTINATARIO>$destinatario</NUMIDDESTINATARIO>
                                <CODSEDEDESTINATARIO>1</CODSEDEDESTINATARIO>
                                <CODTIPOIDPROPIETARIO>C</CODTIPOIDPROPIETARIO>
                                <NUMIDPROPIETARIO>$propietario</NUMIDPROPIETARIO>
                                <CODSEDEPROPIETARIO>1</CODSEDEPROPIETARIO>
                                <DUENOPOLIZA>E</DUENOPOLIZA>
                                <NUMPOLIZATRANSPORTE>" . $arConfiguracionTransporte->getNumeroPoliza() . "</NUMPOLIZATRANSPORTE>
                                <FECHAVENCIMIENTOPOLIZACARGA>" . $arConfiguracionTransporte->getFechaVencePoliza()->format('Y/m/d') . "</FECHAVENCIMIENTOPOLIZACARGA>
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
            $respuesta = false;
            echo $cadena_xml->ErrorMSG;
        }


        return $respuesta;

    }

    public function reportarRndcManifiesto($cliente, $arConfiguracionTransporte, $arrDespacho): string
    {
        $em = $this->getEntityManager();
        $respuesta = true;
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
                                                <FECHAEXPEDICIONMANIFIESTO>" . $arrDespacho['fechaSalida']->format('Y/m/d') . "</FECHAEXPEDICIONMANIFIESTO>
                                                <CODMUNICIPIOORIGENMANIFIESTO>" . $arrDespacho['codigoCiudadOrigen'] . "</CODMUNICIPIOORIGENMANIFIESTO>
                                                <CODMUNICIPIODESTINOMANIFIESTO>" . $arrDespacho['codigoCiudadDestino'] . "</CODMUNICIPIODESTINOMANIFIESTO>
                                                <CODIDTITULARMANIFIESTO>" . $arrPoseedor['codigoIdentificacionFk'] . "</CODIDTITULARMANIFIESTO>
                                                <NUMIDTITULARMANIFIESTO>" . $arrPoseedor['numeroIdentificacion'] . "</NUMIDTITULARMANIFIESTO>
                                                <NUMPLACA>" . $arrDespacho['codigoVehiculoFk'] . "</NUMPLACA>
                                                <CODIDCONDUCTOR>" . $arrConductor['codigoIdentificacionFk'] . "</CODIDCONDUCTOR>
                                                <NUMIDCONDUCTOR>" . $arrConductor['numeroIdentificacion'] . "</NUMIDCONDUCTOR>
                                                <VALORFLETEPACTADOVIAJE>" . $arrDespacho['vrFletePago'] . "</VALORFLETEPACTADOVIAJE>
                                                <RETENCIONFUENTEMANIFIESTO>" . $arrDespacho['vrRetencionFuente'] . "</RETENCIONFUENTEMANIFIESTO>
                                                <RETENCIONICAMANIFIESTOCARGA>0</RETENCIONICAMANIFIESTOCARGA>
                                                <VALORANTICIPOMANIFIESTO>" . $arrDespacho['vrAnticipo'] . "</VALORANTICIPOMANIFIESTO>
                                                <FECHAPAGOSALDOMANIFIESTO>" . $arrDespacho['fechaSalida']->format('Y/m/d') . "</FECHAPAGOSALDOMANIFIESTO>                                                
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
            $respuesta = false;
            echo $cadena_xml->ErrorMSG;
        }


        return $respuesta;

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
        $resultado =  $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

}