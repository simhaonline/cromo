<?php

namespace App\Controller\Transporte\Tablero\Transporte\Guia;


use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;
class ResumenController extends AbstractController
{
   /**
    * @Route("/transporte/tablero/transporte/guia/resumen", name="transporte_tablero_transporte_guia_resumen")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arResumen = [];
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaIngresoDesde', DateType::class, ['widget' => 'single_text', 'required' => false, 'data' => $fecha])
            ->add('fechaIngresoHasta', DateType::class, ['widget' => 'single_text', 'required' => false, 'data' => $fecha])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                if ($form->get('btnFiltrar')->isClicked()) {
                    $raw['filtros'] = $this->getFiltros($form);
                    $arResumen = $em->getRepository(TteGuia::class)->tableroResumen($raw);

                }
                if ($form->get('btnExcel')->isClicked()) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);
                    $raw['filtros'] = $this->getFiltros($form);
                    General::get()->setExportar($em->getRepository(TteGuia::class)->tableroResumen($raw), "Resumen guias");
                }
            }
        }

        return $this->render('transporte/tablero/transporte/guia/resumen.html.twig', [
            'arResumen' => $arResumen,
            'form' => $form->createView()
            ]);
    }

    private function getFiltros($form)
    {
        $filtro = [
            'fechaIngresoDesde' => $form->get('fechaIngresoDesde')->getData() ?$form->get('fechaIngresoDesde')->getData()->format('Y-m-d'): null,
            'fechaIngresoHasta' => $form->get('fechaIngresoHasta')->getData() ?$form->get('fechaIngresoHasta')->getData()->format('Y-m-d'): null,
        ];
        return $filtro;

    }
}

