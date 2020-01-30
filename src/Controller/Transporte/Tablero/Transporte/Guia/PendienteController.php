<?php

namespace App\Controller\Transporte\Tablero\Transporte\Guia;


use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PendienteController extends MaestroController
{


    public $tipo = "transporte";
    public $proceso = "ttet0003";




    /**
    * @Route("/transporte/tablero/transporte/guia/pendiente", name="transporte_tablero_transporte_guia_pendiente")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrResumenPendientes = $em->getRepository(TteGuia::class)->resumenPendientesCuenta();
        return $this->render('transporte/tablero/transporte/guia/pendiente.html.twig', [
            'arrResumenPendientes' => $arrResumenPendientes
            ]);
    }
}

