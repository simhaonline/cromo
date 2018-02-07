<?php

namespace App\Controller\Proceso\Recogida\Recogida;

use App\Entity\Recogida;
use App\Entity\RecogidaProgramada;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgramadaController extends Controller
{
   /**
    * @Route("/pro/recogida/recogida/programada", name="mto_recogida_recogida_programada")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $arRecogidasProgramadas = $this->getDoctrine()->getRepository(RecogidaProgramada::class)->findAll();
                $fecha = new \DateTime('now');
                $fechaHora = $fecha->format('Y-m-d');
                foreach ($arRecogidasProgramadas as $arRecogidaProgramada) {
                    $arRecogida = new Recogida();
                    $fechaRecogida = date_create($fechaHora . " " . $arRecogidaProgramada->getHora()->format('H:i'));
                    $arRecogida->setFechaRegistro(new \DateTime('now'));
                    $arRecogida->setFecha($fechaRecogida);
                    $arRecogida->setClienteRel($arRecogidaProgramada->getClienteRel());
                    $arRecogida->setOperacionRel($arRecogidaProgramada->getOperacionRel());
                    $arRecogida->setCiudadRel($arRecogidaProgramada->getCiudadRel());
                    $arRecogida->setAnunciante($arRecogidaProgramada->getAnunciante());
                    $arRecogida->setDireccion($arRecogidaProgramada->getDireccion());
                    $arRecogida->setTelefono($arRecogidaProgramada->getTelefono());
                    $arRecogida->setComentario($arRecogidaProgramada->getComentario());
                    $em->persist($arRecogida);
                }
                $em->flush();
            }
        }
        $query = $this->getDoctrine()->getRepository(RecogidaProgramada::class)->lista();
        $arRecogidasProgramadas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('proceso/recogida/recogida/programada.html.twig', ['arRecogidasProgramadas' => $arRecogidasProgramadas, 'form' => $form->createView()]);
    }
}

