<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvBodega;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BodegaController extends Controller
{
   /**
    * @Route("/inventario/buscar/bodega/lista/{campoCodigo}", name="inventario_buscar_bodega_lista")
    */    
    public function lista(Request $request, $campoCodigo)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarBodegaCodigo')])
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarBodegaNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarBodegaCodigo',$form->get('txtCodigo')->getData());
                $session->set('filtroInvBuscarBodegaNombre',$form->get('txtNombre')->getData());
            }
        }
        $arBodegas = $paginator->paginate($em->getRepository(InvBodega::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/bodega.html.twig', array(
            'arBodegas' => $arBodegas,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

}

