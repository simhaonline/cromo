<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvTercero;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TerceroController extends Controller
{
   /**
    * @Route("/inv/bus/tercero/{campoCodigo}/{campoNombre}/{tipo}", name="inventario_bus_tercero")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre, $tipo = null)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroNombre')])
            ->add('TxtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroCodigo')])
            ->add('TxtNit', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroIdentificacion')])
            ->add('BtnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroInvTerceroCodigo',$form->get('TxtCodigo')->getData());
                $session->set('filtroInvTerceroNombre',$form->get('TxtNombre')->getData());
                $session->set('filtroInvTerceroIdentificacion',$form->get('TxtNit')->getData());
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(InvTercero::class)->lista($tipo), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

