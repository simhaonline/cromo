<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class DesembarcoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/proceso/transporte/guia/desembarco", name="transporte_proceso_transporte_guia_desembarco")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, ['data' => $session->get('filtroTteDespachoCodigo')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnDesembarco', SubmitType::class, ['label' => 'Desembarco', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                if(is_numeric($form->get('txtDespachoCodigo')->getData())) {
                    $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                } else {
                    $session->set('filtroTteDespachoCodigo', null);
                    Mensajes::error("El codigo del despacho debe ser numerico");
                }

            }
            if($form->get('btnDesembarco')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados){
                    $arOperacion = $this->getUser()->getOperacionRel();
                    $usuario = $this->getUser()->getUserName();
                    $em->getRepository(TteGuia::class)->desembarco($arrSeleccionados, $arOperacion, $usuario);
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaDesembarco(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/proceso/transporte/guia/desembarco.html.twig', [
            'form' => $form->createView(),
            'arGuias' => $arGuias
        ]);
    }
}

