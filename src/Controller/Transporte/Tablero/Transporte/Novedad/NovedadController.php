<?php

namespace App\Controller\Transporte\Tablero\Transporte\Novedad;

use App\Entity\Transporte\TteNovedad;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;

class NovedadController extends Controller
{
    /**
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     * @Route("/transporte/tablero/transporte/novedad/mes", name="transporte_tablero_transporte_novedad_mes")
     */
    public function principal()
    {
        $em = $this->getDoctrine()->getManager();
        $arrNovedades = $em->getRepository(TteNovedad::class)->novedadesPorDiaMesAtual();
        $arrNovedadesAnio = $em->getRepository(TteNovedad::class)->novedadesPorMesAnioActual();

        $arrCategoriasMes = array_map(
            function ($novedad){
                return "Dia ".$novedad['dia'];
            },$arrNovedades
        );
        $arrCategoriasAnio = array_map(
            function ($novedad){
                $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                return $meses[$novedad['mes'] - 1];
            },$arrNovedadesAnio
        );

        $cantidad = array_map(function ($novedad) {
            return intval($novedad['cantidad']);
        }, $arrNovedades);

        $cantidadAnio = array_map(function ($novedad) {
            return intval($novedad['cantidad']);
        }, $arrNovedadesAnio);

        $seriesMes = [
            array(
                "name" => "Novedades por día",
                "data" => $cantidad,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
                ))
        ];

        $seriesAnio = [
            array(
                "name" => "Novedades por mes",
                "data" => $cantidadAnio,
                "dataLabels" => array(
                    "enabled" => true,
                    "rotation" => -90,
                    "color" => "#FFFFFF",
                    "align" => "right",
                    "y" => 10,
                    "style" => array(
                        "fontSize" => "13px",
                        "fontFamily" => "Verdana, sans-serif")
                ))
        ];

        $ob = new Highchart();
        $ob->chart->renderTo('container');
        $ob->chart->type('column');
        $ob->title->text('Novedades por dia en el mes');
        $ob->xAxis->title(array('text' => "Dia"));
        $ob->xAxis->categories($arrCategoriasMes);
        $ob->yAxis->title(array('text' => "Novedades"));
        $ob->series($seriesMes);
        
        $obAnio = new Highchart();
        $obAnio->chart->renderTo('container2');
        $obAnio->chart->type('column');
        $obAnio->title->text('Novedades por mes en el año');
        $obAnio->xAxis->title(array('text' => "Mes"));
        $obAnio->xAxis->categories($arrCategoriasAnio);
        $obAnio->yAxis->title(array('text' => "Novedades"));
        $obAnio->series($seriesAnio);

        return $this->render('transporte/tablero/transporte/novedad/novedad.html.twig', [
            'arrNovedades' => $arrNovedades,
            'arrNovedadesAnio' => $arrNovedadesAnio,
            'chart' => $ob,
            'chartAnio' => $obAnio,
            'obAnio' => $obAnio
        ]);
    }
}

