<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SoporteController extends Controller
{
   /**
    * @Route("/tte/pro/transporte/guia/soporte", name="transporte_pro_transporte_guia_soporte")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->findBy(array('codigoGuiaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnSoporte', SubmitType::class, array('label' => 'Cumplir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespacho = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(TteGuia::class)->listaSoporte($codigoDespacho);
            }
            if ($form->get('btnSoporte')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->soporte($arrGuias);
                $codigoDespacho = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(TteGuia::class)->listaSoporte($codigoDespacho);
            }
        }
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/guia/soporte.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

