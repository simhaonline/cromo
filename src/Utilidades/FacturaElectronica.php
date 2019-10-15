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

    public function enviarCadena($arrFactura){
        $em = $this->em;
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
            $arRespuesta = new GenRespuestaFacturaElectronica();
            $arRespuesta->setStatusCode($resp['statusCode']);
            $arRespuesta->setErrorMessage($resp['errorMessage']);
            $arRespuesta->setErrorReason($resp['errorReason']);
            $em->persist($arRespuesta);
            $em->flush();
        }
        curl_close($ch);
    }

    private function generarXmlCadena($arrFactura) {
        $em = $this->em;
        $cufe = $arrFactura['doc_numero'].$arrFactura['doc_fecha'].$arrFactura['doc_hora'].$arrFactura['doc_subtotal'].'01'.$arrFactura['doc_iva'].'04'.$arrFactura['doc_inc'].'03'.$arrFactura['doc_ica'].$arrFactura['doc_total'].$arrFactura['dat_nitFacturador'].$arrFactura['ad_numeroIdentificacion'].$arrFactura['dat_claveTecnica'].$arrFactura['dat_tipoAmbiente'];
        $cufeHash = hash('sha384', $cufe);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Invoice xmlns:ds='http://www.w3.org/2000/09/xmldsig#' xmlns='urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' xmlns:cac='urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' xmlns:cbc='urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' xmlns:ext='urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' xmlns:sts='dian:gov:co:facturaelectronica:Structures-2-1' xmlns:xades='http://uri.etsi.org/01903/v1.3.2#' xmlns:xades141='http://uri.etsi.org/01903/v1.4.1#' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='urn:oasis:names:specification:ubl:schema:xsd:Invoice-2'>
	<ext:UBLExtensions>
		<ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
					<sts:InvoiceControl>
						<sts:InvoiceAuthorization>{$arrFactura['res_numero']}</sts:InvoiceAuthorization>
						<sts:AuthorizationPeriod>
							<cbc:StartDate>{$arrFactura['res_fechaDesde']}</cbc:StartDate>
							<cbc:EndDate>{$arrFactura['res_fechaHasta']}</cbc:EndDate>
						</sts:AuthorizationPeriod>
						<sts:AuthorizedInvoices>
							<sts:Prefix>{$arrFactura['res_prefijo']}</sts:Prefix>
							<sts:From>{$arrFactura['res_desde']}</sts:From>
							<sts:To>{$arrFactura['res_hasta']}</sts:To>
						</sts:AuthorizedInvoices>
                   </sts:InvoiceControl>
				</sts:DianExtensions>
          </ext:ExtensionContent>
        </ext:UBLExtension>
	</ext:UBLExtensions>	
	<cbc:CustomizationID>05</cbc:CustomizationID>
	<cbc:ProfileExecutionID>2</cbc:ProfileExecutionID>
	<cbc:ID>{$arrFactura['doc_numero']}</cbc:ID>
	<cbc:UUID schemeID='2' schemeName='CUFE-SHA384'>{$cufeHash}</cbc:UUID>
	<cbc:IssueDate>{$arrFactura['doc_fecha']}</cbc:IssueDate>
	<cbc:IssueTime>{$arrFactura['doc_hora']}</cbc:IssueTime>
	<cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
	<cbc:Note>Prueba Factura Electronica Datos Reales 2</cbc:Note>
	<cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
	<cbc:LineCountNumeric>1</cbc:LineCountNumeric>
	<cac:AccountingSupplierParty>
		<cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
		<cac:Party>
			<cac:PartyName>
				<cbc:Name>Cadena S.A.</cbc:Name>
			</cac:PartyName>
			<cac:PhysicalLocation>
				<cac:Address>
					<cbc:ID>05380</cbc:ID>
					<cbc:CityName>LA ESTRELLA</cbc:CityName>
					<cbc:PostalZone>055460</cbc:PostalZone>
					<cbc:CountrySubentity>Antioquia</cbc:CountrySubentity>
					<cbc:CountrySubentityCode>05</cbc:CountrySubentityCode>
					<cac:AddressLine>
						<cbc:Line>Cra. 50 #97a Sur-180 a 97a Sur-394</cbc:Line>
					</cac:AddressLine>
					<cac:Country>
						<cbc:IdentificationCode>CO</cbc:IdentificationCode>
						<cbc:Name languageID=\"es\">Colombia</cbc:Name>
					</cac:Country>
				</cac:Address>
			</cac:PhysicalLocation>
			<cac:PartyTaxScheme>
				<cbc:RegistrationName>Cadena S.A.</cbc:RegistrationName>
				<cbc:CompanyID schemeID=\"0\" schemeName=\"31\" schemeAgencyID=\"195\" schemeAgencyName=\"CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)\">890930534</cbc:CompanyID>
				<cbc:TaxLevelCode listName=\"05\">O-99</cbc:TaxLevelCode>
				<cac:RegistrationAddress>
					<cbc:ID>05380</cbc:ID>
					<cbc:CityName>LA ESTRELLA</cbc:CityName>
					<cbc:PostalZone>055468</cbc:PostalZone>
					<cbc:CountrySubentity>Antioquia</cbc:CountrySubentity>
					<cbc:CountrySubentityCode>05</cbc:CountrySubentityCode>
					<cac:AddressLine>
						<cbc:Line>Cra. 50 #97a Sur-180 a 97a Sur-394</cbc:Line>
					</cac:AddressLine>
					<cac:Country>
						<cbc:IdentificationCode>CO</cbc:IdentificationCode>
						<cbc:Name languageID=\"es\">Colombia</cbc:Name>
					</cac:Country>
				</cac:RegistrationAddress>
				<cac:TaxScheme>
					<cbc:ID>01</cbc:ID>
					<cbc:Name>IVA</cbc:Name>
				</cac:TaxScheme>
			</cac:PartyTaxScheme>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName>Cadena S.A.</cbc:RegistrationName>
				<cbc:CompanyID schemeID=\"0\" schemeName=\"31\" schemeAgencyID=\"195\" schemeAgencyName=\"CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)\">890930534</cbc:CompanyID>
				<cac:CorporateRegistrationScheme>
					<cbc:ID>SETP</cbc:ID>
					<cbc:Name>1485596</cbc:Name>
				</cac:CorporateRegistrationScheme>
			</cac:PartyLegalEntity>
			<cac:Contact>
				<cbc:ElectronicMail>leandro.ocampo@cadena.com.co</cbc:ElectronicMail>
			</cac:Contact>
		</cac:Party>
	</cac:AccountingSupplierParty>
	<cac:AccountingCustomerParty>
		<cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
		<cac:Party>
			<cac:PartyName>
				<cbc:Name>ADQUIRIENTE DE EJEMPLO</cbc:Name>
			</cac:PartyName>
			<cac:PhysicalLocation>
				<cac:Address>
					<cbc:ID>66001</cbc:ID>
					<cbc:CityName>PEREIRA</cbc:CityName>
					<cbc:PostalZone>54321</cbc:PostalZone>
					<cbc:CountrySubentity>Risaralda</cbc:CountrySubentity>
					<cbc:CountrySubentityCode>66</cbc:CountrySubentityCode>
					<cac:AddressLine>
						<cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
					</cac:AddressLine>
					<cac:Country>
						<cbc:IdentificationCode>CO</cbc:IdentificationCode>
						<cbc:Name languageID=\"es\">Colombia</cbc:Name>
					</cac:Country>
				</cac:Address>
			</cac:PhysicalLocation>
			<cac:PartyTaxScheme>
				<cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
				<cbc:CompanyID schemeID=\"3\" schemeName=\"31\" schemeAgencyID=\"195\" schemeAgencyName=\"CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)\">1017173008</cbc:CompanyID>
				<cbc:TaxLevelCode listName=\"05\">O-99</cbc:TaxLevelCode>
				<cac:RegistrationAddress>
					<cbc:ID>66001</cbc:ID>
					<cbc:CityName>PEREIRA</cbc:CityName>
					<cbc:PostalZone>54321</cbc:PostalZone>
					<cbc:CountrySubentity>Risaralda</cbc:CountrySubentity>
					<cbc:CountrySubentityCode>66</cbc:CountrySubentityCode>
					<cac:AddressLine>
						<cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
					</cac:AddressLine>
					<cac:Country>
						<cbc:IdentificationCode>CO</cbc:IdentificationCode>
						<cbc:Name languageID=\"es\">Colombia</cbc:Name>
					</cac:Country>
				</cac:RegistrationAddress>
				<cac:TaxScheme>
					<cbc:ID>01</cbc:ID>
					<cbc:Name>IVA</cbc:Name>
				</cac:TaxScheme>
			</cac:PartyTaxScheme>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
				<cbc:CompanyID schemeID=\"3\" schemeName=\"31\" schemeAgencyID=\"195\" schemeAgencyName=\"CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)\">1017173008</cbc:CompanyID>
				<cac:CorporateRegistrationScheme>
					<cbc:Name>1485596</cbc:Name>
				</cac:CorporateRegistrationScheme>
			</cac:PartyLegalEntity>
			<cac:Contact>
				<cbc:ElectronicMail>leandro.sys89@gmail.com</cbc:ElectronicMail>
			</cac:Contact>
		</cac:Party>
	</cac:AccountingCustomerParty>
	<cac:PaymentMeans>
		<cbc:ID>1</cbc:ID>
		<cbc:PaymentMeansCode>10</cbc:PaymentMeansCode>
		<cbc:PaymentID>Efectivo</cbc:PaymentID>
	</cac:PaymentMeans>
	<cac:TaxTotal>
		<cbc:TaxAmount currencyID=\"COP\">19000.00</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxableAmount currencyID=\"COP\">100000.00</cbc:TaxableAmount>
			<cbc:TaxAmount currencyID=\"COP\">19000.00</cbc:TaxAmount>
			<cac:TaxCategory>
				<cbc:Percent>19.00</cbc:Percent>
				<cac:TaxScheme>
					<cbc:ID>01</cbc:ID>
					<cbc:Name>IVA</cbc:Name>
				</cac:TaxScheme>
			</cac:TaxCategory>
		</cac:TaxSubtotal>
	</cac:TaxTotal>
	<cac:LegalMonetaryTotal>
		<cbc:LineExtensionAmount currencyID=\"COP\">100000.00</cbc:LineExtensionAmount>
		<cbc:TaxExclusiveAmount currencyID=\"COP\">100000.00</cbc:TaxExclusiveAmount>
		<cbc:TaxInclusiveAmount currencyID=\"COP\">119000.00</cbc:TaxInclusiveAmount>
		<cbc:PayableAmount currencyID=\"COP\">119000.00</cbc:PayableAmount>
	</cac:LegalMonetaryTotal>
	<cac:InvoiceLine>
		<cbc:ID>1</cbc:ID>
		<cbc:InvoicedQuantity>1.00</cbc:InvoicedQuantity>
		<cbc:LineExtensionAmount currencyID=\"COP\">100000.00</cbc:LineExtensionAmount>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID=\"COP\">19000.00</cbc:TaxAmount>			
			<cac:TaxSubtotal>
				<cbc:TaxableAmount currencyID=\"COP\">100000.00</cbc:TaxableAmount>
				<cbc:TaxAmount currencyID=\"COP\">19000.00</cbc:TaxAmount>
				<cac:TaxCategory>
					<cbc:Percent>19.00</cbc:Percent>
					<cac:TaxScheme>
						<cbc:ID>01</cbc:ID>
						<cbc:Name>IVA</cbc:Name>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
		<cac:Item>
			<cbc:Description>Frambuesas</cbc:Description>
			<cac:StandardItemIdentification>
				<cbc:ID schemeID=\"999\">03222314-7</cbc:ID>
			</cac:StandardItemIdentification>
		</cac:Item>
		<cac:Price>
			<cbc:PriceAmount currencyID=\"COP\">100000.00</cbc:PriceAmount>
			<cbc:BaseQuantity unitCode=\"EA\">1.00</cbc:BaseQuantity>
		</cac:Price>
	</cac:InvoiceLine>
	<DATA>
		<UBL21>true</UBL21>
		<Partnership>
			<ID>901192048</ID>
			<TechKey>fc8eac422eba16e22ffd8c6f94b3f40a6e38162c</TechKey>
			<SetTestID>b9ca446f-395e-44e2-847d-300e1a0f61fe</SetTestID>
		</Partnership>
	</DATA>
</Invoice>";
        return $xml;
    }
}