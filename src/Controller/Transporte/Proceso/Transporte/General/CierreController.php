<?php

namespace App\Controller\Transporte\Proceso\Transporte\General;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCierre;
use App\Entity\TteGuia;
use App\Form\Type\Transporte\CierreType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CierreController extends Controller
{
   /**
    * @Route("/transporte/proceso/transporte/general/cierre", name="transporte_proceso_transporte_general_cierre")
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
                $respuesta = $em->getRepository(TteCierre::class)->generar($request->request->get('OpGenerar'));
            }
            if ($request->request->get('OpDeshacer')) {
                $respuesta = $em->getRepository(TteCierre::class)->deshacer($request->request->get('OpDeshacer'));
            }
        }
        $query = $this->getDoctrine()->getRepository(TteCierre::class)->lista();
        $arCierres = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/general/cierre/lista.html.twig', ['arCierres' => $arCierres, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/transporte/general/cierre/nuevo/{id}", name="transporte_proceso_transporte_general_cierre_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierre = new TteCierre();
        if($id != 0) {
            $arCierre = $em->getRepository(TteCierre::class)->find($id);
        }
        $form = $this->createForm(CierreType::class, $arCierre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($arCierre);
            $em->flush();
            return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_cierre'));

        }
        return $this->render('transporte/proceso/transporte/general/cierre/nuevo.html.twig', [
            'arCierre' => $arCierre,
            'form' => $form->createView()]);
    }

}

