<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvTercero;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TerceroController extends Controller
{
   /**
    * @Route("/inv/bus/tercero/{campoCodigo}/{campoNombre}", name="inv_bus_tercero")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre'))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arTerceros = $em->getRepository(InvTercero::class)->findAll();
        $arTerceros = $paginator->paginate($arTerceros, $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

