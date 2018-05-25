<?php

namespace App\Controller\Principal;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/", name="inicio")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('principal/inicio.html.twig');
    }
}

