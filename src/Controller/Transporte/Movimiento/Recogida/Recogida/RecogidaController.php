<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Recogida;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecogida;
use App\Form\Type\Transporte\RecogidaType;
use App\Formato\Transporte\Recogida;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecogidaController extends Controller
{
   /**
    * @Route("/transporte/movimiento/recogida/recogida/lista", name="transporte_movimiento_recogida_recogida_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteRecogida::class)->lista();
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/recogida/recogida/lista.html.twig', ['arRecogidas' => $arRecogidas]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/detalle/{codigoRecogida}", name="transporte_movimiento_recogida_recogida_detalle")
     */
    public function detalle(Request $request, $codigoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(TteRecogida::class)->find($codigoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recogida();
                $formato->Generar($em, $codigoRecogida);
            }
        }

        return $this->render('transporte/movimiento/recogida/recogida/detalle.html.twig', [
            'arRecogida' => $arRecogida,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/nuevo/{codigoRecogida}", name="transporte_movimiento_recogida_recogida_nuevo")
     */
    public function nuevo(Request $request, $codigoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if($codigoRecogida == 0) {
            $arRecogida = new TteRecogida();
            $arRecogida->setFechaRegistro(new \DateTime('now'));
            $arRecogida->setFecha(new \DateTime('now'));
        }

        $form = $this->createForm(RecogidaType::class, $arRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogida = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arRecogida->setClienteRel($arCliente);
                    $arRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                    $em->persist($arRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_lista'));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/recogida/recogida/nuevo.html.twig', ['arRecogida' => $arRecogida,'form' => $form->createView()]);
    }

}

