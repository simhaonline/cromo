<?php

namespace App\Controller\Transporte\Movimiento\Transporte\Guia;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Form\Type\Transporte\GuiaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuiaController extends Controller
{
   /**
    * @Route("/tte/mto/transporte/guia/lista", name="tte_mto_transporte_guia_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->lista();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', ['arGuias' => $arGuias]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/nuevo/{codigoGuia}", name="tte_mto_transporte_guia_nuevo")
     */
    public function nuevo(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = new TteGuia();
        if($codigoGuia == 0) {
            $arGuia->setFechaIngreso(new \DateTime('now'));
        }

        $form = $this->createForm(GuiaType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arGuia = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $arGuia->setClienteRel($arCliente);
                    $arGuia->setOperacionIngresoRel($this->getUser()->getOperacionRel());
                    $arGuia->setOperacionCargoRel($this->getUser()->getOperacionRel());
                    $arGuia->setFactura($arGuia->getGuiaTipoRel()->getFactura());
                    $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel()->getCiudadRel());
                    $em->persist($arGuia);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('tte_mto_transporte_guia_nuevo', array('codigoGuia' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('tte_mto_transporte_guia_lista'));
                    }
                }
            }


        }
        return $this->render('transporte/movimiento/transporte/guia/nuevo.html.twig', ['arGuia' => $arGuia,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/detalle/{codigoGuia}", name="tte_mto_transporte_guia_detalle")
     */
    public function detalle(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($codigoGuia);
                if($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('tte_mto_transporte_guia_detalle', array('codigoGuia' => $codigoGuia)));
                    //$formato = new \App\Formato\TteDespacho();
                    //$formato->Generar($em, $codigoGuia);
                }

            }
        }

        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }
}

