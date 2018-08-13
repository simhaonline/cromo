<?php

namespace App\Controller\Transporte\Movimiento\Monitoreo\Monitoreo;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
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
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arMonitoreos = $paginator->paginate($em->getRepository(TteMonitoreo::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/lista.html.twig', ['arMonitoreos' => $arMonitoreos]);
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
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($codigoGuia);
                if($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_detalle', array('codigoGuia' => $codigoGuia)));
                    //$formato = new \App\Formato\TteDespacho();
                    //$formato->Generar($em, $codigoGuia);
                }

            }
        }
        $arMonitoreoDetalles = $this->getDoctrine()->getRepository(TteMonitoreoDetalle::class)->monitoreo($codigoMonitoreo);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/detalle.html.twig', [
            'arMonitoreo' => $arMonitoreo,
            'arMonitoreoDetalles' => $arMonitoreoDetalles,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/detalle/adicionar/reporte/{codigoMonitoreo}/{codigoMonitoreoDetalle}", name="transporte_movimiento_monitoreo_monitoreo_detalle_adicionar_reporte")
     */
    public function detalleAdicionarNovedad(Request $request, $codigoMonitoreo, $codigoMonitoreoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arNovedad = new TteNovedad();
        if($codigoNovedad == 0) {
            $arNovedad->setEstadoAtendido(true);
            $arNovedad->setFechaReporte(new \DateTime('now'));
            $arNovedad->setFecha(new \DateTime('now'));
        } else {
            $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        }
        $form = $this->createForm(NovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad = $form->getData();
            $arNovedad->setGuiaRel($arGuia);
            if($codigoNovedad == 0) {
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtendido(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
            }

            $em->persist($arNovedad);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/transporte/guia/detalleAdicionarNovedad.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/mapa/{codigoMonitoreo}", name="transporte_movimiento_monitoreo_monitoreo_mapa")
     */
    public function verMapa(Request $request, $codigoMonitoreo)
    {
        $em = $this->getDoctrine()->getManager();
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->find($codigoMonitoreo);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($codigoGuia);
                if($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_detalle', array('codigoGuia' => $codigoGuia)));
                    //$formato = new \App\Formato\TteDespacho();
                    //$formato->Generar($em, $codigoGuia);
                }

            }
        }
        $arMonitoreoDetalles = $this->getDoctrine()->getRepository(TteMonitoreoDetalle::class)->monitoreo($codigoMonitoreo);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/mapa.html.twig', [
            'arMonitoreo' => $arMonitoreo,
            'arMonitoreoDetalles' => $arMonitoreoDetalles,
            'form' => $form->createView()]);
    }

}

