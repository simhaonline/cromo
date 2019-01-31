<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTemporal;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteServicio;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImportarGuiaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/utilidad/transporte/importar/guia", name="transporte_utilidad_transporte_importar_guia")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaIngresoDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date form-control',], 'required' => false,'data' => $session->get('filtroGuiaFechaIngresoDesde') != '' ? date_create($session->get('filtroGuiaFechaIngresoDesde')) : null ])
            ->add('fechaIngresoHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date form-control',], 'required' => false,'data' => $session->get('filtroGuiaFechaIngresoHasta') != '' ? date_create($session->get('filtroGuiaFechaIngresoHasta')) : null ])
            ->add('codigoClienteFk', TextType::class, ['required' => false,'data' => $session->get('filtroGuiaCodigoCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->add('btnImportar', SubmitType::class, ['label' => "Importar", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->add('btnMarcar', SubmitType::class, ['label' => "Marcar exportado", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $em->getRepository(TteGuiaTemporal::class)->createQueryBuilder('t')->delete(TteGuiaTemporal::class)->getQuery()->execute();
                $session->set('filtroGuiaFechaIngresoDesde', $form->get('fechaIngresoDesde')->getData() ? $form->get('fechaIngresoDesde')->getData()->format('Y-m-d') : '');
                $session->set('filtroGuiaFechaIngresoHasta', $form->get('fechaIngresoHasta')->getData() ? $form->get('fechaIngresoHasta')->getData()->format('Y-m-d') : '');
                $session->set('filtroGuiaCodigoCliente', $form->get('codigoClienteFk')->getData() ?? '');
                $this->pendiente();
            }
            if ($form->get('btnImportar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $this->importarGuia($arrSeleccionados);
                }
                $em->getRepository(TteGuiaTemporal::class)->createQueryBuilder('t')->delete(TteGuiaTemporal::class)->getQuery()->execute();
            }
            if($form->get('btnMarcar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados){
                    $this->marcarExportado($arrSeleccionados);
                }
                $em->getRepository(TteGuiaTemporal::class)->createQueryBuilder('t')->delete(TteGuiaTemporal::class)->getQuery()->execute();
            }
            return $this->redirect($this->generateUrl('transporte_utilidad_transporte_importar_guia'));
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuiaTemporal::class)->findAll(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/importarGuia/importarGuia.html.twig', [
            'formFiltro' => $form->createView(),
            'arGuias' => $arGuias
        ]);
    }

    private function pendiente()
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arConfiguracionTransporte = $em->find(TteConfiguracion::class, 1);
//        $url = 'http://159.65.52.53/galio/public/index.php/api/pendientes/guia/' . $arConfiguracionTransporte->getCodigoOperadorFk();
        $url = $arConfiguracion->getWebServiceGalioUrl() . '/api/pendientes/guia/' . $arConfiguracionTransporte->getCodigoOperadorFk();
        $arrDatos['nit'] = $em->find(TteCliente::class,$session->get('filtroGuiaCodigoCliente'))->getNumeroIdentificacion();
        $arrDatos['fechaHasta'] = $session->get('filtroGuiaFechaIngresoHasta');
        $arrDatos['fechaDesde'] = $session->get('filtroGuiaFechaIngresoDesde');
        $arrDatos = json_encode($arrDatos);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($arrDatos)]);
        $arrGuias = json_decode(curl_exec($ch));

        if ($arrGuias) {
            foreach ($arrGuias as $arrGuia) {
                $arCliente = $em->getRepository(TteCliente::class)->findOneBy(['numeroIdentificacion' => $arrGuia->nit]);
                $arCiudadOrigen = $em->find(TteCiudad::class, $arrGuia->codigoCiudadOrigenFk);
                $arCiudadDestino = $em->find(TteCiudad::class, $arrGuia->codigoCiudadDestinoFk);
                $arGuiaTemporal = new TteGuiaTemporal();
                $arGuiaTemporal->setNumero($arrGuia->numero);
                $arGuiaTemporal->setOperacion($arrGuia->operacion);
                $arGuiaTemporal->setCodigoGuiaTipoFk($arrGuia->codigoGuiaTipoFk);
                $arGuiaTemporal->setClienteRel($arCliente);
                $arGuiaTemporal->setFechaIngreso(date_create($arrGuia->fechaIngreso->date));
                $arGuiaTemporal->setClienteDocumento($arrGuia->clienteDocumento);
                $arGuiaTemporal->setDestinatarioNombre($arrGuia->destinatarioNombre);
                $arGuiaTemporal->setDestinatarioTelefono($arrGuia->destinatarioTelefono);
                $arGuiaTemporal->setCiudadOrigenRel($arCiudadOrigen);
                $arGuiaTemporal->setCiudadDestinoRel($arCiudadDestino);
                $arGuiaTemporal->setUnidades($arrGuia->unidades);
                $arGuiaTemporal->setPesoFacturado($arrGuia->pesoFacturado);
                $arGuiaTemporal->setVrDeclara($arrGuia->vrDeclara);
                $arGuiaTemporal->setVrFlete($arrGuia->vrFlete);
                $arGuiaTemporal->setVrManejo($arrGuia->vrManejo);
                $em->persist($arGuiaTemporal);
            }
            $em->flush();
        }
    }

    /**
     * @param $arrSeleccionados array
     */
    private function importarGuia($arrSeleccionados)
    {
        $em = $this->getDoctrine()->getManager();
        $arrNumeros = [];
        foreach ($arrSeleccionados as $codigoGuiaTemporal) {
            $arGuiaTemporal = $em->find(TteGuiaTemporal::class, $codigoGuiaTemporal);
            $arGuiaTipo = $em->find(TteGuiaTipo::class, $arGuiaTemporal->getCodigoGuiaTipoFk());
            $arOperacion = $em->find(TteOperacion::class,$arGuiaTemporal->getOperacion());
            $arServicio = $em->find(TteServicio::class, 'PAQ');
            if ($arGuiaTemporal) {
                if(!$em->find(TteGuia::class,$arGuiaTemporal->getNumero())){
                    $arGuia = new TteGuia();
                    $arGuia->setCodigoGuiaPk($arGuiaTemporal->getNumero());
                    $arGuia->setCiudadOrigenRel($arGuiaTemporal->getCiudadOrigenRel());
                    $arGuia->setGuiaTipoRel($arGuiaTipo);
                    $arGuia->setCiudadDestinoRel($arGuiaTemporal->getCiudadDestinoRel());
                    $arGuia->setClienteRel($arGuiaTemporal->getClienteRel());
                    $arGuia->setServicioRel($arServicio);
                    $arGuia->setOperacionCargoRel($arOperacion);
                    $arGuia->setOperacionIngresoRel($arOperacion);
                    $arGuia->setTelefonoDestinatario($arGuiaTemporal->getDestinatarioTelefono());
                    $arGuia->setFechaIngreso($arGuiaTemporal->getFechaIngreso());
                    $arGuia->setDocumentoCliente($arGuiaTemporal->getClienteDocumento());
                    $arGuia->setNumero($arGuiaTemporal->getNumero());
                    $arGuia->setNombreDestinatario($arGuiaTemporal->getDestinatarioNombre());
                    $arGuia->setUnidades($arGuiaTemporal->getUnidades());
                    $arGuia->setPesoFacturado($arGuiaTemporal->getPesoFacturado());
                    $arGuia->setVrDeclara($arGuiaTemporal->getVrDeclara());
                    $arGuia->setVrFlete($arGuiaTemporal->getVrFlete());
                    $arGuia->setVrManejo($arGuiaTemporal->getVrManejo());
                    $arrNumeros[] = $arGuia->getNumero();
                    $em->persist($arGuia);
                } else {
                    Mensajes::error("El consecutivo '{$arGuiaTemporal->getNumero()}' ya existe en el sistema, no se puede importar");
                    return $this->redirect($this->generateUrl('transporte_utilidad_transporte_importar_guia'));
                }
            }
        }
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arConfiguracionTransporte = $em->find(TteConfiguracion::class, 1);
//        $url = 'http://159.65.52.53/galio/public/index.php/api/importar/guia';
        $url = $arConfiguracion->getWebServiceGalioUrl() . '/api/importar/guia';
        $ch = curl_init($url);
        $arrDatos['numeros'] = $arrNumeros;
        $arrDatos['codigoOperador'] = $arConfiguracionTransporte->getCodigoOperadorFk();
        $arrDatos = json_encode($arrDatos);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($arrDatos)]);
        $respuesta = curl_exec($ch);
        curl_close($ch);
        if ($respuesta) {
            $em->flush();
            Mensajes::success('Guias exportadas correctamente');
        } else {
            Mensajes::error('Ha ocurrido un error al momento de exportar las guias');
        }
    }

    private function marcarExportado($arrNumeros){
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arConfiguracionTransporte = $em->find(TteConfiguracion::class, 1);
        $url = $arConfiguracion->getWebServiceGalioUrl() . '/api/importar/guia';
        foreach ($arrNumeros as $codigoGuiaTemporal){
            $arrNumeros[] = $em->find(TteGuiaTemporal::class,$codigoGuiaTemporal)->getNumero();
        }
        $arrDatos['numeros'] = $arrNumeros;
        $arrDatos['codigoOperador'] = $arConfiguracionTransporte->getCodigoOperadorFk();
        $arrDatos = json_encode($arrDatos);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($arrDatos)]);
        $respuesta = curl_exec($ch);
        curl_close($ch);
        if ($respuesta) {
            Mensajes::success('Guias marcadas como exportadas correctamente');
        } else {
            Mensajes::error('Ha ocurrido un error al momento de marcar las guias');
        }
    }
}