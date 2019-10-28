<?php

namespace App\Controller\Transporte\Tablero\Transporte\Despacho;


use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
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
    * @Route("/transporte/tablero/transporte/despacho/resumen", name="transporte_tablero_transporte_despacho_resumen")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arResumen = [];
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'required' => false, 'data' => $fecha])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $raw['filtros'] = $this->getFiltros($form);
                    $arResumen = $em->getRepository(TteDespacho::class)->tableroResumen($raw);

                }
            }
        }

        return $this->render('transporte/tablero/transporte/despacho/resumen.html.twig', [
            'arResumen' => $arResumen,
            'form' => $form->createView()
            ]);
    }

    private function getFiltros($form)
    {
        $filtro = [
            'fechaDesde' => $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ?$form->get('fechaHasta')->getData()->format('Y-m-d'): null,
        ];
        return $filtro;

    }
}
