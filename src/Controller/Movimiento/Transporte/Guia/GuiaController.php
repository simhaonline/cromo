<?php

namespace App\Controller\Movimiento\Transporte\Guia;

use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuiaController extends Controller
{
   /**
    * @Route("/mto/transporte/guia/lista", name="mto_transporte_guia_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Guia::class)->lista();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/transporte/guia/lista.html.twig', ['arGuias' => $arGuias]);
    }

    /**
     * @Route("/mto/transporte/guia/detalle/{codigoGuia}", name="mto_transporte_guia_detalle")
     */
    public function detalle(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                //$formato = new \App\Formato\Despacho();
                //$formato->Generar($em, $codigoGuia);
            }
        }

        return $this->render('movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }
}

