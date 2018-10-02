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
        $arrParametrosLista = $em->getRepository($clase)->parametrosLista();
        $formBotonera = Estandares::botoneraLista();
        $formBotonera->handleRequest($request);
        if($formBotonera->isSubmitted() && $formBotonera->isValid()){

        }
        return $this->render('recursoHumano/movimiento/embargo/lista.html.twig', [
            'arrParametrosLista' => $arrParametrosLista,
            'request' => $request,
            'formBotonera' => $formBotonera->createView()
        ]);
    }


}