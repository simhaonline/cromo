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
        $fecha = new \DateTime('now');
        $arrProduccion = $em->getRepository(TteGuia::class)->tableroProduccionMes($fecha);
        $arrProduccionAnio = $em->getRepository(TteGuia::class)->tableroProduccionAnio($fecha);
        //Mucho cuidado el problema es que la query devuelve un string
        $arrCategorias = array_column($arrProduccion, "dia");
        $arrCategoriasAnio = array_column($arrProduccionAnio, "dia");
        $flete = array_map(function($produccion){ return intval($produccion['flete']); }, $arrProduccion);
        $manejo = array_map(function($produccion){ return intval($produccion['manejo']); }, $arrProduccion);
        $fleteAnio = array_map(function($produccion){ return intval($produccion['flete']); }, $arrProduccionAnio);
        $manejoAnio = array_map(function($produccion){ return intval($produccion['manejo']); }, $arrProduccionAnio);
        $series = array(
            array(
                "name" => "flete",
                "data" => $flete,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
                    )),
            array("name" => "manejo",
                "data" => $manejo,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
            )),
        );

        $seriesAnio = array(
            array(
                "name" => "flete",
                "data" => $fleteAnio,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
                )),
            array("name" => "manejo",
                "data" => $manejoAnio,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
                )),
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

        $obProduccionAnio = new Highchart();
        $obProduccionAnio->chart->renderTo('container2');
        $obProduccionAnio->chart->type('column');
        $obProduccionAnio->title->text('Produccion por año');
        $obProduccionAnio->xAxis->title(array('text'  => "Año"));
        $obProduccionAnio->xAxis->categories($arrCategoriasAnio);
        $obProduccionAnio->yAxis->title(array('text'  => "Produccion"));
        $obProduccionAnio->series($seriesAnio);

        return $this->render('transporte/tablero/transporte/guia/produccion.html.twig', [
            'arrProducciones' => $arrProduccion,
            'arrProduccionesAnio' => $arrProduccionAnio,
            'chart' => $ob,
            'chartAnio' => $obProduccionAnio,
            ]);
    }
}

