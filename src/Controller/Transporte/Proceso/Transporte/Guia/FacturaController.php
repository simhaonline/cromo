<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaController extends Controller
{
   /**
    * @Route("/transporte/pro/transporte/guia/factura", name="transporte_pro_transporte_guia_factura")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnFactura', SubmitType::class, array('label' => 'Facturar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFactura')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->generarFactura($arrGuias);
            }
        }
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->pendienteGenerarFactura();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/guia/factura.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

