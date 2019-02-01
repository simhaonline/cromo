<?php

namespace App\Controller\Financiero\Informe\Contabilidad;

use App\Controller\Estructura\MensajesController;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Financiero\Auxiliar1;
use App\Formato\Transporte\ControlFactura;
use App\Formato\Transporte\FacturaInforme;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AuxiliarController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/informe/contabilidad/auxiliar/lista", name="financiero_informe_contabilidad_auxiliar_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtComprobante', TextType::class, ['required' => false, 'data' => $session->get('filtroFinComprobante'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroDesde', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroDesde'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroHasta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroHasta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCuenta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCuenta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCentroCosto', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCentroCosto'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFinRegistroFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, ['label' => 'Pdf', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroFinCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroFinComprobante', $form->get('txtComprobante')->getData());
                $session->set('filtroFinNumeroDesde', $form->get('txtNumeroDesde')->getData());
                $session->set('filtroFinNumeroHasta', $form->get('txtNumeroHasta')->getData());
                $session->set('filtroFinCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroFinCentroCosto', $form->get('txtCentroCosto')->getData());
                $session->set('filtroFinNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroFinRegistroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(FinRegistro::class)->auxiliar())->execute(), "Registros");
            }
            if ($form->get('btnPdf')->isClicked()) {
                $objMensaje = new Auxiliar1();
                $objMensaje->Generar($em);
            }
        }
        $query = $this->getDoctrine()->getRepository(FinRegistro::class)->auxiliar();
        $arRegistros = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('financiero/informe/auxiliar/registros.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView() ]);
    }
}

