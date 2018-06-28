<?php

namespace App\Controller\Transporte\Buscar;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecogida;
use App\Form\Type\Transporte\RecogidaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
   /**
    * @Route("/transporte/bus/cliente/{campoCodigo}/{campoNombre}", name="transporte_bus_cliente")
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
        $arClientes = $em->getRepository(TteCliente::class)->findAll();
        $arClientes = $paginator->paginate($arClientes, $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

