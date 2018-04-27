<?php

namespace App\Controller\Cartera\Administracion\Cliente;

use App\Entity\Cartera\CarCliente;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
   /**
    * @Route("/car/adm/cliente/lista", name="car_adm_cliente_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(CarCliente::class)->lista();
        $arCliente = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('cartera/administracion/cliente/lista.html.twig', ['arCliente' => $arCliente]);
    }

}

