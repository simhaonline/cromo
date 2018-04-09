<?php

namespace App\Controller\Transporte\Movimiento\Monitoreo\Monitoreo;

use App\Entity\TteMonitoreo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MonitoreoController extends Controller
{
   /**
    * @Route("/tte/mon/monitoreo/monitoreo/lista", name="tte_mon_monitoreo_monitoreo_lista")
    */    
    public function lista()
    {
        $arMonitoreos = $this->getDoctrine()->getRepository(TteMonitoreo::class)->lista();
        return $this->render('transporte/movimiento/monitoreo/monitoreo/lista.html.twig', ['arMonitoreos' => $arMonitoreos]);
    }
}

