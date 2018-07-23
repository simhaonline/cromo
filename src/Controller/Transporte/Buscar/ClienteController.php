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
use Symfony\Component\HttpFoundation\Session\Session;

class ClienteController extends Controller
{
   /**
    * @Route("/transporte/bus/cliente/{campoCodigo}/{campoNombre}", name="transporte_bus_cliente")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre'))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNombreCliente', $form->get('TxtNombre')->getData());
            $session->set('filtroNitCliente', $form->get('TxtNit')->getData());
            $session->set('filtroCodigoCliente', $form->get('TxtCodigo')->getData());
        }
        $arClientes = $em->getRepository(TteCliente::class)->findAll();
        $arClientes = $paginator->paginate($em->getRepository(TteCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('transporte/buscar/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

