<?php

namespace App\Controller\Transporte\Informe\Financiero;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Formato\Transporte\Rentabilidad;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RentabilidadController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/financiero/despacho/rentabilidad", name="transporte_informe_financiero_despacho_rentabilidad")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $fecha])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arDespachos = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnPdf')->isClicked()) {
                    if($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                        $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                        $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                        $queryBuilder = $this->getDoctrine()->getRepository(TteDespacho::class)->rentabilidad($fechaDesde, $fechaHasta);
                        $arDespachos = $queryBuilder->getQuery()->getResult();
                        $arDespachos = $paginator->paginate($arDespachos, $request->query->getInt('page', 1), 1000);
                    }
                }
                if ($form->get('btnPdf')->isClicked()) {
                    $formato = new Rentabilidad();
                    $formato->Generar($em, $form->get('fechaDesde')->getData()->format('Y-m-d'),$form->get('fechaHasta')->getData()->format('Y-m-d') );
                }
                if ($form->get('btnExcel')->isClicked()) {
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    General::get()->setExportar($em->getRepository(TteDespacho::class)->rentabilidad($fechaDesde, $fechaHasta)->getQuery()->getResult(), "Desembarcos");
                }
            }
        }

        return $this->render('transporte/informe/financiero/rentabilidad.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()]);
    }

}

