<?php

namespace App\Controller\Informe\Transporte\Guia;

use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PendienteDespachoRutaController extends Controller
{
   /**
    * @Route("/inf/transporte/guia/pendientedespachoruta", name="inf_transporte_guia_pendiente_despacho_ruta")
    */    
    public function lista(Request $request)
    {
        $arGuias = $this->getDoctrine()->getRepository(Guia::class)->informeDespachoPendienteRuta();
        return $this->render('informe/transporte/guia/despachoPendienteRuta.html.twig', ['arGuias' => $arGuias]);
    }
}

