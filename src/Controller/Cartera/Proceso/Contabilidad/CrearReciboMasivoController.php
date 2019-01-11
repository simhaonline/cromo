<?php

namespace App\Controller\Cartera\Proceso\Contabilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CrearReciboMasivoController extends Controller
{
    /**
     * @Route("/cartera/proceso/contabilidad/crearrecibomasivo/lista", name="cartera_proceso_contabilidad_crearrecibomasivo_lista")
     */
    public function lista(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $paginator  = $this->get('knp_paginator');
        $arCrearReciboMasivos=$paginator->paginate($em->getRepository('App:Cartera\CarCuentaCobrar')->crearReciboMasivoLista(), $request->query->getInt('page', 1),100);
        return $this->render('cartera/proceso/contabilidad/crearrecibomasivo/lista.html.twig', [
            'arCrearReciboMasivos' => $arCrearReciboMasivos,
        ]);
    }
}
