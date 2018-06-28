<?php

namespace App\Controller\Transporte\Buscar;

use App\Entity\Transporte\TteConductor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConductorController extends Controller
{
   /**
    * @Route("/tte/bus/conductor/{campoCodigo}/{campoNombre}", name="transporte_bus_conductor")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre'))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'Numero identificacion'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arConductores = $em->getRepository(TteConductor::class)->findAll();
        $arConductores = $paginator->paginate($arConductores, $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/conductor.html.twig', array(
            'arConductores' => $arConductores,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

