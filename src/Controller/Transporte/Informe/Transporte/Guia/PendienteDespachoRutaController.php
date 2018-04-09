<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PendienteDespachoRutaController extends Controller
{
   /**
    * @Route("/tte/inf/transporte/guia/pendientedespachoruta", name="tte_inf_transporte_guia_pendiente_despacho_ruta")
    */    
    public function lista(Request $request)
    {
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->informeDespachoPendienteRuta();
        return $this->render('transporte/informe/transporte/guia/despachoPendienteRuta.html.twig', ['arGuias' => $arGuias]);
    }
}

