<?php

namespace App\Controller\Cartera\Buscar;

use App\Entity\Cartera\CarCliente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class ClienteController extends Controller
{
   /**
    * @Route("/cartera/buscar/cliente/{campoCodigo}/{campoNombre}", name="cartera_buscar_cliente")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCarNombreCliente')))
            ->add('txtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('txtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCarNombreCliente', $form->get('txtNombre')->getData());
            $session->set('filtroCarNitCliente', $form->get('txtNit')->getData());
            $session->set('filtroCarCodigoCliente', $form->get('txtCodigo')->getData());
        }
        $arClientes = $em->getRepository(CarCliente::class)->findAll();
        $arClientes = $paginator->paginate($em->getRepository(CarCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('cartera/buscar/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

