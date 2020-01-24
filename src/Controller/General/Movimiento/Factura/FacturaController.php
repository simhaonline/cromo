<?php

namespace App\Controller\General\Movimiento\Factura;

use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFactura;
use App\Entity\General\GenFacturaDetalle;
use App\Entity\Transporte\TteConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FacturaController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "GenFactura";


protected $clase = GenFactura::class;
    protected $claseNombre = "GenFactura";
    protected $modulo = "General";
    protected $funcion = "Movimiento";
    protected $grupo = "Factura";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general/movimiento/factura/lista", name="general_movimiento_factura_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arGenConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
        $arConfiguracion = $em->getRepository(TteConfiguracion::class)->find(1);
        $xml = new \DOMDocument("1.0", "UTF-8");
        $xml->xmlStandalone = false;

        $comprobantes = $xml->createElement('Comprobantes');
        $comprobantes->setAttribute("schemaLocation", "http://www.dian.gov.co/contratos/facturaelectronica/v1");
        $xml->appendChild($comprobantes);
        $comprobantes->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $form = $this->createFormBuilder()
            ->add('modulo', ChoiceType::class, [
                'choices' => ['INVENTARIO' => 'INV', 'TRANSPORTE' => 'TTE']
                , 'required' => false
                , 'empty_data' => ''
                , 'data' => $session->get('filtroGenFacturaModulo')
                , 'placeholder' => 'TODOS'])
            ->add('numero', IntegerType::class, ['required' => false,'data' => $session->get('filtroGenFacturaNumero')])
            ->add('tercero', TextType::class, ['required' => false,'data' => $session->get('filtroGenFacturaTercero')])
            ->add('identificacion', TextType::class, ['required' => false,'data' => $session->get('filtroGenFacturaIdentificacion')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExportar', SubmitType::class, ['label' => 'Exportar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroGenFacturaNumero',$form->get('numero')->getData());
                $session->set('filtroGenFacturaTercero',$form->get('tercero')->getData());
                $session->set('filtroGenFacturaIdentificacion',$form->get('identificacion')->getData());
                $session->set('filtroGenFacturaModulo',$form->get('modulo')->getData());
            }
            if ($form->get('btnExportar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoFactura) {
                    $arFactura = $em->getRepository(GenFactura::class)->find($codigoFactura);
                    if ($arFactura) {
                        $this->xml($arFactura, $arConfiguracion, $arGenConfiguracion, $xml, $comprobantes);
                    }
                }
            }
        }
        $arFacturas = $paginator->paginate($em->getRepository(GenFactura::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('general/movimiento/factura/lista.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $arFactura GenFactura
     * @param $arConfiguracion TteConfiguracion
     * @param $arGenConfiguracion GenConfiguracion
     * @param $xml \DOMDocument
     * @param $comprobantes \DOMElement
     */
    private function xml($arFactura, $arConfiguracion, $arGenConfiguracion, &$xml, &$comprobantes)
    {
        //---------Comprobante-----------------------------------------------------
        $comprobante = $xml->createElement("Comprobante");
        $comprobante = $comprobantes->appendChild($comprobante);
        // ------ Informacion del organismo-------------------------------------------------------
        $informacionOrganismo = $xml->createElement("informacionOrganismo");
        $informacionOrganismo = $comprobante->appendChild($informacionOrganismo);
        // Encabezado de la factura---------------------------------------------------------------
        if ($arFactura->getFacturaTipo() == 'FAC') {
            $factura = $xml->createElementNS("http://www.dian.gov.co/contratos/facturaelectronica/v1", "fe:Invoice");

        }
//        elseif ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
//            $factura = $xml->createElementNS("http://www.dian.gov.co/contratos/facturaelectronica/v1", "fe:CreditNote");
//
//        } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 3) {
//            $factura = $xml->createElementNS("http://www.dian.gov.co/contratos/facturaelectronica/v1", "fe:DebitNote");
//        }
        $factura = $informacionOrganismo->appendChild($factura);
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:clm54217', 'urn:un:unece:uncefact:codelist:specification:54217:2001');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:clm66411', 'urn:un:unece:uncefact:codelist:specification:66411:2001');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:clmIANAMIMEMediaType', 'urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:qdt', 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sts', 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:udt', 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2');
        $factura->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        //---------Extensions
        $UBLExtensions = $xml->createElement("ext:UBLExtensions");
        $UBLExtensions = $factura->appendChild($UBLExtensions);
        //---------Extension----------------------------------------------------------------------
        $UBLExtension = $xml->createElement("ext:UBLExtension");
        $UBLExtension = $UBLExtensions->appendChild($UBLExtension);
        //---------ExtensionContent---------------------------------------------------------------
        $ExtensionContent = $xml->createElement("ext:ExtensionContent");
        $ExtensionContent = $UBLExtension->appendChild($ExtensionContent);
        //---------DianExtensions-----------------------------------------------------------------
        $DianExtensions = $xml->createElement("sts:DianExtensions");
        $DianExtensions = $ExtensionContent->appendChild($DianExtensions);
        //---------InvoiceControl-----------------------------------------------------------------
        $InvoiceControl = $xml->createElement("sts:InvoiceControl");
        $InvoiceControl = $DianExtensions->appendChild($InvoiceControl);
        //---------InvoiceAuthorization  ////Resolución otorgada para la numeración---------------
        $InvoiceAuthorization = $xml->createElement("sts:InvoiceAuthorization", "{$arFactura->getNumeroResolucionDianFactura()}");
        $InvoiceControl->appendChild($InvoiceAuthorization);
        //---------AuthorizationPeriod  ////Resolución otorgada para la numeración----------------
        $AuthorizationPeriod = $xml->createElement("sts:AuthorizationPeriod");
        $AuthorizationPeriod = $InvoiceControl->appendChild($AuthorizationPeriod);
        //---------StartDate  ////Vigencia de la numeración desde---------------------------------
        $StartDate = $xml->createElement("cbc:StartDate", "{$arFactura->getFechaDesdeVigenciaResolucion()->format('Y-m-d')}");
        $AuthorizationPeriod->appendChild($StartDate);
        //---------EndDate  ////Vigencia de la numeración hasta-----------------------------------
        $EndDate = $xml->createElement("cbc:EndDate", "{$arFactura->getFechaHastaVigenciaResolucion()->format('Y-m-d')}");
        $AuthorizationPeriod->appendChild($EndDate);
        //---------AuthorizedInvoices-------------------------------------------------------------
        $AuthorizedInvoices = $xml->createElement("sts:AuthorizedInvoices");
        $AuthorizedInvoices = $InvoiceControl->appendChild($AuthorizedInvoices);
        //---------Prefix  ////Prefijo------------------------------------------------------------
        $Prefix = $xml->createElement("sts:Prefix", "{$arFactura->getPrefijo()}");
        $AuthorizedInvoices->appendChild($Prefix);
        //---------From  ////From Rango de Numeración (mínimo) otorgada---------------------------
        $From = $xml->createElement("sts:From", "{$arFactura->getNumeracionDesdeResolucion()}");
        $AuthorizedInvoices->appendChild($From);
        //---------To  ////To Rango de Numeración (maximo) otorgada-------------------------------
        $To = $xml->createElement("sts:To", "{$arFactura->getNumeracionHastaResolucion()}");
        $AuthorizedInvoices->appendChild($To);
        //---------InvoiceSource------------------------------------------------------------------
        $InvoiceSource = $xml->createElement("sts:InvoiceSource");
        $DianExtensions->appendChild($InvoiceSource);
        //---------IdentificationCode-------------------------------------------------------------
        $IdentificationCode = $xml->createElement("cbc:IdentificationCode", "CO");
        $IdentificationCode->setAttribute("listAgencyID", "6");
        $IdentificationCode->setAttribute("listAgencyName", "United Nations Economic Commission for Europe");
        $IdentificationCode->setAttribute("listSchemeURI", "urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode2.0");
        $InvoiceSource->appendChild($IdentificationCode);
        //---------SoftwareProvider----------------------------------------------------------------
        $SoftwareProvider = $xml->createElement("sts:SoftwareProvider");
        $SoftwareProvider = $DianExtensions->appendChild($SoftwareProvider);
        //---------ProviderID-----Prestador del Servicio-------------------------------------------
        $ProviderID = $xml->createElement("sts:ProviderID");
        $ProviderID->setAttribute("schemeAgencyID", "195");
        $ProviderID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $SoftwareProvider->appendChild($ProviderID);
        //---------SoftwareID-----Identificador del Software----------------------------------------
        $softwareID = $xml->createElement("sts:SoftwareID");
        $softwareID->setAttribute("schemeAgencyID", "195");
        $softwareID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $SoftwareProvider->appendChild($softwareID);

        //---------SoftwareSecurityCode----Código de Seguridad--------------------------------------
        $SoftwareSecurityCode = $xml->createElement("sts:SoftwareSecurityCode");
        $SoftwareSecurityCode->setAttribute("schemeAgencyID", "195");
        $SoftwareSecurityCode->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $DianExtensions->appendChild($SoftwareSecurityCode);


        //---------UBLVersionID---------------------------------------------------------------------
        $UBLVersionID = $xml->createElement("cbc:UBLVersionID", "UBL 2.0");
        $factura->appendChild($UBLVersionID);
//        if ($arFactura->getFacturaTipoRel()->getTipo() == 3) {
//            //---------CustomizationID---------------------------------------------------------------------
//            $CustomizationID = $xml->createElement("cbc:CustomizationID", "urn:oasis:names:specification:ubl:xpath:Order-2.0:sbs-1.0-draft");
//            $factura->appendChild($CustomizationID);
//        }
        //---------ProfileID------------------------------------------------------------------------
        $ProfileID = $xml->createElement("cbc:ProfileID", "DIAN 1.0");
        $factura->appendChild($ProfileID);
        //---------ID--------------------------------------------------------------------------------
        $ID = $xml->createElement("cbc:ID", $arFactura->getPrefijo() . "" . $arFactura->getNumero());
        $factura->appendChild($ID);
        //---------UUID------------------------------------------------------------------------------
        $UUID = $xml->createElement("cbc:UUID", "");
        $UUID->setAttribute("schemeAgencyID", "195");
        $UUID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $factura->appendChild($UUID);
        //---------IssueDate-------------------------------------------------------------------------
        $fechaActual = new \DateTime('now');
        $IssueDate = $xml->createElement("cbc:IssueDate", "{$fechaActual->format('Y-m-d')}");
        $factura->appendChild($IssueDate);
        //---------IssueTime--------------------------------------------------------------
        $IssueTime = $xml->createElement("cbc:IssueTime", "{$fechaActual->format('H:i:s')}");
        $factura->appendChild($IssueTime);
        //---------InvoiceTypeCode-----------------Tipo de comprobante-------------------- ///////////////////////////////////////////////////////Tabla 16 -  Tipos de Factura
        if ($arFactura->getFacturaTipo() == 'FAC') {
            $InvoiceTypeCode = $xml->createElement("cbc:InvoiceTypeCode", "1");
            $InvoiceTypeCode->setAttribute("listAgencyID", "195");
            $InvoiceTypeCode->setAttribute("listAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
            $InvoiceTypeCode->setAttribute("listSchemeURI", "http://www.dian.gov.co/contratos/facturaelectronica/v1/InvoiceType");
            $factura->appendChild($InvoiceTypeCode);
        }
        //Notas hasta 20 notas-----------------------------------------------------------------------
//        //---------Note------------------------------------------------------------------------------
//        $Note = $xml->createElement("cbc:Note", "{$arFactura->getDescripcion()}");
//        $factura->appendChild($Note);
        //---------Note1------------------------------------------------------------------------------
//        $Note1 = $xml->createElement("cbc:Note", $arFactura->getFechaSuspension() != null ? $arFactura->getFechaSuspension()->format("Y-m-d") : "");
//        $factura->appendChild($Note1);
        //---------Note2------------------------------------------------------------------------------
//        $Note2 = $xml->createElement("cbc:Note", $arFactura->getFechaCancelacion() != null ? $arFactura->getFechaCancelacion()->format("Y-m-d") : "");
//        $factura->appendChild($Note2);
        //---------Note3------------------------------------------------------------------------------
//        $Note3 = $xml->createElement("cbc:Note", "{$arFactura->getClienteRel()->getSectorRel()->getNombre()}");
//        $factura->appendChild($Note3);
        //---------Note4------------------------------------------------------------------------------
        $Note4 = $xml->createElement("cbc:Note", "{$arFactura->getComentario()}");
        $factura->appendChild($Note4);
        //---------Note5------------------------------------------------------------------------------
//        $Note5 = $xml->createElement("cbc:Note", "{$arConfiguracion->getInformacionPagoFactura()}");
//        $factura->appendChild($Note5);
        //---------Note6------------------------------------------------------------------------------
        $Note6 = $xml->createElement("cbc:Note", htmlspecialchars("{$arFactura->getTercero()}"));
        $factura->appendChild($Note6);
//        if ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
//            //---------Note7------------------------------------------------------------------------------
//            $Note7 = $xml->createElement("cbc:Note", "{{$arFactura->getFechaVence()->format('Y-m-d')}}");
//            $factura->appendChild($Note7);
//        }
        //---------DocumentCurrencyCode--------------------------------------------------------------
        $DocumentCurrencyCode = $xml->createElement("cbc:DocumentCurrencyCode", "COP");
        $factura->appendChild($DocumentCurrencyCode);
//        //---------AccountingCostCode
//        $AccountingCostCode = $xml->createElement("cac:AccountingCostCode");
//        $factura->appendChild($AccountingCostCode);
//        //---------AccountingCost
//        $AccountingCost = $xml->createElement("cac:AccountingCost");
//        $factura->appendChild($AccountingCost);
//        //---------LineCountNumeric
//        $LineCountNumeric = $xml->createElement("cac:LineCountNumeric");
//        $factura->appendChild($LineCountNumeric);
//        //---------InvoicePeriod--------------------------------------------------------------------
        $InvoicePeriod = $xml->createElement("cac:InvoicePeriod");
        $InvoicePeriod = $factura->appendChild($InvoicePeriod);
        //---------InvoicePeriodStartDate--------------------------------------------------------------------
        $InvoicePeriodStartDate = $xml->createElement("cbc:StartDate", "{$arFactura->getFecha()->format('Y-m-d')}");
        $InvoicePeriod->appendChild($InvoicePeriodStartDate);
        //---------InvoicePeriodEndDate--------------------------------------------------------------------
        $InvoicePeriodEndDate = $xml->createElement("cbc:EndDate", "{$arFactura->getFechaVence()->format('Y-m-d')}");
        $InvoicePeriod->appendChild($InvoicePeriodEndDate);
        //---------OrderReference--------------------------------------------------------------------
//        $OrderReference = $xml->createElement("cac:OrderReference");
//        $OrderReference = $factura->appendChild($OrderReference);
//        //---------ID--------------------------------------------------------------------------------
//        $OrderReferenceID = $xml->createElement("cbc:ID", "");
//        $OrderReference->appendChild($OrderReferenceID);
//        //---------OrderReferenceIssueDate--------------------------------------------------------------------------------
//        $OrderReferenceIssueDate = $xml->createElement("cbc:IssueDate", "");
//        $OrderReference->appendChild($OrderReferenceIssueDate);
//        //---------CustomerReference--------------------------------------------------------------------------------
//        $CustomerReference = $xml->createElement("cbc:CustomerReference", "");
//        $OrderReference->appendChild($CustomerReference);


        //---------AccountingSupplierParty------------------INFORMACION DEL OBLIGADO----------------------------------
        $AccountingSupplierParty = $xml->createElement("fe:AccountingSupplierParty");
        $AccountingSupplierParty = $factura->appendChild($AccountingSupplierParty);
        //---------AdditionalAccountID--------------------------------------------------------------------------------
        $AdditionalAccountID = $xml->createElement("cbc:AdditionalAccountID", "1"); /////Tipo persona 1	Persona Jurídica, 2	Perona Natural,
        $AdditionalAccountID->setAttribute("schemeAgencyID", "195");
        $AdditionalAccountID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $AccountingSupplierParty->appendChild($AdditionalAccountID);
        //---------Party
        $Party = $xml->createElement("fe:Party");
        $Party = $AccountingSupplierParty->appendChild($Party);
        //---------PartyIdentification
        $PartyIdentification = $xml->createElement("cac:PartyIdentification");
        $PartyIdentification = $Party->appendChild($PartyIdentification);
        //---------PartyIdentificationID
        $PartyIdentificationID = $xml->createElement("cbc:ID", "{$arGenConfiguracion->getNit()}");
        $PartyIdentificationID->setAttribute("schemeAgencyID", "195");
        $PartyIdentificationID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $PartyIdentificationID->setAttribute("schemeID", "31");
        $PartyIdentification->appendChild($PartyIdentificationID);
        //---------PartyName
        $PartyName = $xml->createElement("cac:PartyName");
        $PartyName = $Party->appendChild($PartyName);
        //---------PartyNameName
        $PartyNameName = $xml->createElement("cbc:Name", "{$arGenConfiguracion->getNombre()}");
        $PartyName->appendChild($PartyNameName);
        //---------PhysicalLocation
        $PhysicalLocation = $xml->createElement("fe:PhysicalLocation");
        $PhysicalLocation = $Party->appendChild($PhysicalLocation);
        //---------Address
        $Address = $xml->createElement("fe:Address");
        $Address = $PhysicalLocation->appendChild($Address);
        //---------CityName
        $CityName = $xml->createElement("cbc:CityName", "{$arGenConfiguracion->getCiudadRel()->getNombre()}");
        $Address->appendChild($CityName);
        //---------AddressLine
        $AddressLine = $xml->createElement("cac:AddressLine");
        $AddressLine = $Address->appendChild($AddressLine);
        //---------Line
        $Line = $xml->createElement("cbc:Line", "{$arGenConfiguracion->getDireccion()}");
        $AddressLine->appendChild($Line);
        //---------Country
        $Country = $xml->createElement("cac:Country");
        $Country = $Address->appendChild($Country);
        //---------IdentificationCode
        $IdentificationCode = $xml->createElement("cbc:IdentificationCode", "CO");
        $IdentificationCode->setAttribute("listAgencyID", "6");
        $IdentificationCode->setAttribute("listAgencyName", "United Nations Economic Commission for Europe");
        $IdentificationCode->setAttribute("listSchemeURI", "urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode2.0");
        $Country->appendChild($IdentificationCode);
        //---------PartyTaxScheme
        $PartyTaxScheme = $xml->createElement("fe:PartyTaxScheme");
        $PartyTaxScheme = $Party->appendChild($PartyTaxScheme);
        //---------TaxLevelCode
        $TaxLevelCode = $xml->createElement("cbc:TaxLevelCode", "2");///Regimen comun 0: SIMPLE 2: COMUN
        $PartyTaxScheme->appendChild($TaxLevelCode);
        //---------TaxScheme
        $TaxScheme = $xml->createElement("cac:TaxScheme");
        $PartyTaxScheme->appendChild($TaxScheme);
        //---------PartyLegalEntity
        $PartyLegalEntity = $xml->createElement("fe:PartyLegalEntity");
        $PartyLegalEntity = $Party->appendChild($PartyLegalEntity);
        //---------RegistrationName
        $RegistrationName = $xml->createElement("cbc:RegistrationName", "{$arGenConfiguracion->getSigla()}");///Regimen comun 0: SIMPLE 2: COMUN
        $PartyLegalEntity->appendChild($RegistrationName);
        //---------AccountingContact
        $AccountingContact = $xml->createElement("cac:AccountingContact");
        $AccountingContact = $AccountingSupplierParty->appendChild($AccountingContact);
        //---------Telephone
        $Telephone = $xml->createElement("cbc:Telephone", "{$arGenConfiguracion->getTelefono()}");
        $AccountingContact->appendChild($Telephone);


        //---------AccountingCustomerParty------------------INFORMACION DEL ADQUIRIENTE----------------------------------
        $AccountingCustomerParty = $xml->createElement("fe:AccountingCustomerParty");
        $AccountingCustomerParty = $factura->appendChild($AccountingCustomerParty);
        //---------CustomerAssignedAccountID
//        $CustomerAssignedAccountID = $xml->createElement("cbc:CustomerAssignedAccountID", "{$arFactura->getCodigoClienteFk()}");
//        $AccountingCustomerParty->appendChild($CustomerAssignedAccountID);
        //---------AdditionalAccountID
        $AdditionalAccountID = $xml->createElement("cbc:AdditionalAccountID", "{$arFactura->getClienteRel()->getOrigenJudicialRel()->getCodigoOrigenJudicialPk()}");
        $AdditionalAccountID->setAttribute("schemeAgencyID", "195");
        $AdditionalAccountID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $AccountingCustomerParty->appendChild($AdditionalAccountID);
        //---------PartyAccountingCustomerParty
        $PartyAccountingCustomerParty = $xml->createElement("fe:Party");
        $PartyAccountingCustomerParty = $AccountingCustomerParty->appendChild($PartyAccountingCustomerParty);
        //---------AccountingCustomerPartyIdentification
        $AccountingCustomerPartyIdentification = $xml->createElement("cac:PartyIdentification");
        $AccountingCustomerPartyIdentification = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPartyIdentification);
        //---------PartyAccountingCustomerIdentificationID
        $PartyAccountingCustomerIdentificationID = $xml->createElement("cbc:ID", "{$arFactura->getIdentificacion()}");
        $PartyAccountingCustomerIdentificationID->setAttribute("schemeAgencyName", "CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)");
        $PartyAccountingCustomerIdentificationID->setAttribute("schemeAgencyID", "195");
        $PartyAccountingCustomerIdentificationID->setAttribute("schemeID", "{$arFactura->getClienteRel()->getTipoIdentificacionRel()->getCodigoDian()}");
        $AccountingCustomerPartyIdentification->appendChild($PartyAccountingCustomerIdentificationID);
        //---------AccountingCustomerPartyName
        $AccountingCustomerPartyName = $xml->createElement("cac:PartyName");
        $AccountingCustomerPartyName = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPartyName);
        //---------AccountingCustomerPartyNameName
        $AccountingCustomerPartyNameName = $xml->createElement("cbc:Name", htmlspecialchars($arFactura->getClienteRel()->getCodigoOrigenJudicialFk() == 1 ? $arFactura->getClienteRel()->getNombreCompleto() : ""));
        $AccountingCustomerPartyName->appendChild($AccountingCustomerPartyNameName);
        //---------AccountingCustomerPhysicalLocation
        $AccountingCustomerPhysicalLocation = $xml->createElement("fe:PhysicalLocation");
        $AccountingCustomerPhysicalLocation = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPhysicalLocation);
        //---------AccountingCustomerAddress
        $AccountingCustomerAddress = $xml->createElement("fe:Address");
        $AccountingCustomerAddress = $AccountingCustomerPhysicalLocation->appendChild($AccountingCustomerAddress);
        //---------AccountingCustomerCityName
        $AccountingCustomerCityName = $xml->createElement("cbc:CityName", "{$arFactura->getCiudadFactura()}");
        $AccountingCustomerAddress->appendChild($AccountingCustomerCityName);
        //---------AccountingCustomerAddressLine
        $AccountingCustomerAddressLine = $xml->createElement("cac:AddressLine");
        $AccountingCustomerAddressLine = $AccountingCustomerAddress->appendChild($AccountingCustomerAddressLine);
        //---------AccountingCustomerLine
        $AccountingCustomerLine = $xml->createElement("cbc:Line", "{$arFactura->getDireccion()}");
        $AccountingCustomerAddressLine->appendChild($AccountingCustomerLine);
        //---------AccountingCustomerCountry
        $AccountingCustomerCountry = $xml->createElement("cac:Country");
        $AccountingCustomerCountry = $AccountingCustomerAddress->appendChild($AccountingCustomerCountry);
        //---------AccountingCustomerIdentificationCode
        $AccountingCustomerIdentificationCode = $xml->createElement("cbc:IdentificationCode", "CO");
        $AccountingCustomerIdentificationCode->setAttribute("listAgencyID", "6");
        $AccountingCustomerIdentificationCode->setAttribute("listAgencyName", "United Nations Economic Commission for Europe");
        $AccountingCustomerIdentificationCode->setAttribute("listSchemeURI", "urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode2.0");
        $AccountingCustomerCountry->appendChild($AccountingCustomerIdentificationCode);
        //---------AccountingCustomerPartyTaxScheme
        $AccountingCustomerPartyTaxScheme = $xml->createElement("fe:PartyTaxScheme");
        $AccountingCustomerPartyTaxScheme = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPartyTaxScheme);
        //---------AccountingCustomerPartyTaxLevelCode
        $AccountingCustomerPartyTaxLevelCode = $xml->createElement("cbc:TaxLevelCode", "2");///Regimen comun 0: SIMPLE 2: COMUN
        $AccountingCustomerPartyTaxScheme->appendChild($AccountingCustomerPartyTaxLevelCode);
        //---------AccountingCustomerTaxScheme
        $AccountingCustomerTaxScheme = $xml->createElement("cac:TaxScheme");
        $AccountingCustomerPartyTaxScheme->appendChild($AccountingCustomerTaxScheme);
        //---------PartyLegalEntity
        $AccountingPartyLegalEntity = $xml->createElement("fe:PartyLegalEntity");
        $AccountingPartyLegalEntity = $PartyAccountingCustomerParty->appendChild($AccountingPartyLegalEntity);
        //---------RegistrationName
        $AccountingRegistrationName = $xml->createElement("cbc:RegistrationName", htmlspecialchars($arFactura->getClienteRel()->getCodigoOrigenJudicialFk() == 1 ? $arFactura->getClienteRel()->getNombreCompleto() : ""));
        $AccountingPartyLegalEntity->appendChild($AccountingRegistrationName);
        if ($arFactura->getClienteRel()->getCodigoOrigenJudicialFk() == 2) { // Este tag aplica si el cliente es persona natural.
            //---------AccountingCustomerPartyContac
            $AccountingCustomerPartyContac = $xml->createElement("cac:Contact");
            $AccountingCustomerPartyContac = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPartyContac);
            //---------AccountingCustomerName
            $AccountingCustomerName = $xml->createElement("cbc:Name", "{$arFactura->getTercero()}");
            $AccountingCustomerPartyContac->appendChild($AccountingCustomerName);
            //---------AccountingCustomerTelephone
            $AccountingCustomerTelephone = $xml->createElement("cbc:Telephone", "{$arFactura->getTelefono()}");
            $AccountingCustomerPartyContac->appendChild($AccountingCustomerTelephone);

            //---------AccountingCustomerPartyPerson
            $AccountingCustomerPartyPerson = $xml->createElement("fe:Person");
            $AccountingCustomerPartyPerson = $PartyAccountingCustomerParty->appendChild($AccountingCustomerPartyPerson);
            //---------AccountingCustomerFirstName
            $AccountingCustomerFirstName = $xml->createElement("cbc:FirstName", "{$arFactura->getClienteRel()->getNombre1()}");
            $AccountingCustomerPartyPerson->appendChild($AccountingCustomerFirstName);
            //---------AccountingCustomerFamilyName
            $AccountingCustomerFamilyName = $xml->createElement("cbc:FamilyName", "{$arFactura->getClienteRel()->getApellido1()} {$arFactura->getClienteRel()->getApellido2()}");
            $AccountingCustomerPartyPerson->appendChild($AccountingCustomerFamilyName);
            //---------AccountingCustomerMiddleName
//            $AccountingCustomerMiddleName = $xml->createElement("cbc:MiddleName", "{$arFactura->getClienteRel()->getNombre2()}");
//            $AccountingCustomerPartyPerson->appendChild($AccountingCustomerMiddleName);
        }

        //---------AccountingCustomerAccountingContact
        $AccountingCustomerAccountingContact = $xml->createElement("cac:AccountingContact");
        $AccountingCustomerAccountingContact = $AccountingCustomerParty->appendChild($AccountingCustomerAccountingContact);
        //---------AccountingCustomerTelephone
        $AccountingCustomerTelephone = $xml->createElement("cbc:Telephone", "{$arFactura->getTelefono()}");
        $AccountingCustomerAccountingContact->appendChild($AccountingCustomerTelephone);


        if ($arFactura->getFacturaTipo() == 'FAC') {
            //---------Delivery------------------Datos de envio---------------------------------
            $Delivery = $xml->createElement("fe:Delivery");
            $Delivery = $factura->appendChild($Delivery);
            //---------DeliveryAddress------------------------------------------------------------
            $DeliveryAddress = $xml->createElement("fe:DeliveryAddress");
            $DeliveryAddress = $Delivery->appendChild($DeliveryAddress);
            //---------CityName------------------------------------------------------------
            $CityName = $xml->createElement("cbc:CityName", $arFactura->getClienteDireccionRel() != null ? $arFactura->getClienteDireccionRel()->getCiudadRel()->getNombre() : $arFactura->getClienteRel()->getCiudadRel()->getNombre());
            $DeliveryAddress->appendChild($CityName);
            //---------AddressLine------------------------------------------------------------
            $DeliveryAddressLine = $xml->createElement("cac:AddressLine");
            $DeliveryAddressLine = $DeliveryAddress->appendChild($DeliveryAddressLine);
            //---------Line------------------------------------------------------------
            $DeliveryLine = $xml->createElement("cbc:Line", $arFactura->getClienteDireccionRel() != null ? $arFactura->getClienteDireccionRel()->getDireccion() : $arFactura->getClienteRel()->getDireccion());
            $DeliveryAddressLine->appendChild($DeliveryLine);
            //---------Country------------------------------------------------------------
            $DeliveryCountry = $xml->createElement("cac:Country");
            $DeliveryCountry = $DeliveryAddress->appendChild($DeliveryCountry);
            //---------CountryIdentificationCode------------------------------------------------------------
            $CountryID = $xml->createElement("cbc:IdentificationCode", "CO");
            $DeliveryCountry->appendChild($CountryID);


            //---------PaymentMeans------------------Medio de pago---------------------------------
            $PaymentMeans = $xml->createElement("cac:PaymentMeans");
            $PaymentMeans = $factura->appendChild($PaymentMeans);
            //---------PaymentMeansCode------------------------------------------------------------
            $PaymentMeansCode = $xml->createElement("cbc:PaymentMeansCode", "41"); // Medios de pago 41: transferencia bancaria
            $PaymentMeans->appendChild($PaymentMeansCode);
            //---------PaymentDueDate----------------Fecha vence
            $PaymentDueDate = $xml->createElement("cbc:PaymentDueDate", "{$arFactura->getFechaVence()->format('Y-m-d')}"); // Medios de pago 41: transferencia bancaria
            $PaymentMeans->appendChild($PaymentDueDate);
            //---------InstructionNote----------------Plazo
            $InstructionNote = $xml->createElement("cbc:InstructionNote", "{$arFactura->getPlazoPago()}"); // Medios de pago 41: transferencia bancaria
            $PaymentMeans->appendChild($InstructionNote);

            //----------PaymentTerms-----------------condiciones de pago
            $PaymentTerms = $xml->createElement("cac:PaymentTerms");
            $PaymentTerms = $factura->appendChild($PaymentTerms);
            //---------PaymentTermsNote------------------------------------------------------------
            $PaymentTermsNote = $xml->createElement("cbc:Note", "{$arFactura->getClienteRel()->getFormaPagoRel()->getNombre()}"); // FORMA DE PAGO
            $PaymentTerms->appendChild($PaymentTermsNote);
        }

        //---------TaxTotal------------------Totales de Impuestos---------------------------------
        $TaxTotal = $xml->createElement("fe:TaxTotal");
        $TaxTotal = $factura->appendChild($TaxTotal);
        //---------TaxAmount------------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrIva()}"); // Valor del impuesto IVa
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxTotal->appendChild($TaxAmount);
        //---------TaxEvidenceIndicator-----------------------------------------------------------
        $TaxEvidenceIndicator = $xml->createElement("cbc:TaxEvidenceIndicator", "false"); // TRUE= Retencion - False = Impuesto
        $TaxTotal->appendChild($TaxEvidenceIndicator);
        //---------TaxSubtotal-----------------------------------------------------------
        $TaxSubtotal = $xml->createElement("fe:TaxSubtotal");
        $TaxSubtotal = $TaxTotal->appendChild($TaxSubtotal);
        //---------TaxableAmount-----------------------------------------------------------
        $TaxableAmount = $xml->createElement("cbc:TaxableAmount", "{$arFactura->getVrBaseAIU()}");
        $TaxableAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxableAmount);
        //---------TaxAmount-----------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrIva()}");
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxAmount);
        //---------Percent-----------------------------------------------------------
        $Percent = $xml->createElement("cbc:Percent", "{$arFactura->getFacturaServicioRel()->getPorcentajeIva()}");
        $TaxSubtotal->appendChild($Percent);
        //---------TaxCategory-----------------------------------------------------------
        $TaxCategory = $xml->createElement("cac:TaxCategory");
        $TaxCategory = $TaxSubtotal->appendChild($TaxCategory);
        //---------TaxScheme-----------------------------------------------------------
        $TaxScheme = $xml->createElement("cac:TaxScheme");
        $TaxScheme = $TaxCategory->appendChild($TaxScheme);
        //---------TaxSchemeID-----------------------------------------------------------
        $TaxSchemeID = $xml->createElement("cbc:ID", "01");
        $TaxScheme->appendChild($TaxSchemeID);


        // RETENCIONES
        // RETENCION FUENTE
        $TaxTotal = $xml->createElement("fe:TaxTotal");
        $TaxTotal = $factura->appendChild($TaxTotal);
        //---------TaxAmount------------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrRetencionFuente()}"); // Valor del Retencion en la fuente
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxTotal->appendChild($TaxAmount);
        //---------TaxEvidenceIndicator-----------------------------------------------------------
        $TaxEvidenceIndicator = $xml->createElement("cbc:TaxEvidenceIndicator", "true"); // TRUE= Retencion - False = Impuesto
        $TaxTotal->appendChild($TaxEvidenceIndicator);
        //---------TaxSubtotal-----------------------------------------------------------
        $TaxSubtotal = $xml->createElement("fe:TaxSubtotal");
        $TaxSubtotal = $TaxTotal->appendChild($TaxSubtotal);
        //---------TaxableAmount-----------------------------------------------------------
        $TaxableAmount = $xml->createElement("cbc:TaxableAmount", "{$arFactura->getVrBaseAIU()}");
        $TaxableAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxableAmount);
        //---------TaxAmount-----------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrIva()}");
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxAmount);
        //---------Percent-----------------------------------------------------------
        $Percent = $xml->createElement("cbc:Percent", "{$arFactura->getFacturaServicioRel()->getPorRetencionFuente()}");
        $TaxSubtotal->appendChild($Percent);
        //---------TaxCategory-----------------------------------------------------------
        $TaxCategory = $xml->createElement("cac:TaxCategory");
        $TaxCategory = $TaxSubtotal->appendChild($TaxCategory);
        //---------TaxScheme-----------------------------------------------------------
        $TaxScheme = $xml->createElement("cac:TaxScheme");
        $TaxScheme = $TaxCategory->appendChild($TaxScheme);
        //---------TaxSchemeID-----------------------------------------------------------
        $TaxSchemeID = $xml->createElement("cbc:ID", "05");
        $TaxScheme->appendChild($TaxSchemeID);

        ///////RETENCIONES
        /// RETENCION FUENTE POR IVA
        $TaxTotal = $xml->createElement("fe:TaxTotal");
        $TaxTotal = $factura->appendChild($TaxTotal);
        //---------TaxAmount------------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrRetencionIva()}"); // Valor del Retencion en la fuente del IVA
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxTotal->appendChild($TaxAmount);
        //---------TaxEvidenceIndicator-----------------------------------------------------------
        $TaxEvidenceIndicator = $xml->createElement("cbc:TaxEvidenceIndicator", "true"); // TRUE= Retencion - False = Impuesto
        $TaxTotal->appendChild($TaxEvidenceIndicator);
        //---------TaxSubtotal-----------------------------------------------------------
        $TaxSubtotal = $xml->createElement("fe:TaxSubtotal");
        $TaxSubtotal = $TaxTotal->appendChild($TaxSubtotal);
        //---------TaxableAmount-----------------------------------------------------------
        $TaxableAmount = $xml->createElement("cbc:TaxableAmount", "{$arFactura->getVrBaseAIU()}");
        $TaxableAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxableAmount);
        //---------TaxAmount-----------------------------------------------------------
        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFactura->getVrIva()}");
        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $TaxSubtotal->appendChild($TaxAmount);
        //---------Percent-----------------------------------------------------------
        $Percent = $xml->createElement("cbc:Percent", "15");
        $TaxSubtotal->appendChild($Percent);
        //---------TaxCategory-----------------------------------------------------------
        $TaxCategory = $xml->createElement("cac:TaxCategory");
        $TaxCategory = $TaxSubtotal->appendChild($TaxCategory);
        //---------TaxScheme-----------------------------------------------------------
        $TaxScheme = $xml->createElement("cac:TaxScheme");
        $TaxScheme = $TaxCategory->appendChild($TaxScheme);
        //---------TaxSchemeID-----------------------------------------------------------
        $TaxSchemeID = $xml->createElement("cbc:ID", "06");
        $TaxScheme->appendChild($TaxSchemeID);


        //----------LegalMonetaryTotal----------------------Totales Legales
        $LegalMonetaryTotal = $xml->createElement("fe:LegalMonetaryTotal");
        $LegalMonetaryTotal = $factura->appendChild($LegalMonetaryTotal);
        //---------LineExtensionAmount-----------------------------------------------------------
        $LineExtensionAmount = $xml->createElement("cbc:LineExtensionAmount", "{$arFactura->getVrSubtotal()}");
        $LineExtensionAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $LegalMonetaryTotal->appendChild($LineExtensionAmount);
        //---------TaxExclusiveAmount-----------------------------------------------------------
        $TaxExclusiveAmount = $xml->createElement("cbc:TaxExclusiveAmount", "{$arFactura->getVrIva()}");
        $TaxExclusiveAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $LegalMonetaryTotal->appendChild($TaxExclusiveAmount);
        //---------TaxInclusiveAmount-----------------------------------------------------------
        $TaxInclusiveAmount = $xml->createElement("cbc:TaxInclusiveAmount", "{$arFactura->getVrTotal()}");
        $TaxInclusiveAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $LegalMonetaryTotal->appendChild($TaxExclusiveAmount);
        //---------PayableAmount-----------------------------------------------------------
        $PayableAmount = $xml->createElement("cbc:PayableAmount", "{$arFactura->getVrNeto()}");
        $PayableAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
        $LegalMonetaryTotal->appendChild($PayableAmount);

        $arFacturasDetalles = $arFactura->getFacturasDetallesFacturaRel();
        if ($arFacturasDetalles) {
            $i = 1;
            /** @var $arFacturaDetalle GenFacturaDetalle */
            foreach ($arFacturasDetalles as $arFacturaDetalle) {
                if ($arFacturaDetalle) {
                    //------------------InvoiceLine--------------------------------------------
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $InvoiceLine = $xml->createElement("fe:InvoiceLine");
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                        $InvoiceLine = $xml->createElement("cac:CreditNoteLine");
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 3) {
                        $InvoiceLine = $xml->createElement("cac:DebitNoteLine");
                    }
                    $InvoiceLine = $factura->appendChild($InvoiceLine);
                    //---------InvoiceLineID-----------------------------------------------------------
                    $InvoiceLineID = $xml->createElement("cbc:ID", "{$i}");
                    $InvoiceLine->appendChild($InvoiceLineID);
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                        $InvoiceLineID = $xml->createElement("cbc:UUID", "{$arFacturaDetalle->getFacturaDetalleRel()->getFacturaRel()->getCodigoCufe()}");
                        $InvoiceLine->appendChild($InvoiceLineID);
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 3) {
                        $InvoiceLineID = $xml->createElement("cbc:UUID", "{$arFacturaDetalle->getFacturaDetalleRel()->getFacturaRel()->getCodigoCufe()}");
                        $InvoiceLine->appendChild($InvoiceLineID);
                    }
                    if ($arFactura->getFacturaTipoRel()->getTipo() != 3) {
                        //---------InvoiceLineNote-----------------------------------------------------------
                        $InvoiceLineNote = $xml->createElement("cbc:Note", "{$arFacturaDetalle->getCodigoPedidoDetalleFk()}");
                        $InvoiceLine->appendChild($InvoiceLineNote);
                    }
                    //---------InvoiceLineInvoicedQuantity-----------------------------------------------------------
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $InvoiceLineInvoicedQuantity = $xml->createElement("cbc:InvoicedQuantity", "{$arFacturaDetalle->getCantidad()}");
                        $InvoiceLineInvoicedQuantity->setAttribute("unitCode", "NIU"); // Tabla 13 - Unidaddes de medida
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                        $InvoiceLineInvoicedQuantity = $xml->createElement("cbc:CreditedQuantity", "{$arFacturaDetalle->getCantidad()}");
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 3) {
                        $InvoiceLineInvoicedQuantity = $xml->createElement("cbc:DebitedQuantity", "{$arFacturaDetalle->getCantidad()}");
                    }
                    $InvoiceLine->appendChild($InvoiceLineInvoicedQuantity);
                    //---------LineExtensionAmount----------------------------------------------------------------------
                    $LineExtensionAmount = $xml->createElement("cbc:LineExtensionAmount", "{$arFacturaDetalle->getVrPrecio()}");
                    $LineExtensionAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
                    $InvoiceLine->appendChild($LineExtensionAmount);

                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        if ($arFacturaDetalle->getCodigoFacturaDetalleFk()) {//Validar si corresponde a una nota
                            //---------InvoiceLineNote-----------------------------------------------------------
                            $InvoiceLineNote = $xml->createElement("cbc:Note", "{$arFacturaDetalle->getFacturaDetalleRel()->getFacturaRel()->getNumero()}");
                            $InvoiceLine->appendChild($InvoiceLineNote);
                        }
                    }
                    //---------TaxTotal-----------------------------------------------------------
                    if ($arFactura->getFacturaTipoRel()->getTipo() != 3) {
                        $TaxTotal = $xml->createElement("cac:TaxTotal");
                        $TaxTotal = $InvoiceLine->appendChild($TaxTotal);
                        //---------TaxAmount-----------------------------------------------------------
                        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFacturaDetalle->getIva()}");
                        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
                        $TaxTotal->appendChild($TaxAmount);
                        $generaImpuesto = $arFacturaDetalle->getIva() > 0 ? "false" : "true";
                        //---------TaxEvidenceIndicator-----------------------------------------------------------
                        $TaxEvidenceIndicator = $xml->createElement("cbc:TaxEvidenceIndicator", "{$generaImpuesto}");
                        $TaxTotal->appendChild($TaxEvidenceIndicator);
                        //---------TaxSubtotal-----------------------------------------------------------
                        $TaxSubtotal = $xml->createElement("cac:TaxSubtotal");
                        $TaxSubtotal = $TaxTotal->appendChild($TaxSubtotal);
                        //---------TaxableAmount-----------------------------------------------------------
                        $TaxableAmount = $xml->createElement("cbc:TaxableAmount", "{$arFacturaDetalle->getSubtotal()}");
                        $TaxableAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
                        $TaxSubtotal->appendChild($TaxableAmount);
                        //---------TaxAmount-----------------------------------------------------------
                        $TaxAmount = $xml->createElement("cbc:TaxAmount", "{$arFacturaDetalle->getIva()}");
                        $TaxAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
                        $TaxSubtotal->appendChild($TaxAmount);
                        //---------Percent-----------------------------------------------------------
                        $Percent = $xml->createElement("cbc:Percent", "{$arFacturaDetalle->getPorIva()}");
                        $TaxSubtotal->appendChild($Percent);
                        //---------TaxCategory-----------------------------------------------------------
                        $TaxCategory = $xml->createElement("cac:TaxCategory");
                        $TaxCategory = $TaxSubtotal->appendChild($TaxCategory);
                        //---------TaxScheme-----------------------------------------------------------
                        $TaxScheme = $xml->createElement("cac:TaxScheme");
                        $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                        //---------TaxSchemeID-----------------------------------------------------------
                        $TaxSchemeID = $xml->createElement("cbc:ID", "01");
                        $TaxScheme->appendChild($TaxSchemeID);
                    }
                    //---------Item-----------------------------------------------------------
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $Item = $xml->createElement("fe:Item");
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 2 || $arFactura->getFacturaTipoRel()->getTipo() == 3) {
                        $Item = $xml->createElement("cac:Item");
                    }
                    $Item = $InvoiceLine->appendChild($Item);
                    //---------Description-----------------------------------------------------------
                    $ItemDescription = $xml->createElement("cbc:Description", "{$arFacturaDetalle->getConceptoServicioRel()->getNombre()}");
                    $Item->appendChild($ItemDescription);
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        //---------SellersItemIdentification-----------------------------------------------------------
                        $ItemSellersItemIdentification = $xml->createElement("cac:SellersItemIdentification");
                        $ItemSellersItemIdentification = $Item->appendChild($ItemSellersItemIdentification);
                        //---------SellersItemIdentificationID-----------------------------------------------------------
                        $SellersItemIdentificationID = $xml->createElement("cbc:ID", "{$arFacturaDetalle->getCodigoFacturaDetallePk()}");
                        $ItemSellersItemIdentification->appendChild($SellersItemIdentificationID);
                    }
                    //---------Price-----------------------------------------------------------
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $Price = $xml->createElement("fe:Price");
                    } elseif ($arFactura->getFacturaTipoRel()->getTipo() == 2 || $arFactura->getFacturaTipoRel()->getTipo() == 3) {
                        $Price = $xml->createElement("cac:Price");
                    }
                    $Price = $InvoiceLine->appendChild($Price);
                    //---------PriceAmount-----------------------------------------------------------
                    $PriceAmount = $xml->createElement("cbc:PriceAmount", "{$arFacturaDetalle->getVrPrecio()}");
                    $PriceAmount->setAttribute("currencyID", "COP"); // Tabla 14 - Monedas
                    $Price->appendChild($PriceAmount);
                    $i++;
                }
            }
        }

    }
}
