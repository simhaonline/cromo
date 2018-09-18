<?php

namespace App\Controller\Documental\General;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
   /**
    * @Route("/documental/general/general/inicio/ver", name="documental_general_general_inicio_ver")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('documental/general/inicio.html.twig');
    }

    /**
     * @Route("/documental/general/lista/{tipo}/{codigo}", name="documental_general_general_lista")
     */
    public function listaAction(Request $request, $tipo, $codigo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->listaDQL($codigoDocumento, $numero));
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:lista.html.twig', array(
            'arArchivos' => $arArchivos,
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
        ));
    }

}

