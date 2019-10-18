<?php

namespace App\Controller\General\Utilidad\FacturaElectronica;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocImagen;
use App\Entity\Documental\DocMasivo;
use App\Entity\General\GenInconsistencia;
use App\Entity\General\GenLog;
use App\Entity\General\GenRespuestaFacturaElectronica;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RespuestaController extends Controller
{
    /**
     * @Route("/general/utilidad/facturaelectronica/respuesta/ver/{entidad}/{codigo}", name="general_utilidad_facturaelectronica_respuesta_ver")
     */
    public function ver(Request $request, $entidad, $codigo)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*if($form->get('btnExcel')->isClicked()){
                ob_clean();
                $this->generarExcelLogComparativo($detalleSeguimiento,"LogExtendido");
            }*/
        }
        $arRespuestas = $paginator->paginate($em->getRepository(GenRespuestaFacturaElectronica::class)->lista($entidad, $codigo), $request->query->getInt('page', 1), 1000);
        return $this->render('general/utilidad/respuesta/ver.html.twig', [
            'arRespuestas'      =>  $arRespuestas,
            'form' => $form->createView()
        ]);
    }


}

