<?php

namespace App\Controller\Transporte\Tablero;

use App\Entity\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GuiaController extends Controller
{
   /**
    * @Route("/tte/tab/guia", name="tte_tab_guia")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrResumenPendientes = $em->getRepository(TteGuia::class)->resumenPendientesCuenta();
        return $this->render('tablero/guia.html.twig', [
            'arrResumenPendientes' => $arrResumenPendientes
            ]);
    }
}

