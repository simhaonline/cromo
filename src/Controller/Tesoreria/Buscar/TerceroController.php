<?php

namespace App\Controller\Tesoreria\Buscar;

use App\Entity\Tesoreria\TesTercero;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TerceroController extends Controller
{
    /**
     * @Route("/compra/buscar/tercero/{campoCodigo}/{campoNombre}/{tipo}", name="tesoreria_buscar_tercero")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre, $tipo = null)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTesTerceroCodigo')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTesTerceroNombre')])
            ->add('txtIdentificacion', TextType::class, ['required' => false, 'data' => $session->get('filtroTesTerceroIdentificacion')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTesTerceroCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroTesTerceroNombre', $form->get('txtNombre')->getData());
                $session->set('filtroTesTerceroIdentificacion', $form->get('txtIdentificacion')->getData());
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(TesTercero::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('tesoreria/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tesoreria/buscar/tercero/movimiento/{campoCodigo}", name="tesoreria_buscar_tercero_movimiento")
     */
    public function buscarTerceroMovimiento(Request $request, $campoCodigo)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTesBuscarTerceroCodigo')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTesBuscarTerceroNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTesBuscarTerceroCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroTesBuscarTerceroNombre', $form->get('txtNombre')->getData());
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(TesTercero::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('tesoreria/buscar/buscarTerceroMovimiento.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

}

