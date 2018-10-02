<?php

namespace App\Controller\RecursoHumano\Movimiento\Embargo;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmbargoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/movimiento/embargo/embargo/lista", name="recursohumano_movimiento_embargo_embargo_lista")
     */
    public function lista(Request $request)
    {
        $clase = RhuEmbargo::class;
        $em = $this->getDoctrine()->getManager();

        $arrOpciones = $em->getRepository($clase)->parametrosLista();
        $form = Estandares::botoneraLista();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('recursoHumano/movimiento/embargo/lista.html.twig', [
            'arrOpciones' => $arrOpciones,
            'request' => $request,
            'form' => $form->createView()
        ]);
    }


}