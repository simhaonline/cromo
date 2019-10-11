<?php


namespace App\Utilidades;
use App\Entity\General\GenRespuestaFacturaElectronica;
use Doctrine\ORM\EntityManagerInterface;


class FacturaElectronica
{
    private $em;
    /*
    */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function enviar($arrFactura){
        $em = $this->em;
        $url = "https://enviardocumentos.dispafel.com/DFFacturaElectronicaEnviarDocumentos/enviarDocumento?wsdl";
        $xml = $this->generarXmlDispapeles($arrFactura);
        $client = new \SoapClient(null, array('location' => $url, 'uri'      => $url, 'trace'    => 1,));
        try{
            $soapResponse = $client->__doRequest($xml,$url,$url,1);
            $plainXML = $this->mungXML($soapResponse);
            $arrayRespuesta = json_decode(json_encode(SimpleXML_Load_String($plainXML, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            $arrReturn = $arrayRespuesta['S_Body']['ns2_enviarDocumentoResponse']['return'];
            if($arrReturn) {
                $arRespuesta = new GenRespuestaFacturaElectronica();
                $arRespuesta->setTipoDocumento($arrReturn['tipoDocumento']);
                if(!is_array($arrReturn['prefijo'])) {
                    $arRespuesta->setPrefijo($arrReturn['prefijo']);
                }
                $arRespuesta->setConsecutivo($arrReturn['consecutivo']);
                if(isset($arrReturn['cufe'])) {
                    $arRespuesta->setCufe($arrReturn['cufe']);
                }
                if(isset($arrReturn['codigoQr'])) {
                    $arRespuesta->setCodigoQR($arrReturn['codigoQr']);
                }
                if(isset($arrReturn['fechaExpedicion'])) {
                    $arRespuesta->setFechaExpedicion(date_create($arrReturn['fechaExpedicion']));
                }
                if(isset($arrReturn['fechaRespuesta'])) {
                    $arRespuesta->setFechaRespuesta(date_create($arrReturn['fechaRespuesta']));
                }
                $arRespuesta->setEstadoProceso($arrReturn['estadoProceso']);
                $arRespuesta->setDescripcionProceso($arrReturn['descripcionProceso']);
                if(isset($arrReturn['listaMensajesProceso'])) {
                    $arRespuesta->setListaMensajesProceso(json_encode($arrReturn['listaMensajesProceso']));
                }
                $em->persist($arRespuesta);
                $em->flush();
            }
        }catch (\SoapFault $e){
            echo $e->getMessage();
        }

    }

    private function generarXmlDispapeles($arrFactura) {
        $xml="<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:wsen='http://wsenviardocumento.webservice.dispapeles.com/'>
<soapenv:Header/>
<soapenv:Body>
  <wsen:enviarDocumento>
<!--**************************************************ENCABEZADO********************************************************-->
     <felCabezaDocumento>
        <usuario>EmpC0tr45c4l</usuario>
        <contrasenia>PwC0tr45c4l</contrasenia>
        <idEmpresa>892</idEmpresa>
        <token>9930ee169f99498ed4e036e9eb2812dfc8f1b39d</token>
        <prefijo>{$arrFactura['prefijo']}</prefijo>
        <consecutivo>{$arrFactura['consecutivo']}</consecutivo>
        <fechafacturacion>{$arrFactura['fechaFacturacion']}</fechafacturacion>
        <tipodocumento>1</tipodocumento>
        <codigoPlantillaPdf>1</codigoPlantillaPdf>
        <aplicafel>SI</aplicafel>
        <cantidadLineas>1</cantidadLineas>
        <centroCostos>Compras</centroCostos>
        <codigovendedor>0</codigovendedor>
        <descripcionCentroCostos>departamento que genera costos para la organización</descripcionCentroCostos>      
        <idErp></idErp>
        <incoterm></incoterm>
        <sucursal>PRINCIPAL</sucursal>
        <tipoOperacion>05</tipoOperacion>
        <version>4</version>
        <nombrevendedor>Alejandro Cruz</nombrevendedor>

<!--*********************************************ADQUIRENTES(ENCABEZADO)************************************************-->
        <listaAdquirentes>
           <tipoIdentificacion>{$arrFactura['ad_tipoIdentificacion']}</tipoIdentificacion>
           <tipoPersona>{$arrFactura['ad_tipoPersona']}</tipoPersona>
           <regimen>{$arrFactura['ad_regimen']}</regimen>
           <barioLocalidad>Suba</barioLocalidad>
           <ciudad>11001</ciudad>
           <codigoCIUU></codigoCIUU>
           <codigoPostal>110111</codigoPostal>
           <departamento>11</departamento>
           <descripcionCiudad>Bogotá, D.c.</descripcionCiudad>
           <digitoverificacion>5</digitoverificacion>
           <direccion>Cra 79a #78 - 55</direccion>
           <email>laura.cucanchon@dispapeles.com</email>
           <envioPorEmailPlataforma>Email</envioPorEmailPlataforma>
           <matriculaMercantil>243122</matriculaMercantil>
           <nitProveedorTecnologico>860028580</nitProveedorTecnologico>
           <nombreCompleto>Juan Camilo Castillo Hernandez</nombreCompleto>
           <nombredepartamento>Bogotá</nombredepartamento>
           <numeroIdentificacion>901127068</numeroIdentificacion>
           <pais>CO</pais>
           <paisnombre>Colombia</paisnombre>
           
           <telefono>5553906</telefono>                      
           <tipoobligacion>O-13</tipoobligacion>
        </listaAdquirentes>

<!--*****************************************CAMPOS ADICIONALES (ENCABEZADO)********************************************-->
        <listaCamposAdicionales>
           <fecha>2019-07-19T19:36:42</fecha>
           <nombreCampo>Campo</nombreCampo>
           <orden>1</orden>
           <seccion>1</seccion>
           <valorCampo>Valor campo</valorCampo>
        </listaCamposAdicionales>

<!--***********************************************DATOS ENTREGA (ENCABEZADO)*******************************************-->
        <listaDatosEntrega>
           <cantidad>15</cantidad>
           <cantidadMaxima>30</cantidadMaxima>
           <cantidadMinima>1</cantidadMinima>
           <ciudadEntrega>Medellín</ciudadEntrega>
           <descripcion>Paquete</descripcion>
           <direccionEntrega>Diag. 15 # 45</direccionEntrega>
           <empresaTransportista>Servientrega</empresaTransportista>
           <identificacionTransportista>1055606987</identificacionTransportista>
           <identificadorTransporte>PCC125</identificadorTransporte>
           <lugarEntrega>Casa</lugarEntrega>
           <nitEmpresaTransportista>860512330</nitEmpresaTransportista>
           <nombreTransportista>Sebastian Bernal</nombreTransportista>
           <paisEntrega>CO</paisEntrega>
           <periodoEntregaEstimado>2019-10-31</periodoEntregaEstimado>
           <periodoEntregaPrometido>2019-10-31</periodoEntregaPrometido>
           <periodoEntregaSolicitado>2019-10-31</periodoEntregaSolicitado>
           <telefonoEntrega>5557895</telefonoEntrega>
           <!-- <tiempoRealEntrega></tiempoRealEntrega> -->
           <tipoIdentificacionEmpresaTransportista>31</tipoIdentificacionEmpresaTransportista>
           <tipoidentificacionTransportista>31</tipoidentificacionTransportista>
           <ultimaFechaEntrega>2019-07-19</ultimaFechaEntrega>
           <dVIdentificaciontransportista>5</dVIdentificaciontransportista>
        </listaDatosEntrega>

<!--*********************************************IMPUESTOS(ENCABEZADO)**************************************************-->
        <listaImpuestos>
           <baseimponible>100000</baseimponible>
           <codigoImpuestoRetencion>01</codigoImpuestoRetencion>
           <isAutoRetenido>false</isAutoRetenido>
           <porcentaje>19</porcentaje>
           <valorImpuestoRetencion>19000</valorImpuestoRetencion>
        </listaImpuestos>
<!--*********************************************MEDIOS PAGO (ENCABEZADO)***********************************************-->
        <listaMediosPagos>
           <medioPago>10</medioPago>
        </listaMediosPagos>
<!--*****************************************ORDENES COMPRA(ENCABEZADO)*************************************************-->
        <listaOrdenesCompras>
           <fechaemisionordencompra>2019-09-03</fechaemisionordencompra>
           <numeroaceptacioninterno>452222</numeroaceptacioninterno>
           <ordencompra>OC122</ordencompra>
        </listaOrdenesCompras>
<!--**************************************************PAGO(ENCABEZADO)**************************************************-->
        <pago>
          <!--  <codigoMonedaCambio>?</codigoMonedaCambio>
           <fechaTasaCambio>?</fechaTasaCambio>-->
           <fechavencimiento>2019-09-28</fechavencimiento> 
           <moneda>COP</moneda>
           <pagoanticipado>0</pagoanticipado>
           <periododepagoa>2</periododepagoa>
           <tipocompra>2</tipocompra>
           <totalCargos>0</totalCargos>
           <totalDescuento>0</totalDescuento>
           <totalbaseconimpuestos>119000</totalbaseconimpuestos>
           <totalbaseimponible>100000</totalbaseimponible>
           <totalfactura>119000</totalfactura>
           <totalimportebruto>100000</totalimportebruto>
           <!-- <trm>?</trm>
           <trm_alterna>?</trm_alterna> -->
        </pago>

<!--**************************************************INICIO DETALLE 1**********************************************-->
<!--**************************************************DETALLE(ENCABEZADO)**********************************************-->
        <listaDetalle>
           <aplicaMandato>No</aplicaMandato>
           <campoAdicional1></campoAdicional1>
           <campoAdicional2></campoAdicional2>
           <campoAdicional3></campoAdicional3>
           <campoAdicional4></campoAdicional4>
           <campoAdicional5></campoAdicional5> 
           <cantidad>10</cantidad>
           <codigoproducto>E770315300</codigoproducto>
           <descripcion>Producto 1</descripcion>
           <descripciones></descripciones>
           <familia></familia>
           <fechaSuscripcionContrato>2019-10-31</fechaSuscripcionContrato>
           <gramaje></gramaje>
           <grupo></grupo>
           <marca></marca>
           <modelo></modelo>
           <muestracomercial></muestracomercial>
           <muestracomercialcodigo></muestracomercialcodigo> 
           <nombreProducto>MINIBLOCK ANOTACIONES CUADRICULADO 50 HOJAS</nombreProducto>
           <posicion>1</posicion>
           <preciosinimpuestos>100000</preciosinimpuestos>
           <preciototal>119000</preciototal>
           <referencia>REFBLK50</referencia>
           <seriales></seriales>
           <tamanio>445454</tamanio> 
           <tipoImpuesto>1</tipoImpuesto>
           <tipocodigoproducto>010</tipocodigoproducto>
           <unidadmedida>94</unidadmedida>
           <valorunitario>10000</valorunitario> 

        <!--*********************************************IMPUESTOS(DETALLE)**************************************************-->
           <listaImpuestos>
            <baseimponible>100000</baseimponible>
            <codigoImpuestoRetencion>01</codigoImpuestoRetencion>
            <isAutoRetenido>false</isAutoRetenido>
            <porcentaje>19</porcentaje>
            <valorImpuestoRetencion>19000</valorImpuestoRetencion>
            </listaImpuestos>
            
        </listaDetalle>  <!--*******FIN DETALLE***************-->

     </felCabezaDocumento> <!--*******FIN ENCABEZADO***************-->
  </wsen:enviarDocumento>
</soapenv:Body>
</soapenv:Envelope>";
        return $xml;
    }

    function mungXML($xml)
    {
        $obj = SimpleXML_Load_String($xml);
        if ($obj === FALSE) return $xml;

        // GET NAMESPACES, IF ANY
        $nss = $obj->getNamespaces(TRUE);
        if (empty($nss)) return $xml;

        // CHANGE ns: INTO ns_
        $nsm = array_keys($nss);
        foreach ($nsm as $key)
        {
            // A REGULAR EXPRESSION TO MUNG THE XML
            $rgx
                = '#'               // REGEX DELIMITER
                . '('               // GROUP PATTERN 1
                . '\<'              // LOCATE A LEFT WICKET
                . '/?'              // MAYBE FOLLOWED BY A SLASH
                . preg_quote($key)  // THE NAMESPACE
                . ')'               // END GROUP PATTERN
                . '('               // GROUP PATTERN 2
                . ':{1}'            // A COLON (EXACTLY ONE)
                . ')'               // END GROUP PATTERN
                . '#'               // REGEX DELIMITER
            ;
            // INSERT THE UNDERSCORE INTO THE TAG NAME
            $rep
                = '$1'          // BACKREFERENCE TO GROUP 1
                . '_'           // LITERAL UNDERSCORE IN PLACE OF GROUP 2
            ;
            // PERFORM THE REPLACEMENT
            $xml =  preg_replace($rgx, $rep, $xml);
        }
        return $xml;
    }
}