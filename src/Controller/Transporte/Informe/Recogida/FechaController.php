<?php

namespace App\Controller\Transporte\Informe\Recogida;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecogida;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FechaController extends Controller
{
   /**
    * @Route("/transporte/informe/recogida/recogida/fecha", name="transporte_informe_recogida_recogida_fecha")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arRecogidas = null;
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $fecha])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroTteCodigoCliente', null);
                $session->set('filtroTteNombreCliente', null);
            }
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
            $arRecogidas = $paginator->paginate($em->getRepository(TteRecogida::class)->fecha($fechaDesde, $fechaHasta), $request->query->getInt('page', 1), 500);
        }
        if ($form->get('btnExcel')->isClicked()) {
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
            General::get()->setExportar($em->getRepository(TteRecogida::class)->fecha($fechaDesde, $fechaHasta)->getQuery()->getResult(), "RecogidasFecha");
        }

        return $this->render('transporte/informe/recogida/recogida/fecha.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }


}

