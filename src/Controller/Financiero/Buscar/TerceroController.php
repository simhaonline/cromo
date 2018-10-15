<?php

namespace App\Controller\Financiero\Buscar;

use App\Entity\Cartera\CarCliente;
use App\Entity\Financiero\FinTercero;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class TerceroController extends Controller
{
   /**
    * @Route("/financiero/buscar/tercero/{campoCodigo}/{campoNombre}", name="financiero_buscar_tercero")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroFinNombreTercero')))
            ->add('txtCodigoTercero', TextType::class, array('label'  => 'Codigo'))
            ->add('txtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroFinNombreTercero', $form->get('txtNombreCorto')->getData());
            $session->set('filtroFinNitTercero', $form->get('txtNit')->getData());
            $session->set('filtroFinCodigoTercero', $form->get('txtCodigoTercero')->getData());
        }
        $arTercero = $em->getRepository(FinTercero::class)->findAll();
        $arTerceros = $paginator->paginate($em->getRepository(FinTercero::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('financiero/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

