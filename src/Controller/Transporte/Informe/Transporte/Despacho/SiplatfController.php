<?php

namespace App\Controller\Transporte\Informe\Transporte\Despacho;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SiplatfController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/despacho/siplatf", name="transporte_informe_transporte_despacho_siplatf")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
//            ->add('txtCodigoDespacho', TextType::class, ['required' => false, 'data' => $session->get('filtroCodigoDespacho'), 'attr' => ['class' => 'form-control']])
//            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTteDespachoSiplatfFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteDespachoSiplatfFechaHasta'))])
//            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
//            $session->set('filtroCodigoDespacho', $form->get('txtCodigoDespacho')->getData());
//            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
//            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroTteDespachoSiplatfFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroTteDespachoSiplatfFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(TteDespachoDetalle::class)->siplatf())->execute(), "Siplatf");
        }
        $query = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->siplatf();
        $arDespachoDetalles = $paginator->paginate($query, $request->query->getInt('page', 1),100);
        return $this->render('transporte/informe/transporte/despacho/siplatf.html.twig', [
            'arDespachoDetalles' => $arDespachoDetalles,
            'form' => $form->createView()]);
    }


}

