<?php

namespace App\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuiaController extends Controller
{
   /**
    * @Route("/inicio", name="inicio")
    */    
    public function inicio()
    {
        return $this->render('general/inicio.html.twig');
    }
}

