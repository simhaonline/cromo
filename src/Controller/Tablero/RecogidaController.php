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
        $fecha = new \DateTime('now');
        $arrResumenRecogidas = $em->getRepository(Recogida::class)->resumenCuenta($fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
        return $this->render('tablero/recogida.html.twig', [
            'arrResumenRecogidas' => $arrResumenRecogidas
            ]);
    }
}

