<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PendienteEntregaController extends Controller
{
   /**
    * @Route("/transporte/informe/transporte/guia/pendiente/entrega", name="transporte_informe_transporte_guia_pendiente_entrega")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaDesdeEntrega', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => $session->get('filtroFechaDesdeEntrega')])
            ->add('fechaHastaEntrega', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $session->get('filtroFechaHastaEntrega')])
            ->add('txtGuia', NumberType::class, ['label' => 'Guia: ', 'required' => false, 'data' => $session->get('filtroNumeroGuia')])
            ->add('txtConductor', TextType::class, ['label' => 'Conductor: ', 'required' => false, 'data' => $session->get('filtroConductor')])
            ->add('txtDocumentoCliente', TextType::class, ['label' => 'Documento cliente: ', 'required' => false, 'data' => $session->get('filtroDocumentoCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroFechaDesdeEntrega',  $form->get('fechaDesdeEntrega')->getData());
            $session->set('filtroFechaHastaEntrega', $form->get('fechaHastaEntrega')->getData());
            $session->set('filtroNumeroGuia', $form->get('txtGuia')->getData());
            $session->set('filtroConductor', $form->get('txtConductor')->getData());
            $session->set('filtroDocumentoCliente', $form->get('txtDocumentoCliente')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->excelPendienteEntrega())->execute(), "Novedades");
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->pendienteEntrega(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/pendienteEntrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }


}

