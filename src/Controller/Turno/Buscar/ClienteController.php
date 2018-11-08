<?php

namespace App\Controller\Turno\Buscar;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Turno\TurCliente;
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
    * @Route("/turno/buscar/cliente/{campoCodigo}/{campoNombre}", name="turno_buscar_cliente")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroTurNombreCliente')))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTurNombreCliente', $form->get('TxtNombre')->getData());
            $session->set('filtroTurNitCliente', $form->get('TxtNit')->getData());
            $session->set('filtroTurCodigoCliente', $form->get('TxtCodigo')->getData());
        }
        $arClientes = $em->getRepository(TurCliente::class)->findAll();
        $arClientes = $paginator->paginate($em->getRepository(TurCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('turno/buscar/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

