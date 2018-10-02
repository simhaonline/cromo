<?php

namespace App\Controller\RecursoHumano\General;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/recursohumano/general/inicio", name="recursohumano_general_general_inicio_ver")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('recursoHumano/inicio.html.twig');
    }
}

