<?php

namespace App\Controller\Calendario;

use App\Entity\Cartera\CarCliente;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CalendarioController extends Controller
{
   /**
    * @Route("/calendario/lista", name="calendario_lista")
    */    
    public function lista(Request $request)
    {
        return $this->render('calendario/calendar.html.twig');
    }

    /**
     * @return JsonResponse
     * @Route("/calendario/guardar/evento/", name="calendario_guardar_evento")
     */
    public function guardarEvento(Request $request){
        $em = $this->getDoctrine()->getManager();
        $responseArray = [];
        return new JsonResponse($responseArray);
    }
}

