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
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $fecha])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        $arGuias = null;
        if ($form->get('btnFiltrar')->isClicked()) {
            if($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                $queryBuilder = $this->getDoctrine()->getRepository(TteGuia::class)->siplatf($fechaDesde, $fechaHasta);
                $arGuias = $queryBuilder->getQuery()->getResult();
                $arGuias = $paginator->paginate($arGuias, $request->query->getInt('page', 1), 1000);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
            General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->siplatf($fechaDesde, $fechaHasta))->execute(), "Siplatf");
        }
        return $this->render('transporte/informe/transporte/despacho/siplatf.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }


}

