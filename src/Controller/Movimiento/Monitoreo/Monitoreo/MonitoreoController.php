<?php

namespace App\Controller\Movimiento\Monitoreo\Monitoreo;

use App\Entity\Monitoreo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MonitoreoController extends Controller
{
   /**
    * @Route("/mon/monitoreo/monitoreo/lista", name="mon_monitoreo_monitoreo_lista")
    */    
    public function lista()
    {
        $arMonitoreos = $this->getDoctrine()->getRepository(Monitoreo::class)->lista();
        return $this->render('movimiento/monitoreo/monitoreo/lista.html.twig', ['arMonitoreos' => $arMonitoreos]);
    }
}

