<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Formato\Transporte\Guia;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImprimirGuiaMasivoController extends Controller
{
   /**
    * @Route("/transporte/utilidad/transporte/imprimirGuiaMasivo/lista", name="transporte_utilidad_transporte_imprimirGuiaMasivo_lista")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtGuiaNumeroDesde', TextType::class, array('label' => 'Numero desde', 'data' => $session->get('filtroNumeroDesde')))
            ->add('txtGuiaNumeroHasta', TextType::class, array('label' => 'Numero hasta', 'data' => $session->get('filtroNumeroHasta')))
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnGenerar')->isClicked()){
                $objFormato = new Guia();
                $objFormato->Generar($em, '', true);
            }
        }
        return $this->render('transporte/utilidad/transporte/imprimirGuiaMasivo/lista.html.twig', [
            'form' => $form->createView()]);
    }
}

