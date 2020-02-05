<?php

namespace App\Controller\Transporte\Tablero\Transporte\Despacho;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteDespacho;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DespachoController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "ttet0007";




    /**
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     * @Route("/transporte/tablero/transporte/despacho/mes", name="transporte_tablero_transporte_despacho_mes")
     */
    public function principal()
    {
        $em = $this->getDoctrine()->getManager();
        $arrDespachos = $em->getRepository(TteDespacho::class)->despachoPorDiaMesAtual();
        $arrDespachosAnio = $em->getRepository(TteDespacho::class)->despachoPorMesAnioActual();

        $arrCategoriasMes = array_map(
            function ($despacho){
                return "Dia ".$despacho['dia'];
            },$arrDespachos
        );
        $arrCategoriasAnio = array_map(
            function ($despacho){
                $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                return $meses[$despacho['mes'] - 1];
            },$arrDespachosAnio
        );

        $cantidad = array_map(function ($novedad) {
            return intval($novedad['cantidad']);
        }, $arrDespachos);

        $cantidadAnio = array_map(function ($novedad) {
            return intval($novedad['cantidad']);
        }, $arrDespachosAnio);

        $seriesMes = [
            array(
                "name" => "Despachos por día",
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
                "name" => "Despachos por mes",
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
        $ob->title->text('Despachos por dia en el mes');
        $ob->xAxis->title(array('text' => "Dia"));
        $ob->xAxis->categories($arrCategoriasMes);
        $ob->yAxis->title(array('text' => "Despacho"));
        $ob->series($seriesMes);
        
        $obAnio = new Highchart();
        $obAnio->chart->renderTo('container2');
        $obAnio->chart->type('column');
        $obAnio->title->text('Despacho por mes en el año');
        $obAnio->xAxis->title(array('text' => "Mes"));
        $obAnio->xAxis->categories($arrCategoriasAnio);
        $obAnio->yAxis->title(array('text' => "Despacho"));
        $obAnio->series($seriesAnio);

        return $this->render('transporte/tablero/transporte/despacho/despacho.html.twig', [
            'arrDespachos' => $arrDespachos,
            'arrDespachosAnio' => $arrDespachosAnio,
            'chart' => $ob,
            'chartAnio' => $obAnio,
            'obAnio' => $obAnio
        ]);
    }
}

