<?php

namespace App\Controller\Tablero;

use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GuiaController extends Controller
{
   /**
    * @Route("/tab/guia", name="tab_guia")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrResumenPendientes = $em->getRepository(Guia::class)->resumenPendientesCuenta();
        return $this->render('tablero/guia.html.twig', [
            'arrResumenPendientes' => $arrResumenPendientes
            ]);
    }
}

