<?php

namespace App\Controller\Transporte\Informe\Servicio\Novedad;

use App\Entity\Transporte\TteNovedad;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PendienteSolucionarController extends Controller
{
   /**
    * @Route("/transporte/inf/control/novedad/pendiente/solucionar", name="transporte_inf_servicio_novedad_pendiente_solucionar")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnReportar', SubmitType::class, array('label' => 'Reportar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    if ($form->get('txtCodigoCliente')->getData() != '') {
                        $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                        $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    } else {
                        $session->set('filtroTteCodigoCliente', null);
                        $session->set('filtroTteNombreCliente', null);
                    }
                }
                if ($form->get('btnReportar')->isClicked()) {
                    $arrNovedades = $request->request->get('chkSeleccionar');
                    $arrControles = $request->request->All();
                    $respuesta = $this->getDoctrine()->getRepository(TteNovedad::class)->setReportar($arrNovedades, $arrControles);
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(TteNovedad::class)->pendienteSolucionar())->execute(), 'Novedades pendientes por solucionar');
                }
            }
        }
        $arNovedades = $paginator->paginate($this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionar(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/informe/servicio/novedad/pendienteSolucionar.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }
}

