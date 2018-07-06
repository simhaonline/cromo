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
class SoporteController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/soporte", name="transporte_proceso_transporte_guia_soporte")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $codigoDespacho = 0;
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('data' => $session->get('filtroTteDespachoCodigo')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnSoporte', SubmitType::class, array('label' => 'Cumplir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
            if ($form->get('btnSoporte')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->soporte($arrGuias);
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaSoporte($codigoDespacho), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/proceso/transporte/guia/soporte.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }

}

