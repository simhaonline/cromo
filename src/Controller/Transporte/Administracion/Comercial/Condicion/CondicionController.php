<?php

namespace App\Controller\Transporte\Administracion\Comercial\Condicion;

use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteCondicion;
use App\Form\Type\Transporte\CondicionType;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CondicionController extends Controller
{
   /**
    * @Route("/transporte/administracion/comercial/condicion/lista", name="transporte_administracion_comercial_condicion_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arCondiciones = $paginator->paginate($em->getRepository(TteCondicion::class)->lista(), $request->query->getInt('page', 1),10);
        return $this->render('transporte/administracion/comercial/condicion/lista.html.twig', ['arCondiciones' => $arCondiciones]);
    }

    /**
     * @Route("/transporte/administracion/comercial/condicion/detalle/{id}", name="transporte_administracion_comercial_condicion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = $em->getRepository(TteCondicion::class)->find($id);

        return $this->render('transporte/administracion/comercial/condicion/detalle.html.twig', array(
            'arCondicion' => $arCondicion,
        ));
    }

    /**
     * @Route("/transporte/administracio/comercial/condicion/nuevo/{id}", name="transporte_administracion_comercial_condicion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = new TteCondicion();
        if ($id != '0') {
            $arCondicion = $em->getRepository(TteCondicion::class)->find($id);
            if (!$arCondicion) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_condicion_lista'));
            }
        }
        $form = $this->createForm(CondicionType::class, $arCondicion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCondicion);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_detalle', ['modulo' => 'transporte','entidad' => 'condicion','id'=> $arCondicion->getCodigoCondicionPk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/condicion/nuevo.html.twig', [
            'arCondicion' => $arCondicion,
            'form' => $form->createView()
        ]);
    }


}

