<?php

namespace App\Controller\Cartera\Informe\Recibo;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
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

class DetalleController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/informe/recibo/detalle", name="cartera_informe_recibo_detalle")
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
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeReciboDetalleFechaDesde') ? date_create($session->get('filtroInvInformeItemRotacionFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeReciboDetalleFechaHasta') ? date_create($session->get('filtroInvInformeItemRotacionFechaHasta')): null])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarReciboNumero')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCarReciboNumero', $form->get('txtNumero')->getData());
            $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroInvInformeReciboDetalleFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
            $session->set('filtroInvInformeReciboDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(CarReciboDetalle::class)->detalle()->getQuery()->getResult(), "Recibos");
        }
        $arReciboDetalles = $paginator->paginate($em->getRepository(CarReciboDetalle::class)->detalle(), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/informe/recibo/detalle.html.twig', [
            'arReciboDetalles' => $arReciboDetalles,
            'form' => $form->createView()]);
    }
}

