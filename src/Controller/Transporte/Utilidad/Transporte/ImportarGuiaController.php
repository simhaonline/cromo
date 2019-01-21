<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuiaTemporal;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $em->getRepository(TteGuiaTemporal::class)->createQueryBuilder('t')->delete(TteGuiaTemporal::class)->getQuery()->execute();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaIngresoDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date form-control',], 'required' => false])
            ->add('fechaIngresoHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date form-control',], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->add('btnImportar', SubmitType::class, ['label' => "Importar", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroGuiaFechaIngresoDesde', $form->get('fechaIngresoDesde')->getData() ? $form->get('fechaIngresoDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroGuiaFechaIngresoHasta', $form->get('fechaIngresoHasta')->getData() ? $form->get('fechaIngresoHasta')->getData()->format('Y-m-d') : null);
                $this->pendiente();
            }
            if ($form->get('btnImportar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $this->importarGuia($arrSeleccionados);
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuiaTemporal::class)->findAll(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/importarGuia/importarGuia.html.twig', [
            'form' => $form->createView(),
            'arGuias' => $arGuias
        ]);
    }

    private function pendiente()
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arConfiguracionTransporte = $em->find(TteConfiguracion::class, 1);
        $url = $arConfiguracion->getWebServiceGalioUrl() . '/api/pendientes/guia/' . $arConfiguracionTransporte->getCodigoOperadorFk();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $arrGuias = json_decode(curl_exec($ch));
        if ($arrGuias) {
            foreach ($arrGuias as $arrGuia) {
                $arGuiaTemporal = new TteGuiaTemporal();
                $arGuiaTemporal->setNumero($arrGuia->numero);
                $arGuiaTemporal->setFechaIngreso(date_create($arrGuia->fechaIngreso));
                $arGuiaTemporal->setClienteDocumento($arrGuia->clienteDocumento);
                $arGuiaTemporal->setDestinatarioNombre($arrGuia->destinatarioNombre);
                $arGuiaTemporal->setCodigoCiudadDestinoFk($arrGuia->ciudadDestino);
                $arGuiaTemporal->setCodigoCiudadOrigenFk($arrGuia->ciudadOrigen);
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
//        $em = $this->getDoctrine()->getManager();
////        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
////        $arConfiguracionTransporte = $em->find(TteConfiguracion::class, 1);
////        $url = $arConfiguracion->getWebServiceGalioUrl() . '/api/importar/guia/' . $arConfiguracionTransporte->getCodigoOperadorFk();
////        $ch = curl_init($url);
////        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
////        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
////        $arrGuias = json_decode(curl_exec($ch));
//        foreach ($arGuias as $arGuia) {
//            $arGuiaTemporal = new TteGuiaTemporal();
//            $arGuiaTemporal->setNumero($arrGuia->numero);
//            $arGuiaTemporal->setFechaIngreso(date_create($arrGuia->fechaIngreso));
//            $arGuiaTemporal->setClienteDocumento($arrGuia->clienteDocumento);
//            $arGuiaTemporal->setDestinatarioNombre($arrGuia->destinatarioNombre);
//            $arGuiaTemporal->setCodigoCiudadDestinoFk($arrGuia->ciudadDestino);
//            $arGuiaTemporal->setCodigoCiudadOrigenFk($arrGuia->ciudadOrigen);
//            $arGuiaTemporal->setUnidades($arrGuia->unidades);
//            $arGuiaTemporal->setPesoFacturado($arrGuia->pesoFacturado);
//            $arGuiaTemporal->setVrDeclara($arrGuia->vrDeclara);
//            $arGuiaTemporal->setVrFlete($arrGuia->vrFlete);
//            $arGuiaTemporal->setVrManejo($arrGuia->vrManejo);
//            $em->persist($arGuiaTemporal);
//        }
//        $em->flush();
    }
}

