<?php

namespace App\Controller\Transporte\Movimiento\Monitoreo\Monitoreo;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Form\Type\Transporte\MonitoreoDetalleType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class MonitoreoController extends Controller
{
   /**
    * @Route("/transporte/movimiento/monitoreo/monitoreo/lista", name="transporte_movimiento_monitoreo_monitoreo_lista")
    */    
    public function lista(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroTteMovMonitoreoFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTteMovMonitoreoFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteMovMonitoreoFechaHasta'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteMovMonitoreoFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroTteMovMonitoreoFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroTteMovMonitoreoFiltroFecha', $form->get('filtrarFecha')->getData());
            }
        }
        $arMonitoreos = $paginator->paginate($em->getRepository(TteMonitoreo::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/lista.html.twig', ['arMonitoreos' => $arMonitoreos, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/detalle/{codigoMonitoreo}", name="transporte_movimiento_monitoreo_monitoreo_detalle")
     */
    public function detalle(Request $request, $codigoMonitoreo)
    {
        $em = $this->getDoctrine()->getManager();
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->find($codigoMonitoreo);
        $form = $this->createFormBuilder()
            ->add('btnRetirarDetalle', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRetirarDetalle')->isClicked()) {
                $arrMonitoreoDetalle = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteMonitoreoDetalle::class)->eliminar($arrMonitoreoDetalle);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_detalle', ['codigoMonitoreo' => $codigoMonitoreo]));
            }
        }
        $arMonitoreoDetalles = $this->getDoctrine()->getRepository(TteMonitoreoDetalle::class)->monitoreo($codigoMonitoreo);
        $arMonitoreoRegistros = $this->getDoctrine()->getRepository(TteMonitoreoRegistro::class)->monitoreo($codigoMonitoreo);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/detalle.html.twig', [
            'arMonitoreo' => $arMonitoreo,
            'arMonitoreoDetalles' => $arMonitoreoDetalles,
            'arMonitoreoRegistros' => $arMonitoreoRegistros,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/detalle/adicionar/reporte/{codigoMonitoreo}/{codigoMonitoreoDetalle}", name="transporte_movimiento_monitoreo_monitoreo_detalle_adicionar_reporte")
     */
    public function detalleAdicionar(Request $request, $codigoMonitoreo, $codigoMonitoreoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->find($codigoMonitoreo);
        $arMonitoreoDetalle = new TteMonitoreoDetalle();
        $form = $this->createForm(MonitoreoDetalleType::class, $arMonitoreoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($codigoMonitoreoDetalle == 0) {
                $arMonitoreoDetalle->setFechaRegistro(new \DateTime('now'));
                $arMonitoreoDetalle->setFechaReporte(new \DateTime('now'));
                $arMonitoreoDetalle->setMonitoreoRel($arMonitoreo);
            }
            $em->persist($arMonitoreoDetalle);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/monitoreo/monitoreo/detalleAdicionar.html.twig', [
            'arMonitoreoDetalle' => $arMonitoreoDetalle,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/mapa/{codigoMonitoreo}", name="transporte_movimiento_monitoreo_monitoreo_mapa")
     */
    public function verMapa(Request $request, $codigoMonitoreo)
    {
        $em = $this->getDoctrine()->getManager();
        //$googleMapsApiKey = $arConfiguracion->getGoogleMapsApiKey();
        //$googleMapsApiKey = "AIzaSyBXwGxeTtvba8Uset2XFjuwAxdRmJlkdcY";
        //Esta es la key de alejandro
        $googleMapsApiKey = "AIzaSyBEONds48sofQeiVLeOewxouvqo203DfZU";
        $arrDatos = $em->getRepository(TteMonitoreoRegistro::class)->datosMapa($codigoMonitoreo);

        return $this->render('transporte/movimiento/monitoreo/monitoreo/mapaRegistro.html.twig', [
            'datos' => $arrDatos ?? [],
            'apikey' => $googleMapsApiKey]);
    }

}

