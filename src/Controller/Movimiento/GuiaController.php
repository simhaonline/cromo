<?php

namespace App\Controller\Movimiento;

use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuiaController extends Controller
{
   /**
    * @Route("/mto/guia/lista", name="mto_guia_lista")
    */    
    public function lista()
    {
        $arGuias = $this->getDoctrine()
            ->getRepository(Guia::class)
            ->listaMovimiento();

        return $this->render('movimiento/guia/lista.html.twig', ['arGuias' => $arGuias]);
    }
}

