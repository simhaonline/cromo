<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntregaController extends Controller
{
   /**
    * @Route("/tte/pro/transporte/guia/entrega", name="transporte_pro_transporte_guia_entrega")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->findBy(array('codigoGuiaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnEntrega', SubmitType::class, array('label' => 'Entregar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespacho = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(TteGuia::class)->listaEntrega($codigoDespacho);
            }
            if ($form->get('btnEntrega')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->entrega($arrGuias, $arrControles);
                $codigoDespacho = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(TteGuia::class)->listaEntrega($codigoDespacho);
            }
        }
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/guia/entrega.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

