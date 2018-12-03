<?php

namespace App\Controller\General;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/gen/general/inicio", name="general_general_inicio")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('base.html.twig');
    }
}

