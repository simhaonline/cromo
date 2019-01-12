<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteDesembarco;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class DesembarcoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/desembarco", name="transporte_informe_transporte_guia_desembarco")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('dtFechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => true, 'data' => date_create($session->get('filtroTteDesembarcoFechaDesde'))])
            ->add('dtFechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => true, 'data' => date_create($session->get('filtroTteDesembarcoFechaHasta'))])
            ->add('chkFiltrarFecha', CheckboxType::class,  ['required' => false,'label' => 'Filtrar fecha'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteDesembarcoFechaDesde',  $form->get('dtFechaDesde')->getData() ? $form->get('dtFechaDesde')->getData()->format('Y-m-d') : null);
            $session->set('filtroTteDesembarcoFechaHasta', $form->get('dtFechaHasta')->getData() ? $form->get('dtFechaHasta')->getData()->format('Y-m-d') : null);
            $session->set('filtroTteDesembarcoFiltrarFecha', $form->get('chkFiltrarFecha')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteDesembarco::class)->lista()->getQuery()->getResult(), "Desembarcos");
        }
        $arDesembarcos = $paginator->paginate($em->getRepository(TteDesembarco::class)->lista(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/desembarco.html.twig', [
            'arDesembarcos' => $arDesembarcos,
            'form' => $form->createView()]);
    }
}