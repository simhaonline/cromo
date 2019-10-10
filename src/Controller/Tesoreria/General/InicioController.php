<?php

namespace App\Controller\Tesoreria\General;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/tesoreria/general/inicio", name="tesoreria_general_general_inicio_ver")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('base.html.twig');
        //return $this->render('v2/tesoreria/inicio.html.twig');
    }
}

