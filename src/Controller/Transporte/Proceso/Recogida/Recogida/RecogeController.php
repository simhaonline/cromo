<?php

namespace App\Controller\Transporte\Proceso\Recogida\Recogida;

use App\Entity\Transporte\TteRecogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecogeController extends Controller
{
   /**
    * @Route("/transporte/proceso/recogida/recogida/recoge", name="transporte_proceso_recogida_recogida_recoge")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteRecogida::class)->findBy(array('codigoRecogidaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('codigoDespachoFk', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnRecogida', SubmitType::class, array('label' => 'Recogida'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespachoRecogida = $form->get('codigoDespachoFk')->getData();
                $query = $this->getDoctrine()->getRepository(TteRecogida::class)->listaRecoge($codigoDespachoRecogida);
            }
            if ($form->get('btnRecogida')->isClicked()) {
                $arrRecogidas = $request->request->get('chkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(TteRecogida::class)->recoge($arrRecogidas, $arrControles);
                $codigoDespachoRecogida = $form->get('codigoDespachoFk')->getData();
                $query = $this->getDoctrine()->getRepository(TteRecogida::class)->listaRecoge($codigoDespachoRecogida);
            }
        }
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/recogida/recogida/recoge.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }
}

