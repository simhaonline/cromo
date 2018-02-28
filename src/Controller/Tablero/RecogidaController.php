<?php

namespace App\Controller\Tablero;

use App\Formato\Despacho;
use App\Entity\Recogida;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;

class RecogidaController extends Controller
{
   /**
    * @Route("/tab/recogida", name="tab_recogida")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        $arrResumenRecogidas = $em->getRepository(Recogida::class)->resumenCuenta($fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
        $arrResumenRecogidasOperacion = $em->getRepository(Recogida::class)->resumenOperacion($fecha->format('Y-m-d'), $fecha->format('Y-m-d'));


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

        return $this->render('tablero/recogida.html.twig', [
            'arrResumenRecogidas' => $arrResumenRecogidas,
            'arrResumenRecogidasOperacion' => $arrResumenRecogidasOperacion,
            'chart' => $ob
            ]);
    }
}

