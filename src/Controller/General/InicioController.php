<?php

namespace App\Controller\General;

use App\Formato\Despacho;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('general/inicio.html.twig');
    }
}

