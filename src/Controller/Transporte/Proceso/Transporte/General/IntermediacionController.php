<?php

namespace App\Controller\Transporte\Proceso\Transporte\General;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\TteGuia;
use App\Form\Type\Transporte\IntermediacionType;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IntermediacionController extends Controller
{
   /**
    * @Route("/transporte/proceso/transporte/general/intermediacion", name="transporte_proceso_transporte_general_intermediacion")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpGenerar')) {
                $respuesta = $em->getRepository(TteIntermediacion::class)->generar($request->request->get('OpGenerar'));
            }
            if ($request->request->get('OpDeshacer')) {
                $respuesta = $em->getRepository(TteIntermediacion::class)->deshacer($request->request->get('OpDeshacer'));
            }
        }
        $query = $this->getDoctrine()->getRepository(TteIntermediacion::class)->lista();
        $arIntermediacions = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/general/intermediacion/lista.html.twig', ['arIntermediacions' => $arIntermediacions, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/transporte/general/intermediacion/nuevo/{id}", name="transporte_proceso_transporte_general_intermediacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIntermediacion = new TteIntermediacion();
        if($id != 0) {
            $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);
        }
        $form = $this->createForm(IntermediacionType::class, $arIntermediacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($arIntermediacion);
            $em->flush();
            return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_intermediacion'));

        }
        return $this->render('transporte/proceso/transporte/general/intermediacion/nuevo.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/transporte/general/intermediacion/detalle/{id}", name="transporte_proceso_transporte_general_intermediacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteIntermediacionDetalle::class)->detalle($id), "Intermediacion");
            }
        }
        $arIntermediacionDetalles = $this->getDoctrine()->getRepository(TteIntermediacionDetalle::class)->detalle($id);
        return $this->render('transporte/proceso/transporte/general/intermediacion/detalle.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'arIntermediacionDetalles' => $arIntermediacionDetalles,
            'form' => $form->createView()]);
    }

}

