<?php

namespace App\Controller\Movimiento\Recogida\Recogida;

use App\Entity\Recogida;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecogidaController extends Controller
{
   /**
    * @Route("/mto/recogida/recogida/lista", name="mto_recogida_recogida_lista")
    */    
    public function lista()
    {
        $arRecogidas = $this->getDoctrine()
            ->getRepository(Recogida::class)->lista();
        return $this->render('movimiento/recogida/recogida/lista.html.twig', ['arRecogidas' => $arRecogidas]);
    }

    /**
     * @Route("/mto/recogida/recogida/detalle/{codigoRecogida}", name="mto_recogida_recogida_detalle")
     */
    public function detalle(Request $request, $codigoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(Recogida::class)->find($codigoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                //$formato = new \App\Formato\Despacho();
                //$formato->Generar($em, $codigoRecogida);
            }
        }

        return $this->render('movimiento/recogida/recogida/detalle.html.twig', [
            'arRecogida' => $arRecogida,
            'form' => $form->createView()]);
    }
}

