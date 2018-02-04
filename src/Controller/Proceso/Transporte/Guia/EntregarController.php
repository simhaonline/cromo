<?php

namespace App\Controller\Proceso\Transporte\Guia;

use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntregarController extends Controller
{
   /**
    * @Route("/pro/transporte/guia/entregar", name="mto_transporte_guia_entregar")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Guia::class)->findBy(array('codigoGuiaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnEntregar', SubmitType::class, array('label' => 'Entregar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespacho = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(Guia::class)->listaEntregar($codigoDespacho);
            }
            if ($form->get('btnEntregar')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(Guia::class)->entregar($arrGuias);

            }
        }
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('proceso/transporte/guia/entregar.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

