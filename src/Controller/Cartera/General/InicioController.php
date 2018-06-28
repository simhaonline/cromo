<?php

namespace App\Controller\Cartera\General;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/car/general/inicio", name="cartera_general_inicio")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('cartera/general/inicio.html.twig');
    }
}

