<?php

namespace App\Controller\Contabilidad\Buscar;

use App\Entity\Cartera\CarCliente;
use App\Entity\Contabilidad\CtbTercero;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class TerceroController extends Controller
{
   /**
    * @Route("/contabilidad/buscar/tercero/{campoCodigo}/{campoNombre}", name="contabilidad_buscar_tercero")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCtbNombreTercero')))
            ->add('txtCodigoTercero', TextType::class, array('label'  => 'Codigo'))
            ->add('txtNit', TextType::class, array('label'  => 'Nit'))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCtbNombreTercero', $form->get('txtNombreCorto')->getData());
            $session->set('filtroCtbNitTercero', $form->get('txtNit')->getData());
            $session->set('filtroCtbCodigoTercero', $form->get('txtCodigoTercero')->getData());
        }
        $arTercero = $em->getRepository(CtbTercero::class)->findAll();
        $arTerceros = $paginator->paginate($em->getRepository(CtbTercero::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('contabilidad/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

