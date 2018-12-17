<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PendienteConductorController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/pendiente/conductor", name="transporte_informe_transporte_guia_pendiente_conductor")
     */
    public function lista2(Request $request)
    {
        $session = New Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTtePendienteSoporteFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTtePendienteSoporteFechaHasta'))])
            ->add('txtCodigoConductor', TextType::class, array('data' => $session->get('filtroTteCodigoConductor')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTtePendienteSoporteFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroTtePendienteSoporteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroTteCodigoConductor', $form->get('txtCodigoConductor')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteGuia::class)->pendienteConductor()->getQuery()->execute(), "Pendiente soporte");
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->pendienteConductor(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/pendienteConductor.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

}

