<?php

namespace App\Controller\Movimiento\Transporte\Cumplido;

use App\Entity\Cumplido;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CumplidoController extends Controller
{
   /**
    * @Route("/mto/transporte/cumplido/lista", name="mto_transporte_cumplido_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Guia::class)->lista();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/transporte/guia/lista.html.twig', ['arGuias' => $arGuias]);
    }

}

