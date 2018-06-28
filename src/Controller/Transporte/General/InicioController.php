<?php

namespace App\Controller\Transporte\General;

use App\Formato\Despacho;
use App\Entity\TteRecogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/tte/general/inicio", name="transporte_general_inicio")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('transporte/general/inicio.html.twig');
    }
}

