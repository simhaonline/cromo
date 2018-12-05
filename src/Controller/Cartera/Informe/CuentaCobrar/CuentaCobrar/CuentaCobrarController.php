<?php

namespace App\Controller\Cartera\Informe\CuentaCobrar\CuentaCobrar;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\General\GenAsesor;
use App\Formato\Cartera\CarteraEdad;
use App\Formato\Cartera\CarteraEdadCliente;
use App\Formato\Cartera\CuentaCobrar;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CuentaCobrarController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/Cartera/informe/cuentaCobrar/CuentaCobrar/lista", name="cartera_informe_cuentaCobrar_cuentaCobrar_lista")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnGenerarVencimientos', SubmitType::class, array('label' => 'Generar rango'))
            ->add('btnCarteraEdadesCliente', SubmitType::class, array('label' => 'Cartera edades'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('cboTipoCuentaRel', EntityType::class, $em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('cboAsesor', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCuentaCobrarNumero')])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnPdf')->isClicked() || $form->get('btnCarteraEdadesCliente')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $arCuentaCobrarTipo = $form->get('cboTipoCuentaRel')->getData();
            if ($arCuentaCobrarTipo) {
                $session->set('filtroCarCuentaCobrarTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
            } else {
                $session->set('filtroCarCuentaCobrarTipo', null);
            }
            $arAsesor = $form->get('cboAsesor')->getData();
            if ($arAsesor != '') {
                $session->set('filtroGenAsesor', $form->get('cboAsesor')->getData()->getCodigoAsesorPk());
            } else {
                $session->set('filtroGenAsesor', null);
            }
            $session->set('filtroCarNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
            $session->set('filtroCarCuentaCobrarNumero', $form->get('txtNumero')->getData());
            $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
        }
        if ($form->get('btnPdf')->isClicked()) {
            $formato = new CuentaCobrar();
            $formato->Generar($em);
        }
        if ($form->get('btnCarteraEdadesCliente')->isClicked()) {
            $formato = new CarteraEdadCliente();
            $formato->Generar($em);
        }
        if ($form->get('btnGenerarVencimientos')->isClicked()) {
            $em->getRepository(CarCuentaCobrar::class)->generarVencimientos();
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(CarCuentaCobrar::class)->pendientes())->execute(), "Cuenta cobrar");
        }
        $query = $this->getDoctrine()->getRepository(CarCuentaCobrar::class)->pendientes();
        $arCuentasCobrar = $paginator->paginate($query, $request->query->getInt('page', 1),50);
        return $this->render('cartera/informe/cuentaCobrar.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()]);
    }


}

