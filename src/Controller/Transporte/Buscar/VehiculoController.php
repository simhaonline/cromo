<?php

namespace App\Controller\Transporte\Buscar;

use App\Entity\Transporte\TteVehiculo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoController extends Controller
{
   /**
    * @Route("/transporte/bus/vehiculo/{campoCodigo}", name="transporte_bus_vehiculo")
    */    
    public function lista(Request $request, $campoCodigo)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtPlaca', TextType::class, array('label'  => 'Nombre'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arVehiculos = $em->getRepository(TteVehiculo::class)->findAll();
        $arVehiculos = $paginator->paginate($arVehiculos, $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/vehiculo.html.twig', array(
            'arVehiculos' => $arVehiculos,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

}

