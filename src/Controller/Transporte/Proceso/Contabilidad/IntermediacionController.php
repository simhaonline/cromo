<?php

namespace App\Controller\Transporte\Proceso\Contabilidad;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteIntermediacionDetalle;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class IntermediacionController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/contabilidad/intermediacion/lista", name="transporte_proceso_contabilidad_intermediacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false, 'data' => $session->get('filtroTteAnio'), 'attr' => ['class' => 'form-control']])
            ->add('txtMes', TextType::class, ['required' => false, 'data' => $session->get('filtroTteMes'), 'attr' => ['class' => 'form-control']])
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteAnio',  $form->get('txtAnio')->getData());
                $session->set('filtroTteMes',  $form->get('txtMes')->getData());
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteIntermediacionDetalle::class)->contabilizar($arr);
            }
        }
        $arIntermediacionDetalles = $paginator->paginate($em->getRepository(TteIntermediacionDetalle::class)->listaContabilizar(), $request->query->getInt('page', 1),100);
        return $this->render('transporte/proceso/contabilidad/intermediacion/lista.html.twig',
            ['arIntermediacionDetalles' => $arIntermediacionDetalles,
            'form' => $form->createView()]);
    }

}

