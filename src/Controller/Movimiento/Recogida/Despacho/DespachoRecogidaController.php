<?php

namespace App\Controller\Movimiento\Recogida\Despacho;

use App\Entity\DespachoRecogida;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoRecogidaController extends Controller
{
   /**
    * @Route("/mto/recogida/despacho/lista", name="mto_recogida_despacho_lista")
    */    
    public function lista()
    {
        $arDespachosRecogida = $this->getDoctrine()
            ->getRepository(DespachoRecogida::class)
            ->listaMovimiento();

        return $this->render('movimiento/recogida/despacho/lista.html.twig', ['arDespachosRecogida' => $arDespachosRecogida]);
    }
}

