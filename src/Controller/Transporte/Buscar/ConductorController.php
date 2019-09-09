<?php

namespace App\Controller\Transporte\Buscar;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteConductor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
class ConductorController extends Controller
{
   /**
    * @Route("/transporte/bus/conductor/{campoCodigo}/{campoNombre}", name="transporte_bus_conductor")
    */    
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre', 'data' => $session->get('filtroTteConductorNombre')))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo'))
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'Numero identificacion'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroTteConductorNombre', $form->get('TxtNombre')->getData());
            }
        }
        $arConductores = $paginator->paginate($em->getRepository(TteConductor::class)->listaDql(), $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/conductor.html.twig', array(
            'arConductores' => $arConductores,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/transporte/bus/ciudad/{campoCodigo}/{campoNombre}", name="transporte_bus_ciudad")
     */
    public function listaCiudad(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo', 'data' => $session->get('filtroTteCiudadCodigo')))
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre', 'data' => $session->get('filtroTteCiudadNombre')))
//            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'Numero identificacion'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroTteCiudadNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroTteCiudadCodigo', $form->get('TxtCodigo')->getData());
            }
        }
        $arCiudades = $paginator->paginate($em->getRepository(TteCiudad::class)->listaDql(), $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/ciudadOrigen.html.twig', array(
            'arCiudades' => $arCiudades,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/transporte/bus/ciudadDestino/{campoCodigo}/{campoNombre}", name="transporte_bus_ciudad_destino")
     */
    public function listaCiudadDestino(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtCodigoCiudadDestino', TextType::class, array('label'  => 'Codigo', 'data' => $session->get('filtroTteCiudadCodigoDestino')))
            ->add('TxtNombreCiudadDestino', TextType::class, array('label'  => 'Nombre', 'data' => $session->get('filtroTteCiudadNombreDestino')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroTteCiudadCodigoDestino', $form->get('TxtCodigoCiudadDestino')->getData());
                $session->set('filtroTteCiudadNombreDestino', $form->get('TxtNombreCiudadDestino')->getData());
            }
        }
        $arCiudades = $paginator->paginate($em->getRepository(TteCiudad::class)->listaDqlCiudadDestino(), $request->query->get('page', 1), 20);
        return $this->render('transporte/buscar/ciudadDestino.html.twig', array(
            'arCiudades' => $arCiudades,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

