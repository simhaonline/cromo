<?php

namespace App\Controller\Transporte\Proceso\Transporte\general;

use App\Entity\Transporte\TteCierre;
use App\Entity\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CierreController extends Controller
{
   /**
    * @Route("/transporte/pro/transporte/general/cierre", name="transporte_pro_transporte_general_cierre")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpGenerar')) {
                $respuesta = $em->getRepository(TteCierre::class)->generar($request->request->get('OpGenerar'));
            }
            if ($request->request->get('OpDeshacer')) {
                $respuesta = $em->getRepository(TteCierre::class)->deshacer($request->request->get('OpDeshacer'));
            }
        }
        $query = $this->getDoctrine()->getRepository(TteCierre::class)->lista();
        $arCierres = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/general/cierre.html.twig', ['arCierres' => $arCierres, 'form' => $form->createView()]);
    }
}

