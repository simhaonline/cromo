<?php

namespace App\Controller\Documental\General;

use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocMasivo;
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
        $query = $em->createQuery($em->getRepository(DocMasivo::class)->listaArchivo($tipo, $codigo));
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);
        return $this->render('documental/general/lista.html.twig', array(
            'arArchivos' => $arArchivos,
            'tipo' => $tipo,
            'codigo' => $codigo,
        ));
    }

    /**
     * @Route("/documental/general/descargar/{codigo}", name="documental_general_general_descargar")
     */
    public function descargarAction($codigo) {
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        $arArchivo = $em->getRepository(DocMasivo::class)->find($codigo);
        $strRuta = $arrConfiguracion['rutaAlmacenamiento'] . "/" . $arArchivo->getCodigoMasivoTipoFk() . "/" .  $arArchivo->getDirectorio() . "/" . $arArchivo->getArchivoDestino();
        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', "application/pdf");
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getArchivo() . '";');
        $response->headers->set('Content-length', $arArchivo->getTamano());
        $response->sendHeaders();
        if(file_exists ($strRuta)){
            $response->setContent(readfile($strRuta));
        }else{
            echo "<script>alert('No existe el archivo en el servidor a pesar de estar asociado en base de datos, por favor comuniquese con soporte');window.close()</script>";
        }
        return $response;

    }

}

