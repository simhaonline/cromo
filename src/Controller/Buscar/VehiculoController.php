<?php

namespace App\Controller\Buscar;

use App\Entity\Vehiculo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoController extends Controller
{
   /**
    * @Route("/bus/vehiculo/{campoCodigo}", name="bus_vehiculo")
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
        $arVehiculos = $em->getRepository(Vehiculo::class)->findAll();
        $arVehiculos = $paginator->paginate($arVehiculos, $request->query->get('page', 1), 20);
        return $this->render('buscar/vehiculo.html.twig', array(
            'arVehiculos' => $arVehiculos,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

}

