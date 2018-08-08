<?php

namespace App\Controller\Transporte\Tablero\Recogida;

use App\Formato\Despacho;
use App\Entity\Transporte\TteRecogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;

class ResumenController extends Controller
{
   /**
    * @Route("/transporte/tablero/recogida/recogida/resumen", name="transporte_tablero_recogida_recogida_resumen")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        $arrResumenRecogidas = $em->getRepository(TteRecogida::class)->resumenCuenta($fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
        $arrResumenRecogidasOperacion = $em->getRepository(TteRecogida::class)->resumenOperacion($fecha->format('Y-m-d'), $fecha->format('Y-m-d'));


        // Chart
        $series = array(
            array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->yAxis->title(array('text'  => "Vertical axis title"));
        $ob->series($series);

        return $this->render('transporte/tablero/recogida/recogida.html.twig', [
            'arrResumenRecogidas' => $arrResumenRecogidas,
            'arrResumenRecogidasOperacion' => $arrResumenRecogidasOperacion,
            'chart' => $ob
            ]);
    }
}

