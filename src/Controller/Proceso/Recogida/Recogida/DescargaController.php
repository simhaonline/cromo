<?php

namespace App\Controller\Proceso\Recogida\Recogida;

use App\Entity\Recogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DescargaController extends Controller
{
   /**
    * @Route("/pro/recogida/recogida/descarga", name="pro_recogida_recogida_descarga")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Recogida::class)->findBy(array('codigoRecogidaPk' => NULL));
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class)
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnDescarga', SubmitType::class, array('label' => 'Descarga'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoDespachoRecogida = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(Recogida::class)->listaDescarga($codigoDespachoRecogida);
            }
            if ($form->get('btnDescarga')->isClicked()) {
                $arrRecogidas = $request->request->get('chkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(Recogida::class)->descarga($arrRecogidas, $arrControles);
                $codigoDespachoRecogida = $form->get('txtNumero')->getData();
                $query = $this->getDoctrine()->getRepository(Recogida::class)->listaDescarga($codigoDespachoRecogida);
            }
        }
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('proceso/recogida/recogida/descarga.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }
}

