<?php

namespace App\Controller\Transporte\Administracion\Conductor;

use App\Entity\Transporte\TteConductor;
use App\Form\Type\Transporte\ConductorType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConductorController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/transporte/administracion/conductor/lista", name="transporte_administracion_transporte_conductor_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arConductor = $paginator->paginate($em->getRepository(TteConductor::class)->lista(), $request->query->getInt('page', 1),10);
        return $this->render('transporte/administracion/conductor/lista.html.twig', ['arConductor' => $arConductor]);
    }

    /**
     * @Route("/transporte/administracion/conductor/nuevo/{id}", name="transporte_administracion_transporte_conductor_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConductor = new TteConductor();
        if ($id != '0') {
            $arConductor = $em->getRepository(TteConductor::class)->find($id);
            if (!$arConductor) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_lista'));
            }
        }
        $form = $this->createForm(ConductorType::class, $arConductor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arConductor);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_detalle', ['modulo' => 'transporte','entidad' => 'conductor','id'=> $arConductor->getCodigoConductorPk()]));
            }
        }
        return $this->render('transporte/administracion/conductor/nuevo.html.twig', [
            'arConductor' => $arConductor,
            'form' => $form->createView()
        ]);
    }

}

