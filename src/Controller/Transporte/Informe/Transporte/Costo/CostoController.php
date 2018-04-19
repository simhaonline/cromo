<?php

namespace App\Controller\Transporte\Informe\Transporte\Costo;

use App\Entity\Transporte\TteCosto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoController extends Controller
{
   /**
    * @Route("/tte/inf/transporte/costo/general", name="tte_inf_transporte_costo_general")
    */    
    public function lista(Request $request)
    {
        $arCostos = $this->getDoctrine()->getRepository(TteCosto::class)->lista();
        return $this->render('transporte/informe/transporte/costo/general.html.twig', ['arCostos' => $arCostos]);
    }
}

