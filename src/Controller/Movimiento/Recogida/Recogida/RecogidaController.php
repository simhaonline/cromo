<?php

namespace App\Controller\Movimiento\Recogida\Recogida;

use App\Entity\Cliente;
use App\Entity\Recogida;
use App\Form\Type\RecogidaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecogidaController extends Controller
{
   /**
    * @Route("/mto/recogida/recogida/lista", name="mto_recogida_recogida_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Recogida::class)->lista();
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/recogida/recogida/lista.html.twig', ['arRecogidas' => $arRecogidas]);
    }

    /**
     * @Route("/mto/recogida/recogida/detalle/{codigoRecogida}", name="mto_recogida_recogida_detalle")
     */
    public function detalle(Request $request, $codigoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(Recogida::class)->find($codigoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Recogida();
                $formato->Generar($em, $codigoRecogida);
            }
        }

        return $this->render('movimiento/recogida/recogida/detalle.html.twig', [
            'arRecogida' => $arRecogida,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/recogida/recogida/nuevo/{codigoRecogida}", name="mto_recogida_recogida_nuevo")
     */
    public function nuevo(Request $request, $codigoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if($codigoRecogida == 0) {
            $arRecogida = new Recogida();
            $arRecogida->setFechaRegistro(new \DateTime('now'));
            $arRecogida->setFecha(new \DateTime('now'));
        }

        $form = $this->createForm(RecogidaType::class, $arRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogida = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(Cliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $prueba = $this->getUser();

                    $arRecogida->setClienteRel($arCliente);
                    $arRecogida->setCodigoOperacionFk('MED');
                    $em->persist($arRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('mto_recogida_recogida_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('mto_recogida_recogida_lista'));
                    }
                }
            }
        }
        return $this->render('movimiento/recogida/recogida/nuevo.html.twig', ['arRecogida' => $arRecogida,'form' => $form->createView()]);
    }

}

