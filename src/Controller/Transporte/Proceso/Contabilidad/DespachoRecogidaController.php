<?php

namespace App\Controller\Transporte\Proceso\Contabilidad;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class DespachoRecogidaController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/contabilidad/despachorecogida/lista", name="transporte_proceso_contabilidad_despachorecogida_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroTteDespachoRecogidaFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTteDespachoRecogidaFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteDespachoRecogidaFechaHasta'))])
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteDespachoRecogidaFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroTteDespachoRecogidaFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroTteDespachoRecogidaFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespachoRecogida::class)->contabilizar($arr);
            }
        }
        $arDespachosRecogida = $paginator->paginate($em->getRepository(TteDespachoRecogida::class)->listaContabilizar(), $request->query->getInt('page', 1),100);
        return $this->render('transporte/proceso/contabilidad/despachoRecogida/lista.html.twig',
            ['arDespachosRecogida' => $arDespachosRecogida,
            'form' => $form->createView()]);
    }

}

