<?php

namespace App\Controller\Transporte\Proceso\Transporte\Despacho;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class EntregaController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/despacho/entrega", name="transporte_proceso_transporte_despacho_entrega")
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
            ->add('btnEntrega', SubmitType::class, array('label' => 'Entrega'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
            if ($form->get('btnEntrega')->isClicked()) {
                $arrDespachos = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->entrega($arrDespachos, $arrControles);
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();

            }
        }
        $arDespachos = $paginator->paginate($em->getRepository(TteDespacho::class)->listaEntrega($codigoDespacho), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/proceso/transporte/despacho/entrega.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()
        ]);
    }

}

