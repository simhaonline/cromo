<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SiplaftController extends Controller
{
   /**
    * @Route("/transporte/utilidad/transporte/siplaft/lista", name="transporte_utilidad_transporte_sipleft_lista")
    */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoDespacho', TextType::class, ['required' => false, 'data' => $session->get('filtroCodigoDespacho'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCodigoDespacho', $form->get('txtCodigoDespacho')->getData());
            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteDespachoDetalle::class)->siplatf()->getQuery()->execute(), "Novedades");
        }
        $query = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->siplatf();
        $arDespachoDetalles = $paginator->paginate($query, $request->query->getInt('page', 1),100);
        return $this->render('transporte/informe/transporte/despacho/detalle.html.twig', [
            'arDespachoDetalles' => $arDespachoDetalles,
            'form' => $form->createView()]);
    }


}

