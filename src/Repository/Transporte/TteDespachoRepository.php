<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TtePoseedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
class TteDespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespacho::class);
    }

    public function lista(): array
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
        return $query->execute();

    }

    public function liquidar($codigoDespacho): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.codigoDespachoFk = :codigoDespacho')
            ->setParameter('codigoDespacho', $codigoDespacho);
        $arrGuias = $query->getSingleResult();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        $arDespacho->setUnidades(intval($arrGuias['unidades']));
        $arDespacho->setPesoReal(intval($arrGuias['pesoReal']));
        $arDespacho->setPesoVolumen(intval($arrGuias['pesoVolumen']));
        $arDespacho->setCantidad(intval($arrGuias['cantidad']));
        $em->persist($arDespacho);
        $em->flush();
        return true;
    }

    public function generar($codigoDespacho): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if(!$arDespacho->getEstadoGenerado()) {
            if($arDespacho->getCantidad() > 0) {
                $fechaActual = new \DateTime('now');
                $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoDespachado = 1, g.fechaDespacho=:fecha 
                      WHERE g.codigoDespachoFk = :codigoDespacho')
                    ->setParameter('codigoDespacho', $codigoDespacho)
                    ->setParameter('fecha', $fechaActual->format('Y-m-d H:i'));
                $query->execute();
                $arDespacho->setFechaSalida($fechaActual);
                $arDespacho->setEstadoGenerado(1);
                if($arDespacho->getNumero() == 0 || $arDespacho->getNumero() == NULL) {
                    $arDespachoTipo = $em->getRepository(TteDespachoTipo::class)->find($arDespacho->getCodigoDespachoTipoFk());
                    $arDespacho->setNumero($arDespachoTipo->getConsecutivo());
                    $arDespachoTipo->setConsecutivo($arDespachoTipo->getConsecutivo() + 1);
                    $em->persist($arDespachoTipo);
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
        if(!$arDespacho->getEstadoAnulado()) {
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

    public function retirarDetalle($arrDetalles): bool
    {
        $em = $this->getEntityManager();
        if($arrDetalles) {
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

    public function reportarRndc($codigoDespacho): string
    {
        $em = $this->getEntityManager();
        try {
            $cliente = new \SoapClient("http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices");
            $arConfiguracionTransporte = $em->getRepository(TteConfiguracion::class)->find(1);
            $respuesta = $this->reportarRndcTerceros($cliente, $arConfiguracionTransporte, $codigoDespacho);


        } catch (Exception $e) {
            return "Error al conectar el servicio: " . $e;
        }
        $respuesta = "";


        return $respuesta;

    }
    public function reportarRndcTerceros($cliente, $arConfiguracionTransporte, $codigoDespacho): string
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT d.codigoDespachoPk,
        d.codigoConductorFk,
        v.codigoPoseedorFk,
        v.codigoPropietarioFk
        FROM App\Entity\Transporte\TteDespacho d          
        LEFT JOIN d.vehiculoRel v     
        WHERE d.codigoDespachoPk = :codigoDespacho
        ORDER BY d.codigoDespachoPk DESC '
        )->setParameter('codigoDespacho', $codigoDespacho);
        $arTerceros =  $query->getSingleResult();

        $arPoseedor = $em->getRepository(TtePoseedor::class)->find($arTerceros['codigoPoseedorFk']);
        $strPoseedorXML = "";
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
                                    <CODTIPOIDTERCERO>". $arPoseedor->getCodigoIdentificacionFk() ."</CODTIPOIDTERCERO>
                                    <NUMIDTERCERO>" . $arPoseedor->getNumeroIdentificacion() . "</NUMIDTERCERO>
                                    <NOMIDTERCERO>" . utf8_decode($arPoseedor->getNombre1()) . "</NOMIDTERCERO>";
                                    if($arPoseedor->getCodigoIdentificacionFk() == "C") {
                                        $strPoseedorXML .= "<PRIMERAPELLIDOIDTERCERO>" . utf8_decode($arPoseedor->getApellido1()) . "</PRIMERAPELLIDOIDTERCERO>
                                                            <SEGUNDOAPELLIDOIDTERCERO>" . utf8_decode($arPoseedor->getApellido2()) . "</SEGUNDOAPELLIDOIDTERCERO>";
                                    }
                                    $strPoseedorXML .= "<CODSEDETERCERO>1</CODSEDETERCERO>";
                                    $strPoseedorXML .= "<NOMSEDETERCERO>PRINCIPAL</NOMSEDETERCERO>";
                                    if($arPoseedor->getTelefono() != "") {
                                        $strPoseedorXML .= "<NUMTELEFONOCONTACTO>" . $arPoseedor->getTelefono() . "</NUMTELEFONOCONTACTO>";
                                    }
                                    if($arPoseedor->getMovil() != "" && $arPoseedor->getCodigoIdentificacionFk() == "C") {
                                        $strPoseedorXML .= "<NUMCELULARPERSONA>" . $arPoseedor->getMovil() . "</NUMCELULARPERSONA>";
                                    }
                                    $strPoseedorXML .= "
                                                        <NOMENCLATURADIRECCION>" . utf8_decode($arPoseedor->getDireccion()) . "</NOMENCLATURADIRECCION>
                                                        <CODMUNICIPIORNDC>" . $arPoseedor->getCodigoCiudadFk() . "</CODMUNICIPIORNDC>";
                                    $strPoseedorXML .= "</variables>
                                                        </root>";

        $respuesta = $cliente->__soapCall('AtenderMensajeRNDC', array($strPoseedorXML));
        $cadena_xml = simplexml_load_string($respuesta);

        $arPropietario = $em->getRepository(TtePoseedor::class)->find($arTerceros['codigoPropietarioFk']);
        $arConductor = $em->getRepository(TteConductor::class)->find($arTerceros['codigoConductorFk']);

        return 1;

    }
}