<?php

namespace App\Controller\General\Utilidad\Control;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocImagen;
use App\Entity\Documental\DocMasivo;
use App\Entity\General\GenInconsistencia;
use App\Entity\General\GenLog;
use App\Entity\General\GenModelo;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InconsistenciaController extends Controller
{
    /**
     * @Route("/general/utilidad/control/inconsistencia/ver/{entidad}/{codigo}", name="general_utilidad_control_inconsistencia_ver")
     */
    public function ver(Request $request, $entidad, $codigo)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnValidar', SubmitType::class, array('label' => 'Validar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnValidar')->isClicked()){
                $arModelo = $em->getRepository(GenModelo::class)->find($entidad);
                $repositorio = "App:{$arModelo->getCodigoModuloFk()}\\{$entidad}";
                $em->getRepository($repositorio)->validarAprobado($codigo);
            }
        }
        $arInconsistencias = $paginator->paginate($em->getRepository(GenInconsistencia::class)->lista($entidad, $codigo), $request->query->getInt('page', 1), 1000);
        return $this->render('general/utilidad/inconsistencia/ver.html.twig', [
            'arInconsistencias'      =>  $arInconsistencias,
            'form' => $form->createView()
        ]);
    }


}

