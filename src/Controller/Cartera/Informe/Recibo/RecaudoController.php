<?php

namespace App\Controller\Cartera\Informe\Recibo;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\General\GenAsesor;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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

class RecaudoController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/informe/recibo/recaudo", name="cartera_informe_recibo_recaudo")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroInformeReciboFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroInformeReciboFechaHasta'))])
            ->add('cboTipoReciboRel', EntityType::class, $em->getRepository(CarReciboTipo::class)->llenarCombo())
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarReciboNumero')])
            ->add('cboAsesor', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $session->set('filtroCarReciboNumero', $form->get('txtNumero')->getData());
            $session->set('filtroInformeReciboFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroInformeReciboFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroCarCodigoCliente', null);
                $session->set('filtroCarNombreCliente', null);
            }
            $arReciboTipo = $form->get('cboTipoReciboRel')->getData();
            if ($arReciboTipo) {
                $session->set('filtroCarInformeReciboTipo', $arReciboTipo->getCodigoReciboTipoPk());
            } else {
                $session->set('filtroCarInformeReciboTipo', null);
            }
            $arAsesor = $form->get('cboAsesor')->getData();
            if ($arAsesor != '') {
                $session->set('filtroGenAsesor', $form->get('cboAsesor')->getData()->getCodigoAsesorPk());
            } else {
                $session->set('filtroGenAsesor', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(CarRecibo::class)->recaudo())->execute(), "Recaudo");
        }
        $arRecibos = $paginator->paginate($em->getRepository(CarRecibo::class)->recaudo(), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/informe/recibo/recaudo.html.twig', [
            'arRecibos' => $arRecibos,
            'form' => $form->createView()
        ]);
    }
}

