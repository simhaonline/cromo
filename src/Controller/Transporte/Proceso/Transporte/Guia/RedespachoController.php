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

class RedespachoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/redespacho", name="transporte_proceso_transporte_guia_redespacho")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arGuia = $em->getRepository(TteGuia::class)->findAll();
        $form = $this->createFormBuilder()
            ->add('txtCampo', TextType::class, array('data' => $session->get('campo')))
            ->add('reDespacho', SubmitType::class, ['label' => 'ReDespacho', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reDespacho')->isClicked()) {
                $em->getRepository(TteGuia::class)->find($arGuia);
            }
        }
        return $this->render('transporte/proceso/transporte/guia/reDespacho.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

