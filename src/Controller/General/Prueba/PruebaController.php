<?php

namespace App\Controller\General\Prueba;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFactura;
use App\Entity\General\GenFacturaDetalle;
use App\Entity\Inventario\InvLote;
use App\Entity\Transporte\TteConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general/prueba", name="general_prueba")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $estado = $em->getRepository(InvLote::class)->notificacionDiariaMercanciaVencidaBodega();
        echo "prueba";
        exit;
    }

}
