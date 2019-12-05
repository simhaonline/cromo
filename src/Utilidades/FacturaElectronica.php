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

    public function enviarDispapeles($arrFactura){
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
                $arRespuesta->setFecha(new \DateTime('now'));
                $arRespuesta->setCodigoModeloFk('InvMovimiento');
                $arRespuesta->setCodigoDocumento($arrFactura['doc_codigo']);
                if(isset($arrReturn['listaMensajesProceso'])) {
                    $arrDato = [];
                    foreach ($arrReturn['listaMensajesProceso'] as $arrMensaje) {
                        $arrDato[] = $arrMensaje['descripcionMensaje'];
                    }
                    $arRespuesta->setErrorReason(json_encode($arrDato));
                }
                $em->persist($arRespuesta);
                $em->flush();
            }
        }catch (\SoapFault $e){
            echo $e->getMessage();
        }

        $respuesta['estado'] = "ER";
        return $respuesta;
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
        <prefijo>{$arrFactura['res_prefijo']}</prefijo>
        <consecutivo>{$arrFactura['doc_numero']}</consecutivo>
        <fechafacturacion>{$arrFactura['doc_fecha']}</fechafacturacion>
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
           <numeroIdentificacion>{$arrFactura['ad_numeroIdentificacion']}</numeroIdentificacion>
           <digitoverificacion>{$arrFactura['ad_digitoVerificacion']}</digitoverificacion>
           <nombreCompleto>{$arrFactura['ad_nombreCompleto']}</nombreCompleto>
           <tipoPersona>{$arrFactura['ad_tipoPersona']}</tipoPersona>
           <regimen>{$arrFactura['ad_regimen']}</regimen>
           <tipoobligacion>{$arrFactura['ad_responsabilidadFiscal']}</tipoobligacion>
                    
           <direccion>{$arrFactura['ad_direccion']}</direccion>
           <barioLocalidad>{$arrFactura['ad_barrio']}</barioLocalidad>
           <ciudad>11001</ciudad>
           <descripcionCiudad>Bogotá, D.c.</descripcionCiudad>
           <departamento>11</departamento>
           <nombredepartamento>Bogotá</nombredepartamento>
           <pais>CO</pais>
           <paisnombre>Colombia</paisnombre>
           <codigoPostal>{$arrFactura['ad_codigoPostal']}</codigoPostal>
           <codigoCIUU>{$arrFactura['ad_codigoCIUU']}</codigoCIUU>                    
           <telefono>{$arrFactura['ad_telefono']}</telefono> 
           
           <envioPorEmailPlataforma>Email</envioPorEmailPlataforma>
           <email>laura.cucanchon@dispapeles.com</email>           
           <matriculaMercantil>243122</matriculaMercantil>
           <nitProveedorTecnologico>860028580</nitProveedorTecnologico>                                                      
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
           <fechavencimiento>{$arrFactura['doc_fecha_vence']}</fechavencimiento> 
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

    public function enviarCadena($arrFactura){
        $em = $this->em;
        $procesoFacturaElectronica = ['estado' => 'NO'];
        $xml = $this->generarXmlCadena($arrFactura);
        $url = "https://api.efacturacadena.com/staging/vp-hab/documentos/proceso/alianzas";
        $datos = base64_encode($xml);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "\"" . $datos . "\"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: text/plain',
                'efacturaAuthorizationToken: 12345'
            )
        );

        $resp = json_decode(curl_exec($ch), true);
        if($resp) {
            if(isset($resp['message'])) {
                if($resp['message'] == 'Endpoint request timed out') {
                    Mensajes::error("Endpoint request timed out");
                    $procesoFacturaElectronica['estado'] = 'CN';
                }
            }
            if(isset($resp['statusCode'])) {
                $procesoFacturaElectronica['estado'] = 'ER';
                $arRespuesta = new GenRespuestaFacturaElectronica();
                $arRespuesta->setFecha(new \DateTime('now'));
                $arRespuesta->setCodigoModeloFk('InvMovimiento');
                $arRespuesta->setCodigoDocumento($arrFactura['doc_codigo']);
                $arRespuesta->setStatusCode($resp['statusCode']);
                if(isset($resp['errorMessage'])) {
                    $arRespuesta->setErrorMessage($resp['errorMessage']);
                }
                if(isset($resp['errorReason'])) {
                    if(is_array($resp['errorReason'])) {
                        $arRespuesta->setErrorReason(json_encode($resp['errorReason']));
                    } else {
                        $arRespuesta->setErrorReason("['Regla:" . $resp['errorReason'] . "']");
                    }
                }
                $em->persist($arRespuesta);
                $em->flush();
            }
        }
        curl_close($ch);
        return $procesoFacturaElectronica;
    }

    private function generarXmlCadena($arrFactura) {
        $em = $this->em;
        $numero = $arrFactura['res_prefijo'] . $arrFactura['doc_numero'];
        $cufe = $numero.$arrFactura['doc_fecha'].$arrFactura['doc_hora'].$arrFactura['doc_subtotal'].'01'.$arrFactura['doc_iva'].'04'.$arrFactura['doc_inc'].'03'.$arrFactura['doc_ica'].$arrFactura['doc_total'].$arrFactura['dat_nitFacturador'].$arrFactura['ad_numeroIdentificacion'].$arrFactura['dat_claveTecnicaCadena'].$arrFactura['dat_tipoAmbiente'];
        $cufeHash = hash('sha384', $cufe);
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('	');
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement("Invoice");
            $xml->writeAttribute('xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
            $xml->writeAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $xml->writeAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $xml->writeAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $xml->writeAttribute('xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
            $xml->writeAttribute('xmlns:sts', 'dian:gov:co:facturaelectronica:Structures-2-1');
            $xml->writeAttribute('xmlns:xades', 'http://uri.etsi.org/01903/v1.3.2#');
            $xml->writeAttribute('xmlns:xades141', 'http://uri.etsi.org/01903/v1.4.1#');
            $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $xml->writeAttribute('xsi:schemaLocation', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $xml->startElement('ext:UBLExtensions');
                $xml->startElement('ext:UBLExtension');
                    $xml->startElement('ext:ExtensionContent');
                        $xml->startElement('sts:DianExtensions');
                            $xml->startElement('sts:InvoiceControl');
                                $xml->writeElement('sts:InvoiceAuthorization', $arrFactura['res_numero']);
                                $xml->startElement('sts:AuthorizationPeriod');
                                    $xml->writeElement('cbc:StartDate', $arrFactura['res_fechaDesde']);
                                    $xml->writeElement('cbc:EndDate', $arrFactura['res_fechaHasta']);
                                $xml->endElement();
                                $xml->startElement('sts:AuthorizedInvoices');
                                    $xml->writeElement('sts:Prefix', $arrFactura['res_prefijo']);
                                    $xml->writeElement('sts:From', $arrFactura['res_desde']);
                                    $xml->writeElement('sts:To', $arrFactura['res_hasta']);
                                $xml->endElement();
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
            $xml->writeElement('cbc:CustomizationID', '05');
            $xml->writeElement('cbc:ProfileExecutionID', '2');
            $xml->writeElement('cbc:ID', $numero);
            $xml->startElement('cbc:UUID');
                $xml->writeAttribute('schemeID', '2');
                $xml->writeAttribute('schemeName', 'CUFE-SHA384');
                $xml->text($cufeHash);
            $xml->endElement();
            $xml->writeElement('cbc:IssueDate', $arrFactura['doc_fecha']);
            $xml->writeElement('cbc:IssueTime', $arrFactura['doc_hora']);
            $xml->writeElement('cbc:InvoiceTypeCode', '01');
            $xml->writeElement('cbc:Note', $cufe);
            $xml->writeElement('cbc:DocumentCurrencyCode', 'COP');
            $xml->writeElement('cbc:LineCountNumeric', $arrFactura['doc_cantidad_item']);
            $xml->startElement('cac:AccountingSupplierParty');
                $xml->writeElement('cbc:AdditionalAccountID', $arrFactura['em_tipoPersona']);
                $xml->startElement('cac:Party');
                    $xml->startElement('cac:PartyName');
                        $xml->writeElement('cbc:Name', $arrFactura['em_nombreCompleto']);
                    $xml->endElement();
                    $xml->startElement('cac:PhysicalLocation');
                        $xml->startElement('cac:Address');
                            $xml->writeElement('cbc:ID', $arrFactura['em_codigoCiudad']);
                            $xml->writeElement('cbc:CityName', $arrFactura['em_nombreCiudad']);
                            $xml->writeElement('cbc:PostalZone', $arrFactura['em_codigoPostal']);
                            $xml->writeElement('cbc:CountrySubentity', $arrFactura['em_nombreDepartamento']);
                            $xml->writeElement('cbc:CountrySubentityCode', $arrFactura['em_codigoDepartamento']);
                            $xml->startElement('cac:AddressLine');
                                $xml->writeElement('cbc:Line', $arrFactura['em_direccion']);
                            $xml->endElement();
                            $xml->startElement('cac:Country');
                                $xml->writeElement('cbc:IdentificationCode', 'CO');
                                $xml->startElement('cbc:Name');
                                $xml->writeAttribute('languageID', 'es');
                                $xml->text('Colombia');
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
                $xml->startElement('cac:PartyTaxScheme');
                    $xml->writeElement('cbc:RegistrationName', $arrFactura['em_nombreCompleto']);
                    $xml->startElement('cbc:CompanyID');
                        $xml->writeAttribute('schemeID', $arrFactura['em_digitoVerificacion']);
                        $xml->writeAttribute('schemeName', '31');
                        $xml->writeAttribute('schemeAgencyID', '195');
                        $xml->writeAttribute('schemeAgencyName', 'CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)');
                        $xml->text($arrFactura['em_numeroIdentificacion']);
                    $xml->endElement();
                    $xml->startElement('cbc:TaxLevelCode');
                        $xml->writeAttribute('listName', '05');
                        $xml->text('O-99');
                    $xml->endElement();
                    $xml->startElement('cac:RegistrationAddress');
                        $xml->writeElement('cbc:ID', $arrFactura['em_codigoCiudad']);
                        $xml->writeElement('cbc:CityName', $arrFactura['em_nombreCiudad']);
                        $xml->writeElement('cbc:PostalZone', $arrFactura['em_codigoPostal']);
                        $xml->writeElement('cbc:CountrySubentity', $arrFactura['em_nombreDepartamento']);
                        $xml->writeElement('cbc:CountrySubentityCode', $arrFactura['em_codigoDepartamento']);
                        $xml->startElement('cac:AddressLine');
                            $xml->writeElement('cbc:Line', $arrFactura['em_direccion']);
                        $xml->endElement();
                        $xml->startElement('cac:Country');
                            $xml->writeElement('cbc:IdentificationCode', 'CO');
                            $xml->startElement('cbc:Name');
                            $xml->writeAttribute('languageID', 'es');
                            $xml->text('Colombia');
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
                $xml->startElement('cac:TaxScheme');
                    $xml->writeElement('cbc:ID', '01');
                    $xml->writeElement('cbc:Name', 'IVA');
                $xml->endElement();
            $xml->endElement();
            $xml->startElement('cac:PartyLegalEntity');
                $xml->writeElement('cbc:RegistrationName', $arrFactura['em_nombreCompleto']);
                $xml->startElement('cbc:CompanyID');
                    $xml->writeAttribute('schemeID','0');
                    $xml->writeAttribute('schemeName','31');
                    $xml->writeAttribute('schemeAgencyID','195');
                    $xml->writeAttribute('schemeAgencyName','CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)');
                    $xml->text($arrFactura['em_numeroIdentificacion']);
                $xml->endElement();
                $xml->startElement('cac:CorporateRegistrationScheme');
                    $xml->writeElement('cbc:ID', $arrFactura['res_prefijo']);
                    $xml->writeElement('cbc:Name', $arrFactura['em_matriculaMercantil']);
                $xml->endElement();
            $xml->endElement();
            $xml->startElement('cac:Contact');
                $xml->writeElement('cbc:ElectronicMail', $arrFactura['em_correo']);
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
            $xml->startElement('cac:AccountingCustomerParty');
                $xml->writeElement('cbc:AdditionalAccountID', $arrFactura['ad_tipoPersona']);
                $xml->startElement('cac:Party');
                    $xml->startElement('cac:PartyName');
                        $xml->writeElement('cbc:Name', $arrFactura['ad_nombreCompleto']);
                    $xml->endElement();
                    $xml->startElement('cac:PhysicalLocation');
                        $xml->startElement('cac:Address');
                            $xml->writeElement('cbc:ID', $arrFactura['ad_codigoCiudad']);
                            $xml->writeElement('cbc:CityName', $arrFactura['ad_nombreCiudad']);
                            $xml->writeElement('cbc:PostalZone', $arrFactura['ad_codigoPostal']);
                            $xml->writeElement('cbc:CountrySubentity', $arrFactura['ad_nombreDepartamento']);
                            $xml->writeElement('cbc:CountrySubentityCode', $arrFactura['ad_codigoDepartamento']);
                            $xml->startElement('cac:AddressLine');
                                $xml->writeElement('cbc:Line', $arrFactura['ad_direccion']);
                            $xml->endElement();
                            $xml->startElement('cac:Country');
                                $xml->writeElement('cbc:IdentificationCode', 'CO');
                                $xml->startElement('cbc:Name');
                                    $xml->writeAttribute('languageID', 'es');
                                    $xml->text('Colombia');
                                $xml->endElement();
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                    $xml->startElement('cac:PartyTaxScheme');
                        $xml->writeElement('cbc:RegistrationName', $arrFactura['ad_nombreCompleto']);
                        $xml->startElement('cbc:CompanyID');
                            $xml->writeAttribute('schemeID', '3');
                            $xml->writeAttribute('schemeName', '31');
                            $xml->writeAttribute('schemeAgencyID', '195');
                            $xml->writeAttribute('schemeAgencyName', 'CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)');
                            $xml->text($arrFactura['ad_numeroIdentificacion']);
                        $xml->endElement();
                        $xml->startElement('cbc:TaxLevelCode');
                            $xml->writeAttribute('listName', '05');
                            $xml->text('O-99');
                        $xml->endElement();
                        $xml->startElement('cac:RegistrationAddress');
                            $xml->writeElement('cbc:ID', $arrFactura['ad_codigoCiudad']);
                            $xml->writeElement('cbc:CityName', $arrFactura['ad_nombreCiudad']);
                            $xml->writeElement('cbc:PostalZone', $arrFactura['ad_codigoPostal']);
                            $xml->writeElement('cbc:CountrySubentity', $arrFactura['ad_nombreDepartamento']);
                            $xml->writeElement('cbc:CountrySubentityCode', $arrFactura['ad_codigoDepartamento']);
                            $xml->startElement('cac:AddressLine');
                                $xml->writeElement('cbc:Line', $arrFactura['ad_direccion']);
                            $xml->endElement();
                            $xml->startElement('cac:Country');
                                $xml->writeElement('cbc:IdentificationCode', 'CO');
                                $xml->startElement('cbc:Name');
                                    $xml->writeAttribute('languageID', 'es');
                                    $xml->text('Colombia');
                                $xml->endElement();
                            $xml->endElement();
                        $xml->endElement();
                        $xml->startElement('cac:TaxScheme');
                            $xml->writeElement('cbc:ID', '01');
                            $xml->writeElement('cbc:Name', 'IVA');
                        $xml->endElement();
                    $xml->endElement();
                $xml->startElement('cac:PartyLegalEntity');
                    $xml->writeElement('cbc:RegistrationName', $arrFactura['ad_nombreCompleto']);
                    $xml->startElement('cbc:CompanyID');
                        $xml->writeAttribute('schemeID', '3');
                        $xml->writeAttribute('schemeName', '31');
                        $xml->writeAttribute('schemeAgencyID', '195');
                        $xml->writeAttribute('schemeAgencyName', 'CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)');
                        $xml->text($arrFactura['ad_numeroIdentificacion']);
                    $xml->endElement();
                    $xml->startElement('cac:CorporateRegistrationScheme');
                        $xml->writeElement('cbc:Name', '1485596');
                    $xml->endElement();
                $xml->endElement();
                $xml->startElement('cac:Contact');
                    $xml->writeElement('cbc:ElectronicMail', $arrFactura['ad_correo']);
                $xml->endElement();
            $xml->endElement();
            $xml->endElement();
            $xml->startElement('cac:PaymentMeans');
                $xml->writeElement('cbc:ID', '1');
                $xml->writeElement('cbc:PaymentMeansCode', '10');
                $xml->writeElement('cbc:PaymentID', 'Efectivo');
            $xml->endElement();
            $xml->startElement('cac:TaxTotal');
                $xml->startElement('cbc:TaxAmount');
                    $xml->writeAttribute('currencyID', 'COP');
                    $xml->text($arrFactura['doc_iva']);
                $xml->endElement();
                $xml->startElement('cac:TaxSubtotal');
                    $xml->startElement('cbc:TaxableAmount');
                        $xml->writeAttribute('currencyID', 'COP');
                        $xml->text($arrFactura['doc_base_iva']);
                    $xml->endElement();
                    $xml->startElement('cbc:TaxAmount');
                        $xml->writeAttribute('currencyID', 'COP');
                        $xml->text($arrFactura['doc_iva']);
                    $xml->endElement();
                    $xml->startElement('cac:TaxCategory');
                        $xml->writeElement('cbc:Percent', '19.00');
                        $xml->startElement('cac:TaxScheme');
                            $xml->writeElement('cbc:ID', '01');
                            $xml->writeElement('cbc:Name', 'IVA');
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
            $xml->startElement('cac:LegalMonetaryTotal');
                $xml->startElement('cbc:LineExtensionAmount');
                    $xml->writeAttribute('currencyID', 'COP');
                    $xml->text($arrFactura['doc_subtotal']);
                $xml->endElement();
                $xml->startElement('cbc:TaxExclusiveAmount');
                    $xml->writeAttribute('currencyID', 'COP');
                    $xml->text($arrFactura['doc_base_iva']);
                $xml->endElement();
                $xml->startElement('cbc:TaxInclusiveAmount');
                    $xml->writeAttribute('currencyID', 'COP');
                    $xml->text($arrFactura['doc_total']);
                $xml->endElement();
                $xml->startElement('cbc:PayableAmount');
                    $xml->writeAttribute('currencyID', 'COP');
                    $xml->text($arrFactura['doc_total']);
                $xml->endElement();
            $xml->endElement();
            foreach ($arrFactura['doc_itemes'] as $item) {
                $xml->startElement('cac:InvoiceLine');
                    $xml->writeElement('cbc:ID', $item['item_id']);
                    $xml->writeElement('cbc:InvoicedQuantity', '1.00');
                    $xml->startElement('cbc:LineExtensionAmount');
                        $xml->writeAttribute('currencyID', 'COP');
                        $xml->text($item['item_subtotal']);
                    $xml->endElement();
                    $xml->startElement('cac:TaxTotal');
                        $xml->startElement('cbc:TaxAmount');
                            $xml->writeAttribute('currencyID','COP');
                            $xml->text($item['item_iva']);
                        $xml->endElement();
                        $xml->startElement('cac:TaxSubtotal');
                            $xml->startElement('cbc:TaxableAmount');
                                $xml->writeAttribute('currencyID', 'COP');
                                $xml->text($item['item_base_iva']);
                            $xml->endElement();
                            $xml->startElement('cbc:TaxAmount');
                                $xml->writeAttribute('currencyID', 'COP');
                                $xml->text($item['item_iva']);
                            $xml->endElement();
                            $xml->startElement('cac:TaxCategory');
                                $xml->writeElement('cbc:Percent', $item['item_porcentaje_iva']);
                                $xml->startElement('cac:TaxScheme');
                                    $xml->writeElement('cbc:ID', '01');
                                    $xml->writeElement('cbc:Name', 'IVA');
                                $xml->endElement();
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                    $xml->startElement('cac:Item');
                        $xml->writeElement('cbc:Description', $item['item_nombre']);
                        $xml->startElement('cac:StandardItemIdentification');
                            $xml->startElement('cbc:ID');
                                $xml->writeAttribute('schemeID', '999');
                                $xml->text($item['item_codigo']);
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                    $xml->startElement('cac:Price');
                        $xml->startElement('cbc:PriceAmount');
                            $xml->writeAttribute('currencyID', 'COP');
                            $xml->text($item['item_precio']);
                        $xml->endElement();
                        $xml->startElement('cbc:BaseQuantity');
                            $xml->writeAttribute('unitCode', 'EA');
                            $xml->text($item['item_cantidad']);
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            }
            $xml->startElement('DATA');
                $xml->writeElement('UBL21', 'true');
                $xml->startElement('Partnership');
                    $xml->writeElement('ID', '901192048');
                    $xml->writeElement('TechKey', 'fc8eac422eba16e22ffd8c6f94b3f40a6e38162c');
                    $xml->writeElement('SetTestID', '82e4944b-1134-4e25-9e9e-4fdd115e70ef');
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();
        $content = $xml->outputMemory();
        return $content;
    }

    public function validarDatos($arrFactura) {
        $arrRespuesta = ['estado' => 'error', 'mensaje' => null];
        if($arrFactura['ad_digitoVerificacion']) {
            if($arrFactura['ad_tipoPersona']) {
                $arrRespuesta = ['estado' => 'ok', 'mensaje' => null];
            } else {
                $arrRespuesta = ['estado' => 'error', 'mensaje' => 'El adquiriente no tiene tipo de persona'];
            }
        } else {
            $arrRespuesta = ['estado' => 'error', 'mensaje' => 'El adquiriente no tiene digito de verificacion'];
        }
        return $arrRespuesta;
    }
}