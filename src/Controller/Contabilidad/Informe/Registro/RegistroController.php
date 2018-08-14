<?php

namespace App\Controller\Contabilidad\Informe\Registro;

use App\Controller\Estructura\MensajesController;
use App\Entity\Contabilidad\CtbRegistro;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
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

class RegistroController extends Controller
{
    /**
     * @Route("/Contabildiad/informe/registro/registro", name="contabilidad_informe_registro_registro_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtComprobante', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbComprobante'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroDesde', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbNumeroDesde'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroHasta', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbNumeroHasta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCuenta', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbCuenta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCentroCosto', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbCentroCosto'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroCtbCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroCtbComprobante', $form->get('txtComprobante')->getData());
                $session->set('filtroCtbNumeroDesde', $form->get('txtNumeroDesde')->getData());
                $session->set('filtroCtbNumeroHasta', $form->get('txtNumeroHasta')->getData());
                $session->set('filtroCtbCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroCtbCentroCosto', $form->get('txtCentroCosto')->getData());
                $session->set('filtroCtbNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(CtbRegistro::class)->registros())->execute(), "Registros");
            }
        }
        $query = $this->getDoctrine()->getRepository(CtbRegistro::class)->registros();
        $arRegistros = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('contabilidad/informe/registro/registros.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView() ]);
    }
}

