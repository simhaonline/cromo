<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class GenerarFacturaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/generarfactura", name="transporte_proceso_transporte_guia_generarfactura")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->generarFactura($arrGuias);
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaGenerarFactura(), $request->query->getInt('page', 1), 100);
        return $this->render('transporte/proceso/transporte/guia/generarFactura.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }
}

