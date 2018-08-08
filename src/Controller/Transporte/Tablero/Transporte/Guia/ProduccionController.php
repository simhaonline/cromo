<?php

namespace App\Controller\Transporte\Tablero\Transporte\Guia;


use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;
class ProduccionController extends Controller
{
   /**
    * @Route("/transporte/tablero/transporte/guia/produccion", name="transporte_tablero_transporte_guia_produccion")
    */    
    public function principal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrProduccion = $em->getRepository(TteGuia::class)->tableroProduccionMes(8);
        //Mucho cuidado el problema es que la query devuelve un string
        $arrCategorias = array_column($arrProduccion, "dia");
        $flete = array_map(function($produccion){ return intval($produccion['flete']); }, $arrProduccion);
        $manejo = array_map(function($produccion){ return intval($produccion['manejo']); }, $arrProduccion);
        $series = array(
            array("name" => "flete",    "data" => $flete),
            array("name" => "manejo",    "data" => $manejo),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('container');
        $ob->chart->type('column');
        //$ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Produccion por mes');
        $ob->xAxis->title(array('text'  => "Dia"));
        $ob->xAxis->categories($arrCategorias);
        $ob->yAxis->title(array('text'  => "Produccion"));
        $ob->series($series);

        return $this->render('transporte/tablero/transporte/guia/produccion.html.twig', [
            'arrProduccion' => $arrProduccion,
            'chart' => $ob
            ]);
    }
}

