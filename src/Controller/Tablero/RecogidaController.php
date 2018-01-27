<?php

namespace App\Controller\Tablero;

use App\Formato\Despacho;
use App\Entity\Recogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RecogidaController extends Controller
{
   /**
    * @Route("/tab/recogida", name="tab_recogida")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrResumenRecogidas = $em->getRepository(Recogida::class)->resumenCuenta('2018/01/26', '2018/01/26');
        return $this->render('tablero/recogida.html.twig');
    }
}

