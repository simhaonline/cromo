<?php

namespace App\Controller\Transporte\Informe\Comercial\Guia;

use App\Entity\General\GenAsesor;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Formato\Transporte\ProduccionAsesor;
use App\Formato\Transporte\ProduccionCliente;

use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProduccionAsesorController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/comercial/guia/produccion/asesor", name="transporte_informe_comercial_guia_produccion_asesor")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => true, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => true, 'data' => $fecha])
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('cboAsesorRel', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arGuias = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $arGuiaTipo = $form->get('cboGuiaTipoRel')->getData();
                    if ($arGuiaTipo) {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                    }
                    $arAsesor = $form->get('cboAsesorRel')->getData();
                    if ($arAsesor) {
                        $session->set('filtroTteGuiaCodigoAsesor', $arAsesor->getCodigoAsesorPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoAsesor', null);
                    }
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    $queryBuilder = $this->getDoctrine()->getRepository(TteGuia::class)->informeProduccionAsesor($fechaDesde, $fechaHasta);
                    $arGuias = $queryBuilder->getQuery()->getResult();
                    $arGuias = $paginator->paginate($arGuias, $request->query->getInt('page', 1), 1000);
                }
                if ($form->get('btnExcel')->isClicked()) {
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    General::get()->setExportar($em->createQuery($this->getDoctrine()->getRepository(TteGuia::class)->informeProduccionAsesor($fechaDesde, $fechaHasta))->execute(), "Produccion_Asesor");
                }
            }
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new ProduccionAsesor();
                $formato->Generar($em, $fechaDesde, $fechaHasta);
            }
        }
        return $this->render('transporte/informe/comercial/guia/informeProduccionAsesor.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }
}
